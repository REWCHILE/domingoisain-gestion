<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->date('date');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            
            // Client details snapshot
            $table->string('client_name');
            $table->string('client_phone')->nullable();
            $table->string('client_address')->nullable();
            $table->string('client_comuna')->nullable();
            $table->string('client_provincia')->nullable();
            
            // Financial & Item details
            $table->text('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('subtotal_neto', 12, 2)->default(0);
            $table->string('tax_type')->default('neto'); // 'neto' (sin doc tributario) o 'factura' (con IVA 19%)
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            
            // Technical details & SEC compliance
            $table->longText('work_details')->nullable();
            $table->string('gasfiter_name')->default('Domingo Isain Plaza Caamaño');
            $table->string('gasfiter_rut')->default('12738961-6');
            $table->string('gasfiter_sec_class')->default('Gasfiter Certificado Autorizado SEC Clase 3');
            
            // Photos (3 evidence photos)
            $table->string('photo_1')->nullable();
            $table->string('photo_2')->nullable();
            $table->string('photo_3')->nullable();
            
            // Status & Notes
            $table->string('status')->default('emitido'); // emitido, pendiente, completado, anulado
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
