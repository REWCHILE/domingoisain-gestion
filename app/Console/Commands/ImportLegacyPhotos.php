<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Certificate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportLegacyPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:legacy-photos {path? : Ruta a la carpeta que contiene las fotos antiguas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vinsula e importa automáticamente las imágenes antiguas (img1, img2, img3) a los certificados importados';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sourceDir = $this->argument('path') ?: base_path('legacy_photos');

        if (!File::exists($sourceDir)) {
            $this->error("No se encontró la carpeta de fotos antiguas en: {$sourceDir}");
            $this->info("Por favor cree la carpeta 'legacy_photos' en la raíz de su proyecto y pegue allí sus imágenes antiguas.");
            return 1;
        }

        $files = File::files($sourceDir);
        $this->info("Se encontraron " . count($files) . " archivos en {$sourceDir}. Indexando...");

        // Map filename without extension => full path
        $imageMap = [];
        foreach ($files as $file) {
            $nameWithoutExt = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $imageMap[$nameWithoutExt] = $file->getRealPath();
        }

        $destStorageDir = storage_path('app/public/certificates');
        if (!File::exists($destStorageDir)) {
            File::makeDirectory($destStorageDir, 0755, true);
        }

        // We need the SQL mapping if stored, or read from certificate metadata
        // In cotizacion.sql: img1, img2, img3 corresponded to IDs in img_cotizacion
        $sqlPath = base_path('cotizacion.sql');
        if (!File::exists($sqlPath)) {
            $sqlPath = base_path('database/dumps/cotizacion.sql');
        }
        if (!File::exists($sqlPath)) {
            $sqlPath = base_path('legacy_photos/cotizacion.sql');
        }

        if (File::exists($sqlPath)) {
            $this->info("Utilizando archivo SQL de mapeo: {$sqlPath}");
            $sqlContent = File::get($sqlPath);
            preg_match_all('/INSERT\s+INTO\s+[`"]?cotizacion[`"]?\s*\([^)]*\)\s*VALUES\s*(.*?);/is', $sqlContent, $matches);

            $tuples = [];
            if (!empty($matches[1])) {
                foreach ($matches[1] as $valuesBlock) {
                    preg_match_all('/\(([^()]+(?:\([^()]*\)[^()]*)*)\)/s', $valuesBlock, $groupMatches);
                    foreach ($groupMatches[1] as $grp) {
                        $tuples[] = $grp;
                    }
                }
            }

            $linkedCount = 0;

            foreach ($tuples as $tuple) {
                $values = str_getcsv($tuple, ',', "'");
                if (count($values) < 18) continue;

                $id = trim($values[0] ?? '');
                $img1Id = trim($values[15] ?? '0');
                $img2Id = trim($values[16] ?? '0');
                $img3Id = trim($values[17] ?? '0');

                if (!is_numeric($id) || intval($id) <= 0) continue;

                $cert = Certificate::where('certificate_number', (string)intval($id))->first();
                if (!$cert) continue;

                $updated = false;

                // Process img1 -> photo_1
                if ($img1Id !== '0' && isset($imageMap[$img1Id])) {
                    $ext = pathinfo($imageMap[$img1Id], PATHINFO_EXTENSION);
                    $newName = "cert_{$id}_photo1.{$ext}";
                    File::copy($imageMap[$img1Id], "{$destStorageDir}/{$newName}");
                    $cert->photo_1 = "certificates/{$newName}";
                    $updated = true;
                }

                // Process img2 -> photo_2
                if ($img2Id !== '0' && isset($imageMap[$img2Id])) {
                    $ext = pathinfo($imageMap[$img2Id], PATHINFO_EXTENSION);
                    $newName = "cert_{$id}_photo2.{$ext}";
                    File::copy($imageMap[$img2Id], "{$destStorageDir}/{$newName}");
                    $cert->photo_2 = "certificates/{$newName}";
                    $updated = true;
                }

                // Process img3 -> photo_3
                if ($img3Id !== '0' && isset($imageMap[$img3Id])) {
                    $ext = pathinfo($imageMap[$img3Id], PATHINFO_EXTENSION);
                    $newName = "cert_{$id}_photo3.{$ext}";
                    File::copy($imageMap[$img3Id], "{$destStorageDir}/{$newName}");
                    $cert->photo_3 = "certificates/{$newName}";
                    $updated = true;
                }

                if ($updated) {
                    $cert->save();
                    $linkedCount++;
                }
            }

            $this->info("¡Vinculación completada con éxito! Se vincularon imágenes a {$linkedCount} certificados usando cotizacion.sql.");
            return 0;
        }

        // Direct Folio Matching Fallback (e.g. 1033.png -> Certificate folio 1033)
        $this->warn("No se encontró 'cotizacion.sql'. Iniciando vinculación directa por número de folio de archivo (ej. 1033.png)...");
        $linkedCount = 0;

        foreach ($imageMap as $filenameKey => $fullFilePath) {
            // Check if filename is e.g. "1033" or "1033_1"
            preg_match('/^(\d+)(?:_([123]))?$/', $filenameKey, $fileMatches);
            if (empty($fileMatches[1])) continue;

            $folioNum = $fileMatches[1];
            $photoIndex = $fileMatches[2] ?? '1';

            $cert = Certificate::where('certificate_number', (string)$folioNum)->first();
            if (!$cert) continue;

            $ext = pathinfo($fullFilePath, PATHINFO_EXTENSION);
            $newName = "cert_{$folioNum}_photo{$photoIndex}.{$ext}";
            File::copy($fullFilePath, "{$destStorageDir}/{$newName}");

            $targetProp = "photo_{$photoIndex}";
            $cert->$targetProp = "certificates/{$newName}";
            $cert->save();
            $linkedCount++;
        }

        $this->info("¡Vinculación por número de archivo completada! Se vincularon {$linkedCount} fotos a los certificados.");
        return 0;

        $sqlContent = File::get($sqlPath);
        preg_match_all('/INSERT\s+INTO\s+[`"]?cotizacion[`"]?\s*\([^)]*\)\s*VALUES\s*(.*?);/is', $sqlContent, $matches);

        $tuples = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $valuesBlock) {
                preg_match_all('/\(([^()]+(?:\([^()]*\)[^()]*)*)\)/s', $valuesBlock, $groupMatches);
                foreach ($groupMatches[1] as $grp) {
                    $tuples[] = $grp;
                }
            }
        }

        $linkedCount = 0;

        foreach ($tuples as $tuple) {
            $values = str_getcsv($tuple, ',', "'");
            if (count($values) < 18) continue;

            $id = trim($values[0] ?? '');
            $img1Id = trim($values[15] ?? '0');
            $img2Id = trim($values[16] ?? '0');
            $img3Id = trim($values[17] ?? '0');

            if (!is_numeric($id) || intval($id) <= 0) continue;

            $cert = Certificate::where('certificate_number', (string)intval($id))->first();
            if (!$cert) continue;

            $updated = false;

            // Process img1 -> photo_1
            if ($img1Id !== '0' && isset($imageMap[$img1Id])) {
                $ext = pathinfo($imageMap[$img1Id], PATHINFO_EXTENSION);
                $newName = "cert_{$id}_photo1.{$ext}";
                File::copy($imageMap[$img1Id], "{$destStorageDir}/{$newName}");
                $cert->photo_1 = "certificates/{$newName}";
                $updated = true;
            }

            // Process img2 -> photo_2
            if ($img2Id !== '0' && isset($imageMap[$img2Id])) {
                $ext = pathinfo($imageMap[$img2Id], PATHINFO_EXTENSION);
                $newName = "cert_{$id}_photo2.{$ext}";
                File::copy($imageMap[$img2Id], "{$destStorageDir}/{$newName}");
                $cert->photo_2 = "certificates/{$newName}";
                $updated = true;
            }

            // Process img3 -> photo_3
            if ($img3Id !== '0' && isset($imageMap[$img3Id])) {
                $ext = pathinfo($imageMap[$img3Id], PATHINFO_EXTENSION);
                $newName = "cert_{$id}_photo3.{$ext}";
                File::copy($imageMap[$img3Id], "{$destStorageDir}/{$newName}");
                $cert->photo_3 = "certificates/{$newName}";
                $updated = true;
            }

            if ($updated) {
                $cert->save();
                $linkedCount++;
            }
        }

        $this->info("¡Vinculación completada con éxito! Se vincularon imágenes a {$linkedCount} certificados.");
        return 0;
    }
}
