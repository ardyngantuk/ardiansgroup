<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'legacy_code',
        'name',
        'category_id',
        'brand_id',
        'legacy_type',
        'department',
        'rack',
        'serial_flag',
        'consignment_flag',
        'hpp_method',
        'tax_type',
        'tax_included',
        'initial_stock',
        'minimum_stock',
        'current_stock',
        'reference_code',
        'description',
        'status',
    ];

    protected $casts = [
        'serial_flag' => 'boolean',
        'consignment_flag' => 'boolean',
        'tax_included' => 'boolean',
        'initial_stock' => 'decimal:3',
        'minimum_stock' => 'decimal:3',
        'current_stock' => 'decimal:3',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function thumbnail(): HasOne
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_thumbnail', true)
            ->orderBy('sort_order');
    }

    public function barcodes(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProductBarcode::class,
            ProductUnit::class
        );
    }

    public function prices(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProductPrice::class,
            ProductUnit::class
        );
    }

    public function inventoryBatches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    public function stockStatus(): string
    {
        if ($this->isOutOfStock()) {
            return 'out';
        }

        return $this->isLowStock() ? 'low' : 'available';
    }

    public function isOutOfStock(): bool
    {
        return $this->current_stock <= 0;
    }
}