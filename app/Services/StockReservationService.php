<?php

namespace App\Services;

use App\Models\InventoryBatch;
use App\Models\Order;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockReservationService
{
    public function release(Order $order): void
    {
        $items = $order->items()->with('saleBatchAllocations')->get();

        foreach ($items as $item) {
            $allocatedQuantity = (float) $item->saleBatchAllocations->sum('quantity');
            if ($allocatedQuantity <= 0 || abs($allocatedQuantity - (float) $item->base_quantity) > 0.001) {
                throw ValidationException::withMessages([
                    'order_status' => 'Reservasi order tidak memiliki allocation FIFO lengkap; stok harus diperiksa manual.',
                ]);
            }
        }

        foreach ($items as $item) {
            $allocations = $item->saleBatchAllocations;
            $allocatedQuantity = (float) $allocations->sum('quantity');
            $product = $item->product()->lockForUpdate()->firstOrFail();
            $stockBefore = (float) $product->current_stock;
            $product->update(['current_stock' => $stockBefore + $allocatedQuantity]);

            foreach ($allocations as $allocation) {
                $batch = InventoryBatch::query()->lockForUpdate()->findOrFail($allocation->inventory_batch_id);
                $batch->update([
                    'remaining_quantity' => (float) $batch->remaining_quantity + (float) $allocation->quantity,
                    'status' => 'open',
                ]);
            }

            StockMovement::create([
                'product_id' => $product->id,
                'product_unit_id' => $item->product_unit_id,
                'movement_type' => 'adjustment_in',
                'quantity' => (float) $item->quantity,
                'base_quantity' => $allocatedQuantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockBefore + $allocatedQuantity,
                'reference_type' => 'order',
                'reference_id' => $order->id,
                'notes' => 'Pelepasan reservasi order ' . $order->order_number,
            ]);

            $item->saleBatchAllocations()->delete();
        }
    }

    public function releaseInTransaction(Order $order): void
    {
        DB::transaction(fn () => $this->release($order));
    }
}
