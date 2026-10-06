<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderActivity;
use App\Models\Payment;
use App\Models\Shipping;
use App\Services\StockReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminOrderController extends Controller
{
    public function __construct(private StockReservationService $stockReservations)
    {
    }

    public function index(Request $request): View
    {
        $orders = Order::with(['customer', 'payments', 'shipping'])->when($request->filled('status'), fn ($query) => $query->where('order_status', $request->string('status')))->latest('order_date')->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function activities(Request $request): View
    {
        $activities = OrderActivity::with(['order', 'payment', 'actor'])->latest()->paginate(30)->withQueryString();

        return view('admin.activities.index', compact('activities'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'order_status' => ['required', 'in:pending,confirmed,processing,ready,shipping,completed,cancelled'],
            'payment_status' => ['required', 'in:pending,paid'],
            'admin_note' => ['nullable', 'string'],
            'courier' => ['nullable', 'string', 'max:100'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'shipping_notes' => ['nullable', 'string'],
        ]);

        if ($data['order_status'] === 'shipping' && blank($data['tracking_number'])) {
            throw ValidationException::withMessages(['tracking_number' => 'Nomor resi wajib diisi sebelum order dikirim.']);
        }

        DB::transaction(function () use ($data, $order, $request): void {
            $order = Order::query()->lockForUpdate()->findOrFail($order->id);
            $payment = $order->payments()->latest()->lockForUpdate()->first() ?? new Payment([
                'order_id' => $order->id,
                'payment_method' => $order->payment_method,
                'amount' => $order->total,
                'status' => 'pending',
            ]);

            if (! $order->canTransitionTo($data['order_status'])) {
                throw ValidationException::withMessages(['order_status' => 'Transisi status order tidak diizinkan.']);
            }

            if (! $payment->canTransitionTo($data['payment_status'])) {
                throw ValidationException::withMessages(['payment_status' => 'Transisi status pembayaran tidak diizinkan.']);
            }

            if ($data['payment_status'] === 'paid' && $order->payment_method !== 'cod' && ! $payment->transaction_reference) {
                throw ValidationException::withMessages(['payment_status' => 'Verifikasi membutuhkan bukti pembayaran.']);
            }

            if ($data['payment_status'] === 'paid' && $data['order_status'] === 'cancelled') {
                throw ValidationException::withMessages(['payment_status' => 'Order yang dibatalkan tidak dapat diverifikasi sebagai paid.']);
            }

            if ($data['order_status'] === 'cancelled' && $order->stock_status === 'sold') {
                throw ValidationException::withMessages(['order_status' => 'Stok order yang sudah terjual tidak dapat dilepas otomatis.']);
            }

            if (in_array($data['order_status'], ['shipping', 'completed'], true) && $order->payment_method !== 'cod' && $data['payment_status'] !== 'paid') {
                throw ValidationException::withMessages(['order_status' => 'Pembayaran harus paid sebelum order dikirim atau diselesaikan.']);
            }

            if ($order->payment_method === 'cod' && $data['payment_status'] === 'paid' && $data['order_status'] !== 'completed') {
                throw ValidationException::withMessages(['payment_status' => 'Pembayaran COD baru dapat dikonfirmasi saat order selesai diterima.']);
            }

            if ($order->payment_method === 'cod' && $data['order_status'] === 'completed' && $data['payment_status'] !== 'paid') {
                throw ValidationException::withMessages(['payment_status' => 'Order COD harus dibayar sebelum ditandai selesai.']);
            }

            $oldOrderStatus = $order->order_status;
            $oldPaymentStatus = $payment->status;
            $oldStockStatus = $order->stock_status;

            if ($data['order_status'] === 'cancelled' && $oldStockStatus === 'reserved') {
                $this->stockReservations->release($order);
            }

            $stockStatus = $oldStockStatus;
            if ($data['payment_status'] === 'paid' && $stockStatus === 'reserved') {
                $stockStatus = 'sold';
            } elseif ($data['order_status'] === 'cancelled' && $stockStatus === 'reserved') {
                $stockStatus = 'released';
            }

            $order->update([
                'order_status' => $data['order_status'],
                'payment_status' => $data['payment_status'],
                'stock_status' => $stockStatus,
                'admin_note' => $data['admin_note'] ?? null,
            ]);

            $payment->fill([
                'order_id' => $order->id,
                'payment_method' => $order->payment_method,
                'amount' => $order->total,
                'status' => $data['payment_status'],
                'paid_at' => $data['payment_status'] === 'paid' ? now() : null,
            ])->save();

            if (in_array($data['order_status'], ['ready', 'shipping', 'completed'], true)) {
                $shippingStatus = match ($data['order_status']) {
                    'ready' => 'processing',
                    'shipping' => 'shipping',
                    'completed' => 'delivered',
                };
                Shipping::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'courier' => $data['courier'] ?? null,
                        'tracking_number' => $data['tracking_number'] ?? null,
                        'delivery_address' => $order->delivery_address,
                        'delivery_fee' => $order->shipping_cost,
                        'status' => $shippingStatus,
                        'shipped_at' => in_array($data['order_status'], ['shipping', 'completed'], true) ? now() : null,
                        'delivered_at' => $data['order_status'] === 'completed' ? now() : null,
                        'notes' => $data['shipping_notes'] ?? null,
                    ],
                );
            }

            if ($data['order_status'] === 'cancelled') {
                $order->shipping()->update(['status' => 'cancelled']);
            }

            if ($oldOrderStatus !== $data['order_status']) {
                OrderActivity::create([
                    'order_id' => $order->id,
                    'event' => 'order_status_changed',
                    'from_status' => $oldOrderStatus,
                    'to_status' => $data['order_status'],
                    'actor_type' => 'admin',
                    'actor_id' => $request->user()?->id,
                    'description' => 'Admin mengubah status order.',
                ]);
            }

            if ($oldPaymentStatus !== $data['payment_status']) {
                OrderActivity::create([
                    'order_id' => $order->id,
                    'payment_id' => $payment->id,
                    'event' => 'payment_status_changed',
                    'from_status' => $oldPaymentStatus,
                    'to_status' => $data['payment_status'],
                    'actor_type' => 'admin',
                    'actor_id' => $request->user()?->id,
                    'description' => 'Admin mengubah status pembayaran.',
                ]);
            }

            if ($oldStockStatus !== $stockStatus) {
                OrderActivity::create([
                    'order_id' => $order->id,
                    'event' => 'stock_status_changed',
                    'from_status' => $oldStockStatus,
                    'to_status' => $stockStatus,
                    'actor_type' => 'admin',
                    'actor_id' => $request->user()?->id,
                    'description' => $stockStatus === 'sold' ? 'Reservasi stok difinalisasi menjadi penjualan.' : 'Reservasi stok dilepas karena order dibatalkan.',
                ]);
            }
        });

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    public function proof(Order $order, Payment $payment): BinaryFileResponse
    {
        abort_unless($payment->order_id === $order->id && $payment->transaction_reference, 404);
        abort_unless(Storage::disk('local')->exists($payment->transaction_reference), 404);

        return response()->file(Storage::disk('local')->path($payment->transaction_reference));
    }

}