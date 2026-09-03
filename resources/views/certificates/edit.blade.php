@extends('layouts.app')

@section('title', 'Editar Certificado N° {{ $certificate->certificate_number }}')

@section('content')
@php
    $initialItems = old('items', $certificate->items_list ?? []);
    if (empty($initialItems)) {
        $initialItems = [['description' => $certificate->description, 'quantity' => $certificate->quantity, 'unit_price' => $certificate->unit_price]];
    }
    $initialDocType = old('document_type', $certificate->document_type);
    $initialRegion = old('client_region', $certificate->client_region ?: 'Región Metropolitana');
    $initialComuna = old('client_comuna', $certificate->client_comuna ?: 'La Florida');
@endphp

<div class="max-w-5xl mx-auto space-y-6" x-data="certificateForm()">

    <!-- Page Title Header -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                <i data-lucide="file-check" class="w-7 h-7 text-emerald-600"></i>
                <span>Editar Certificado de Servicio N° {{ $certificate->certificate_number }}</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1 font-medium">Complete los datos requeridos para emitir el documento oficial de Domingo Isa&iacute;n Plaza Caama&ntilde;o</p>
        </div>
        <a href="{{ route('certificates.index') }}" class="px-4 py-2 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 shadow-xs transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Volver al Listado</span>
        </a>
    </div>

    <!-- Main Form -->
    <form action="{{ route('certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- 1. Tipo de Documento, Folio & Fecha Block -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i data-lucide="layers" class="w-4 h-4 text-sky-600"></i>
                    <span>Seleccione Tipo de Documento a Emitir</span>
                    <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Option 1: Certificado SEC -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="document_type" value="certificado" x-model="documentType" class="sr-only">
                        <div :class="documentType === 'certificado' ? 'bg-emerald-50 border-2 border-emerald-500 text-emerald-950 shadow-md ring-4 ring-emerald-500/10' : 'bg-white border-2 border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-50/50'"
                             class="p-4 rounded-2xl flex items-center justify-between transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div :class="documentType === 'certificado' ? 'bg-emerald-500 text-white shadow-xs' : 'bg-slate-100 text-slate-500'"
                                     class="p-2.5 rounded-xl transition-colors">
                                    <i data-lucide="award" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-black uppercase tracking-wide">CERTIFICADO DE SERVICIO SEC</div>
                                    <div class="text-xs text-slate-500 font-medium mt-0.5">Documento oficial con respaldo normativo SEC</div>
                                </div>
                            </div>
                            <div :class="documentType === 'certificado' ? 'border-emerald-600 bg-emerald-600' : 'border-slate-300'"
                                 class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all shrink-0">
                                <div x-show="documentType === 'certificado'" class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                    </label>

                    <!-- Option 2: Cotización de Servicio -->
                    <label class="cursor-pointer group">
                        <input type="radio" name="document_type" value="cotizacion" x-model="documentType" class="sr-only">
                        <div :class="documentType === 'cotizacion' ? 'bg-sky-50 border-2 border-sky-500 text-sky-950 shadow-md ring-4 ring-sky-500/10' : 'bg-white border-2 border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-50/50'"
                             class="p-4 rounded-2xl flex items-center justify-between transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div :class="documentType === 'cotizacion' ? 'bg-sky-500 text-white shadow-xs' : 'bg-slate-100 text-slate-500'"
                                     class="p-2.5 rounded-xl transition-colors">
                                    <i data-lucide="file-text" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-black uppercase tracking-wide">COTIZACI&Oacute;N DE SERVICIO</div>
                                    <div class="text-xs text-slate-500 font-medium mt-0.5">Propuesta comercial previa para el cliente</div>
                                </div>
                            </div>
                            <div :class="documentType === 'cotizacion' ? 'border-sky-600 bg-sky-600' : 'border-slate-300'"
                                 class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all shrink-0">
                                <div x-show="documentType === 'cotizacion'" class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-3 border-t border-slate-200">
                <div>
                    <label for="certificate_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        N&deg; Folio <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="certificate_number" name="certificate_number" value="{{ old('certificate_number', $certificate->certificate_number) }}" required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-sky-600 font-black text-lg focus:outline-none focus:bg-white focus:border-sky-500 shadow-xs">
                </div>

                <div>
                    <label for="date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Fecha de Emisi&oacute;n <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="date" name="date" value="{{ old('date', $certificate->date) }}" required
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-semibold focus:outline-none focus:bg-white focus:border-sky-500 shadow-xs">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Estado del Documento <span class="text-rose-500">*</span>
                    </label>
                    
    <select id="status" name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-semibold focus:outline-none focus:bg-white focus:border-sky-500 shadow-xs">
        <option value="emitido" {{ old('status', $certificate->status) === 'emitido' ? 'selected' : '' }}>Emitido / Vigente</option>
        <option value="completado" {{ old('status', $certificate->status) === 'completado' ? 'selected' : '' }}>Completado</option>
        <option value="pendiente" {{ old('status', $certificate->status) === 'pendiente' ? 'selected' : '' }}>Pendiente de Trabajo</option>
        <option value="anulado" {{ old('status', $certificate->status) === 'anulado' ? 'selected' : '' }}>Anulado</option>
    </select>
    
                </div>
            </div>
        </div>

        <!-- 2. Datos del Cliente (Dynamic Region & Comunas of Chile) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
            <h2 class="text-lg font-black text-slate-900 flex items-center gap-2 border-b border-slate-200 pb-3">
                <i data-lucide="user" class="w-5 h-5 text-sky-600"></i>
                <span>Datos del Cliente</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="client_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Nombre / Raz&oacute;n Social <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $certificate->client_name) }}" required placeholder="Ej: Don Juan / Inmobiliaria XYZ"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:outline-none focus:bg-white focus:border-sky-500 shadow-xs">
                </div>

                <div>
                    <label for="client_phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tel&eacute;fono de Contacto</label>
                    <input type="text" id="client_phone" name="client_phone" value="{{ old('client_phone', $certificate->client_phone) }}" placeholder="Ej: +56 9 8888 7777"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:outline-none focus:bg-white focus:border-sky-500 shadow-xs">
                </div>

                <div class="md:col-span-2">
                    <label for="client_address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Direcci&oacute;n Completa</label>
                    <input type="text" id="client_address" name="client_address" value="{{ old('client_address', $certificate->client_address) }}" placeholder="Ej: Nuestra Se&ntilde;ora de F&aacute;tima 9530"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:outline-none focus:bg-white focus:border-sky-500 shadow-xs">
                </div>

                <!-- Automatic Región Selector -->
                <div>
                    <label for="client_region" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Regi&oacute;n de Chile <span class="text-rose-500">*</span>
                    </label>
                    <select id="client_region" name="client_region" x-model="selectedRegion" @change="onRegionChange()"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-semibold text-sm focus:outline-none focus:bg-white focus:border-sky-500 shadow-xs">
                        <template x-for="rName in Object.keys(regions)" :key="rName">
                            <option :value="rName" x-text="rName" :selected="rName === selectedRegion"></option>
                        </template>
                    </select>
                </div>

                <!-- Automatic Comunas Selector -->
                <div>
                    <label for="client_comuna" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Comuna <span class="text-rose-500">*</span>
                    </label>
                    <select id="client_comuna" name="client_comuna" x-model="selectedComuna"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 font-semibold text-sm focus:outline-none focus:bg-white focus:border-sky-500 shadow-xs">
                        <template x-for="cName in availableComunas" :key="cName">
                            <option :value="cName" x-text="cName" :selected="cName === selectedComuna"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <!-- 3. Servicio & Cálculo de Precios con Ítems Dinámicos -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <i data-lucide="calculator" class="w-5 h-5 text-emerald-600"></i>
                    <span>Descripci&oacute;n de Servicios e &Iacute;tems</span>
                </h2>
                
                <button type="button" @click="addItem()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>+ A&ntilde;adir Servicio</span>
                </button>
            </div>

            <!-- Dynamic Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                            <th class="px-4 py-3 min-w-[260px]">Descripci&oacute;n del Servicio</th>
                            <th class="px-4 py-3 w-28 text-center">Cantidad</th>
                            <th class="px-4 py-3 w-40 text-center">Precio Unitario ($)</th>
                            <th class="px-4 py-3 w-40 text-right">Total ($)</th>
                            <th class="px-4 py-3 w-16 text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-4 py-3">
                                    <input type="text" :name="'items[' + index + '][description]'" x-model="item.description" required
                                           placeholder="Ej: Sellado de fugas de gas / Certificaci&oacute;n SEC"
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-medium focus:border-sky-500 shadow-xs">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.quantity" min="1" required
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-bold text-center focus:border-sky-500 shadow-xs">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" :name="'items[' + index + '][unit_price]'" x-model.number="item.unit_price" min="0" step="1000" required
                                           class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 text-sm font-bold text-right focus:border-sky-500 shadow-xs">
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="font-black text-slate-900 text-base" x-text="formatMoney((item.quantity || 0) * (item.unit_price || 0))"></span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="Eliminar este servicio">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <input type="hidden" name="tax_type" value="neto">

            <!-- Dynamic Live Calculation Summary Card (High Contrast & Clear Banner) -->
            <div class="p-6 rounded-2xl bg-slate-900 text-white border-2 border-slate-800 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <i data-lucide="receipt" class="w-5 h-5 text-sky-400"></i>
                        <span class="text-xs font-bold text-sky-400 uppercase tracking-wider">Desglose de Pago:</span>
                    </div>
                    <p class="text-xs text-slate-300">Valores expresados exclusivamente en <strong class="text-white">Neto Directo</strong></p>
                </div>
                <div class="sm:text-right">
                    <span class="text-xs font-extrabold text-slate-300 uppercase tracking-wider block mb-1">Total Neto a Pagar</span>
                    <span class="text-3xl sm:text-4xl font-black text-emerald-400 tracking-tight" x-text="formatMoney(total)"></span>
                </div>
            </div>
        </div>

        <!-- 4. Detalle Técnico del Trabajo SEC -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-3">
                <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <i data-lucide="wrench" class="w-5 h-5 text-amber-600"></i>
                    <span>Detalle T&eacute;cnico del Trabajo SEC</span>
                </h2>
                
                <!-- Quick Preset Buttons (With Lucide icons) -->
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs text-slate-500 font-semibold">Plantillas r&aacute;pidas:</span>
                    <button type="button" @click="applyTemplate('sellado')" 
                            :class="activeTemplate === 'sellado' ? 'bg-sky-600 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="px-3 py-1.5 text-xs rounded-xl border border-slate-200 transition-all cursor-pointer flex items-center gap-1.5">
                        <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-sky-500"></i>
                        <span>Sellado Fuga DS66</span>
                    </button>
                    <button type="button" @click="applyTemplate('hermeticidad')" 
                            :class="activeTemplate === 'hermeticidad' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="px-3 py-1.5 text-xs rounded-xl border border-slate-200 transition-all cursor-pointer flex items-center gap-1.5">
                        <i data-lucide="gauge" class="w-3.5 h-3.5 text-emerald-500"></i>
                        <span>Prueba Hermeticidad</span>
                    </button>
                    <button type="button" @click="applyTemplate('calefon')" 
                            :class="activeTemplate === 'calefon' ? 'bg-amber-600 text-white font-bold shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                            class="px-3 py-1.5 text-xs rounded-xl border border-slate-200 transition-all cursor-pointer flex items-center gap-1.5">
                        <i data-lucide="flame" class="w-3.5 h-3.5 text-amber-500"></i>
                        <span>Calef&oacute;n / Caldera</span>
                    </button>
                </div>
            </div>

            <!-- High-Contrast Dynamic Input Box for Fuga de Gas Template -->
            <template x-if="activeTemplate === 'sellado'">
                <div class="p-5 bg-sky-50 border-2 border-sky-200 rounded-2xl space-y-4 shadow-xs">
                    <div class="flex items-center justify-between flex-wrap gap-2 border-b border-sky-200/80 pb-3">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 rounded-lg bg-sky-600 text-white shadow-xs">
                                <i data-lucide="sliders" class="w-4 h-4"></i>
                            </div>
                            <span class="text-sm font-black text-slate-900 uppercase tracking-wider">
                                Ajustes Din&aacute;micos: Plantilla Sellado Fugas de Gas
                            </span>
                        </div>
                        <span class="text-xs text-sky-800 font-semibold bg-sky-100 px-3 py-1 rounded-full border border-sky-200">
                            Modifica los valores para actualizar la descripci&oacute;n en tiempo real
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="ruler" class="w-4 h-4 text-sky-600"></i>
                                <span>Metros Lineales de Red:</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" 
                                       x-model.number="metrosLineales" 
                                       @input="updateSelladoText()" 
                                       min="1" max="1000" 
                                       placeholder="30"
                                       class="w-full px-4 py-2.5 bg-white border-2 border-sky-200 rounded-xl text-slate-900 text-base font-bold focus:border-sky-600 focus:ring-2 focus:ring-sky-500/20 shadow-xs">
                                <span class="text-xs font-bold text-slate-700 shrink-0 bg-white px-3 py-2.5 rounded-xl border border-sky-200">metros</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                <i data-lucide="gauge" class="w-4 h-4 text-sky-600"></i>
                                <span>Presi&oacute;n de Hermeticidad (mmca):</span>
                            </label>
                            <div class="flex items-center gap-2">
                                <input type="number" 
                                       x-model.number="presionMmca" 
                                       @input="updateSelladoText()" 
                                       min="1" max="2000" 
                                       placeholder="368"
                                       class="w-full px-4 py-2.5 bg-white border-2 border-sky-200 rounded-xl text-slate-900 text-base font-bold focus:border-sky-600 focus:ring-2 focus:ring-sky-500/20 shadow-xs">
                                <span class="text-xs font-bold text-slate-700 shrink-0 bg-white px-3 py-2.5 rounded-xl border border-sky-200">mmca</span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <div>
                <label for="work_details" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Detalle del Trabajo, Normativas SEC, Garant&iacute;a y Mediciones
                </label>
                <textarea id="work_details" name="work_details" rows="7" placeholder="Describa aqu&iacute; las observaciones t&eacute;cnicas, garant&iacute;a o pruebas realizadas..."
                          class="w-full p-4 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-sm leading-relaxed focus:bg-white focus:border-sky-500 shadow-xs font-mono">{{ old('work_details', $certificate->work_details) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                <div>
                    <label for="gasfiter_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Responsable T&eacute;cnico</label>
                    <input type="text" id="gasfiter_name" name="gasfiter_name" value="{{ old('gasfiter_name', $certificate->gasfiter_name ?: 'Domingo Isain Plaza Caamaño') }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 text-xs font-semibold focus:bg-white focus:border-sky-500 shadow-xs">
                </div>
                <div>
                    <label for="gasfiter_rut" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">RUT Gasfiter SEC</label>
                    <input type="text" id="gasfiter_rut" name="gasfiter_rut" value="{{ old('gasfiter_rut', $certificate->gasfiter_rut ?: '12738961-6') }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 text-xs font-semibold focus:bg-white focus:border-sky-500 shadow-xs">
                </div>
                <div>
                    <label for="gasfiter_sec_class" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Acreditaci&oacute;n SEC</label>
                    <input type="text" id="gasfiter_sec_class" name="gasfiter_sec_class" value="{{ old('gasfiter_sec_class', $certificate->gasfiter_sec_class ?: 'Gasfiter Certificado Autorizado SEC') }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 text-xs font-semibold focus:bg-white focus:border-sky-500 shadow-xs">
                </div>
            </div>
        </div>

        <!-- 5. Evidencia Fotográfica con Lucide SVG Icons -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-6"
             x-data="{ 
                 extraPhotos: [],
                 addExtraPhoto() {
                     this.extraPhotos.push({ id: Date.now(), fileName: '', previewUrl: null });
                     this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
                 },
                 removeExtraPhoto(index) {
                     this.extraPhotos.splice(index, 1);
                 }
             }">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3 flex-wrap gap-2">
                <div>
                    <h2 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <i data-lucide="image" class="w-5 h-5 text-purple-600"></i>
                        <span>Evidencia Fotogr&aacute;fica / Documentos del Servicio</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5 font-medium">Seleccione fotograf&iacute;as o archivos desde su galer&iacute;a/dispositivo o capture con la c&aacute;mara</p>
                </div>

                <button type="button" @click="addExtraPhoto()" class="px-3.5 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold rounded-xl border border-purple-200 transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>+ A&ntilde;adir Archivo/Foto Extra (Foto 4+)</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Photo 1 -->
                <div x-data="{ 
                        fileName: '', 
                        previewUrl: null,
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (file) {
                                this.fileName = file.name;
                                this.previewUrl = URL.createObjectURL(file);
                            }
                        }
                    }"
                     class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-xs hover:border-sky-300 transition-all space-y-3 flex flex-col justify-between">
                    <div>
                        <label class="block text-xs font-bold text-sky-700 uppercase tracking-wider mb-2">Imagen 1: Trabajo / Fuga / Evidencia</label>
                        <input type="file" id="photo_1_input" name="photo_1" @change="handleFile($event)" class="hidden">
                        
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="document.getElementById('photo_1_input').click()" 
                                    class="w-full py-2.5 px-4 bg-sky-50 hover:bg-sky-100 text-sky-700 text-xs font-bold rounded-xl border border-sky-200 transition-all flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                                <i data-lucide="upload-cloud" class="w-4 h-4 text-sky-600"></i>
                                <span>Subir Archivo / Foto</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <template x-if="fileName">
                            <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-bold">
                                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                                <span class="truncate max-w-[180px]" x-text="fileName"></span>
                            </div>
                        </template>
                        <template x-if="!fileName">
                            <span class="text-slate-400 text-[11px] font-medium italic block text-center">Sin archivo seleccionado</span>
                        </template>

                        <template x-if="previewUrl">
                            <div class="mt-2 text-center">
                                <img :src="previewUrl" class="h-28 w-full object-cover rounded-xl border border-slate-200 shadow-xs">
                            </div>
                        </template>
                        
    @if($certificate->photo_1)
        <div class="mt-2 text-center" x-show="!previewUrl">
            <span class="text-[10px] text-slate-500 block mb-1 font-semibold">Imagen actual:</span>
            <img src="{{ asset('storage/' . $certificate->photo_1) }}" class="h-28 w-full object-cover rounded-xl border border-slate-200 shadow-xs">
        </div>
    @endif
    

                        <p class="text-[11px] text-slate-400 pt-1 border-t border-slate-100 font-medium">Fotograf&iacute;a de la red, equipo o fuga inspeccionada.</p>
                    </div>
                </div>

                <!-- Photo 2 (Default SEC QR) -->
                <div x-data="{ 
                        fileName: '', 
                        previewUrl: null,
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (file) {
                                this.fileName = file.name;
                                this.previewUrl = URL.createObjectURL(file);
                            }
                        }
                    }"
                     class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-xs hover:border-emerald-300 transition-all space-y-3 flex flex-col justify-between">
                    <div>
                        <label class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2">Imagen 2: Credencial / QR SEC</label>
                        <input type="file" id="photo_2_input" name="photo_2" @change="handleFile($event)" class="hidden">
                        
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="document.getElementById('photo_2_input').click()" 
                                    class="w-full py-2.5 px-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-200 transition-all flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                                <i data-lucide="upload-cloud" class="w-4 h-4 text-emerald-600"></i>
                                <span>Subir Archivo / Foto</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <template x-if="fileName">
                            <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-bold">
                                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                                <span class="truncate max-w-[180px]" x-text="fileName"></span>
                            </div>
                        </template>
                        <template x-if="!fileName">
                            <span class="text-slate-400 text-[11px] font-medium italic block text-center">Usar&aacute; QR Oficial SEC de Domingo</span>
                        </template>

                        <template x-if="previewUrl">
                            <div class="mt-2 text-center">
                                <img :src="previewUrl" class="h-28 w-full object-cover rounded-xl border border-slate-200 shadow-xs">
                            </div>
                        </template>
                        
    @if($certificate->photo_2)
        <div class="mt-2 text-center" x-show="!previewUrl">
            <span class="text-[10px] text-slate-500 block mb-1 font-semibold">Imagen actual:</span>
            <img src="{{ asset('storage/' . $certificate->photo_2) }}" class="h-28 w-full object-cover rounded-xl border border-slate-200 shadow-xs">
        </div>
    @endif
    

                        <p class="text-[11px] text-slate-400 pt-1 border-t border-slate-100 font-medium">Credencial QR SEC Domingo Isa&iacute;n Plaza Caama&ntilde;o.</p>
                    </div>
                </div>

                <!-- Photo 3 -->
                <div x-data="{ 
                        fileName: '', 
                        previewUrl: null,
                        handleFile(e) {
                            const file = e.target.files[0];
                            if (file) {
                                this.fileName = file.name;
                                this.previewUrl = URL.createObjectURL(file);
                            }
                        }
                    }"
                     class="bg-white p-5 rounded-2xl border-2 border-slate-200 shadow-xs hover:border-amber-300 transition-all space-y-3 flex flex-col justify-between">
                    <div>
                        <label class="block text-xs font-bold text-amber-700 uppercase tracking-wider mb-2">Imagen 3: Medici&oacute;n / Man&oacute;metro</label>
                        <input type="file" id="photo_3_input" name="photo_3" @change="handleFile($event)" class="hidden">
                        
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="document.getElementById('photo_3_input').click()" 
                                    class="w-full py-2.5 px-4 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold rounded-xl border border-amber-200 transition-all flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                                <i data-lucide="upload-cloud" class="w-4 h-4 text-amber-600"></i>
                                <span>Subir Archivo / Foto</span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <template x-if="fileName">
                            <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-bold">
                                <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                                <span class="truncate max-w-[180px]" x-text="fileName"></span>
                            </div>
                        </template>
                        <template x-if="!fileName">
                            <span class="text-slate-400 text-[11px] font-medium italic block text-center">Sin archivo seleccionado</span>
                        </template>

                        <template x-if="previewUrl">
                            <div class="mt-2 text-center">
                                <img :src="previewUrl" class="h-28 w-full object-cover rounded-xl border border-slate-200 shadow-xs">
                            </div>
                        </template>
                        
    @if($certificate->photo_3)
        <div class="mt-2 text-center" x-show="!previewUrl">
            <span class="text-[10px] text-slate-500 block mb-1 font-semibold">Imagen actual:</span>
            <img src="{{ asset('storage/' . $certificate->photo_3) }}" class="h-28 w-full object-cover rounded-xl border border-slate-200 shadow-xs">
        </div>
    @endif
    

                        <p class="text-[11px] text-slate-400 pt-1 border-t border-slate-100 font-medium">Prueba de hermeticidad o instrumento de medici&oacute;n.</p>
                    </div>
                </div>

            </div>

            <!-- Dynamic Extra Photos Container (Foto 4, 5...) -->
            <template x-if="extraPhotos.length > 0">
                <div class="pt-4 border-t border-slate-200 space-y-4">
                    <h3 class="text-xs font-bold text-purple-700 uppercase tracking-wider">Fotos Adicionales de Evidencia (Foto 4+)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <template x-for="(ex, index) in extraPhotos" :key="ex.id">
                            <div class="bg-white p-4 rounded-2xl border-2 border-purple-200 shadow-xs space-y-3 flex flex-col justify-between relative">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-xs font-bold text-purple-700 uppercase tracking-wider" x-text="'Foto Adicional ' + (index + 4)"></label>
                                    <button type="button" @click="removeExtraPhoto(index)" class="text-rose-500 hover:bg-rose-50 p-1.5 rounded-lg transition-colors cursor-pointer" title="Eliminar foto extra">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>

                                <input type="file" :id="'extra_photo_' + index" name="extra_photos[]"
                                       @change="const file = $event.target.files[0]; if(file) { ex.fileName = file.name; ex.previewUrl = URL.createObjectURL(file); }"
                                       class="hidden">

                                <button type="button" @click="document.getElementById('extra_photo_' + index).click()"
                                        class="w-full py-2.5 px-3 bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold rounded-xl border border-purple-200 transition-all flex items-center justify-center gap-2 cursor-pointer shadow-xs">
                                    <i data-lucide="upload-cloud" class="w-4 h-4 text-purple-600"></i>
                                    <span>Subir Archivo / Foto</span>
                                </button>

                                <div class="space-y-2">
                                    <template x-if="ex.fileName">
                                        <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-bold">
                                            <i data-lucide="check-circle" class="w-4 h-4 shrink-0"></i>
                                            <span class="truncate max-w-[180px]" x-text="ex.fileName"></span>
                                        </div>
                                    </template>
                                    <template x-if="!ex.fileName">
                                        <span class="text-slate-400 text-[11px] font-medium italic block text-center">Sin archivo seleccionado</span>
                                    </template>

                                    <template x-if="ex.previewUrl">
                                        <div class="mt-2 text-center">
                                            <img :src="ex.previewUrl" class="h-28 w-full object-cover rounded-xl border border-slate-200 shadow-xs">
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>

        <!-- Submit Button Bar -->
        <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-200">
            <a href="{{ route('certificates.index') }}" class="px-6 py-3 bg-white hover:bg-slate-50 text-slate-700 shadow-xs font-bold text-sm rounded-xl border border-slate-200 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-black text-sm rounded-xl shadow-lg shadow-emerald-500/25 hover:-translate-y-0.5 transition-all flex items-center gap-2 cursor-pointer">
                <i data-lucide="check-circle" class="w-5 h-5"></i> <span>Guardar Cambios</span>
            </button>
        </div>

    </form>

