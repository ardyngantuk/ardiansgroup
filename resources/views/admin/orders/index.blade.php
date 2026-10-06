@extends('admin.layout')

@section('title', 'Pesanan')

@section('content')

@php
    $statusLabels = [
        'pending' => 'Menunggu konfirmasi',
        'confirmed' => 'Dikonfirmasi',
        'processing' => 'Sedang diproses',
        'ready' => 'Siap dikirim',
        'shipping' => 'Sedang dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $statusStyles = [
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
        'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
        'processing' => 'bg-violet-50 text-violet-700 border-violet-200',
        'ready' => 'bg-cyan-50 text-cyan-700 border-cyan-200',
        'shipping' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
    ];
@endphp

{{-- HEADER --}}
<div class="flex flex-col gap-6 border-b border-stone-200 pb-7 lg:flex-row lg:items-end lg:justify-between">
    <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#9A763D]">
            Operasional
        </p>

        <h1 class="mt-2 font-serif text-3xl font-semibold tracking-tight text-[#171614] sm:text-4xl">
            Pesanan pelanggan
        </h1>

        <p class="mt-3 max-w-2xl text-sm leading-6 text-stone-500">
            Kelola status pesanan, pembayaran, kurir, dan nomor resi dari satu halaman.
        </p>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <form method="GET">
            <select
                name="status"
                onchange="this.form.submit()"
                class="h-11 w-full border border-stone-300 bg-white px-4 text-sm text-stone-700 outline-none transition focus:border-[#9A763D] focus:ring-1 focus:ring-[#9A763D] sm:w-52"
            >
                <option value="">Semua status</option>

                @foreach($statusLabels as $status => $label)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </form>

        <a
            href="{{ route('admin.activities.index') }}"
            class="inline-flex h-11 items-center justify-center border border-stone-300 bg-white px-5 text-sm font-semibold text-stone-700 transition hover:border-stone-900 hover:bg-stone-900 hover:text-white"
        >
            Log aktivitas
        </a>
    </div>
</div>


