<?php

namespace App\Services;

use App\Models\OrderActivity;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentVerificationService
{
    public function transition(Payment $payment, string $status, ?int $actorId = null): void
    {
        if (! in_array($status, ['paid', 'failed'], true)) {
            throw ValidationException::withMessages(['status' => 'Status pembayaran tidak valid.']);
        }

        DB::transaction(function () use ($payment, $status, $actorId): void {
            $payment = Payment::query()->with('order')->lockForUpdate()->findOrFail($payment->id);
            $order = $payment->order()->lockForUpdate()->firstOrFail();

            if (! $payment->canTransitionTo($status)) {
                throw ValidationException::withMessages(['status' => 'Transisi status pembayaran tidak diizinkan.']);
            }

            if ($status === 'paid' && $payment->payment_method !== 'cod' && blank($payment->transaction_reference)) {
                throw ValidationException::withMessages(['status' => 'Verifikasi membutuhkan bukti pembayaran.']);
            }

            if ($status === 'paid' && $payment->payment_method === 'cod') {
                throw ValidationException::withMessages(['status' => 'Pembayaran COD dikonfirmasi saat order selesai diterima.']);
            }

            if ($order->order_status === 'cancelled') {
                throw ValidationException::withMessages(['status' => 'Pembayaran order yang dibatalkan tidak dapat diubah.']);
            }

            $oldPaymentStatus = $payment->status;
            $oldStockStatus = $order->stock_status;
            $stockStatus = $oldStockStatus;

            if ($status === 'paid' && $stockStatus === 'reserved') {
                $stockStatus = 'sold';
            }

            $payment->update([
                'status' => $status,
                'paid_at' => $status === 'paid' ? now() : null,
            ]);
            $order->update([
                'payment_status' => $status,
                'stock_status' => $stockStatus,
            ]);

            OrderActivity::create([
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'event' => 'payment_status_changed',
                'from_status' => $oldPaymentStatus,
                'to_status' => $status,
                'actor_type' => 'admin',
                'actor_id' => $actorId,
                'description' => $status === 'paid' ? 'Admin memverifikasi pembayaran.' : 'Admin menolak pembayaran.',
            ]);

            if ($oldStockStatus !== $stockStatus) {
                OrderActivity::create([
                    'order_id' => $order->id,
                    'event' => 'stock_status_changed',
                    'from_status' => $oldStockStatus,
                    'to_status' => $stockStatus,
                    'actor_type' => 'admin',
                    'actor_id' => $actorId,
                    'description' => 'Reservasi stok difinalisasi menjadi penjualan setelah pembayaran diverifikasi.',
                ]);
            }
        });
    }
}