</div>

<script>
function certificateForm() {
    const chileRegions = {"Región Metropolitana": ["Santiago", "Cerrillos", "Cerro Navia", "Conchalí", "El Bosque", "Estación Central", "Huechuraba", "Independencia", "La Cisterna", "La Florida", "La Granja", "La Pintana", "La Reina", "Las Condes", "Lo Barnechea", "Lo Espejo", "Lo Prado", "Macul", "Maipú", "Ñuñoa", "Pedro Aguirre Cerda", "Peñalolén", "Providencia", "Pudahuel", "Quilicura", "Quinta Normal", "Recoleta", "Renca", "San Joaquín", "San Miguel", "San Ramón", "Vitacura", "Puente Alto", "Pirque", "San José de Maipo", "San Bernardo", "Buin", "Calera de Tango", "Paine", "Melipilla", "Alhué", "Curacaví", "María Pinto", "San Pedro", "Talagante", "El Monte", "Isla de Maipo", "Padre Hurtado", "Peñaflor", "Colina", "Lampa", "Til Til"], "Región de Valparaíso": ["Valparaíso", "Viña del Mar", "Concón", "Quilpué", "Villa Alemana", "Limache", "Olmué", "Quillota", "La Calera", "Hijuelas", "La Cruz", "Nogales", "San Antonio", "Cartagena", "El Quisco", "El Tabo", "Santo Domingo", "San Felipe", "Los Andes", "Casablanca", "Llaillay", "Panquehue", "Putaendo", "Santa María", "Calle Larga", "Rinconada", "San Esteban", "Cabildo", "La Ligua", "Papudo", "Petorca", "Zapallar", "Algarrobo", "Isla de Pascua", "Juan Fernández"], "Región de O'Higgins": ["Rancagua", "Machalí", "Graneros", "Mostazal", "Codegua", "Doñihue", "Coltauco", "Coinco", "Peumo", "Pichidegua", "Las Cabras", "San Vicente", "Rengo", "Malloa", "Quinta de Tilcoco", "San Fernando", "Chimbarongo", "Placilla", "Nancagua", "Chépica", "Santa Cruz", "Lolol", "Pumanque", "Palmilla", "Peralillo", "Pichilemu", "Navidad", "Litueche", "La Estrella", "Marchigüe", "Paredones"], "Región del Maule": ["Talca", "San Clemente", "Pelarco", "Pencahue", "Maule", "San Rafael", "Curepto", "Constitución", "Empedrado", "Curicó", "Teno", "Romeral", "Molina", "Sagrada Familia", "Hualañé", "Licantén", "Vichuquén", "Rauco", "Linares", "Yerbas Buenas", "Colbún", "Longaví", "Parral", "Retiro", "Villa Alegre", "San Javier", "Cauquenes", "Pelluhue", "Chanco"], "Región del Biobío": ["Concepción", "Coronel", "Chiguayante", "Florida", "Hualqui", "Lota", "Penco", "San Pedro de la Paz", "Santa Juana", "Talcahuano", "Tomé", "Hualpén", "Los Ángeles", "Antuco", "Cabrero", "Laja", "Mulchén", "Nacimiento", "Negrete", "Quilaco", "Quilleco", "San Rosendo", "Santa Bárbara", "Tucapel", "Yumbel", "Alto Biobío", "Lebu", "Arauco", "Cañete", "Contulmo", "Curanilahue", "Los Álamos", "Tirúa"], "Región de La Araucanía": ["Temuco", "Padre Las Casas", "Carahue", "Cholchol", "Cunco", "Curarrehue", "Freire", "Galvarino", "Gorbea", "Lautaro", "Loncoche", "Melipeuco", "Nueva Imperial", "Perquenco", "Pitrufquén", "Pucón", "Saavedra", "Teodoro Schmidt", "Toltén", "Vilcún", "Villarrica", "Angol", "Collipulli", "Curacautín", "Ercilla", "Lonquimay", "Los Sauces", "Lumaco", "Purén", "Renaico", "Traiguén", "Victoria"], "Región de Coquimbo": ["La Serena", "Coquimbo", "Andacollo", "La Higuera", "Paihuano", "Vicuña", "Illapel", "Canela", "Los Vilos", "Salamanca", "Ovalle", "Combarbalá", "Monte Patria", "Punitaqui", "Río Hurtado"], "Región de Antofagasta": ["Antofagasta", "Mejillones", "Sierra Gorda", "Taltal", "Calama", "Ollagüe", "San Pedro de Atacama", "Tocopilla", "María Elena"], "Región de Los Lagos": ["Puerto Montt", "Calbuco", "Cochamó", "Fresia", "Frutillar", "Los Muermos", "Llanquihue", "Maullín", "Puerto Varas", "Castro", "Ancud", "Chonchi", "Curaco de Vélez", "Dalcahue", "Puqueldón", "Queilén", "Quellón", "Quemchi", "Quinchao", "Osorno", "Puerto Octay", "Purranque", "Puyehue", "Río Negro", "San Juan de la Costa", "San Pablo", "Chaitén", "Futaleufú", "Hualaihué", "Palena"], "Región de Los Ríos": ["Valdivia", "Corral", "Lanco", "Los Lagos", "Máfil", "Mariquina", "Paillaco", "Panguipulli", "La Unión", "Futrono", "Lago Ranco", "Río Bueno"], "Región de Tarapacá": ["Iquique", "Alto Hospicio", "Pozo Almonte", "Camiña", "Colchane", "Huara", "Pica"], "Región de Arica y Parinacota": ["Arica", "Camarones", "Putre", "General Lagos"], "Región de Atacama": ["Copiapó", "Caldera", "Tierra Amarilla", "Chañaral", "Diego de Almagro", "Vallenar", "Alto del Carmen", "Freirina", "Huasco"], "Región de Ñuble": ["Chillán", "Chillán Viejo", "Quirihue", "Cobquecura", "Ninhue", "Treguaco", "San Carlos", "Coihueco", "San Nicolás", "San Fabián", "Pinto", "Bulnes", "San Ignacio", "El Carmen", "Yungay", "Pemuco", "Quillón", "Ránquil", "Portezuelo", "Coelemu"], "Región de Aysén": ["Coyhaique", "Lago Verde", "Aysén", "Cisnes", "Guaitecas", "Cochrane", "O'Higgins", "Tortel", "Chile Chico", "Río Ibáñez"], "Región de Magallanes": ["Punta Arenas", "Laguna Blanca", "Río Verde", "San Gregorio", "Cabo de Hornos", "Antártica", "Porvenir", "Primavera", "Timaukel", "Natales", "Torres del Paine"]};

    return {
        documentType: {{ json_encode($initialDocType) }},
        regions: chileRegions,
        selectedRegion: {{ json_encode($initialRegion) }},
        selectedComuna: {{ json_encode($initialComuna) }},
        items: {{ json_encode($initialItems) }},
        taxType: 'neto',
        activeTemplate: null,
        metrosLineales: 30,
        presionMmca: 368,

        get availableComunas() {
            return this.regions[this.selectedRegion] || [];
        },

        onRegionChange() {
            const list = this.availableComunas;
            if (list.length > 0 && !list.includes(this.selectedComuna)) {
                this.selectedComuna = list[0];
            }
        },

        addItem() {
            this.items.push({ description: '', quantity: 1, unit_price: 0 });
            this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        get subtotal() {
            return this.items.reduce((sum, item) => sum + ((Number(item.quantity) || 0) * (Number(item.unit_price) || 0)), 0);
        },

        get total() {
            return this.subtotal;
        },

        formatMoney(val) {
            return '$' + new Intl.NumberFormat('es-CL').format(val || 0);
        },

        updateSelladoText() {
            this.activeTemplate = 'sellado';
            const area = document.getElementById('work_details');
            if (!area) return;
            const m = this.metrosLineales || 30;
            const p = this.presionMmca || 368;
            area.value = "Se realizó sellado de fuga de gas en red de " + m + " metros lineales aproximadamente\n\n" +
                "Se asegura hermeticidad de acuerdo al Decreto Supremo 66 Artículo 44.2.3 SEC no importa si es una o más fugas. Se utilizará prodoral r6-1 sellante alemán para Fugas de Gas aceptado por SEC ds66 artículo 7: DIN EN 13090 Y NAG-203.\n\n" +
                "En procedimiento necesitamos desconectar artefactos y medidor para realizar la inyección, necesitamos provisión de electricidad y acceso libre a su domicilio y medidor mientras dure el procedimiento.\n\n" +
                "Prueba de hermeticidad final a " + p + "mmca estanco por 5 minutos, sin fugas.\n\n" +
                "Tiempo de ejecución 2 horas aproximadamente, se entrega certificado de servicio realizado, garantía 3 años por efectos de sellado.\n" +
                "Se solicita pago contado una vez realizado el trabajo.\n\n" +
                "Responsable Domingo Isain Plaza Caamaño Rut 12738961-6\n" +
                "Gasfiter Certificado Autorizado SEC";
        },

        applyTemplate(type) {
            const area = document.getElementById('work_details');
            if (this.activeTemplate === type) {
                this.activeTemplate = null;
                if (area) area.value = '';
            } else {
                this.activeTemplate = type;
                if (type === 'sellado') {
                    this.updateSelladoText();
                } else if (type === 'hermeticidad') {
                    if (area) area.value = "Prueba de hermeticidad en instalación de gas según normativa SEC DS66.\n\nSe realiza presurización a 368 mmca manteniéndose estable por 15 minutos sin caídas de presión.\n\nInstalación apta y conforme a normas de seguridad vigentes.";
                } else if (type === 'calefon') {
                    if (area) area.value = "Servicio técnico y mantenimiento preventivo/correctivo de artefacto a gas (Calefón/Caldera).\n\nVerificación de ducto de evacuación de gases de combustión, limpieza de inyectores, prueba de encendido y sellado de conexiones. Proceso verificado bajo norma SEC.";
                }
            }
        }
    };
}
</script>
@endsection
