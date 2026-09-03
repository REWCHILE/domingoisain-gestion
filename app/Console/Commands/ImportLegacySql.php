<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Certificate;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportLegacySql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:legacy-sql {file? : Ruta al archivo .sql exportado de phpMyAdmin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa la base de datos antigua (tabla cotizacion) a la nueva estructura de Instalgaschile';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!$filePath) {
            $filePath = base_path('cotizacion.sql');
            if (!file_exists($filePath)) {
                $filePath = base_path('database/dumps/cotizacion.sql');
            }
        }

        if (!file_exists($filePath)) {
            $this->error("No se encontró el archivo SQL en: {$filePath}");
            $this->info("Coloque su archivo 'cotizacion.sql' en la raíz del proyecto o especifique la ruta.");
            return 1;
        }

        $this->info("Iniciando lectura del archivo SQL: {$filePath}...");
        $sqlContent = file_get_contents($filePath);

        $importedCount = 0;
        $skippedCount = 0;

        // Regex to match INSERT INTO `cotizacion` or `cotizaciones`
        // Structure: VALUES (id, precio, por_descuento, cli_nombre, cli_telefono, cli_region, cli_ciudad, cli_comuna, cli_dire, cli_email, estado, creado, modificado, detalle, tecnico, img1, img2, img3)
        preg_match_all('/INSERT\s+INTO\s+[`"]?cotizacion[`"]?\s*\([^)]*\)\s*VALUES\s*(.*?);/is', $sqlContent, $matches);

        if (empty($matches[1])) {
            // Try multi-line or simpler insert matching
            preg_match_all('/\((.*?)\)/s', $sqlContent, $tupleMatches);
            $tuples = $tupleMatches[1] ?? [];
        } else {
            $tuples = [];
            foreach ($matches[1] as $valuesBlock) {
                // Split value groups: (val1, val2...), (val3, val4...)
                preg_match_all('/\(([^()]+(?:\([^()]*\)[^()]*)*)\)/s', $valuesBlock, $groupMatches);
                foreach ($groupMatches[1] as $grp) {
                    $tuples[] = $grp;
                }
            }
        }

        // Parse `detalle_cotiza` items table if present
        $itemsMap = [];
        preg_match_all('/INSERT\s+INTO\s+[`"]?detalle_cotiza[`"]?\s*\([^)]*\)\s*VALUES\s*(.*?);/is', $sqlContent, $detMatches);
        if (!empty($detMatches[1])) {
            foreach ($detMatches[1] as $valuesBlock) {
                preg_match_all('/\(([^()]+(?:\([^()]*\)[^()]*)*)\)/s', $valuesBlock, $groupMatches);
                foreach ($groupMatches[1] as $grp) {
                    $itemVals = str_getcsv($grp, ',', "'");
                    if (count($itemVals) >= 4) {
                        $cotId = trim($itemVals[1] ?? '0');
                        $desc = trim(str_replace(['\r\n', '\n', '\r', "\\r\\n", "\\n", "\\r"], ' ', $itemVals[2] ?? ''));
                        $desc = preg_replace('/\s+/', ' ', $desc);
                        $itemPrecio = floatval(trim($itemVals[3] ?? 0));
                        $itemQty = intval(trim($itemVals[4] ?? 1));
                        if ($itemQty <= 0) $itemQty = 1;

                        if ($cotId !== '0' && !empty($desc)) {
                            $itemsMap[$cotId][] = [
                                'description' => $desc,
                                'quantity' => $itemQty,
                                'unit_price' => $itemPrecio,
                            ];
                        }
                    }
                }
            }
        }

        $this->info("Analizando " . count($tuples) . " registros principales y " . count($itemsMap) . " grupos de detalles...");

        DB::beginTransaction();
        try {
            foreach ($tuples as $tuple) {
                $values = str_getcsv($tuple, ',', "'");
                if (count($values) < 14) continue;

                $id = trim($values[0] ?? '');
                $precio = floatval(trim($values[1] ?? 0));
                $cliNombre = trim($values[3] ?? 'Cliente Sin Nombre');
                $cliTelefono = trim($values[4] ?? '');
                $cliComunaId = trim($values[7] ?? '');
                $cliDireccion = trim($values[8] ?? '');
                $cliEmail = trim($values[9] ?? '');
                $estadoVal = trim($values[10] ?? '1');
                $creado = trim($values[11] ?? now());
                $modificado = trim($values[12] ?? now());
                $detalle = trim(str_replace(['\r\n', '\n', '\r', "\\r\\n", "\\n", "\\r"], "\n", $values[13] ?? ''));

                if (!is_numeric($id) || intval($id) <= 0) continue;

                $folioNumber = intval($id);

                // Check if already imported
                if (Certificate::where('certificate_number', (string)$folioNumber)->exists()) {
                    $skippedCount++;
                    continue;
                }

                // Map estado: 1 = cotizacion/pendiente, 2 = certificado/completado
                $docType = ($estadoVal == '1') ? 'cotizacion' : 'certificado';
                $status = ($estadoVal == '2') ? 'completado' : 'emitido';

                // Find or create Client
                $client = Client::firstOrCreate(
                    ['name' => $cliNombre],
                    [
                        'phone' => $cliTelefono,
                        'address' => $cliDireccion,
                        'comuna' => $cliComunaId ?: 'Santiago',
                        'provincia' => 'Santiago',
                    ]
                );

                $shortDesc = trim(str_replace(['\r\n', '\n', '\r', "\\r\\n", "\\n", "\\r"], ' ', mb_substr(strip_tags($detalle), 0, 150)));
                $shortDesc = preg_replace('/\s+/', ' ', $shortDesc);
                if (empty($shortDesc)) {
                    $shortDesc = $docType === 'cotizacion' ? 'Cotización de Servicio' : 'Certificado de Servicio SEC';
                }

                $fullItemDesc = !empty($detalle) ? $detalle : $shortDesc;

                $certItems = $itemsMap[$id] ?? [
                    [
                        'description' => $fullItemDesc,
                        'quantity' => 1,
                        'unit_price' => $precio,
                    ]
                ];

                Certificate::create([
                    'certificate_number' => (string)$folioNumber,
                    'document_type' => $docType,
                    'date' => Carbon::parse($creado)->format('Y-m-d'),
                    'user_id' => 1,
                    'client_id' => $client->id,
                    'client_name' => $cliNombre,
                    'client_phone' => $cliTelefono,
                    'client_address' => $cliDireccion,
                    'client_comuna' => $cliComunaId ?: 'Santiago',
                    'client_provincia' => 'Santiago',
                    'description' => $shortDesc,
                    'items' => $certItems,
                    'quantity' => 1,
                    'unit_price' => $precio,
                    'subtotal_neto' => $precio,
                    'tax_type' => 'neto',
                    'tax_amount' => 0,
                    'total_price' => $precio,
                    'work_details' => $detalle,
                    'gasfiter_name' => 'Domingo Isain Plaza Caamaño',
                    'gasfiter_rut' => '12738961-6',
                    'gasfiter_sec_class' => 'Gasfiter Certificado Autorizado SEC Clase 3',
                    'status' => $status,
                    'created_at' => Carbon::parse($creado),
                    'updated_at' => Carbon::parse($modificado),
                ]);

                $importedCount++;
            }

            DB::commit();
            $this->info("Importación completada con éxito:");
            $this->info(" - Registros importados: {$importedCount}");
            $this->info(" - Registros omitidos (ya existían): {$skippedCount}");

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("Error durante la importación: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
