<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    protected $table = 'order_items';

    public const UPDATED_AT = null;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_unit_id',
        'product_name',
        'unit_name',
        'quantity',
        'base_quantity',
        'selling_price',
        'subtotal',
        'hpp',
        'total_hpp',
        'profit',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'base_quantity' => 'decimal:3',
        'selling_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'hpp' => 'decimal:2',
        'total_hpp' => 'decimal:2',
        'profit' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class);
    }

    public function saleBatchAllocations(): HasMany
    {
        return $this->hasMany(SaleBatchAllocation::class);
    }
}