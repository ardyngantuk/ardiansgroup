@extends('admin.layout')

@section('title', 'Pembayaran')

@section('content')
@php
    $statusLabels = ['pending' => 'Menunggu verifikasi', 'paid' => 'Sudah dibayar', 'failed' => 'Ditolak', 'cancelled' => 'Dibatalkan', 'refunded' => 'Direfund'];
    $statusStyles = ['pending' => 'border-amber-200 bg-amber-50 text-amber-700', 'paid' => 'border-emerald-200 bg-emerald-50 text-emerald-700', 'failed' => 'border-red-200 bg-red-50 text-red-700', 'cancelled' => 'border-stone-200 bg-stone-100 text-stone-600', 'refunded' => 'border-blue-200 bg-blue-50 text-blue-700'];
@endphp
<div class="flex flex-col gap-6 border-b border-stone-200 pb-7 lg:flex-row lg:items-end lg:justify-between">
    <div><p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#9A763D]">Penjualan</p><h1 class="mt-2 font-serif text-3xl font-semibold tracking-tight text-[#171614] sm:text-4xl">Pembayaran</h1><p class="mt-3 max-w-2xl text-sm leading-6 text-stone-500">Periksa bukti transfer dan kelola status pembayaran customer dari satu tempat.</p></div>
    <form method="GET"><select name="status" onchange="this.form.submit()" class="h-11 w-full border border-stone-300 bg-white px-4 text-sm text-stone-700 outline-none focus:border-[#9A763D] sm:w-56"><option value="">Semua status</option>@foreach($statusLabels as $status => $label)<option value="{{ $status }}" @selected(request('status') === $status)>{{ $label }}</option>@endforeach</select></form>
</div>
<div class="mt-7 overflow-hidden border border-stone-200 bg-white">
    <div class="flex items-center justify-between border-b border-stone-200 px-6 py-4"><div><h2 class="text-sm font-semibold text-stone-900">Daftar pembayaran</h2><p class="mt-1 text-xs text-stone-400">{{ $payments->total() }} pembayaran tercatat</p></div><a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-[#9A763D] hover:underline">Lihat pesanan →</a></div>
    <div class="overflow-x-auto"><table class="w-full min-w-[980px] text-left text-sm"><thead class="border-b border-stone-200 bg-[#F8F7F4]"><tr class="text-[10px] font-semibold uppercase tracking-[.16em] text-stone-400"><th class="px-6 py-4">Pembayaran</th><th class="px-6 py-4">Customer</th><th class="px-6 py-4">Metode</th><th class="px-6 py-4">Nominal</th><th class="px-6 py-4">Bukti</th><th class="px-6 py-4">Aksi</th></tr></thead>
        <tbody class="divide-y divide-stone-100">
        @forelse($payments as $payment)
            <tr class="align-top transition hover:bg-[#FCFBF8]"><td class="px-6 py-5"><p class="font-semibold text-stone-900">{{ $payment->order?->order_number ?? '-' }}</p><p class="mt-1 text-xs text-stone-400">{{ $payment->created_at?->format('d M Y, H:i') }}</p><span class="mt-3 inline-flex border px-2.5 py-1 text-[11px] font-semibold {{ $statusStyles[$payment->status] ?? 'border-stone-200 bg-stone-50 text-stone-600' }}">{{ $statusLabels[$payment->status] ?? ucfirst($payment->status) }}</span></td><td class="px-6 py-5"><p class="font-medium text-stone-800">{{ $payment->order?->customer?->name ?? '-' }}</p><p class="mt-1 text-xs text-stone-400">{{ $payment->order?->customer?->phone ?? '-' }}</p></td><td class="px-6 py-5"><p class="font-medium capitalize text-stone-700">{{ str_replace('_', ' ', $payment->payment_method) }}</p><p class="mt-1 text-xs text-stone-400">{{ $payment->order?->order_status ? ucfirst($payment->order->order_status) : '-' }}</p></td><td class="px-6 py-5"><p class="font-semibold text-[#9A763D]">Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</p></td><td class="px-6 py-5">@if($payment->transaction_reference)<a href="{{ route('admin.orders.payments.proof', [$payment->order, $payment]) }}" target="_blank" class="inline-flex border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100">Lihat bukti ↗</a>@else<span class="text-xs text-stone-400">Belum diunggah</span>@endif</td><td class="px-6 py-5">@if($payment->status === 'pending')<div class="flex flex-wrap gap-2"><form method="POST" action="{{ route('admin.payments.update', $payment) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="paid"><button class="border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 hover:bg-emerald-100">Verifikasi</button></form><form method="POST" action="{{ route('admin.payments.update', $payment) }}">@csrf @method('PATCH')<input type="hidden" name="status" value="failed"><button class="border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">Tolak</button></form></div>@else<span class="text-xs text-stone-400">Tidak ada aksi</span>@endif</td></tr>
        @empty
            <tr><td colspan="6" class="px-6 py-14 text-center text-sm text-stone-500">Belum ada pembayaran yang sesuai filter.</td></tr>
        @endforelse
        </tbody></table></div>
    @if($payments->hasPages())<div class="border-t border-stone-200 px-6 py-4">{{ $payments->links() }}</div>@endif
</div>
@endsection
