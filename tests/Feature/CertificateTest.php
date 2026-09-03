<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_certificates_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/certificates');

        $response->assertStatus(200);
    }

    public function test_pdf_generation_works(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $cert = Certificate::create([
            'certificate_number' => '14408',
            'date' => '2026-08-08',
            'user_id' => $admin->id,
            'client_name' => 'Don Juan',
            'client_phone' => '988887777',
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
            'work_details' => 'Prueba de sellado',
            'gasfiter_name' => 'Domingo Isain Plaza Caamaño',
            'gasfiter_rut' => '12738961-6',
            'gasfiter_sec_class' => 'Clase 3',
        ]);

        $response = $this->actingAs($admin)->get("/certificates/{$cert->id}/pdf");

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
