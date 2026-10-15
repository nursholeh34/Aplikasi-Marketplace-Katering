<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_LABELS = [
        'pending' => 'Menunggu konfirmasi',
        'confirmed' => 'Dikonfirmasi',
        'delivered' => 'Terkirim',
        'cancelled' => 'Dibatalkan',
    ];

    /** Alur status yang diizinkan. */
    public const TRANSITIONS = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['delivered', 'cancelled'],
        'delivered' => [],
        'cancelled' => [],
    ];

    protected $fillable = [
        'invoice_number', 'customer_id', 'merchant_id', 'delivery_date',
        'delivery_address', 'notes', 'total', 'status',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
            'total' => 'integer',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function nextStatuses(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'text-bg-warning',
            'confirmed' => 'text-bg-primary',
            'delivered' => 'text-bg-success',
            'cancelled' => 'text-bg-secondary',
            default => 'text-bg-light',
        };
    }
}
