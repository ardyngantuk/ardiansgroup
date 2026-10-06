<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'payment_method',
        'transaction_reference',
        'amount',
        'paid_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(OrderActivity::class);
    }

    public function canTransitionTo(string $nextStatus): bool
    {
        if ($nextStatus === $this->status) {
            return true;
        }

        return in_array($nextStatus, [
            'pending' => ['paid', 'failed', 'cancelled'],
            'failed' => ['pending', 'cancelled'],
            'paid' => [],
            'cancelled' => [],
            'refunded' => [],
        ][$this->status] ?? [], true);
    }
}