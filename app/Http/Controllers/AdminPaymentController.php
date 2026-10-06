<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPaymentController extends Controller
{
    public function __construct(private PaymentVerificationService $paymentVerification)
    {
    }

    public function index(Request $request): View
    {
        $payments = Payment::query()
            ->with(['order.customer'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:paid,failed'],
        ]);

        $this->paymentVerification->transition($payment, $data['status'], $request->user()?->id);

        return back()->with('success', $data['status'] === 'paid' ? 'Pembayaran berhasil diverifikasi.' : 'Pembayaran ditolak.');
    }
}
