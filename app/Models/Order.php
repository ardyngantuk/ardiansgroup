<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'tracking_token',
        'checkout_token',
        'customer_id',
        'order_date',
        'subtotal',
        'discount',
        'shipping_cost',
        'total',
        'order_status',
        'payment_method',
        'payment_status',
        'stock_status',
        'delivery_address',
        'delivery_zone',
        'delivery_distance_km',
        'customer_note',
        'admin_note',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'delivery_distance_km' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function shipping(): HasOne
    {
        return $this->hasOne(Shipping::class);
    }

    public function saleBatchAllocations()
    {
        return $this->hasManyThrough(
            SaleBatchAllocation::class,
            OrderItem::class,
            'order_id',
            'order_item_id',
            'id',
            'id'
        );
    }
    public function activities(): HasMany
    {
        return $this->hasMany(OrderActivity::class);
    }

    public function getShippingStatusAttribute(): ?string
    {
        return $this->shipping?->status;
    }

    public function canTransitionTo(string $nextStatus): bool
    {
        if ($nextStatus === $this->order_status) {
            return true;
        }

        return in_array($nextStatus, [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['ready', 'cancelled'],
            'ready' => ['shipping', 'cancelled'],
            'shipping' => ['completed'],
            'completed' => [],
            'cancelled' => [],
        ][$this->order_status] ?? [], true);
    }
}