<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Instalgaschile SPA</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #1e293b;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #334155;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }
        .header {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
            padding: 35px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            margin: 10px 0 0 0;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .sub-header {
            color: #bae6fd;
            font-size: 13px;
            font-weight: 600;
            margin-top: 4px;
        }
        .content {
            padding: 40px 35px;
            background-color: #1e293b;
        }
        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 20px;
        }
        .message-text {
            font-size: 15px;
            line-height: 1.6;
            color: #cbd5e1;
            margin-bottom: 25px;
        }
        .info-card {
            background-color: #0f172a;
            border-left: 4px solid #38bdf8;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 30px;
        }
        .info-card p {
            margin: 4px 0;
            font-size: 13px;
            color: #94a3b8;
        }
        .info-card strong {
            color: #f1f5f9;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
            color: #0f172a !important;
            font-size: 16px;
            font-weight: 800;
            text-decoration: none;
            padding: 16px 36px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(56, 189, 248, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .alt-link {
            font-size: 12px;
            color: #64748b;
            word-break: break-all;
            margin-top: 25px;
            line-height: 1.5;
            background-color: #0f172a;
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid #334155;
        }
        .alt-link a {
            color: #38bdf8;
            text-decoration: underline;
        }
        .footer {
            background-color: #0f172a;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #334155;
            font-size: 12px;
            color: #64748b;
        }
        .footer strong {
            color: #cbd5e1;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" alt="Instalgaschile®" style="height: 65px; width: auto; max-width: 280px; margin-bottom: 8px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.3));">
            @else
                <div style="font-size: 28px; font-weight: 900; color: #ffffff;">Instalgaschile®</div>
            @endif
            <h1>Bienvenido a la Plataforma</h1>
            <div class="sub-header">Gestión de Certificados y Cotizaciones SEC</div>
        </div>

        <!-- Body Content -->
        <div class="content">
            <div class="greeting">¡Hola, {{ $user->name }}! 👋</div>
            
            <div class="message-text">
                Te damos la bienvenida al sistema oficial de gestión de <strong>Instalgaschile SPA</strong>. Se ha creado exitosamente tu perfil de acceso para la emisión y administración de certifiados y cotizaciones SEC.
            </div>

            <div class="info-card">
                <p><strong>Usuario / Email:</strong> {{ $user->email }}</p>
                <p><strong>Rol asignado:</strong> {{ $user->role === 'admin' ? 'Administrador del Sistema' : 'Técnico Autorizado SEC' }}</p>
                @if($user->sec_code)
                    <p><strong>Código / Clase SEC:</strong> {{ $user->sec_code }}</p>
                @endif
                @if($user->rut)
                    <p><strong>RUT:</strong> {{ $user->rut }}</p>
                @endif
            </div>

            <div class="message-text">
                Para comenzar a ingresar al sistema y realizar tus gestiones, por favor establece tu contraseña personal haciendo clic en el siguiente botón:
            </div>

            <div class="btn-container">
                <a href="{{ $setupUrl }}" class="btn" target="_blank">Establecer Mi Contraseña</a>
            </div>

            <div class="alt-link">
                Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:<br>
                <a href="{{ $setupUrl }}" target="_blank">{{ $setupUrl }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Instalgaschile SPA</strong> — Servicio Técnico Certificado SEC</p>
            <p>RUT: 76.776.528-2 | Av. Lib. Bernardo O'Higgins 1302, Santiago</p>
            <p>Teléfono: +56 9 4987 7316 | domi@instalgaschile.cl</p>
            <p style="margin-top: 15px; font-size: 11px; color: #475569;">Este enlace caducará en 60 minutos por razones de seguridad.</p>
        </div>
    </div>
</body>
</html>
