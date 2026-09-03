<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                <th class="py-4 px-5">Folio / Fecha</th>
                <th class="py-4 px-5">Cliente & Contacto</th>
                <th class="py-4 px-5">Descripción Servicio</th>
                <th class="py-4 px-5">Tributación / Total</th>
                <th class="py-4 px-5">Emisor / Técnico</th>
                <th class="py-4 px-5 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            @forelse($certificates as $cert)
                <tr class="hover:bg-sky-50/40 transition-colors">
                    
                    <!-- Folio & Date -->
                    <td class="py-4 px-5">
                        <div class="flex items-center gap-2">
                            <span class="font-black text-sky-600 text-base">N° {{ $cert->certificate_number }}</span>
                        </div>
                        <span class="block text-xs text-slate-500 font-medium mt-0.5">{{ \Carbon\Carbon::parse($cert->date)->format('d/m/Y') }}</span>
                        
                        <!-- Document Type Badge -->
                        @if($cert->document_type === 'cotizacion')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700 border border-sky-200 mt-1.5 uppercase tracking-wide">
                                Cotizaci&oacute;n
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 mt-1.5 uppercase tracking-wide">
                                Certificado SEC
                            </span>
                        @endif
                    </td>

                    <!-- Client Info -->
                    <td class="py-4 px-5">
                        <span class="font-bold text-slate-900 block">{{ $cert->client_name }}</span>
                        <span class="text-xs text-slate-500 block mt-0.5 font-medium">
                            {{ $cert->client_address ? $cert->client_address . ', ' : '' }}{{ $cert->client_region ?: $cert->client_comuna }}
                        </span>
                        @if($cert->client_phone)
                            <span class="text-xs text-sky-600 font-semibold flex items-center gap-1 mt-1">
                                <i data-lucide="phone" class="w-3 h-3"></i> {{ $cert->client_phone }}
                            </span>
                        @endif
                    </td>

                    <!-- Description -->
                    <td class="py-4 px-5">
                        <p class="text-slate-700 font-medium text-xs line-clamp-2 max-w-xs leading-relaxed">{{ $cert->description }}</p>
                    </td>

                    <!-- Financial -->
                    <td class="py-4 px-5">
                        <div class="font-black text-slate-900 text-base tracking-tight">
                            ${{ number_format($cert->total_price, 0, ',', '.') }}
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 mt-1">
                            Valor Neto
                        </span>
                    </td>

                    <!-- Technician / SEC Responsible -->
                    <td class="py-4 px-5">
                        <span class="font-bold text-slate-800 text-xs block">
                            {{ $cert->gasfiter_name ?: 'Domingo Isain Plaza Caamaño' }}
                        </span>
                        <span class="text-[11px] text-slate-500 block mt-0.5">
                            RUT: {{ $cert->gasfiter_rut ?: '12.738.961-6' }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="py-4 px-5 text-right">
                        <div class="flex items-center justify-end gap-1.5 flex-wrap">
                            
                            <!-- Ver Detalle -->
                            <a href="{{ route('certificates.show', $cert->id) }}" 
                               title="Ver Certificado"
                               class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-colors">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>

                            <!-- Descargar PDF -->
                            <a href="{{ route('certificates.pdf', $cert->id) }}" target="_blank"
                               title="Descargar PDF Certificado"
                               class="p-2 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-xl transition-colors">
                                <i data-lucide="file-down" class="w-4 h-4"></i>
                            </a>

                            <!-- Compartir WhatsApp -->
                            @php
                                $pdfUrl = route('certificates.pdf', $cert->id);
                                $docName = $cert->document_type === 'cotizacion' ? 'la Cotización' : 'el Certificado';
                                $waText = rawurlencode("Hola {$cert->client_name}, le compartimos {$docName} de Servicio N° {$cert->certificate_number} de Domingo Isaín Plaza Caamaño por un total de {$cert->formatted_total}.\n\nEnlace directo para descargar el documento PDF:\n{$pdfUrl}");
                                $waPhone = preg_replace('/[^0-9]/', '', $cert->client_phone);
                            @endphp
                            <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank"
                               title="Enviar por WhatsApp"
                               class="p-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl transition-colors">
                                <i data-lucide="send" class="w-4 h-4"></i>
                            </a>

                            <!-- Editar -->
                            <a href="{{ route('certificates.edit', $cert->id) }}" 
                               title="Editar Certificado"
                               class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl transition-colors">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a>

                            <!-- Eliminar (Admin Only) -->
                            @if(Auth::user() && Auth::user()->isAdmin())
                                <form action="{{ route('certificates.destroy', $cert->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar el certificado N° {{ $cert->certificate_number }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Eliminar Certificado" class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl transition-colors cursor-pointer">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="file-x" class="w-12 h-12 text-slate-300 mb-3"></i>
                            <p class="text-base font-bold text-slate-800">No se encontraron certificados</p>
                            <p class="text-xs text-slate-500 mt-1">Pruebe modificando los términos de búsqueda o filtros.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($certificates->hasPages())
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        {{ $certificates->links('vendor.pagination.custom') }}
    </div>
@endif