<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_number',
        'document_type',
        'date',
        'user_id',
        'client_id',
        'client_name',
        'client_phone',
        'client_address',
        'client_region',
        'client_comuna',
        'client_provincia',
        'description',
        'items',
        'quantity',
        'unit_price',
        'subtotal_neto',
        'tax_type',
        'tax_amount',
        'total_price',
        'work_details',
        'gasfiter_name',
        'gasfiter_rut',
        'gasfiter_sec_class',
        'photo_1',
        'photo_2',
        'photo_3',
        'extra_photos',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'items' => 'array',
            'extra_photos' => 'array',
            'unit_price' => 'decimal:2',
            'subtotal_neto' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_price' => 'decimal:2',
            'quantity' => 'integer',
        ];
    }

    public function getExtraPhotosUrlsAttribute(): array
    {
        if (!empty($this->extra_photos) && is_array($this->extra_photos)) {
            return array_map(function ($path) {
                return asset('storage/' . $path);
            }, $this->extra_photos);
        }
        return [];
    }

    public function getItemsListAttribute(): array
    {
        if (!empty($this->items) && is_array($this->items)) {
            return array_map(function ($item) {
                $qty = isset($item['quantity']) ? (int)$item['quantity'] : 1;
                $price = isset($item['unit_price']) ? (float)$item['unit_price'] : 0;
                return [
                    'description' => $item['description'] ?? '',
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'total' => $qty * $price,
                ];
            }, $this->items);
        }

        return [
            [
                'description' => $this->description ?: 'Servicio Técnico',
                'quantity' => $this->quantity ?: 1,
                'unit_price' => (float)($this->unit_price ?: 0),
                'total' => (float)($this->subtotal_neto ?: (($this->quantity ?: 1) * ($this->unit_price ?: 0))),
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return '$' . number_format($this->total_price, 0, ',', '.');
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return '$' . number_format($this->subtotal_neto, 0, ',', '.');
    }

    public function getFormattedTaxAttribute(): string
    {
        return '$' . number_format($this->tax_amount, 0, ',', '.');
    }

    public function getPhoto1UrlAttribute(): string
    {
        if ($this->photo_1) {
            return asset('storage/' . $this->photo_1);
        }
        return asset('images/logotipo-holding.png');
    }

    public function getPhoto2UrlAttribute(): string
    {
        if ($this->photo_2) {
            return asset('storage/' . $this->photo_2);
        }
        return asset('images/domingo-isain-gasfiter-sec-qr.png');
    }

    public function getPhoto3UrlAttribute(): string
    {
        if ($this->photo_3) {
            return asset('storage/' . $this->photo_3);
        }
        return asset('images/logotipo-sec.png');
    }
}
