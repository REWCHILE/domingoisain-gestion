<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

$emails = [
    'domi@domingoisain.cl',
    'domi@instalgaschile.cl',
    'contacto@domingoisain.cl'
];

echo "========================================\n";
echo " CREANDO Y VERIFICANDO USUARIOS ADMIN\n";
echo "========================================\n";

foreach ($emails as $email) {
    $user = User::updateOrCreate(
        ['email' => $email],
        [
            'name' => 'Domingo Isain Plaza Caamaño',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'rut' => '12.738.961-6',
            'phone' => '949877316',
            'sec_code' => 'Clase 3 - Gasfiter Certificado Autorizado SEC'
        ]
    );

    $test = Auth::attempt(['email' => $email, 'password' => 'admin123']);
    $status = $test ? "LISTO PARA INGRESAR (OK)" : "ERROR";
    echo ">> [{$email}] con clave [admin123] => {$status}\n";
}

echo "========================================\n";
echo " PROCESO COMPLETADO EXITOSAMENTE\n";
echo "========================================\n";