{{-- DESKTOP TABLE --}}
<div class="mt-7 hidden overflow-hidden border border-stone-200 bg-white xl:block">

    <div class="border-b border-stone-200 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-stone-900">
                    Daftar pesanan
                </h2>

                <p class="mt-1 text-xs text-stone-400">
                    Perbarui informasi pesanan langsung dari tabel.
                </p>
            </div>

            <p class="text-xs text-stone-400">
                {{ $orders->total() }} pesanan
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[1200px] text-left text-sm">

            <thead class="border-b border-stone-200 bg-[#F8F7F4]">
                <tr class="text-[10px] font-semibold uppercase tracking-[0.16em] text-stone-400">
                    <th class="px-6 py-4">Pesanan</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Pembayaran</th>
                    <th class="px-6 py-4">Status & Pengiriman</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-stone-100">

            @forelse($orders as $order)

                <tr class="align-top transition hover:bg-[#FCFBF8]">

                    {{-- ORDER --}}
                    <td class="px-6 py-5">
                        <p class="font-semibold text-stone-900">
                            {{ $order->order_number }}
                        </p>

                        <p class="mt-1 text-xs text-stone-400">
                            {{ $order->order_date?->format('d M Y, H:i') }}
                        </p>
                    </td>


                    {{-- CUSTOMER --}}
                    <td class="px-6 py-5">
                        <p class="font-medium text-stone-800">
                            {{ $order->customer?->name ?? '-' }}
                        </p>

                        <p class="mt-1 text-xs text-stone-400">
                            {{ $order->customer?->phone ?? '-' }}
                        </p>
                    </td>


                    {{-- TOTAL --}}
                    <td class="px-6 py-5">
                        <p class="font-semibold text-[#9A763D]">
                            Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                        </p>

                        <p class="mt-1 text-xs text-stone-400">
                            Ongkir
                            Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}
                        </p>
                    </td>


                    {{-- PAYMENT --}}
                    <td class="px-6 py-5">

                        <p class="font-medium capitalize text-stone-700">
                            {{ str_replace('_', ' ', ucfirst($order->payment_method)) }}
                        </p>

                        <span class="mt-2 inline-flex border px-2.5 py-1 text-[11px] font-semibold
                            {{ $order->payment_status === 'paid'
                                ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                : 'border-amber-200 bg-amber-50 text-amber-700'
                            }}"
                        >
                            {{ $order->payment_status === 'paid' ? 'Sudah dibayar' : 'Belum dibayar' }}
                        </span>

                        <p class="mt-3 text-xs leading-5 text-stone-500">
                            {{ $order->delivery_zone
                                ? ucfirst($order->delivery_zone) . ' · ' .
                                  number_format((float) $order->delivery_distance_km, 0, ',', '.') . ' km'
                                : 'Zona lama'
                            }}
                        </p>

                        @php
                            $payment = $order->payments->first();
                        @endphp

                        @if ($payment?->transaction_reference)
                            <a
                                href="{{ route('admin.orders.payments.proof', [$order, $payment]) }}"
                                target="_blank"
                                class="mt-2 inline-block text-xs font-semibold text-blue-700 hover:underline"
                            >
                                Lihat bukti pembayaran ↗
                            </a>
                        @endif

                    </td>


                    {{-- STATUS --}}
                    <td class="w-[500px] px-6 py-5">

                        <div class="mb-3 flex items-center justify-between gap-3">

                            <span class="border px-2.5 py-1 text-[11px] font-semibold
                                {{ $statusStyles[$order->order_status] ?? 'border-stone-200 bg-stone-50 text-stone-600' }}"
                            >
                                {{ $statusLabels[$order->order_status] ?? ucfirst($order->order_status) }}
                            </span>

                            <span class="text-[10px] uppercase tracking-wider text-stone-400">
                                Status saat ini
                            </span>

                        </div>

                        <form
                            method="POST"
                            action="{{ route('admin.orders.update', $order) }}"
                            class="space-y-2"
                        >
                            @csrf
                            @method('PATCH')

                            <div class="grid grid-cols-2 gap-2">

                                <select
                                    name="order_status"
                                    aria-label="Status pesanan"
                                    class="h-9 border border-stone-300 bg-white px-3 text-xs text-stone-700 outline-none focus:border-[#9A763D]"
                                >
                                    @foreach($statusLabels as $status => $label)
                                        <option
                                            value="{{ $status }}"
                                            @selected($order->order_status === $status)
                                        >
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>

                                <select
                                    name="payment_status"
                                    aria-label="Status pembayaran"
                                    class="h-9 border border-stone-300 bg-white px-3 text-xs text-stone-700 outline-none focus:border-[#9A763D]"
                                >
                                    <option
                                        value="pending"
                                        @selected($order->payment_status === 'pending')
                                    >
                                        Belum dibayar
                                    </option>

                                    <option
                                        value="paid"
                                        @selected($order->payment_status === 'paid')
                                    >
                                        Sudah dibayar
                                    </option>
                                </select>

                            </div>

                            <div class="flex gap-2">

                                <input
                                    name="courier"
                                    value="{{ $order->shipping?->courier }}"
                                    placeholder="Kurir"
                                    class="h-9 min-w-0 flex-1 border border-stone-300 px-3 text-xs outline-none focus:border-[#9A763D]"
                                >

                                <input
                                    name="tracking_number"
                                    value="{{ $order->shipping?->tracking_number }}"
                                    placeholder="Nomor resi"
                                    class="h-9 min-w-0 flex-1 border border-stone-300 px-3 text-xs outline-none focus:border-[#9A763D]"
                                >

                                <button
                                    type="submit"
                                    class="h-9 whitespace-nowrap bg-[#171614] px-4 text-xs font-semibold text-white transition hover:bg-[#9A763D]"
                                >
                                    Simpan
                                </button>

                            </div>

                        </form>

                        @if ($order->payment_method === 'cod')
                            <p class="mt-3 border-l-2 border-[#C6A15B] pl-3 text-[11px] leading-5 text-amber-700">
                                COD: tandai pembayaran setelah barang diterima dan pesanan selesai.
                            </p>
                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <p class="text-sm font-medium text-stone-700">
                            Belum ada pesanan
                        </p>

                        <p class="mt-1 text-xs text-stone-400">
                            Pesanan pelanggan akan muncul di halaman ini.
                        </p>
                    </td>
                </tr>

            @endforelse

            </tbody>
        </table>
    </div>
</div>


