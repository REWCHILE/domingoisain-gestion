<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Certificate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin (Domingo Isain - Owner)
        $admin = User::firstOrCreate(
            ['email' => 'domi@instalgaschile.cl'],
            [
                'name' => 'Domingo Isain Plaza Caamaño',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '949877316',
                'rut' => '12.738.961-6',
                'sec_code' => 'Clase 3 - Gasfiter Certificado Autorizado SEC',
                'is_active' => true,
            ]
        );

        // 2. Create Demo Technicians
        $tech1 = User::firstOrCreate(
            ['email' => 'tecnico@instalgaschile.cl'],
            [
                'name' => 'Carlos M. (Técnico SEC)',
                'password' => Hash::make('tecnico123'),
                'role' => 'technician',
                'phone' => '912345678',
                'rut' => '18.456.789-0',
                'sec_code' => 'Clase 3',
                'is_active' => true,
            ]
        );

        // 3. Create Sample Client
        $client = Client::firstOrCreate(
            ['name' => 'Don Juan'],
            [
                'phone' => '+569 8888 7777',
                'address' => 'Nuestra Señora de Fatima 9530',
                'comuna' => 'La Florida',
                'provincia' => 'Santiago',
                'email' => 'donjuan@example.com',
            ]
        );

        // 4. Create Initial Certificate 14408 matching original pdf
        Certificate::firstOrCreate(
            ['certificate_number' => '14408'],
            [
                'date' => '2026-08-08',
                'user_id' => $admin->id,
                'client_id' => $client->id,
                'client_name' => 'Don Juan',
                'client_phone' => 'X',
                'client_address' => 'Nuestra Señora de Fatima 9530',
                'client_comuna' => 'La Florida',
                'client_provincia' => 'Santiago',
                'description' => 'Sellado de fugas de gas en red',
                'quantity' => 1,
                'unit_price' => 800000,
                'subtotal_neto' => 800000,
                'tax_type' => 'neto',
                'tax_amount' => 0,
                'total_price' => 800000,
                'work_details' => "Se realizó sellado de fuga de gas en red de 30 metros lineales.\n\nSe asegura hermeticidad de acuerdo al Decreto Supremo 66 Artículo 44.2.3 SEC. Se utilizó prodoral r6-1 sellante alemán para Fugas de Gas aceptado por SEC ds66 artículo 7: DIN EN 13090 Y NAG-203.\n\nSolucionado, garantía 3 años por efectos de sellado.\n\nPrueba de hermeticidad final a 368mmca estanco por 5 minutos, sin fugas\n\nResponsable Domingo Isain Plaza Caamaño Rut 12738961-6\nGasfiter Certificado Autorizado SEC Clase 3",
                'gasfiter_name' => 'Domingo Isain Plaza Caamaño',
                'gasfiter_rut' => '12738961-6',
                'gasfiter_sec_class' => 'Gasfiter Certificado Autorizado SEC Clase 3',
                'photo_1' => null,
                'photo_2' => null,
                'photo_3' => null,
                'status' => 'emitido',
            ]
        );

        // 5. Call CertificateSeeder for rich realistic certificates
        $this->call(CertificateSeeder::class);
    }
}
