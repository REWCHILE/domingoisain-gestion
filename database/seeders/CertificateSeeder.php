<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure Users exist
        $admin = User::firstOrCreate(
            ['email' => 'alvaro@rew.cl'],
            [
                'name' => 'Álvaro',
                'password' => Hash::make('satorprogramador2021#'),
                'role' => 'admin',
            ]
        );

        $domingo = User::firstOrCreate(
            ['email' => 'domi@instalgaschile.cl'],
            [
                'name' => 'Domingo Isain Plaza Caamaño',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'rut' => '12.738.961-6',
                'phone' => '+56 9 4987 7316',
                'sec_code' => 'Gasfiter Certificado Autorizado SEC Clase 3',
            ]
        );

        $tech = User::firstOrCreate(
            ['email' => 'tecnico@instalgaschile.cl'],
            [
                'name' => 'Carlos Gutiérrez - Técnico Terreno',
                'password' => Hash::make('tecnico123'),
                'role' => 'technician',
                'rut' => '16.852.147-9',
                'phone' => '+56 9 8765 4321',
                'sec_code' => 'Gasfiter Autorizado SEC Clase 3',
            ]
        );

        // Clear existing test certificates to populate fresh real data
        Certificate::truncate();

        $realCertificates = [
            [
                'number' => '14409',
                'date' => '2026-08-08',
                'client_name' => 'Juan Pérez González',
                'client_phone' => '+56 9 5578 5784',
                'client_address' => 'Av. Venezuela 5840',
                'client_comuna' => 'La Florida',
                'client_provincia' => 'Santiago',
                'tax_type' => 'neto',
                'status' => 'completado',
                'items' => [
                    ['description' => 'Sellado de fugas de gas en red de 30 metros', 'quantity' => 1, 'unit_price' => 800000],
                    ['description' => 'Prueba de hermeticidad final norma DS66 SEC', 'quantity' => 1, 'unit_price' => 45000],
                ],
                'details' => "Se realizó sellado de fuga de gas en red de 30 metros lineales.\n\nSe asegura hermeticidad de acuerdo al Decreto Supremo 66 Artículo 44.2.3 SEC. Se utilizó prodoral r6-1 sellante alemán para Fugas de Gas aceptado por SEC ds66 artículo 7: DIN EN 13090 Y NAG-203.\n\nSolucionado, garantía 3 años por efectos de sellado.\n\nPrueba de hermeticidad final a 368mmca estanco por 5 minutos, sin fugas.\n\nResponsable Domingo Isain Plaza Caamaño Rut 12738961-6\nGasfiter Certificado Autorizado SEC Clase 3",
            ],
            [
                'number' => '14410',
                'date' => '2026-08-09',
                'client_name' => 'Inmobiliaria & Constructora San Cristóbal SpA',
                'client_phone' => '+56 2 2345 6789',
                'client_address' => 'Av. Providencia 2350, Piso 8',
                'client_comuna' => 'Providencia',
                'client_provincia' => 'Santiago',
                'tax_type' => 'factura',
                'status' => 'emitido',
                'items' => [
                    ['description' => 'Inspección técnica y prueba de estanqueidad red matriz', 'quantity' => 1, 'unit_price' => 450000],
                    ['description' => 'Reemplazo de llave de paso esférica gas 3/4" bola de bronce', 'quantity' => 2, 'unit_price' => 85000],
                ],
                'details' => "Prueba de hermeticidad en instalación de gas según normativa SEC DS66.\n\nSe realiza presurización a 368 mmca manteniéndose estable por 15 minutos sin caídas de presión.\n\nInstalación apta y conforme a normas de seguridad vigentes SEC.",
            ],
            [
                'number' => '14411',
                'date' => '2026-08-09',
                'client_name' => 'María Angélica Silva Riquelme',
                'client_phone' => '+56 9 9123 4567',
                'client_address' => 'Calle Los Leones 1420, Dpto 402',
                'client_comuna' => 'Providencia',
                'client_provincia' => 'Santiago',
                'tax_type' => 'neto',
                'status' => 'completado',
                'items' => [
                    ['description' => 'Mantención preventiva y limpieza de inyectores Calefón Junkers 13L', 'quantity' => 1, 'unit_price' => 65000],
                    ['description' => 'Cambio de flexible de gas normado inoxidable 1/2"', 'quantity' => 1, 'unit_price' => 28000],
                ],
                'details' => "Servicio técnico y mantenimiento preventivo/correctivo de artefacto a gas (Calefón Junkers 13 Litros Tiro Forzado).\n\nVerificación de ducto de evacuación de gases de combustión, limpieza de inyectores, prueba de encendido y sellado de conexiones. Proceso verificado bajo norma SEC.",
            ],
            [
                'number' => '14412',
                'date' => '2026-08-10',
                'client_name' => 'Roberto Morales Fuentes',
                'client_phone' => '+56 9 8888 1234',
                'client_address' => 'Av. Las Condes 9850, Casa 12',
                'client_comuna' => 'Las Condes',
                'client_provincia' => 'Santiago',
                'tax_type' => 'factura',
                'status' => 'pendiente',
                'items' => [
                    ['description' => 'Inspección de red de gas cocina y quincho', 'quantity' => 1, 'unit_price' => 120000],
                    ['description' => 'Instalación de regulador de presión R-50 de alta capacidad', 'quantity' => 1, 'unit_price' => 75000],
                ],
                'details' => "Evaluación en terreno para regulación de presión y prueba de hermeticidad de red de gas en zona de cocina y quincho exterior.",
            ],
            [
                'number' => '14413',
                'date' => '2026-08-10',
                'client_name' => 'Restaurant El Fogón Andino Ltda',
                'client_phone' => '+56 2 2888 9900',
                'client_address' => 'Av. Larraín 6540',
                'client_comuna' => 'La Reina',
                'client_provincia' => 'Santiago',
                'tax_type' => 'factura',
                'status' => 'emitido',
                'items' => [
                    ['description' => 'Certificación SEC de estanqueidad en cocina industrial', 'quantity' => 1, 'unit_price' => 380000],
                    ['description' => 'Instalación de válvula corte rápido solenoide con sensor de gas', 'quantity' => 1, 'unit_price' => 160000],
                    ['description' => 'Prueba de evacuación de monóxido de carbono', 'quantity' => 1, 'unit_price' => 45000],
                ],
                'details' => "Inspección técnica anual para local comercial gastronómico. Certificación de estanqueidad de freidoras, hornos y cocinas industriales bajo norma NCh 2235 y Decreto 66 SEC.",
            ],
        ];

        foreach ($realCertificates as $data) {
            $client = Client::firstOrCreate(
                ['name' => $data['client_name']],
                [
                    'phone' => $data['client_phone'],
                    'address' => $data['client_address'],
                    'comuna' => $data['client_comuna'],
                    'provincia' => $data['client_provincia'],
                ]
            );

            $subtotal = 0;
            foreach ($data['items'] as $it) {
                $subtotal += ($it['quantity'] * $it['unit_price']);
            }

            $taxAmount = $data['tax_type'] === 'factura' ? round($subtotal * 0.19, 2) : 0;
            $totalPrice = $subtotal + $taxAmount;

            Certificate::create([
                'certificate_number' => $data['number'],
                'date' => $data['date'],
                'user_id' => $domingo->id,
                'client_id' => $client->id,
                'client_name' => $data['client_name'],
                'client_phone' => $data['client_phone'],
                'client_address' => $data['client_address'],
                'client_comuna' => $data['client_comuna'],
                'client_provincia' => $data['client_provincia'],
                'description' => $data['items'][0]['description'],
                'items' => $data['items'],
                'quantity' => $data['items'][0]['quantity'],
                'unit_price' => $data['items'][0]['unit_price'],
                'subtotal_neto' => $subtotal,
                'tax_type' => $data['tax_type'],
                'tax_amount' => $taxAmount,
                'total_price' => $totalPrice,
                'work_details' => $data['details'],
                'gasfiter_name' => $domingo->name,
                'gasfiter_rut' => $domingo->rut,
                'gasfiter_sec_class' => $domingo->sec_code,
                'status' => $data['status'],
            ]);
        }
    }
}