{{-- MOBILE / TABLET --}}
<div class="mt-7 space-y-4 xl:hidden">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-semibold text-stone-900">
                Daftar pesanan
            </h2>

            <p class="mt-1 text-xs text-stone-400">
                {{ $orders->total() }} pesanan
            </p>
        </div>
    </div>

    @forelse($orders as $order)

        <article class="border border-stone-200 bg-white">

            {{-- CARD HEADER --}}
            <div class="flex items-start justify-between gap-4 border-b border-stone-200 px-5 py-4">

                <div>
                    <p class="font-semibold text-stone-900">
                        {{ $order->order_number }}
                    </p>

                    <p class="mt-1 text-xs text-stone-400">
                        {{ $order->order_date?->format('d M Y, H:i') }}
                    </p>
                </div>

                <span class="shrink-0 border px-2.5 py-1 text-[10px] font-semibold
                    {{ $statusStyles[$order->order_status] ?? 'border-stone-200 bg-stone-50 text-stone-600' }}"
                >
                    {{ $statusLabels[$order->order_status] ?? ucfirst($order->order_status) }}
                </span>

            </div>


            {{-- CUSTOMER --}}
            <div class="grid grid-cols-2 border-b border-stone-100">

                <div class="px-5 py-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                        Customer
                    </p>

                    <p class="mt-1 text-sm font-medium text-stone-800">
                        {{ $order->customer?->name ?? '-' }}
                    </p>

                    <p class="mt-1 text-xs text-stone-400">
                        {{ $order->customer?->phone ?? '-' }}
                    </p>
                </div>

                <div class="border-l border-stone-100 px-5 py-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                        Total
                    </p>

                    <p class="mt-1 text-sm font-semibold text-[#9A763D]">
                        Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                    </p>

                    <p class="mt-1 text-xs text-stone-400">
                        Ongkir Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}
                    </p>
                </div>

            </div>


            {{-- PAYMENT --}}
            <div class="border-b border-stone-100 px-5 py-4">

                <div class="flex items-center justify-between gap-4">

                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                            Pembayaran
                        </p>

                        <p class="mt-1 text-sm text-stone-700">
                            {{ str_replace('_', ' ', ucfirst($order->payment_method)) }}
                        </p>
                    </div>

                    <span class="border px-2.5 py-1 text-[10px] font-semibold
                        {{ $order->payment_status === 'paid'
                            ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                            : 'border-amber-200 bg-amber-50 text-amber-700'
                        }}"
                    >
                        {{ $order->payment_status === 'paid' ? 'Sudah dibayar' : 'Belum dibayar' }}
                    </span>

                </div>

                @php
                    $payment = $order->payments->first();
                @endphp

                @if ($payment?->transaction_reference)
                    <a
                        href="{{ route('admin.orders.payments.proof', [$order, $payment]) }}"
                        target="_blank"
                        class="mt-3 inline-block text-xs font-semibold text-blue-700 hover:underline"
                    >
                        Lihat bukti pembayaran ↗
                    </a>
                @endif

            </div>


            {{-- SHIPPING --}}
            <div class="border-b border-stone-100 px-5 py-4">

                <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                    Pengiriman
                </p>

                <p class="mt-1 text-sm text-stone-700">
                    {{ $order->delivery_zone
                        ? ucfirst($order->delivery_zone) . ' · ' .
                          number_format((float) $order->delivery_distance_km, 0, ',', '.') . ' km'
                        : 'Zona lama'
                    }}
                </p>

                <p class="mt-1 text-xs text-stone-400">
                    Ongkir Rp {{ number_format((float) $order->shipping_cost, 0, ',', '.') }}
                </p>

            </div>


            {{-- UPDATE --}}
            <div class="bg-[#FAF9F6] px-5 py-5">

                <div class="mb-4">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                        Kelola pesanan
                    </p>

                    <p class="mt-1 text-xs leading-5 text-stone-500">
                        Perbarui status pesanan, pembayaran, kurir, dan nomor resi.
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.orders.update', $order) }}"
                    class="space-y-3"
                >
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                            Status pesanan
                        </label>

                        <select
                            name="order_status"
                            class="h-10 w-full border border-stone-300 bg-white px-3 text-sm text-stone-700 outline-none focus:border-[#9A763D]"
                        >
                            @foreach($statusLabels as $status => $label)
                                <option
                                    value="{{ $status }}"
                                    @selected($order->order_status === $status)
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                            Status pembayaran
                        </label>

                        <select
                            name="payment_status"
                            class="h-10 w-full border border-stone-300 bg-white px-3 text-sm text-stone-700 outline-none focus:border-[#9A763D]"
                        >
                            <option
                                value="pending"
                                @selected($order->payment_status === 'pending')
                            >
                                Belum dibayar
                            </option>

                            <option
                                value="paid"
                                @selected($order->payment_status === 'paid')
                            >
                                Sudah dibayar
                            </option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">

                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                                Kurir
                            </label>

                            <input
                                name="courier"
                                value="{{ $order->shipping?->courier }}"
                                placeholder="Contoh: JNE"
                                class="h-10 w-full border border-stone-300 bg-white px-3 text-sm outline-none focus:border-[#9A763D]"
                            >
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                                Nomor resi
                            </label>

                            <input
                                name="tracking_number"
                                value="{{ $order->shipping?->tracking_number }}"
                                placeholder="Nomor resi"
                                class="h-10 w-full border border-stone-300 bg-white px-3 text-sm outline-none focus:border-[#9A763D]"
                            >
                        </div>

                    </div>

                    <button
                        type="submit"
                        class="flex h-11 w-full items-center justify-center bg-[#171614] px-4 text-sm font-semibold text-white transition hover:bg-[#9A763D]"
                    >
                        Simpan perubahan
                    </button>

                </form>

                @if ($order->payment_method === 'cod')
                    <div class="mt-4 border-l-2 border-[#C6A15B] pl-3">
                        <p class="text-[11px] leading-5 text-amber-700">
                            COD: tandai <strong>Sudah dibayar</strong> hanya setelah barang diterima dan status pesanan selesai.
                        </p>
                    </div>
                @endif

            </div>

        </article>

    @empty

        <div class="border border-stone-200 bg-white px-5 py-14 text-center">
            <p class="text-sm font-medium text-stone-700">
                Belum ada pesanan
            </p>

            <p class="mt-1 text-xs text-stone-400">
                Pesanan pelanggan akan muncul di halaman ini.
            </p>
        </div>

    @endforelse

</div>


{{-- PAGINATION --}}
<div class="mt-6">
    {{ $orders->links() }}
</div>

@endsection