@extends('layouts.app')

@section('title', 'Certificado N&deg; ' . $certificate->certificate_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs animate-fade-in-up">
        <div>
            <span class="text-xs text-sky-600 font-bold uppercase tracking-wider block">Certificado Oficial SEC</span>
            <h1 class="text-2xl font-black text-slate-900">Certificado de Servicio N&deg; {{ $certificate->certificate_number }}</h1>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Emitido el {{ \Carbon\Carbon::parse($certificate->date)->format('d/m/Y') }} por {{ $certificate->gasfiter_name }}</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            
            <!-- Download PDF Button -->
            <a href="{{ route('certificates.pdf', $certificate->id) }}" target="_blank" 
               class="px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition-all flex items-center gap-2">
                <i data-lucide="file-down" class="w-4 h-4"></i>
                <span>Descargar / Imprimir PDF</span>
            </a>

            <!-- WhatsApp Share Button -->
            @php
                $pdfUrl = route('certificates.pdf', $certificate->id);
                $docName = $certificate->document_type === 'cotizacion' ? 'la Cotizaci&oacute;n' : 'el Certificado';
                $waText = rawurlencode("Hola {$certificate->client_name}, le compartimos {$docName} de Servicio N&deg; {$certificate->certificate_number} de Domingo Isa&iacute;n Plaza Caama&ntilde;o por un total de {$certificate->formatted_total}.\n\nEnlace directo para descargar el documento PDF:\n{$pdfUrl}");
                $waPhone = preg_replace('/[^0-9]/', '', $certificate->client_phone);
            @endphp
            <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank"
               class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-xs font-semibold text-sm rounded-xl transition-colors flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                <span>Enviar WhatsApp</span>
            </a>

            <!-- Edit Button -->
            <a href="{{ route('certificates.edit', $certificate->id) }}" 
               class="px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold text-sm rounded-xl border border-amber-200 shadow-xs transition-colors flex items-center gap-2">
                <i data-lucide="edit" class="w-4 h-4"></i>
                <span>Editar</span>
            </a>

            <!-- Back Button -->
            <a href="{{ route('certificates.index') }}" 
               class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 transition-colors flex items-center gap-1.5">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Volver</span>
            </a>

        </div>
    </div>

    <!-- On-screen Official Certificate Preview (A4 Paper Style) -->
    <div class="bg-white text-slate-900 rounded-2xl p-8 md:p-12 shadow-sm border border-slate-200/90 font-sans max-w-4xl mx-auto space-y-8 animate-fade-in-up stagger-1">
        
        <!-- 1. Header: Logo, Title & SEC Badge -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/branding/logo-domingoisain.png') }}" alt="Domingo Isa&iacute;n - T&eacute;cnico en Ingenier&iacute;a" class="h-20 w-auto">
            </div>

            <div class="text-center">
                <h2 class="text-2xl font-black uppercase tracking-wider text-slate-900 underline underline-offset-4">
                    {{ $certificate->document_type === 'cotizacion' ? 'COTIZACI&Oacute;N DE SERVICIO' : 'CERTIFICADO DE SERVICIO' }}
                </h2>
            </div>

            <div class="text-right space-y-1">
                <p class="text-sm font-bold">N&deg;: <span class="text-sky-600 text-lg">{{ $certificate->certificate_number }}</span></p>
                <p class="text-xs font-semibold text-slate-600">FECHA: {{ \Carbon\Carbon::parse($certificate->date)->format('d/m/Y') }}</p>
                <img src="{{ asset('images/logotipo-sec.png') }}" alt="Certificados SEC" class="h-20 w-auto ml-auto mt-1">
            </div>
        </div>

        <!-- 2. Client Data Box -->
        <div class="grid grid-cols-2 gap-6 bg-slate-50 p-5 rounded-xl border border-slate-200 text-sm">
            <div>
                <p class="font-bold text-slate-900 mb-2 uppercase tracking-wide text-xs">Datos Cliente:</p>
                <p><strong class="w-24 inline-block">Nombre:</strong> {{ $certificate->client_name }}</p>
                <p><strong class="w-24 inline-block">Provincia:</strong> {{ $certificate->client_provincia ?: 'Santiago' }}</p>
                <p><strong class="w-24 inline-block">Comuna:</strong> {{ $certificate->client_comuna ?: 'La Florida' }}</p>
            </div>
            <div>
                <p class="font-bold text-slate-900 mb-2 uppercase tracking-wide text-xs opacity-0">Contacto:</p>
                <p><strong class="w-24 inline-block">Tel&eacute;fono:</strong> {{ $certificate->client_phone ?: 'X' }}</p>
                <p><strong class="w-24 inline-block">Direcci&oacute;n:</strong> {{ $certificate->client_address }}</p>
            </div>
        </div>

        <!-- 3. Items Table -->
        <div class="overflow-hidden border border-slate-300 rounded-lg">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-900 font-bold border-b border-slate-300">
                        <th class="py-3 px-4">Descripci&oacute;n</th>
                        <th class="py-3 px-4 text-center">Precio Unit.</th>
                        <th class="py-3 px-4 text-center">Cantidad</th>
                        <th class="py-3 px-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($certificate->items_list as $item)
                        <tr>
                            <td class="py-3 px-4 font-semibold text-slate-800">{{ $item['description'] }}</td>
                            <td class="py-3 px-4 text-center">${{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-center">{{ $item['quantity'] }}</td>
                            <td class="py-3 px-4 text-right font-bold">${{ number_format($item['total'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 4. Technical Detail Box -->
        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 text-sm space-y-3">
            <h3 class="font-bold text-slate-900 uppercase tracking-wide text-xs border-b border-slate-200 pb-2">
                Detalle Trabajo:
            </h3>
            <div class="text-slate-800 whitespace-pre-line leading-relaxed font-sans">
                {{ $certificate->work_details }}
            </div>
        </div>

        <!-- 5. Images & Evidence Section -->
        @php
            $isPdf1 = $certificate->photo_1 && str_ends_with(strtolower($certificate->photo_1), '.pdf');
            $isPdf2 = $certificate->photo_2 && str_ends_with(strtolower($certificate->photo_2), '.pdf');
            $isPdf3 = $certificate->photo_3 && str_ends_with(strtolower($certificate->photo_3), '.pdf');
        @endphp

        @if(!$certificate->photo_1 && !$certificate->photo_3)
            <div class="max-w-md mx-auto text-center">
                <div class="border border-slate-200 rounded-xl p-4 bg-slate-50 flex flex-col items-center justify-between">
                    <div class="text-xs font-bold text-sky-800 uppercase tracking-tight mb-2">
                        Gasfiter Certificado Autorizado SEC<br>Domingo Isain
                    </div>
                    @if($certificate->photo_2)
                        @if($isPdf2)
                            <div class="my-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex flex-col items-center">
                                <i data-lucide="file-text" class="w-10 h-10 text-emerald-600 mb-2"></i>
                                <span class="text-xs font-bold text-emerald-900 mb-2">Documento SEC (PDF)</span>
                                <a href="{{ asset('storage/' . $certificate->photo_2) }}" target="_blank" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-xs flex items-center gap-1.5">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    <span>Ver Credencial PDF</span>
                                </a>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $certificate->photo_2) }}" alt="QR SEC" class="h-40 w-auto object-contain my-1">
                        @endif
                    @else
                        <img src="{{ asset('images/domingo-isain-gasfiter-sec-qr.png') }}" alt="QR SEC" class="h-40 w-auto object-contain my-1">
                    @endif
                    <span class="text-[11px] font-semibold text-slate-500 mt-2">Escanear para Verificaci&oacute;n SEC</span>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <!-- Photo 1 -->
                <div class="border border-slate-200 rounded-xl p-2 bg-slate-50 flex flex-col justify-between items-center">
                    @if($certificate->photo_1)
                        @if($isPdf1)
                            <div class="h-44 w-full flex flex-col items-center justify-center p-3 bg-sky-50 border border-sky-200 rounded-lg">
                                <i data-lucide="file-text" class="w-10 h-10 text-sky-600 mb-2"></i>
                                <span class="text-xs font-bold text-sky-900 mb-2">Documento de Inspección</span>
                                <a href="{{ asset('storage/' . $certificate->photo_1) }}" target="_blank" class="px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-lg shadow-xs flex items-center gap-1.5">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    <span>Ver Documento PDF</span>
                                </a>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $certificate->photo_1) }}" alt="Evidencia 1" class="h-44 w-full object-cover rounded-lg">
                        @endif
                    @else
                        <img src="{{ asset('images/logotipo-holding.png') }}" alt="Evidencia 1" class="h-44 w-full object-contain p-4 rounded-lg bg-white">
                    @endif
                    <span class="text-[11px] font-semibold text-slate-600 block mt-2">Evidencia de Instalaci&oacute;n / Fuga</span>
                </div>

                <!-- Photo 2: SEC QR -->
                <div class="border border-slate-200 rounded-xl p-2 bg-slate-50 flex flex-col items-center justify-between">
                    <div class="text-[11px] font-bold text-sky-800 uppercase tracking-tight">
                        Gasfiter Certificado Autorizado SEC<br>Domingo Isain
                    </div>
                    @if($certificate->photo_2)
                        @if($isPdf2)
                            <div class="my-2 p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex flex-col items-center">
                                <i data-lucide="file-text" class="w-8 h-8 text-emerald-600 mb-1"></i>
                                <span class="text-xs font-bold text-emerald-900 mb-2">Credencial SEC (PDF)</span>
                                <a href="{{ asset('storage/' . $certificate->photo_2) }}" target="_blank" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-xs flex items-center gap-1">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    <span>Ver PDF</span>
                                </a>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $certificate->photo_2) }}" alt="QR SEC" class="h-32 w-auto object-contain my-1">
                        @endif
                    @else
                        <img src="{{ asset('images/domingo-isain-gasfiter-sec-qr.png') }}" alt="QR SEC" class="h-32 w-auto object-contain my-1">
                    @endif
                    <span class="text-[10px] font-semibold text-slate-500">Escanear para Verificaci&oacute;n SEC</span>
                </div>

                <!-- Photo 3 -->
                <div class="border border-slate-200 rounded-xl p-2 bg-slate-50 flex flex-col justify-between items-center">
                    @if($certificate->photo_3)
                        @if($isPdf3)
                            <div class="h-44 w-full flex flex-col items-center justify-center p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <i data-lucide="file-text" class="w-10 h-10 text-amber-600 mb-2"></i>
                                <span class="text-xs font-bold text-amber-900 mb-2">Prueba / Medición</span>
                                <a href="{{ asset('storage/' . $certificate->photo_3) }}" target="_blank" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-lg shadow-xs flex items-center gap-1.5">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    <span>Ver Documento PDF</span>
                                </a>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $certificate->photo_3) }}" alt="Evidencia 3" class="h-44 w-full object-cover rounded-lg">
                        @endif
                    @else
                        <img src="{{ asset('images/logotipo-sec.png') }}" alt="Evidencia 3" class="h-44 w-full object-contain p-4 rounded-lg bg-white">
                    @endif
                    <span class="text-[11px] font-semibold text-slate-600 block mt-2">Prueba de Hermeticidad / Man&oacute;metro</span>
                </div>
            </div>
        @endif

        @if(!empty($certificate->extra_photos) && is_array($certificate->extra_photos))
            <div class="pt-2 border-t border-slate-200 space-y-2">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Fotograf&iacute;as Adicionales de Evidencia</h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @foreach($certificate->extra_photos as $idx => $exPath)
                        @php
                            $isExPdf = str_ends_with(strtolower($exPath), '.pdf');
                        @endphp
                        <div class="border border-slate-200 rounded-lg p-1.5 bg-slate-50 text-center flex flex-col justify-between items-center">
                            @if($isExPdf)
                                <div class="h-28 w-full flex flex-col items-center justify-center bg-purple-50 border border-purple-200 rounded p-2">
                                    <i data-lucide="file-text" class="w-6 h-6 text-purple-600 mb-1"></i>
                                    <a href="{{ asset('storage/' . $exPath) }}" target="_blank" class="text-[10px] font-bold text-purple-700 hover:underline">Ver PDF</a>
                                </div>
                            @else
                                <img src="{{ asset('storage/' . $exPath) }}" class="h-28 w-full object-cover rounded">
                            @endif
                            <span class="text-[10px] font-medium text-slate-500 mt-1 block">Foto Extra {{ $idx + 4 }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 6. Total Amount Bar -->
        <div class="border-t-2 border-b-2 border-slate-900 py-3 flex items-center justify-between">
            <span class="text-base font-bold text-slate-900 uppercase">Total Neto a Pagar</span>
            <span class="text-2xl font-black text-slate-900">${{ number_format($certificate->total_price, 0, ',', '.') }}</span>
        </div>

        <!-- 7. Footer Branding, QR Verification and Domingo's Digital Signature -->
        <div class="pt-4 grid grid-cols-12 gap-4 items-end text-xs text-slate-700">
            
            <!-- Company Info Left -->
            <div class="col-span-4 space-y-1">
                <p class="font-bold text-slate-900 text-sm">Domingo Isain Plaza Caama&ntilde;o</p>
                <p>RUT: 12738961-6</p>
                <p>Estado 215 oficina 703, Santiago</p>
                <p>Ingenier&iacute;a Civil y Servicios de la Construcci&oacute;n</p>
                <p>Gasfiter Certificado Autorizado SEC</p>
                <p>+56 9 4987 7316 / 949 877 316</p>
            </div>

            <!-- Sub-brand Logos Center -->
            <div class="col-span-4 flex items-center justify-center gap-2">
                <img src="{{ asset('images/logotipo-holding.png') }}" alt="Holding" class="h-28 w-auto">
            </div>

            <!-- Digital Signature Right -->
            <div class="col-span-4 text-center space-y-1 border-t border-slate-300 pt-2">
                <img src="{{ asset('images/firma-domingo.png') }}" alt="Firma Domingo Isain" class="h-44 w-auto mx-auto mb-1">
                <p class="font-bold text-slate-900 text-xs">Domingo Isa&iacute;n</p>
                <p class="text-[10px] text-slate-600 font-medium">T&eacute;cnico en Ingenier&iacute;a<br>Gasfiter Certificado Autorizado SEC<br>RUT: 12738961-6</p>
            </div>

        </div>

    </div>

</div>
@endsection
