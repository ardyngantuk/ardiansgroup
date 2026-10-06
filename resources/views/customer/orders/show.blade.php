<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $order->order_number }} | Ardians Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="order-page min-h-screen antialiased">
    @include('partials.store-header')
    <main class="mx-auto max-w-6xl px-4 py-8 lg:px-8 lg:py-12">
        @if(session('success'))
            <section class="order-hero mb-8 rounded-2xl border border-[#C6A15B]/50 px-6 py-10 text-center sm:px-10">
                <div class="order-check mx-auto flex h-16 w-16 items-center justify-center rounded-full border-2 border-[#E0C47A] text-3xl text-[#E0C47A]">✓</div>
                <p class="mt-6 text-xs font-bold uppercase tracking-[.24em] text-[#E0C47A]">Pesanan berhasil</p>
                <h1 class="font-display mt-3 text-4xl font-bold sm:text-5xl">Terima kasih, {{ $order->customer?->name }}!</h1>
                <p class="order-muted mx-auto mt-3 max-w-xl text-sm leading-6">Pesanan Anda telah berhasil dibuat dan sedang diproses. Simpan nomor pesanan untuk melihat perkembangannya.</p>
                <div class="mx-auto mt-7 grid max-w-4xl gap-px overflow-hidden rounded-xl border border-[#C6A15B]/40 bg-[#C6A15B]/30 sm:grid-cols-3">
                    <div class="bg-[#11100E] px-4 py-4 text-left"><p class="order-muted text-[10px] uppercase tracking-widest">Nomor order</p><div class="mt-2 flex items-center justify-between gap-3"><p class="min-w-0 truncate text-sm font-bold">{{ $order->order_number }}</p><button type="button" data-copy-value="{{ $order->order_number }}" class="shrink-0 text-[10px] font-bold text-[#E0C47A] hover:text-white">Salin</button></div></div>
                    <div class="bg-[#11100E] px-4 py-4 text-left"><p class="order-muted text-[10px] uppercase tracking-widest">Token tracking</p><div class="mt-2 flex items-center justify-between gap-3"><p class="min-w-0 truncate text-sm font-bold tracking-widest">{{ $order->tracking_token }}</p><button type="button" data-copy-value="{{ $order->tracking_token }}" class="shrink-0 text-[10px] font-bold text-[#E0C47A] hover:text-white">Salin</button></div></div>
                    <div class="bg-[#11100E] px-4 py-4 text-left sm:text-right"><p class="order-muted text-[10px] uppercase tracking-widest">Total pembayaran</p><p class="mt-2 text-lg font-bold text-[#E0C47A]">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p></div>
                </div>
                <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="#detail-pesanan" class="gold-button inline-flex min-h-11 items-center justify-center rounded-lg px-6 text-sm font-bold">Lihat pesanan <span class="ml-2">→</span></a>
                    <a href="{{ route('products.index') }}" class="inline-flex min-h-11 items-center justify-center rounded-lg border border-[#C6A15B]/50 px-6 text-sm font-bold text-[#F5F1E8] hover:border-[#E0C47A]">Kembali belanja</a>
                </div>
            </section>
        @else
            <div class="mb-8 flex items-end justify-between gap-4">
                <div><p class="text-xs font-bold uppercase tracking-[.2em] text-[#E0C47A]">Pesanan saya</p><h1 class="font-display mt-2 text-4xl font-bold">Tracking Pesanan</h1><p class="order-muted mt-2 text-sm">Pantau perjalanan pesanan Anda secara real-time.</p></div>
                <p class="hidden text-right text-sm text-[#E0C47A] sm:block">{{ $order->order_number }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 rounded-lg border border-rose-400/40 bg-rose-950/30 px-4 py-3 text-sm text-rose-200">{{ $errors->first() }}</div>
        @endif
        @php
            $trackingSteps = [['pending', 'Pesanan dibuat'], ['confirmed', 'Dikonfirmasi'], ['processing', 'Diproses'], ['ready', 'Siap dikirim'], ['shipping', 'Dikirim'], ['completed', 'Selesai']];
            $currentStep = array_search($order->order_status, array_column($trackingSteps, 0), true);
        @endphp
        <section class="order-panel rounded-xl border p-5 sm:p-7">
            <div class="flex items-center justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-[.18em] text-[#E0C47A]">Status pesanan</p><h2 class="font-display mt-2 text-2xl font-bold">Perjalanan pesanan</h2></div><span class="rounded-full border border-[#C6A15B]/50 px-3 py-1 text-xs font-bold capitalize text-[#E0C47A]">{{ $order->order_status }}</span></div>
            <div class="mt-8 grid gap-5 sm:grid-cols-3 lg:grid-cols-6">
                @foreach($trackingSteps as $stepIndex => [$state, $label])
                    @php $isDone = $currentStep !== false && $stepIndex <= $currentStep; $isCurrent = $order->order_status === $state; @endphp
                    <div class="relative text-center">
                        @if($stepIndex < count($trackingSteps) - 1)<span class="absolute left-1/2 top-4 hidden h-px w-full bg-[#C6A15B]/30 lg:block"></span>@endif
                        <span class="relative mx-auto flex h-8 w-8 items-center justify-center rounded-full border {{ $isDone ? 'border-[#E0C47A] bg-[#E0C47A] text-[#11100E]' : 'border-[#C6A15B]/40 text-[#C6A15B]' }} text-xs font-bold">{{ $isDone ? '✓' : sprintf('%02d', $stepIndex + 1) }}</span>
                        <p class="mt-3 text-xs font-semibold {{ $isCurrent ? 'text-[#E0C47A]' : '' }}">{{ $label }}</p><p class="order-muted mt-1 text-[10px]">{{ $isCurrent ? 'Saat ini' : ($isDone ? 'Selesai' : 'Menunggu') }}</p>
                    </div>
                @endforeach
            </div>
        </section>
        <section class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="order-panel rounded-xl border p-5"><p class="text-xs font-bold uppercase tracking-widest text-[#E0C47A]">Alamat pengiriman</p><p class="order-muted mt-3 text-sm leading-6">{{ $order->delivery_address }}</p><p class="mt-2 text-xs text-[#C6A15B]">{{ $order->delivery_distance_km ? number_format((float) $order->delivery_distance_km, 0, ',', '.') . ' km' : 'Jarak sesuai zona pengiriman' }}</p></div>
            <div class="order-panel rounded-xl border p-5"><p class="text-xs font-bold uppercase tracking-widest text-[#E0C47A]">Metode pembayaran</p><p class="mt-3 font-semibold capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</p><p class="order-muted mt-2 text-xs">{{ $order->payment_status === 'paid' ? 'Pembayaran terverifikasi' : 'Menunggu verifikasi' }}</p></div>
            <div class="order-panel rounded-xl border p-5"><p class="text-xs font-bold uppercase tracking-widest text-[#E0C47A]">Metode pengiriman</p><p class="mt-3 font-semibold">{{ $order->shipping?->courier ?: 'Diantar' }}</p><p class="order-muted mt-2 text-xs">Ongkir Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}</p></div>
        </section>
        <div id="detail-pesanan" class="mt-6 grid gap-6 lg:grid-cols-[1fr_320px]">
            <section class="order-panel rounded-xl border">
                <div class="order-line border-b px-5 py-5 sm:px-7"><h2 class="font-display text-2xl font-bold">Detail pesanan</h2><p class="order-muted mt-1 text-xs">{{ $order->items->count() }} produk dalam pesanan ini</p></div>
                <div class="divide-y divide-[#C6A15B]/20">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between gap-4 px-5 py-5 sm:px-7"><div><p class="font-semibold">{{ $item->product_name }}</p><p class="order-muted mt-1 text-xs">{{ $item->quantity }} {{ $item->unit_name }} × Rp {{ number_format((float) $item->selling_price, 0, ',', '.') }}</p></div><strong class="text-sm text-[#E0C47A]">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</strong></div>
                    @endforeach
                </div>
                <div class="order-line space-y-3 border-t px-5 py-5 text-sm sm:px-7"><div class="flex justify-between"><span class="order-muted">Subtotal</span><span>Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}</span></div><div class="flex justify-between"><span class="order-muted">Ongkir</span><span>Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}</span></div><div class="flex justify-between pt-2 text-base font-bold"><span>Total</span><span class="text-xl text-[#E0C47A]">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</span></div></div>
            </section>
            <aside class="space-y-6">
                <section class="order-panel rounded-xl border p-5 sm:p-6"><h2 class="font-display text-2xl font-bold">Pembayaran</h2>@if($order->payment_method === 'cod')<p class="order-muted mt-3 text-sm leading-6">Pesanan COD dibayar saat barang diterima.</p>@elseif($order->payment_status === 'paid')<p class="mt-3 rounded-lg bg-emerald-950/40 p-3 text-sm text-emerald-200">Pembayaran sudah diverifikasi admin.</p>@else<p class="order-muted mt-3 text-sm leading-6">Upload bukti pembayaran agar admin dapat memverifikasi pesanan.</p><form method="POST" action="{{ route('customer.orders.proof', [$order, 'token' => request('token')]) }}" enctype="multipart/form-data" class="mt-5">@csrf<label for="proof" class="text-sm font-semibold">Bukti pembayaran</label><input id="proof" name="proof" type="file" accept="image/jpeg,image/png,image/webp" required class="mt-2 block w-full text-xs text-[#c9c0ae]"><button class="gold-button mt-4 w-full rounded-lg px-5 py-3 text-sm font-bold">Upload bukti</button></form>@endif</section>
                @php
                    $waNumber = preg_replace('/\D+/', '', (string) ($storefront['whatsapp'] ?? ''));
                    $waMessage = rawurlencode('Halo Admin ARDIAN\'S, saya ingin konfirmasi pesanan ' . $order->order_number . '.');
                @endphp
                <section class="order-panel rounded-xl border p-5 sm:p-6"><h2 class="font-display text-xl font-bold">Butuh bantuan?</h2><p class="order-muted mt-2 text-sm leading-6">Konfirmasi atau tanyakan perubahan pesanan langsung kepada admin.</p><a href="{{ $waNumber ? 'https://wa.me/' . $waNumber . '?text=' . $waMessage : '#' }}" target="_blank" rel="noopener" class="gold-button mt-5 flex min-h-11 items-center justify-center rounded-lg px-4 text-sm font-bold">Chat via WhatsApp <span class="ml-2">→</span></a>@if(!$waNumber)<p class="order-muted mt-3 text-[11px]">Nomor WhatsApp admin belum diatur.</p>@endif</section>
            </aside>
        </div>
    </main>
    @include('partials.store-footer')
    <script>
            (() => {
                const currentOrder = {
                    orderNumber: @json($order->order_number),
                    token: @json($order->tracking_token),
                    url: @json(route('customer.orders.show', $order)),
                };

                try {
                    const stored = JSON.parse(localStorage.getItem('ardians_saved_orders') || '[]');
                    const orders = Array.isArray(stored) ? stored : [];
                    const uniqueOrders = new Map([[currentOrder.orderNumber, currentOrder]]);

                    orders.forEach((order) => {
                        if (order && order.orderNumber && order.token && order.url && !uniqueOrders.has(order.orderNumber)) {
                            uniqueOrders.set(order.orderNumber, order);
                        }
                    });

                    localStorage.setItem('ardians_saved_orders', JSON.stringify(Array.from(uniqueOrders.values()).slice(0, 10)));
                } catch (error) {
                    localStorage.removeItem('ardians_saved_orders');
                }

                document.querySelectorAll('[data-copy-value]').forEach((button) => {
                    button.addEventListener('click', async () => {
                        await navigator.clipboard.writeText(button.dataset.copyValue);
                        const originalLabel = button.textContent;
                        button.textContent = 'Tersalin';
                        window.setTimeout(() => { button.textContent = originalLabel; }, 1400);
                    });
                });
            })();
    </script>
</body>
</html>
