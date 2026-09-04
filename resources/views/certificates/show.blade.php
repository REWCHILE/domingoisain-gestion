@extends('layouts.app')

@section('title', ($certificate->document_type === 'cotizacion' ? 'Cotización N° ' : 'Certificado N° ') . $certificate->certificate_number)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-xs animate-fade-in-up">
        <div>
            <span class="text-xs text-sky-600 font-bold uppercase tracking-wider block">
                {{ $certificate->document_type === 'cotizacion' ? 'Cotización Oficial' : 'Certificado Oficial SEC' }}
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">
                {{ $certificate->document_type === 'cotizacion' ? 'Cotización' : 'Certificado' }} de Servicio N° {{ $certificate->certificate_number }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">
                Emitido el {{ \Carbon\Carbon::parse($certificate->date)->format('d/m/Y') }} por {{ $certificate->gasfiter_name ?: 'Domingo Isain Plaza Caamaño' }}
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
            
            <!-- Download PDF Button -->
            <a href="{{ route('certificates.pdf', $certificate->id) }}" target="_blank" 
               class="flex-1 sm:flex-initial justify-center px-4 sm:px-5 py-2.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-sky-500/20 transition-all flex items-center gap-2">
                <i data-lucide="file-down" class="w-4 h-4 shrink-0"></i>
                <span class="whitespace-nowrap">Descargar PDF</span>
            </a>

            <!-- WhatsApp Share Button -->
            @php
                $pdfUrl = route('certificates.pdf', $certificate->id);
                $docName = $certificate->document_type === 'cotizacion' ? 'la Cotización' : 'el Certificado';
                $waText = rawurlencode("Hola {$certificate->client_name}, le compartimos {$docName} de Servicio N° {$certificate->certificate_number} de Domingo Isaín Plaza Caamaño por un total de {$certificate->formatted_total}.\n\nEnlace directo para descargar el documento PDF:\n{$pdfUrl}");
                $waPhone = preg_replace('/[^0-9]/', '', $certificate->client_phone);
            @endphp
            <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank"
               class="flex-1 sm:flex-initial justify-center px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-xs font-semibold text-xs sm:text-sm rounded-xl transition-colors flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4 shrink-0 text-emerald-600"></i>
                <span class="whitespace-nowrap">WhatsApp</span>
            </a>

            <!-- Edit Button -->
            <a href="{{ route('certificates.edit', $certificate->id) }}" 
               class="px-3 sm:px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold text-xs sm:text-sm rounded-xl border border-amber-200 shadow-xs transition-colors flex items-center gap-1.5 justify-center">
                <i data-lucide="edit" class="w-4 h-4 shrink-0"></i>
                <span>Editar</span>
            </a>

            <!-- Back Button -->
            <a href="{{ route('certificates.index') }}" 
               class="px-3 sm:px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl border border-slate-200 transition-colors flex items-center gap-1.5 justify-center">
                <i data-lucide="arrow-left" class="w-4 h-4 shrink-0"></i>
                <span>Volver</span>
            </a>

        </div>
    </div>

    <!-- On-screen Official Certificate Preview (A4 Paper Style) -->
    <div class="bg-white text-slate-900 rounded-2xl p-4 sm:p-6 md:p-10 shadow-sm border border-slate-200/90 font-sans max-w-4xl mx-auto space-y-6 md:space-y-8 animate-fade-in-up stagger-1">
        
        <!-- 1. Header: Logo, Title & SEC Badge -->
        <div class="border-b-2 border-slate-900 pb-5 space-y-4 md:space-y-0 md:flex md:items-center md:justify-between">
            
            <!-- Left on Desktop / Top Row on Mobile -->
            <div class="flex items-center justify-between md:justify-start gap-3">
                <img src="{{ asset('images/branding/logo-domingoisain.png') }}" 
                     alt="Domingo Isaín - Técnico en Ingeniería" 
                     class="h-12 sm:h-16 md:h-20 w-auto object-contain shrink-0">

                <!-- Mobile only: Right-side SEC info & badge -->
                <div class="md:hidden text-right space-y-0.5 shrink-0">
                    <p class="text-xs font-bold text-slate-900">N°: <span class="text-sky-600 text-sm font-black">{{ $certificate->certificate_number }}</span></p>
                    <p class="text-[10px] font-semibold text-slate-500">FECHA: {{ \Carbon\Carbon::parse($certificate->date)->format('d/m/Y') }}</p>
                    <img src="{{ asset('images/logotipo-sec.png') }}" alt="Certificados SEC" class="h-10 sm:h-12 w-auto ml-auto object-contain">
                </div>
            </div>

            <!-- Center Title -->
            <div class="text-center py-1 md:py-0 md:px-4">
                <h2 class="text-lg sm:text-xl md:text-2xl font-black uppercase tracking-wide text-slate-900 underline underline-offset-4">
                    {{ $certificate->document_type === 'cotizacion' ? 'COTIZACIÓN DE SERVICIO' : 'CERTIFICADO DE SERVICIO' }}
                </h2>
            </div>

            <!-- Desktop only: Right-side SEC Badge & Number -->
            <div class="hidden md:block text-right space-y-1 shrink-0">
                <p class="text-sm font-bold">N°: <span class="text-sky-600 text-lg font-black">{{ $certificate->certificate_number }}</span></p>
                <p class="text-xs font-semibold text-slate-600">FECHA: {{ \Carbon\Carbon::parse($certificate->date)->format('d/m/Y') }}</p>
                <img src="{{ asset('images/logotipo-sec.png') }}" alt="Certificados SEC" class="h-20 w-auto ml-auto mt-1 object-contain">
            </div>

        </div>

        <!-- 2. Client Data Box -->
        <div class="bg-slate-50 p-4 sm:p-5 rounded-xl border border-slate-200 text-xs sm:text-sm">
            <p class="font-bold text-slate-900 mb-3 uppercase tracking-wider text-[11px] sm:text-xs">Datos Cliente:</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2.5">
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-slate-900 min-w-[75px] sm:min-w-[85px] shrink-0">Nombre:</span>
                    <span class="text-slate-800 break-words">{{ $certificate->client_name }}</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-slate-900 min-w-[75px] sm:min-w-[85px] shrink-0">Teléfono:</span>
                    <span class="text-slate-800 break-words">{{ $certificate->client_phone ?: '-' }}</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-slate-900 min-w-[75px] sm:min-w-[85px] shrink-0">Provincia:</span>
                    <span class="text-slate-800 break-words">{{ $certificate->client_provincia ?: 'Santiago' }}</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-slate-900 min-w-[75px] sm:min-w-[85px] shrink-0">Dirección:</span>
                    <span class="text-slate-800 break-words">{{ $certificate->client_address ?: '-' }}</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-slate-900 min-w-[75px] sm:min-w-[85px] shrink-0">Comuna:</span>
                    <span class="text-slate-800 break-words">{{ $certificate->client_comuna }}</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-slate-900 min-w-[75px] sm:min-w-[85px] shrink-0">Email:</span>
                    <span class="text-slate-800 break-words">{{ $certificate->client_email ?: '-' }}</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-slate-900 min-w-[75px] sm:min-w-[85px] shrink-0">Región:</span>
                    <span class="text-slate-800 break-words">{{ $certificate->client_region }}</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-slate-900 min-w-[75px] sm:min-w-[85px] shrink-0">Modalidad:</span>
                    <span class="text-slate-800 uppercase font-semibold text-sky-700">Neto Directo</span>
                </div>
            </div>
        </div>

        <!-- 3. Items Table -->
        <div class="overflow-x-auto border border-slate-300 rounded-lg">
            <table class="w-full text-left text-xs sm:text-sm border-collapse min-w-[460px]">
                <thead>
                    <tr class="bg-slate-100 text-slate-900 font-bold border-b border-slate-300">
                        <th class="py-2.5 sm:py-3 px-3 sm:px-4">Descripción</th>
                        <th class="py-2.5 sm:py-3 px-2 sm:px-4 text-center whitespace-nowrap">Precio Unit.</th>
                        <th class="py-2.5 sm:py-3 px-2 sm:px-4 text-center whitespace-nowrap">Cantidad</th>
                        <th class="py-2.5 sm:py-3 px-3 sm:px-4 text-right whitespace-nowrap">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($certificate->items_list as $item)
                        <tr>
                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 font-medium text-slate-800">{!! nl2br(e(preg_replace('/<br\s*\/?>/i', "\n", $item['description']))) !!}</td>
                            <td class="py-2.5 sm:py-3 px-2 sm:px-4 text-center whitespace-nowrap">${{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                            <td class="py-2.5 sm:py-3 px-2 sm:px-4 text-center whitespace-nowrap">{{ $item['quantity'] }}</td>
                            <td class="py-2.5 sm:py-3 px-3 sm:px-4 text-right font-bold text-slate-900 whitespace-nowrap">${{ number_format($item['total'], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 4. Technical Detail Box -->
        <div class="bg-slate-50 p-4 sm:p-6 rounded-xl border border-slate-200 text-xs sm:text-sm space-y-2 sm:space-y-3">
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
                            <img src="{{ asset('storage/' . $certificate->photo_2) }}" alt="QR SEC" class="h-36 sm:h-40 w-auto object-contain my-1">
                        @endif
                    @else
                        <img src="{{ asset('images/domingo-isain-gasfiter-sec-qr.png') }}" alt="QR SEC" class="h-36 sm:h-40 w-auto object-contain my-1">
                    @endif
                    <span class="text-[11px] font-semibold text-slate-500 mt-2">Escanear para Verificación SEC</span>
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
                    <span class="text-[11px] font-semibold text-slate-600 block mt-2">Evidencia de Instalación / Fuga</span>
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
                    <span class="text-[10px] font-semibold text-slate-500">Escanear para Verificación SEC</span>
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
                    <span class="text-[11px] font-semibold text-slate-600 block mt-2">Prueba de Hermeticidad / Manómetro</span>
                </div>
            </div>
        @endif

        @if(!empty($certificate->extra_photos) && is_array($certificate->extra_photos))
            <div class="pt-2 border-t border-slate-200 space-y-2">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Fotografías Adicionales de Evidencia</h4>
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
        <div class="border-t-2 border-b-2 border-slate-900 py-2.5 sm:py-3 flex items-center justify-between">
            <span class="text-sm sm:text-base font-bold text-slate-900 uppercase">
                {{ $certificate->document_type === 'cotizacion' ? 'Total Cotizado Neto' : 'Total Neto' }} a Pagar
            </span>
            <span class="text-xl sm:text-2xl font-black text-slate-900">${{ number_format($certificate->total_price, 0, ',', '.') }}</span>
        </div>

        <!-- 7. Footer Branding & Domingo's Digital Signature -->
        <div class="pt-4 grid grid-cols-1 md:grid-cols-12 gap-6 md:gap-4 items-center md:items-end text-xs text-slate-700">
            
            <!-- Company Info Left -->
            <div class="col-span-1 md:col-span-4 space-y-1 text-center md:text-left">
                <p class="font-bold text-slate-900 text-sm">Domingo Isain Plaza Caamaño</p>
                <p>RUT: 12738961-6</p>
                <p>Estado 215 oficina 703, Santiago</p>
                <p>Ingeniería Civil y Construcción</p>
                <p>Gasfiter Certificado Autorizado SEC</p>
                <p>Tel: +56 9 4987 7316 / 949 877 316</p>
            </div>

            <!-- Logo Domingo Isaín Center -->
            <div class="col-span-1 md:col-span-4 flex items-center justify-center py-2 md:py-0">
                <img src="{{ asset('images/branding/logo-domingoisain.png') }}" alt="Domingo Isaín" class="h-16 md:h-20 w-auto object-contain">
            </div>

            <!-- Digital Signature Right -->
            <div class="col-span-1 md:col-span-4 text-center space-y-1 border-t border-slate-200 md:border-slate-300 pt-3 md:pt-2">
                <img src="{{ asset('images/firma-domingo.png') }}" alt="Firma Domingo Isain" class="h-36 sm:h-40 md:h-44 w-auto mx-auto mb-1 object-contain">
                <p class="font-bold text-slate-900 text-xs">Domingo Isaín®</p>
                <p class="text-[10px] text-slate-600 font-medium">
                    {{ $certificate->gasfiter_name ?: 'Domingo Isain Plaza Caamaño' }}<br>
                    RUT: {{ $certificate->gasfiter_rut ?: '12.738.961-6' }}
                </p>
            </div>

        </div>

    </div>

</div>
@endsection
