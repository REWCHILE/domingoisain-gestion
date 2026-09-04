<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado_Servicio_{{ $certificate->certificate_number }}</title>
    <style>
        @page {
            margin: 12px 20px;
            size: A4 portrait;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.25;
            margin: 0;
            padding: 0;
        }
        * {
            box-sizing: border-box;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* Header Table */
        .header-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .header-table td {
            vertical-align: middle;
        }

        .title-box {
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            letter-spacing: 0.5px;
            color: #0f172a;
        }

        /* Section Titles */
        .section-header {
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        /* Client Info Key-Value Table */
        .client-box {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            background-color: #f8fafc;
            margin-bottom: 6px;
        }
        .client-box td {
            padding: 3px 6px;
            vertical-align: top;
            font-size: 9px;
        }
        .client-box td.lbl {
            font-weight: bold;
            color: #0f172a;
            width: 16%;
        }
        .client-box td.val {
            color: #334155;
            width: 34%;
            word-wrap: break-word;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 6px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            overflow: hidden;
        }
        .items-table th {
            background-color: #f1f5f9;
            border-bottom: 2px solid #0f172a;
            padding: 4px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            color: #0f172a;
        }
        .items-table td {
            padding: 4px 6px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 9px;
            word-wrap: break-word;
        }

        /* Details Box */
        .details-container {
            background-color: #f8fafc;
            border-radius: 5px;
            padding: 5px 8px;
            margin-bottom: 6px;
            border: 1px solid #cbd5e1;
        }
        .details-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 2px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .details-content {
            font-size: 9px;
            line-height: 1.25;
            white-space: pre-line;
            color: #334155;
            word-wrap: break-word;
        }

        /* Photographs Evidence Grid */
        .photos-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .photos-table td {
            text-align: center;
            vertical-align: middle;
            padding: 2px;
        }
        .photo-card {
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 3px;
            background-color: #f8fafc;
        }
        .photo-img {
            max-width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: 4px;
        }
        .qr-img {
            max-width: 100%;
            height: 85px;
            object-fit: contain;
        }

        /* Total Bar */
        .total-bar {
            width: 100%;
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
            padding: 4px 0;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            background-color: #f8fafc;
        }

        /* Footer Table */
        .footer-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        .footer-table td {
            vertical-align: bottom;
            font-size: 8.5px;
            color: #334155;
        }
    </style>
</head>
<body>

    <!-- 1. Header Section -->
    <table class="header-table">
        <tr>
            <!-- Left Logo -->
            <td style="width: 44%; text-align: left; vertical-align: middle;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height: 60px; max-width: 95%; width: auto;" alt="Domingo Isaín - Técnico en Ingeniería">
                @else
                    <span style="font-size: 18px; font-weight: bold; color: #0284c7;">Domingo Isaín®</span>
                @endif
            </td>

            <!-- Center Title -->
            <td style="width: 32%; text-align: center; vertical-align: middle;">
                <div class="title-box">{{ $certificate->document_type === 'cotizacion' ? 'COTIZACIÓN DE SERVICIO' : 'CERTIFICADO DE SERVICIO Y COTIZACIÓN' }}</div>
            </td>

            <!-- Right SEC Badge & Number -->
            <td style="width: 24%; text-align: right; vertical-align: middle;">
                <div style="font-size: 12.5px; font-weight: bold; color: #0f172a; margin-bottom: 2px;">
                    N°: {{ $certificate->certificate_number }}
                </div>
                <div style="font-size: 10px; font-weight: bold; color: #475569; margin-bottom: 3px;">
                    FECHA: {{ \Carbon\Carbon::parse($certificate->date)->format('d/m/Y') }}
                </div>
                @if($secLogoBase64)
                    <img src="{{ $secLogoBase64 }}" style="height: 72px; width: auto;" alt="SEC Badge">
                @endif
            </td>
        </tr>
    </table>

    <!-- 2. Client Data Key-Value Table -->
    <div class="section-header">DATOS CLIENTE:</div>
    <table class="client-box">
        <tr>
            <td class="lbl">Nombre:</td>
            <td class="val">{{ $certificate->client_name }}</td>
            <td class="lbl">Teléfono:</td>
            <td class="val">{{ $certificate->client_phone ?: '-' }}</td>
        </tr>
        <tr>
            <td class="lbl">Provincia:</td>
            <td class="val">{{ $certificate->client_provincia ?: 'Santiago' }}</td>
            <td class="lbl">Dirección:</td>
            <td class="val">{{ $certificate->client_address ?: '-' }}</td>
        </tr>
        <tr>
            <td class="lbl">Comuna:</td>
            <td class="val">{{ $certificate->client_comuna }}</td>
            <td class="lbl">Email:</td>
            <td class="val">{{ $certificate->client_email ?: '-' }}</td>
        </tr>
        <tr>
            <td class="lbl">Región:</td>
            <td class="val">{{ $certificate->client_region }}</td>
            <td class="lbl">Modalidad:</td>
            <td class="val" style="text-transform: uppercase;">Neto Directo</td>
        </tr>
    </table>

    <!-- 3. Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 48%;">Descripción</th>
                <th style="width: 18%; text-align: center;">Precio Unit.</th>
                <th style="width: 14%; text-align: center;">Cantidad</th>
                <th style="width: 20%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($certificate->items_list as $item)
                <tr>
                    <td>{!! nl2br(e(preg_replace('/<br\s*\/?>/i', "\n", $item['description']))) !!}</td>
                    <td style="text-align: center;">${{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $item['quantity'] }}</td>
                    <td style="text-align: right;" class="font-bold">${{ number_format($item['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 4. Work Detail Text Container -->
    <div class="details-container">
        <div class="details-title">Detalle Trabajo:</div>
        <div class="details-content">{{ $certificate->work_details }}</div>
    </div>

    <!-- 5. Photographs Evidence Row -->
    @if(!$photo1Base64 && !$photo3Base64)
        <!-- Without uploaded evidence photos: Display only centered SEC QR card -->
        <table class="photos-table" style="margin: 0 auto; width: 50%;">
            <tr>
                <td style="width: 100%; text-align: center;">
                    <div class="photo-card" style="padding: 10px;">
                        <div style="font-size: 10px; font-weight: bold; color: #0369a1; margin-bottom: 4px;">
                            Gasfiter Certificado Autorizado SEC<br>Domingo Isain
                        </div>
                        @if($secQrBase64)
                            <img src="{{ $secQrBase64 }}" class="qr-img" style="height: 125px;" alt="QR SEC">
                        @endif
                        <div style="font-size: 9px; font-weight: bold; color: #475569; margin-top: 4px;">Escanear para Verificación SEC</div>
                    </div>
                </td>
            </tr>
        </table>
    @else
        <!-- Standard 3-column row for Certificados or Cotizaciones with custom uploaded photos -->
        <table class="photos-table">
            <tr>
                <!-- Photo 1 -->
                <td style="width: 33.33%;">
                    <div class="photo-card">
                        @if($photo1Base64)
                            <img src="{{ $photo1Base64 }}" class="photo-img" alt="Evidencia 1">
                        @elseif($holdingLogoBase64)
                            <img src="{{ $holdingLogoBase64 }}" style="max-height: 75px; width: auto; padding: 4px;" alt="Holding">
                        @endif
                        <div style="font-size: 8px; font-weight: bold; color: #475569; margin-top: 3px;">Evidencia de Instalación / Fuga</div>
                    </div>
                </td>

                <!-- Photo 2: SEC QR -->
                <td style="width: 33.33%;">
                    <div class="photo-card">
                        <div style="font-size: 8.5px; font-weight: bold; color: #0369a1; margin-bottom: 2px;">
                            Gasfiter Certificado Autorizado SEC<br>Domingo Isain
                        </div>
                        @if($secQrBase64)
                            <img src="{{ $secQrBase64 }}" class="qr-img" alt="QR SEC">
                        @endif
                        <div style="font-size: 8px; font-weight: bold; color: #475569; margin-top: 3px;">Escanear para Verificación SEC</div>
                    </div>
                </td>

                <!-- Photo 3 -->
                <td style="width: 33.33%;">
                    <div class="photo-card">
                        @if($photo3Base64)
                            <img src="{{ $photo3Base64 }}" class="photo-img" alt="Evidencia 3">
                        @elseif($secLogoBase64)
                            <img src="{{ $secLogoBase64 }}" style="max-height: 75px; width: auto; padding: 4px;" alt="SEC Logo">
                        @endif
                        <div style="font-size: 8px; font-weight: bold; color: #475569; margin-top: 3px;">Prueba de Hermeticidad / Manómetro</div>
                    </div>
                </td>
            </tr>
        </table>
    @endif

    @if(!empty($extraPhotosBase64))
        <div class="section-header" style="margin-top: 6px;">FOTOGRAFÍAS ADICIONALES DE EVIDENCIA:</div>
        <table class="photos-table">
            <tr>
                @foreach($extraPhotosBase64 as $idx => $exB64)
                    <td style="width: 33.33%;">
                        <div class="photo-card">
                            <img src="{{ $exB64 }}" class="photo-img" alt="Evidencia Extra {{ $idx + 4 }}">
                            <div style="font-size: 8px; font-weight: bold; color: #475569; margin-top: 3px;">Foto Extra {{ $idx + 4 }}</div>
                        </div>
                    </td>
                    @if(($idx + 1) % 3 === 0 && !$loop->last)
                        </tr><tr>
                    @endif
                @endforeach
            </tr>
        </table>
    @endif

    <!-- 6. Total Bar -->
    <div class="total-bar">
        {{ $certificate->document_type === 'cotizacion' ? 'Total Cotizado Neto' : 'Total Neto' }} a Pagar: ${{ number_format($certificate->total_price, 0, ',', '.') }}
    </div>

    <!-- 7. Footer Branding, Registro QR & Digital Signature -->
    <table class="footer-table">
        <tr>
            <!-- Company Info Left -->
            <td style="width: 24%;">
                <strong style="font-size: 10px; color: #0f172a;">Domingo Isain Plaza Caamaño</strong><br>
                RUT: 12738961-6<br>
                Estado 215 oficina 703, Santiago<br>
                Ingeniería Civil y Construcción<br>
                Gasfiter Certificado Autorizado SEC<br>
                Tel: +56 9 4987 7316 / 949 877 316
            </td>

            <!-- Logo Domingo Isaín Center -->
            <td style="width: 51%; text-align: center; vertical-align: middle;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" style="height: 60px; max-width: 90%; width: auto;" alt="Domingo Isaín">
                @endif
            </td>

            <!-- Digital Signature Right -->
            <td style="width: 25%; text-align: center;">
                @if($firmaBase64)
                    <img src="{{ $firmaBase64 }}" style="height: 160px; width: auto; margin-bottom: 2px;" alt="Firma Domingo">
                @endif
                <div style="font-weight: bold; font-size: 9.5px; color: #0f172a;">Domingo Isaín®</div>
                <div style="font-size: 8px; color: #475569;">
                    {{ $certificate->gasfiter_name ?: 'Domingo Isain Plaza Caamaño' }}<br>
                    RUT: {{ $certificate->gasfiter_rut ?: '12.738.961-6' }}
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
