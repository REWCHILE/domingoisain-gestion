<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Certificate::with('user', 'client')->latest();

        // If technician, filter by user unless admin
        if ($user->isTechnician()) {
            $query->where('user_id', $user->id);
        }

        // Search filter (Search by Folio, Client Name, Address/Street/Number, Comuna, Phone, or Description)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('client_address', 'like', "%{$search}%")
                  ->orWhere('client_comuna', 'like', "%{$search}%")
                  ->orWhere('client_provincia', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Document type filter (cotizacion vs certificado)
        if ($docType = $request->input('document_type')) {
            $query->where('document_type', $docType);
        }

        $certificates = $query->paginate(15)->withQueryString();

        // Metrics calculations
        $metricsQuery = Certificate::query();
        if ($user->isTechnician()) {
            $metricsQuery->where('user_id', $user->id);
        }

        $totalCertificates = (clone $metricsQuery)->count();
        $totalNetoAmount = (clone $metricsQuery)->sum('subtotal_neto');
        $totalFacturasCount = (clone $metricsQuery)->where('tax_type', 'factura')->count();
        $totalSinDocCount = (clone $metricsQuery)->where('tax_type', 'neto')->count();
        $thisMonthCount = (clone $metricsQuery)->whereMonth('created_at', now()->month)->count();

        if ($request->ajax() || $request->wantsJson() || $request->has('ajax')) {
            $tableHtml = view('certificates.partials.table', compact('certificates'))->render();
            return response()->json([
                'tableHtml' => $tableHtml,
                'totalCertificates' => number_format($totalCertificates),
                'formattedNeto' => '$' . number_format($totalNetoAmount, 0, ',', '.'),
                'totalFacturasCount' => number_format($totalFacturasCount),
                'totalSinDocCount' => number_format($totalSinDocCount),
                'thisMonthCount' => number_format($thisMonthCount),
            ]);
        }

        return view('certificates.index', compact(
            'certificates',
            'totalCertificates',
            'totalNetoAmount',
            'totalFacturasCount',
            'totalSinDocCount',
            'thisMonthCount'
        ));
    }

    public function create()
    {
        // Generate next certificate folio number
        $lastCert = Certificate::orderByRaw('CAST(certificate_number AS UNSIGNED) DESC')->first();
        $nextNumber = $lastCert ? intval($lastCert->certificate_number) + 1 : 6574352;

        // Default details start empty so the user can select via template pills or type freely
        $defaultDetails = "";

        return view('certificates.create', compact('nextNumber', 'defaultDetails'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'certificate_number' => 'required|unique:certificates,certificate_number',
            'document_type' => 'required|in:certificado,cotizacion',
            'date' => 'required|date',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:100',
            'client_address' => 'nullable|string|max:255',
            'client_region' => 'nullable|string|max:100',
            'client_comuna' => 'nullable|string|max:100',
            'client_provincia' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'items' => 'nullable|array',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'tax_type' => 'nullable|in:neto,factura',
            'work_details' => 'nullable|string',
            'gasfiter_name' => 'required|string',
            'gasfiter_rut' => 'required|string',
            'gasfiter_sec_class' => 'required|string',
            'status' => 'nullable|in:emitido,pendiente,completado,anulado',
            'photo_1' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:15360',
            'photo_2' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:15360',
            'photo_3' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:15360',
            'extra_photos' => 'nullable|array',
            'extra_photos.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:15360',
        ]);

        $validated['status'] = !empty($validated['status']) ? $validated['status'] : $request->input('status', 'emitido');
        $validated['tax_type'] = 'neto';

        // Process items list
        $rawItems = $request->input('items', []);
        $processedItems = [];
        $subtotal = 0;

        if (is_array($rawItems) && count($rawItems) > 0) {
            foreach ($rawItems as $item) {
                $desc = trim($item['description'] ?? '');
                if ($desc === '') continue;
                $qty = max(1, intval($item['quantity'] ?? 1));
                $price = max(0, floatval($item['unit_price'] ?? 0));
                $subtotal += ($qty * $price);
                $processedItems[] = [
                    'description' => $desc,
                    'quantity' => $qty,
                    'unit_price' => $price,
                ];
            }
        }

        if (count($processedItems) > 0) {
            $mainDescription = count($processedItems) === 1 
                ? $processedItems[0]['description'] 
                : implode(' / ', array_column($processedItems, 'description'));
            $mainQuantity = $processedItems[0]['quantity'];
            $mainUnitPrice = $processedItems[0]['unit_price'];
        } else {
            $mainDescription = $request->input('description') ?: 'Servicio Técnico';
            $mainQuantity = max(1, intval($request->input('quantity', 1)));
            $mainUnitPrice = max(0, floatval($request->input('unit_price', 0)));
            $subtotal = $mainQuantity * $mainUnitPrice;
            $processedItems = [
                [
                    'description' => $mainDescription,
                    'quantity' => $mainQuantity,
                    'unit_price' => $mainUnitPrice,
                ]
            ];
        }

        $taxAmount = 0;
        $totalPrice = $subtotal;

        // Auto-save or link Client catalog
        $client = Client::firstOrCreate(
            ['name' => $validated['client_name']],
            [
                'phone' => $validated['client_phone'],
                'address' => $validated['client_address'],
                'comuna' => $validated['client_comuna'],
                'provincia' => $validated['client_provincia'],
            ]
        );

        // Upload and optimize photos if provided (Auto-resizes Full HD/4K photos from mobile phones to 1200px)
        $photoPaths = [];
        foreach (['photo_1', 'photo_2', 'photo_3'] as $photoKey) {
            if ($request->hasFile($photoKey)) {
                $photoPaths[$photoKey] = $this->uploadAndOptimizeImage($request->file($photoKey), 'certificates');
            } else {
                $photoPaths[$photoKey] = null;
            }
        }

        // Upload extra evidence photos (Photo 4, Photo 5...)
        $extraPaths = [];
        if ($request->hasFile('extra_photos')) {
            foreach ($request->file('extra_photos') as $file) {
                if ($file && $file->isValid()) {
                    $optPath = $this->uploadAndOptimizeImage($file, 'certificates');
                    if ($optPath) {
                        $extraPaths[] = $optPath;
                    }
                }
            }
        }

        $certificate = Certificate::create([
            'certificate_number' => $validated['certificate_number'],
            'document_type' => $validated['document_type'] ?? 'certificado',
            'date' => $validated['date'],
            'user_id' => Auth::id(),
            'client_id' => $client->id,
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'],
            'client_address' => $validated['client_address'],
            'client_region' => $validated['client_region'] ?? 'Región Metropolitana',
            'client_comuna' => $validated['client_comuna'],
            'client_provincia' => $validated['client_provincia'],
            'description' => $mainDescription,
            'items' => $processedItems,
            'quantity' => $mainQuantity,
            'unit_price' => $mainUnitPrice,
            'subtotal_neto' => $subtotal,
            'tax_type' => $validated['tax_type'],
            'tax_amount' => $taxAmount,
            'total_price' => $totalPrice,
            'work_details' => $validated['work_details'],
            'gasfiter_name' => $validated['gasfiter_name'],
            'gasfiter_rut' => $validated['gasfiter_rut'],
            'gasfiter_sec_class' => $validated['gasfiter_sec_class'],
            'status' => $validated['status'],
            'photo_1' => $photoPaths['photo_1'],
            'photo_2' => $photoPaths['photo_2'],
            'photo_3' => $photoPaths['photo_3'],
            'extra_photos' => $extraPaths,
        ]);

        return redirect()->route('certificates.show', $certificate->id)
            ->with('success', 'Certificado N° ' . $certificate->certificate_number . ' emitido exitosamente.');
    }

    public function show(Certificate $certificate)
    {
        // Check authorization
        $user = Auth::user();
        if ($user->isTechnician() && $certificate->user_id !== $user->id) {
            abort(403, 'No está autorizado para ver este certificado.');
        }

        return view('certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        $user = Auth::user();
        if ($user->isTechnician() && $certificate->user_id !== $user->id) {
            abort(403, 'No está autorizado para editar este certificado.');
        }

        return view('certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $user = Auth::user();
        if ($user->isTechnician() && $certificate->user_id !== $user->id) {
            abort(403, 'No está autorizado para modificar este certificado.');
        }

        $validated = $request->validate([
            'certificate_number' => 'required|unique:certificates,certificate_number,' . $certificate->id,
            'document_type' => 'required|in:certificado,cotizacion',
            'date' => 'required|date',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'nullable|string|max:100',
            'client_address' => 'nullable|string|max:255',
            'client_region' => 'nullable|string|max:100',
            'client_comuna' => 'nullable|string|max:100',
            'client_provincia' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'items' => 'nullable|array',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'tax_type' => 'nullable|in:neto,factura',
            'work_details' => 'nullable|string',
            'gasfiter_name' => 'required|string',
            'gasfiter_rut' => 'required|string',
            'gasfiter_sec_class' => 'required|string',
            'status' => 'required|in:emitido,pendiente,completado,anulado',
            'photo_1' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:15360',
            'photo_2' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:15360',
            'photo_3' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:15360',
            'extra_photos' => 'nullable|array',
            'extra_photos.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:15360',
        ]);

        $validated['tax_type'] = 'neto';

        // Process items list
        $rawItems = $request->input('items', []);
        $processedItems = [];
        $subtotal = 0;

        if (is_array($rawItems) && count($rawItems) > 0) {
            foreach ($rawItems as $item) {
                $desc = trim($item['description'] ?? '');
                if ($desc === '') continue;
                $qty = max(1, intval($item['quantity'] ?? 1));
                $price = max(0, floatval($item['unit_price'] ?? 0));
                $subtotal += ($qty * $price);
                $processedItems[] = [
                    'description' => $desc,
                    'quantity' => $qty,
                    'unit_price' => $price,
                ];
            }
        }

        if (count($processedItems) > 0) {
            $mainDescription = count($processedItems) === 1 
                ? $processedItems[0]['description'] 
                : implode(' / ', array_column($processedItems, 'description'));
            $mainQuantity = $processedItems[0]['quantity'];
            $mainUnitPrice = $processedItems[0]['unit_price'];
        } else {
            $mainDescription = $request->input('description') ?: 'Servicio Técnico';
            $mainQuantity = max(1, intval($request->input('quantity', 1)));
            $mainUnitPrice = max(0, floatval($request->input('unit_price', 0)));
            $subtotal = $mainQuantity * $mainUnitPrice;
            $processedItems = [
                [
                    'description' => $mainDescription,
                    'quantity' => $mainQuantity,
                    'unit_price' => $mainUnitPrice,
                ]
            ];
        }

        $taxAmount = 0;
        $totalPrice = $subtotal;

        $updateData = [
            'certificate_number' => $validated['certificate_number'],
            'document_type' => $validated['document_type'] ?? 'certificado',
            'date' => $validated['date'],
            'client_name' => $validated['client_name'],
            'client_phone' => $validated['client_phone'],
            'client_address' => $validated['client_address'],
            'client_region' => $validated['client_region'] ?? 'Región Metropolitana',
            'client_comuna' => $validated['client_comuna'],
            'client_provincia' => $validated['client_provincia'],
            'description' => $mainDescription,
            'items' => $processedItems,
            'quantity' => $mainQuantity,
            'unit_price' => $mainUnitPrice,
            'subtotal_neto' => $subtotal,
            'tax_type' => $validated['tax_type'],
            'tax_amount' => $taxAmount,
            'total_price' => $totalPrice,
            'work_details' => $validated['work_details'],
            'gasfiter_name' => $validated['gasfiter_name'],
            'gasfiter_rut' => $validated['gasfiter_rut'],
            'gasfiter_sec_class' => $validated['gasfiter_sec_class'],
            'status' => $validated['status'],
        ];

        foreach (['photo_1', 'photo_2', 'photo_3'] as $photoKey) {
            if ($request->hasFile($photoKey)) {
                if ($certificate->$photoKey) {
                    Storage::disk('public')->delete($certificate->$photoKey);
                }
                $updateData[$photoKey] = $this->uploadAndOptimizeImage($request->file($photoKey), 'certificates');
            }
        }

        $extraPaths = $certificate->extra_photos ?: [];
        if ($request->hasFile('extra_photos')) {
            foreach ($request->file('extra_photos') as $file) {
                if ($file && $file->isValid()) {
                    $optPath = $this->uploadAndOptimizeImage($file, 'certificates');
                    if ($optPath) {
                        $extraPaths[] = $optPath;
                    }
                }
            }
        }
        $updateData['extra_photos'] = $extraPaths;

        $certificate->update($updateData);

        return redirect()->route('certificates.show', $certificate->id)
            ->with('success', 'Certificado actualizado correctamente.');
    }

    public function destroy(Certificate $certificate)
    {
        $user = Auth::user();
        if (! $user->isAdmin()) {
            abort(403, 'Solo el Administrador Domingo Isain puede eliminar certificados.');
        }

        foreach (['photo_1', 'photo_2', 'photo_3'] as $photoKey) {
            if ($certificate->$photoKey) {
                Storage::disk('public')->delete($certificate->$photoKey);
            }
        }

        $number = $certificate->certificate_number;
        $certificate->delete();

        return redirect()->route('certificates.index')
            ->with('success', "Certificado N° {$number} eliminado.");
    }

    public function downloadPdf(Certificate $certificate)
    {
        // Prepare base64 images for DomPDF embedding
        $logoBase64 = $this->getImageBase64(public_path('images/branding/logo-domingoisain.png'));
        $secLogoBase64 = $this->getImageBase64(public_path('images/logotipo-sec.png'));
        $secQrBase64 = $certificate->photo_2 
            ? $this->getImageBase64(storage_path('app/public/' . $certificate->photo_2))
            : $this->getImageBase64(public_path('images/domingo-isain-gasfiter-sec-qr.png'));
        $holdingLogoBase64 = $this->getImageBase64(public_path('images/logotipo-holding.png'));
        $firmaBase64 = $this->getImageBase64(public_path('images/firma-domingo.png'));

        $photo1Base64 = $certificate->photo_1 ? $this->getImageBase64(storage_path('app/public/' . $certificate->photo_1)) : null;
        $photo3Base64 = $certificate->photo_3 ? $this->getImageBase64(storage_path('app/public/' . $certificate->photo_3)) : null;

        $extraPhotosBase64 = [];
        if (!empty($certificate->extra_photos) && is_array($certificate->extra_photos)) {
            foreach ($certificate->extra_photos as $exPath) {
                $b64 = $this->getImageBase64(storage_path('app/public/' . $exPath));
                if ($b64) {
                    $extraPhotosBase64[] = $b64;
                }
            }
        }

        // Generate QR code for registro_instalgaschile.pdf
        $registroQrBase64 = '';
        try {
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(140, 1),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $pdfUrl = url('registro_instalgaschile.pdf');
            $svgContent = $writer->writeString($pdfUrl);
            $registroQrBase64 = 'data:image/svg+xml;base64,' . base64_encode($svgContent);
        } catch (\Throwable $e) {
            $registroQrBase64 = '';
        }

        $pdf = Pdf::loadView('certificates.pdf', compact(
            'certificate',
            'logoBase64',
            'secLogoBase64',
            'secQrBase64',
            'holdingLogoBase64',
            'firmaBase64',
            'photo1Base64',
            'photo3Base64',
            'extraPhotosBase64',
            'registroQrBase64'
        ));

        $pdf->setPaper('a4', 'portrait');

        $prefix = $certificate->document_type === 'cotizacion' ? 'Cotizacion_Servicio' : 'Certificado_Servicio';
        return $pdf->stream("{$prefix}_{$certificate->certificate_number}.pdf");
    }

    private function getImageBase64($path)
    {
        if (file_exists($path)) {
            $type = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if ($type === 'pdf') {
                return '';
            }
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        return '';
    }

    /**
     * Auto-resizes and optimizes high-resolution (Full HD / 4K) mobile images
     * to a maximum width of 1200px with 85% JPEG quality.
     */
    private function uploadAndOptimizeImage($file, $path = 'certificates')
    {
        if (!$file || !$file->isValid()) return null;

        // If the uploaded file is a PDF, store it directly without image processing
        $ext = strtolower($file->getClientOriginalExtension());
        if ($ext === 'pdf' || $file->getMimeType() === 'application/pdf') {
            return $file->store($path, 'public');
        }

        $filename = \Illuminate\Support\Str::random(40) . '.jpg';
        $storageDirectory = storage_path('app/public/' . $path);
        if (!file_exists($storageDirectory)) {
            mkdir($storageDirectory, 0755, true);
        }
        $fullPath = $storageDirectory . '/' . $filename;

        if (extension_loaded('gd')) {
            $info = @getimagesize($file->getRealPath());
            if ($info) {
                $mime = $info['mime'];
                $srcImg = null;
                if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                    $srcImg = @imagecreatefromjpeg($file->getRealPath());
                } elseif ($mime === 'image/png') {
                    $srcImg = @imagecreatefrompng($file->getRealPath());
                } elseif ($mime === 'image/webp') {
                    $srcImg = @imagecreatefromwebp($file->getRealPath());
                }

                if ($srcImg) {
                    $origW = imagesx($srcImg);
                    $origH = imagesy($srcImg);
                    $maxW = 1200;

                    if ($origW > $maxW) {
                        $newW = $maxW;
                        $newH = intval(($origH / $origW) * $newW);
                        $destImg = imagecreatetruecolor($newW, $newH);

                        if ($mime === 'image/png') {
                            imagealphablending($destImg, false);
                            imagesavealpha($destImg, true);
                        }

                        imagecopyresampled($destImg, $srcImg, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
                        imagejpeg($destImg, $fullPath, 85);
                        imagedestroy($destImg);
                        imagedestroy($srcImg);
                        return $path . '/' . $filename;
                    } else {
                        imagejpeg($srcImg, $fullPath, 85);
                        imagedestroy($srcImg);
                        return $path . '/' . $filename;
                    }
                }
            }
        }

        return $file->store($path, 'public');
    }

    public function showImportForm()
    {
        return view('certificates.import');
    }

    public function processImport(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file',
        ]);

        $file = $request->file('sql_file');
        $sqlContent = file_get_contents($file->getRealPath());

        $importedCount = 0;
        $skippedCount = 0;

        preg_match_all('/INSERT\s+INTO\s+[`"]?cotizacion[`"]?\s*\([^)]*\)\s*VALUES\s*(.*?);/is', $sqlContent, $matches);

        $tuples = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $valuesBlock) {
                preg_match_all('/\(([^()]+(?:\([^()]*\)[^()]*)*)\)/s', $valuesBlock, $groupMatches);
                foreach ($groupMatches[1] as $grp) {
                    $tuples[] = $grp;
                }
            }
        } else {
            preg_match_all('/\((.*?)\)/s', $sqlContent, $tupleMatches);
            $tuples = $tupleMatches[1] ?? [];
        }

        $itemsMap = [];
        preg_match_all('/INSERT\s+INTO\s+[`"]?detalle_cotiza[`"]?\s*\([^)]*\)\s*VALUES\s*(.*?);/is', $sqlContent, $detMatches);
        if (!empty($detMatches[1])) {
            foreach ($detMatches[1] as $valuesBlock) {
                preg_match_all('/\(([^()]+(?:\([^()]*\)[^()]*)*)\)/s', $valuesBlock, $groupMatches);
                foreach ($groupMatches[1] as $grp) {
                    $itemVals = str_getcsv($grp, ',', "'");
                    if (count($itemVals) >= 4) {
                        $cotId = trim($itemVals[1] ?? '0');
                        $desc = trim(str_replace(['\r\n', '\n', '\r', "\\r\\n", "\\n", "\\r"], ' ', $itemVals[2] ?? ''));
                        $desc = preg_replace('/\s+/', ' ', $desc);
                        $itemPrecio = floatval(trim($itemVals[3] ?? 0));
                        $itemQty = intval(trim($itemVals[4] ?? 1));
                        if ($itemQty <= 0) $itemQty = 1;

                        if ($cotId !== '0' && !empty($desc)) {
                            $itemsMap[$cotId][] = [
                                'description' => $desc,
                                'quantity' => $itemQty,
                                'unit_price' => $itemPrecio,
                            ];
                        }
                    }
                }
            }
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($tuples as $tuple) {
                $values = str_getcsv($tuple, ',', "'");
                if (count($values) < 14) continue;

                $id = trim($values[0] ?? '');
                $precio = floatval(trim($values[1] ?? 0));
                $cliNombre = trim($values[3] ?? 'Cliente Sin Nombre');
                $cliTelefono = trim($values[4] ?? '');
                $cliComunaId = trim($values[7] ?? '');
                $cliDireccion = trim($values[8] ?? '');
                $estadoVal = trim($values[10] ?? '1');
                $creado = trim($values[11] ?? now());
                $modificado = trim($values[12] ?? now());
                $detalle = trim(str_replace(['\r\n', '\n', '\r', "\\r\\n", "\\n", "\\r"], "\n", $values[13] ?? ''));

                if (!is_numeric($id) || intval($id) <= 0) continue;

                $folioNumber = intval($id);

                if (Certificate::where('certificate_number', (string)$folioNumber)->exists()) {
                    $skippedCount++;
                    continue;
                }

                $docType = ($estadoVal == '1') ? 'cotizacion' : 'certificado';
                $status = ($estadoVal == '2') ? 'completado' : 'emitido';

                $client = Client::firstOrCreate(
                    ['name' => $cliNombre],
                    [
                        'phone' => $cliTelefono,
                        'address' => $cliDireccion,
                        'comuna' => $cliComunaId ?: 'Santiago',
                        'provincia' => 'Santiago',
                    ]
                );

                $shortDesc = trim(str_replace(['\r\n', '\n', '\r', "\\r\\n", "\\n", "\\r"], ' ', mb_substr(strip_tags($detalle), 0, 150)));
                $shortDesc = preg_replace('/\s+/', ' ', $shortDesc);
                if (empty($shortDesc)) {
                    $shortDesc = $docType === 'cotizacion' ? 'Cotización de Servicio' : 'Certificado de Servicio SEC';
                }

                $fullItemDesc = !empty($detalle) ? $detalle : $shortDesc;

                $certItems = $itemsMap[$id] ?? [
                    [
                        'description' => $fullItemDesc,
                        'quantity' => 1,
                        'unit_price' => $precio,
                    ]
                ];

                Certificate::create([
                    'certificate_number' => (string)$folioNumber,
                    'document_type' => $docType,
                    'date' => \Carbon\Carbon::parse($creado)->format('Y-m-d'),
                    'user_id' => \Illuminate\Support\Facades\Auth::id(),
                    'client_id' => $client->id,
                    'client_name' => $cliNombre,
                    'client_phone' => $cliTelefono,
                    'client_address' => $cliDireccion,
                    'client_comuna' => $cliComunaId ?: 'Santiago',
                    'client_provincia' => 'Santiago',
                    'description' => $shortDesc,
                    'items' => $certItems,
                    'quantity' => 1,
                    'unit_price' => $precio,
                    'subtotal_neto' => $precio,
                    'tax_type' => 'neto',
                    'tax_amount' => 0,
                    'total_price' => $precio,
                    'work_details' => $detalle,
                    'gasfiter_name' => 'Domingo Isain Plaza Caamaño',
                    'gasfiter_rut' => '12738961-6',
                    'gasfiter_sec_class' => 'Gasfiter Certificado Autorizado SEC',
                    'status' => $status,
                    'created_at' => \Carbon\Carbon::parse($creado),
                    'updated_at' => \Carbon\Carbon::parse($modificado),
                ]);

                $importedCount++;
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('certificates.index')
                ->with('success', "Importación completada: {$importedCount} registros importados, {$skippedCount} omitidos.");

        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', "Error al importar archivo SQL: " . $e->getMessage());
        }
    }
}
