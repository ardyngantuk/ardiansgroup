<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderActivity;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    public function index(Request $request): View
    {
        $orderNumber = trim((string) $request->query('order_number', ''));
        $token = trim((string) $request->query('token', ''));

        if ($orderNumber !== '' || $token !== '') {
            $order = Order::where('order_number', $orderNumber)->first();
            if (! $order || $token === '' || ! hash_equals((string) $order->tracking_token, $token)) {
                return view('customer.orders.index', [
                    'orderNumber' => $orderNumber,
                    'lookupError' => 'Nomor order atau token tracking tidak valid.',
                    'whatsappNumber' => Setting::getValue('store.whatsapp', ''),
                ]);
            }

            return redirect()->route('customer.orders.show', [$order, 'token' => $token]);
        }

        return view('customer.orders.index', [
            'orderNumber' => $orderNumber,
            'lookupError' => null,
            'whatsappNumber' => Setting::getValue('store.whatsapp', ''),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        $this->authorizeCustomer($request, $order);
        $order->load(['customer', 'items', 'payments', 'shipping']);

        return view('customer.orders.show', compact('order'));
    }

    public function uploadProof(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeCustomer($request, $order);

        abort_if($order->payment_method === 'cod', 422, 'Pesanan COD tidak membutuhkan bukti pembayaran.');
        abort_if($order->payment_status === 'paid', 422, 'Pembayaran pesanan ini sudah diverifikasi.');
        abort_unless(in_array($order->order_status, ['pending', 'confirmed', 'processing'], true), 422, 'Bukti pembayaran tidak dapat diunggah pada status order ini.');

        $data = $request->validate([
            'proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        /** @var Payment $payment */
        $payment = $order->payments()->latest()->firstOrFail();
        $oldPaymentStatus = $payment->status;
        if ($payment->transaction_reference) {
            Storage::disk('local')->delete($payment->transaction_reference);
        }

        $path = $data['proof']->store('payment-proofs', 'local');
        $payment->update([
            'transaction_reference' => $path,
            'status' => 'pending',
            'notes' => 'Bukti pembayaran diunggah customer dan menunggu verifikasi admin.',
        ]);
        OrderActivity::create([
            'order_id' => $order->id,
            'payment_id' => $payment->id,
            'event' => 'payment_proof_uploaded',
            'from_status' => $oldPaymentStatus,
            'to_status' => 'pending',
            'actor_type' => 'customer',
            'description' => 'Customer mengunggah bukti pembayaran.',
        ]);

        return redirect()->route('customer.orders.show', [$order, 'token' => $request->query('token')])->with('success', 'Bukti pembayaran berhasil diunggah dan menunggu verifikasi admin.');
    }

    private function authorizeCustomer(Request $request, Order $order): void
    {
        abort_unless($request->filled('token') && hash_equals((string) $order->tracking_token, (string) $request->query('token')), 404);
    }
}