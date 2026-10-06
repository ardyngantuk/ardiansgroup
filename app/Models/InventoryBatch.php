<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryBatch extends Model
{
    protected $table = 'inventory_batches';

    protected $fillable = [
        'product_id',
        'purchase_item_id',
        'received_at',
        'original_quantity',
        'remaining_quantity',
        'unit_cost',
        'status',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'original_quantity' => 'decimal:3',
        'remaining_quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseItem::class);
    }

    public function saleAllocations(): HasMany
    {
        return $this->hasMany(SaleBatchAllocation::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isDepleted(): bool
    {
        return $this->status === 'depleted';
    }
}