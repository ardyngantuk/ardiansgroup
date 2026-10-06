<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductUnit extends Model
{
    protected $table = 'product_units';

    protected $fillable = [
        'product_id',
        'unit_level',
        'unit_name',
        'abbreviation',
        'conversion_to_base',
        'cost_price',
        'default_selling_price',
    ];

    protected $casts = [
        'conversion_to_base' => 'decimal:3',
        'cost_price' => 'decimal:2',
        'default_selling_price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function barcodes(): HasMany
    {
        return $this->hasMany(ProductBarcode::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function toBaseQuantity(float $quantity): float
    {
        return $quantity * (float) $this->conversion_to_base;
    }
}