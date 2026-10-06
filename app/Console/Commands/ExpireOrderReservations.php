<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderActivity;
use App\Services\StockReservationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExpireOrderReservations extends Command
{
    protected $signature = 'orders:expire-reservations {--hours=24 : Umur maksimum reservation dalam jam}';

    protected $description = 'Membatalkan reservation transfer/QRIS yang belum dibayar dan melepas stoknya';

    public function handle(StockReservationService $stockReservations): int
    {
        $hours = max(1, (int) $this->option('hours'));
        $cutoff = now()->subHours($hours);
        $expired = Order::query()
            ->whereIn('payment_method', ['bank_transfer', 'qris', 'e_wallet'])
            ->where('payment_status', 'pending')
            ->where('stock_status', 'reserved')
            ->whereIn('order_status', ['pending', 'confirmed'])
            ->where('order_date', '<', $cutoff)
            ->orderBy('id')
            ->get();

        $cancelled = 0;
        foreach ($expired as $order) {
            try {
                DB::transaction(function () use ($order, $stockReservations): void {
                    $order = Order::query()->lockForUpdate()->findOrFail($order->id);
                    if ($order->stock_status !== 'reserved' || $order->payment_status !== 'pending') {
                        return;
                    }

                    $stockReservations->release($order);
                    $order->update([
                        'order_status' => 'cancelled',
                        'stock_status' => 'released',
                        'admin_note' => trim(($order->admin_note ? $order->admin_note . "\n" : '') . 'Otomatis dibatalkan karena pembayaran melewati batas waktu.'),
                    ]);

                    OrderActivity::create([
                        'order_id' => $order->id,
                        'event' => 'order_expired',
                        'from_status' => 'pending',
                        'to_status' => 'cancelled',
                        'actor_type' => 'system',
                        'description' => 'Reservation otomatis dibatalkan setelah batas waktu pembayaran.',
                        'metadata' => ['hours' => (int) $this->option('hours')],
                    ]);
                    OrderActivity::create([
                        'order_id' => $order->id,
                        'event' => 'stock_status_changed',
                        'from_status' => 'reserved',
                        'to_status' => 'released',
                        'actor_type' => 'system',
                        'description' => 'Stok reservation dilepas oleh proses kedaluwarsa otomatis.',
                    ]);
                });
                $cancelled++;
                $this->line("Expired: {$order->order_number}");
            } catch (Throwable $exception) {
                $this->warn("Dilewati {$order->order_number}: {$exception->getMessage()}");
            }
        }

        $this->info("{$cancelled} reservation kedaluwarsa diproses.");

        return self::SUCCESS;
    }
}
