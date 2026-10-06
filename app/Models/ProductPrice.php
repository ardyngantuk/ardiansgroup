<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    protected $table = 'product_prices';

    protected $fillable = [
        'product_unit_id',
        'tier',
        'minimum_quantity',
        'selling_price',
        'points',
        'sales_commission',
    ];

    protected $casts = [
        'tier' => 'integer',
        'minimum_quantity' => 'decimal:3',
        'selling_price' => 'decimal:2',
        'points' => 'decimal:2',
        'sales_commission' => 'decimal:2',
    ];

    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class);
    }
}