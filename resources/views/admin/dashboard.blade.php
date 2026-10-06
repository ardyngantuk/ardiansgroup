@extends('admin.layout')

@section('title', 'Overview')

@section('content')

@php
    $statusLabels = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'processing' => 'Diproses',
        'ready' => 'Siap dikirim',
        'shipping' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan'
    ];

    $statusColors = [
        'pending' => 'bg-amber-500',
        'confirmed' => 'bg-sky-500',
        'processing' => 'bg-blue-500',
        'ready' => 'bg-violet-500',
        'shipping' => 'bg-indigo-500',
        'completed' => 'bg-emerald-500',
        'cancelled' => 'bg-rose-500'
    ];

    $maxRevenue = max(1, (float) $dailySales->max('revenue'));
@endphp


{{-- =========================================================
     PAGE HEADER
========================================================= --}}
<div class="flex flex-col gap-6 border-b border-stone-200 pb-7
            lg:flex-row lg:items-end lg:justify-between">

    <div>
        <div class="flex items-center gap-3">
            <span class="h-px w-7 bg-[#B28A45]"></span>

            <p class="text-[10px] font-semibold uppercase
                      tracking-[0.25em] text-[#9A763D]">
                Overview
            </p>
        </div>

        <h1 class="mt-4 font-serif text-3xl font-normal
                   tracking-tight text-stone-900 sm:text-4xl">
            Ringkasan operasional
        </h1>

        <p class="mt-2 max-w-xl text-sm leading-6 text-stone-500">
            Pantau penjualan, pesanan, dan kondisi stok toko
            dalam satu tampilan.
        </p>
    </div>


    <div class="flex flex-wrap gap-2">

        <a
            href="{{ route('admin.orders.index') }}"
            class="inline-flex h-10 items-center gap-2
                   bg-stone-900 px-4 text-xs font-semibold
                   text-white transition hover:bg-[#B28A45]"
        >
            <svg
                width="15"
                height="15"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
            >
                <path d="M4 5h16v14H4z"></path>
                <path d="M8 9h8M8 13h5"></path>
            </svg>

            Pesanan
        </a>


        <a
            href="{{ route('admin.products.create') }}"
            class="inline-flex h-10 items-center gap-2
                   border border-stone-300 bg-white px-4
                   text-xs font-semibold text-stone-700
                   transition hover:border-[#B28A45]
                   hover:text-[#9A763D]"
        >
            <span class="text-lg leading-none font-light">+</span>

            Tambah produk
        </a>

    </div>

</div>



{{-- =========================================================
     KPI
========================================================= --}}
<div class="mt-7 grid grid-cols-2 border-y border-stone-200
            bg-white lg:grid-cols-5">

    {{-- Omzet --}}
    <div class="border-b border-stone-200 p-5
                lg:border-b-0 lg:border-r">

        <p class="text-[9px] font-semibold uppercase
                  tracking-[0.18em] text-stone-400">
            Omzet bulan ini
        </p>

        <p class="mt-4 text-xl font-semibold tracking-tight
                  text-stone-900 sm:text-2xl">
            Rp {{ number_format((float) $monthlyRevenue, 0, ',', '.') }}
        </p>

        <p class="mt-2 text-[11px] text-stone-500">
            {{ number_format($monthlyOrders, 0, ',', '.') }}
            order bulan ini
        </p>

    </div>


    {{-- Pending --}}
    <div class="border-b border-stone-200 p-5
                lg:border-b-0 lg:border-r">

        <div class="flex items-center justify-between">

            <p class="text-[9px] font-semibold uppercase
                      tracking-[0.18em] text-stone-400">
                Perlu diproses
            </p>

            <span class="h-1.5 w-1.5 rounded-full bg-[#B28A45]"></span>

        </div>

        <p class="mt-4 text-xl font-semibold tracking-tight
                  text-stone-900 sm:text-2xl">
            {{ number_format($pendingOrders, 0, ',', '.') }}
        </p>

        <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="mt-2 inline-flex text-[11px] text-stone-500 transition hover:text-[#9A763D]">
            {{ number_format($pendingPayments, 0, ',', '.') }} menunggu pembayaran →
        </a>

    </div>


    {{-- Low stock --}}
    <div class="border-b border-stone-200 p-5
                lg:border-b-0 lg:border-r">

        <div class="flex items-center justify-between">

            <p class="text-[9px] font-semibold uppercase
                      tracking-[0.18em] text-stone-400">
                Stok perlu perhatian
            </p>

            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>

        </div>

        <a href="{{ route('admin.stock.index', ['status' => 'low']) }}" class="mt-4 block text-xl font-semibold tracking-tight text-stone-900 transition hover:text-[#9A763D] sm:text-2xl">
            {{ number_format($lowStockProducts, 0, ',', '.') }}
        </a>

        <a href="{{ route('admin.stock.index', ['status' => 'out']) }}" class="mt-2 inline-flex text-[11px] text-stone-500 transition hover:text-[#9A763D]">
            {{ number_format($outOfStockProducts, 0, ',', '.') }} produk habis →
        </a>

    </div>


    {{-- Monthly expenses --}}
    <div class="border-b border-stone-200 p-5 lg:border-b-0 lg:border-r">

        <p class="text-[9px] font-semibold uppercase tracking-[0.18em] text-stone-400">
            Pengeluaran bulan ini
        </p>

        <p class="mt-4 text-xl font-semibold tracking-tight text-stone-900 sm:text-2xl">
            Rp {{ number_format((float) $monthlyExpenses, 0, ',', '.') }}
        </p>

        <a href="{{ route('admin.expenses.index') }}" class="mt-2 inline-flex text-[11px] text-stone-500 transition hover:text-[#9A763D]">
            Kelola pengeluaran →
        </a>

    </div>


    {{-- Catalog --}}
    <div class="p-5">

        <p class="text-[9px] font-semibold uppercase
                  tracking-[0.18em] text-stone-400">
            Katalog aktif
        </p>

        <p class="mt-4 text-xl font-semibold tracking-tight
                  text-stone-900 sm:text-2xl">
            {{ number_format($activeProducts, 0, ',', '.') }}
        </p>

        <p class="mt-2 text-[11px] text-stone-500">
            dari {{ number_format($products, 0, ',', '.') }}
            total produk
        </p>

    </div>

</div>



{{-- =========================================================
     SALES + ORDER PIPELINE
========================================================= --}}
<div class="mt-6 grid gap-6 xl:grid-cols-[1.55fr_1fr]">


    {{-- SALES CHART --}}
    <section class="border border-stone-200 bg-white">

        <div class="flex flex-col gap-3 border-b border-stone-100
                    p-5 sm:flex-row sm:items-end
                    sm:justify-between sm:p-6">

            <div>

                <p class="text-[9px] font-semibold uppercase
                          tracking-[0.2em] text-stone-400">
                    Performa penjualan
                </p>

                <h2 class="mt-2 font-serif text-xl font-normal
                           text-stone-900">
                    7 hari terakhir
                </h2>

            </div>


            <div class="text-left sm:text-right">

                <p class="text-[9px] uppercase tracking-wider
                          text-stone-400">
                    Total terverifikasi
                </p>

                <p class="mt-1 text-sm font-semibold text-[#9A763D]">
                    Rp {{ number_format((float) $dailySales->sum('revenue'), 0, ',', '.') }}
                </p>

            </div>

        </div>


        <div class="p-5 sm:p-6">

            <div class="flex h-56 items-end gap-2
                        border-b border-stone-200
                        px-1 sm:gap-4">

                @foreach ($dailySales as $day)

                    <div
                        class="group flex h-full min-w-0 flex-1
                               flex-col items-center justify-end gap-2"
                    >

                        <div class="relative flex h-full w-full
                                    items-end">

                            {{-- Tooltip --}}
                            <span
                                class="absolute bottom-full left-1/2
                                       z-10 mb-2 hidden -translate-x-1/2
                                       whitespace-nowrap border
                                       border-stone-200 bg-stone-900
                                       px-2 py-1 text-[9px] text-white
                                       shadow-sm group-hover:block"
                            >
                                Rp {{ number_format($day['revenue'], 0, ',', '.') }}
                            </span>


                            {{-- Bar --}}
                            <div
                                class="w-full bg-[#B28A45]
                                       transition-all duration-200
                                       group-hover:bg-stone-900"
                                style="
                                    height:
                                    {{ max(6, ($day['revenue'] / $maxRevenue) * 100) }}%
                                "
                            ></div>

                        </div>


                        <span class="text-[9px] font-semibold text-stone-500">
                            {{ $day['label'] }}
                        </span>

                        <span class="hidden text-[8px] text-stone-400 sm:block">
                            {{ $day['date'] }}
                        </span>

                    </div>

                @endforeach

            </div>


            <p class="mt-4 text-[10px] leading-5 text-stone-400">
                Grafik omzet hanya menghitung order dengan pembayaran
                terverifikasi.
            </p>

        </div>

    </section>



    {{-- ORDER PIPELINE --}}
    <section class="border border-stone-200 bg-white">

        <div class="flex items-end justify-between
                    border-b border-stone-100 p-5 sm:p-6">

            <div>

                <p class="text-[9px] font-semibold uppercase
                          tracking-[0.2em] text-stone-400">
                    Pipeline order
                </p>

                <h2 class="mt-2 font-serif text-xl font-normal
                           text-stone-900">
                    Status pesanan
                </h2>

            </div>


            <a
                href="{{ route('admin.orders.index') }}"
                class="text-[10px] font-semibold text-[#9A763D]
                       hover:text-stone-900"
            >
                Kelola →
            </a>

        </div>


        <div class="space-y-5 p-5 sm:p-6">

            @foreach ([
                'pending',
                'confirmed',
                'processing',
                'ready',
                'shipping',
                'completed'
            ] as $status)

                @php
                    $count = (int) ($statusCounts->get($status) ?? 0);
                    $percentage = $orders > 0
                        ? ($count / $orders) * 100
                        : 0;
                @endphp

                <div>

                    <div class="mb-2 flex items-center
                                justify-between gap-3">

                        <span class="text-xs text-stone-600">
                            {{ $statusLabels[$status] }}
                        </span>

                        <span class="text-xs font-semibold
                                     text-stone-900">
                            {{ number_format($count, 0, ',', '.') }}
                        </span>

                    </div>


                    <div class="h-1.5 bg-stone-100">

                        <div
                            class="h-full bg-[#B28A45]"
                            style="
                                width:
                                {{ max($count > 0 ? 5 : 0, $percentage) }}%
                            "
                        ></div>

                    </div>

                </div>

            @endforeach

        </div>

    </section>

</div>



{{-- =========================================================
     INVENTORY + TOP PRODUCTS
========================================================= --}}
<div class="mt-6 grid gap-6 xl:grid-cols-[1fr_1.35fr]">


    {{-- INVENTORY --}}
    <section class="border border-stone-200 bg-white">

        <div class="flex items-end justify-between
                    border-b border-stone-100 p-5 sm:p-6">

            <div>

                <p class="text-[9px] font-semibold uppercase
                          tracking-[0.2em] text-stone-400">
                    Inventory watch
                </p>

                <h2 class="mt-2 font-serif text-xl font-normal
                           text-stone-900">
                    Stok perlu perhatian
                </h2>

            </div>


            <a
                href="{{ route('admin.module', 'stok-minimum') }}"
                class="text-[10px] font-semibold text-[#9A763D]
                       hover:text-stone-900"
            >
                Lihat stok →
            </a>

        </div>


        <div class="p-5 sm:p-6">

            <div class="grid grid-cols-2 gap-px
                        border border-stone-200 bg-stone-200">

                <div class="bg-[#FAF8F3] p-5">

                    <p class="text-2xl font-semibold text-rose-600">
                        {{ number_format($outOfStockProducts, 0, ',', '.') }}
                    </p>

                    <p class="mt-1 text-[10px] font-semibold
                              uppercase tracking-wider text-stone-500">
                        Produk habis
                    </p>

                </div>


                <div class="bg-[#FAF8F3] p-5">

                    <p class="text-2xl font-semibold text-[#B28A45]">
                        {{ number_format($lowStockProducts, 0, ',', '.') }}
                    </p>

                    <p class="mt-1 text-[10px] font-semibold
                              uppercase tracking-wider text-stone-500">
                        Di bawah minimum
                    </p>

                </div>

            </div>


            <div class="mt-6 border-t border-stone-100 pt-5">

                <p class="text-sm font-semibold text-stone-800">
                    Aksi yang disarankan
                </p>

                <p class="mt-2 text-xs leading-6 text-stone-500">
                    Periksa batch FIFO dan buat pembelian baru sebelum
                    stok kritis mengganggu pesanan.
                </p>


                <a
                    href="{{ route('admin.module', 'batch-fifo') }}"
                    class="mt-4 inline-flex items-center
                           text-xs font-semibold text-[#9A763D]
                           hover:text-stone-900"
                >
                    Buka batch FIFO

                    <span class="ml-2">→</span>
                </a>

            </div>

        </div>

    </section>



    {{-- TOP PRODUCTS --}}
    <section class="border border-stone-200 bg-white">

        <div class="flex items-end justify-between
                    border-b border-stone-100 p-5 sm:p-6">

            <div>

                <p class="text-[9px] font-semibold uppercase
                          tracking-[0.2em] text-stone-400">
                    Produk terlaris
                </p>

                <h2 class="mt-2 font-serif text-xl font-normal
                           text-stone-900">
                    Kontributor omzet
                </h2>

            </div>


            <a
                href="{{ route('admin.products.index') }}"
                class="text-[10px] font-semibold text-[#9A763D]
                       hover:text-stone-900"
            >
                Katalog →
            </a>

        </div>


        <div class="divide-y divide-stone-100 px-5 sm:px-6">

            @forelse ($topProducts as $product)

                <div
                    class="flex items-center justify-between
                           gap-4 py-4"
                >

                    <div class="min-w-0">

                        <p class="truncate text-sm font-semibold
                                  text-stone-800">
                            {{ $product->product_name }}
                        </p>

                        <p class="mt-1 text-[10px] text-stone-400">
                            {{ number_format((float) $product->total_quantity, 0, ',', '.') }}
                            unit terjual
                        </p>

                    </div>


                    <p class="shrink-0 text-xs font-semibold
                              text-[#9A763D] sm:text-sm">
                        Rp {{ number_format((float) $product->revenue, 0, ',', '.') }}
                    </p>

                </div>

            @empty

                <p class="py-10 text-center text-sm text-stone-500">
                    Belum ada penjualan terverifikasi.
                </p>

            @endforelse

        </div>

    </section>

</div>



{{-- =========================================================
     RECENT ORDERS
========================================================= --}}
<section class="mt-6 border border-stone-200 bg-white">

    <div class="flex items-end justify-between
                border-b border-stone-200 p-5 sm:p-6">

        <div>

            <p class="text-[9px] font-semibold uppercase
                      tracking-[0.2em] text-stone-400">
                Aktivitas terbaru
            </p>

            <h2 class="mt-2 font-serif text-xl font-normal
                       text-stone-900">
                Pesanan masuk
            </h2>

        </div>


        <a
            href="{{ route('admin.orders.index') }}"
            class="text-xs font-semibold text-[#9A763D]
                   hover:text-stone-900"
        >
            Lihat semua
            <span aria-hidden="true">→</span>
        </a>

    </div>


    {{-- ================================
         MOBILE ORDER CARDS
    ================================= --}}
    <div class="divide-y divide-stone-100 md:hidden">

        @forelse ($recentOrders as $order)

            <div class="p-5">

                <div class="flex items-start
                            justify-between gap-4">

                    <div>

                        <p class="text-sm font-semibold
                                  text-stone-800">
                            {{ $order->order_number }}
                        </p>

                        <p class="mt-1 text-xs text-stone-500">
                            {{ $order->customer?->name ?? '-' }}
                        </p>

                    </div>


                    <span
                        class="inline-flex shrink-0 px-2 py-1
                               text-[9px] font-semibold
                               {{ $order->order_status === 'completed'
                                    ? 'bg-emerald-50 text-emerald-700'
                                    : ($order->order_status === 'cancelled'
                                        ? 'bg-rose-50 text-rose-700'
                                        : 'bg-amber-50 text-amber-800') }}"
                    >
                        {{ $statusLabels[$order->order_status] ?? ucfirst($order->order_status) }}
                    </span>

                </div>


                <div class="mt-4 flex items-end
                            justify-between">

                    <div>

                        <p class="text-[9px] uppercase
                                  tracking-wider text-stone-400">
                            Total
                        </p>

                        <p class="mt-1 text-sm font-semibold
                                  text-stone-900">
                            Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                        </p>

                    </div>


                    <p class="text-[10px] text-stone-400">
                        {{ $order->order_date?->format('d M Y, H:i') }}
                    </p>

                </div>

            </div>

        @empty

            <div class="px-5 py-12 text-center
                        text-sm text-stone-500">
                Belum ada pesanan.
            </div>

        @endforelse

    </div>



    {{-- ================================
         DESKTOP TABLE
    ================================= --}}
    <div class="hidden overflow-x-auto md:block">

        <table class="w-full min-w-[700px] text-left text-sm">

            <thead class="border-b border-stone-100
                          bg-[#FAF9F6]">

                <tr>

                    <th class="px-6 py-3.5 text-[9px]
                               font-semibold uppercase
                               tracking-[0.18em]
                               text-stone-400">
                        Pesanan
                    </th>

                    <th class="px-6 py-3.5 text-[9px]
                               font-semibold uppercase
                               tracking-[0.18em]
                               text-stone-400">
                        Pelanggan
                    </th>

                    <th class="px-6 py-3.5 text-[9px]
                               font-semibold uppercase
                               tracking-[0.18em]
                               text-stone-400">
                        Total
                    </th>

                    <th class="px-6 py-3.5 text-[9px]
                               font-semibold uppercase
                               tracking-[0.18em]
                               text-stone-400">
                        Status
                    </th>

                    <th class="px-6 py-3.5 text-[9px]
                               font-semibold uppercase
                               tracking-[0.18em]
                               text-stone-400">
                        Waktu
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-stone-100">

                @forelse ($recentOrders as $order)

                    <tr class="transition hover:bg-[#FAF9F6]">

                        <td class="px-6 py-4 font-semibold
                                   text-stone-800">
                            {{ $order->order_number }}
                        </td>


                        <td class="px-6 py-4 text-stone-500">
                            {{ $order->customer?->name ?? '-' }}
                        </td>


                        <td class="px-6 py-4 font-semibold
                                   text-stone-800">
                            Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                        </td>


                        <td class="px-6 py-4">

                            <span
                                class="inline-flex px-2.5 py-1
                                       text-[10px] font-semibold
                                       {{ $order->order_status === 'completed'
                                            ? 'bg-emerald-50 text-emerald-700'
                                            : ($order->order_status === 'cancelled'
                                                ? 'bg-rose-50 text-rose-700'
                                                : 'bg-amber-50 text-amber-800') }}"
                            >
                                {{ $statusLabels[$order->order_status] ?? ucfirst($order->order_status) }}
                            </span>

                        </td>


                        <td class="px-6 py-4 text-xs text-stone-400">
                            {{ $order->order_date?->format('d M Y, H:i') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-12 text-center
                                   text-sm text-stone-500"
                        >
                            Belum ada pesanan.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</section>


@endsection