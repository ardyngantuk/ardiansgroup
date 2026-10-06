@extends('admin.layout')

@section('title', 'Produk')

@section('content')

{{-- HEADER --}}
<div class="flex flex-col gap-6 border-b border-stone-200 pb-7 lg:flex-row lg:items-end lg:justify-between">

    <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#9A763D]">
            Inventory
        </p>

        <h1 class="mt-2 font-serif text-3xl font-semibold tracking-tight text-[#171614] sm:text-4xl">
            Produk & harga
        </h1>

        <p class="mt-3 max-w-2xl text-sm leading-6 text-stone-500">
            Kelola katalog, harga, unit, dan ketersediaan produk tanpa menghapus histori dari import Excel.
        </p>
    </div>

    <a
        href="{{ route('admin.products.create') }}"
        class="inline-flex h-11 items-center justify-center bg-[#171614] px-5 text-sm font-semibold text-white transition hover:bg-[#9A763D]"
    >
        + Tambah produk
    </a>

</div>


{{-- SEARCH --}}
<form method="GET" class="mt-7">

    <div class="flex w-full max-w-2xl gap-2">

        <div class="relative flex-1">

            <input
                name="q"
                value="{{ $search }}"
                placeholder="Cari nama atau kode produk..."
                class="h-11 w-full border border-stone-300 bg-white px-4 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-[#9A763D] focus:ring-1 focus:ring-[#9A763D]"
            >

        </div>

        <button
            type="submit"
            class="h-11 bg-white px-5 text-sm font-semibold text-stone-700 ring-1 ring-stone-200 transition hover:bg-stone-900 hover:text-white"
        >
            Cari
        </button>

    </div>

</form>


{{-- DESKTOP --}}
<div class="mt-7 hidden overflow-hidden border border-stone-200 bg-white lg:block">

    {{-- TABLE HEADER --}}
    <div class="flex items-center justify-between border-b border-stone-200 px-6 py-4">

        <div>
            <h2 class="text-sm font-semibold text-stone-900">
                Daftar produk
            </h2>

            <p class="mt-1 text-xs text-stone-400">
                {{ $products->total() }} produk
            </p>
        </div>

        <p class="text-[10px] font-semibold uppercase tracking-[0.15em] text-stone-400">
            Product inventory
        </p>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full min-w-[950px] text-left text-sm">

            <thead class="border-b border-stone-200 bg-[#F8F7F4]">

                <tr class="text-[10px] font-semibold uppercase tracking-[0.16em] text-stone-400">

                    <th class="px-6 py-4">
                        Produk
                    </th>

                    <th class="px-6 py-4">
                        Kategori
                    </th>

                    <th class="px-6 py-4">
                        Unit
                    </th>

                    <th class="px-6 py-4">
                        Harga
                    </th>

                    <th class="px-6 py-4">
                        Stok
                    </th>

                    <th class="px-6 py-4">
                        Status
                    </th>

                    <th class="px-6 py-4">
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-stone-100">

            @forelse ($products as $product)

                @php
                    $price = $product->units
                        ->flatMap(fn ($unit) => $unit->prices)
                        ->sortBy('minimum_quantity')
                        ->first();

                    $formattedPrice = $price?->selling_price !== null
                        ? 'Rp ' . number_format((float) $price->selling_price, 0, ',', '.')
                        : 'Belum diatur';
                @endphp


                <tr class="group align-middle transition hover:bg-[#FCFBF8]">

                    {{-- PRODUCT --}}
                    <td class="px-6 py-5">

                        <p class="font-semibold text-stone-900">
                            {{ $product->name }}
                        </p>

                        <p class="mt-1 text-xs text-stone-400">
                            {{ $product->legacy_code ?: 'Tanpa kode' }}
                        </p>

                    </td>


                    {{-- CATEGORY --}}
                    <td class="px-6 py-5">

                        <span class="text-stone-600">
                            {{ $product->category?->name ?? '-' }}
                        </span>

                    </td>


                    {{-- UNITS --}}
                    <td class="px-6 py-5">

                        <p class="font-medium text-stone-700">
                            {{ $product->units->count() }}
                        </p>

                        <p class="mt-1 text-xs text-stone-400">
                            unit
                        </p>

                    </td>


                    {{-- PRICE --}}
                    <td class="px-6 py-5">

                        <p class="font-semibold {{ $price ? 'text-[#9A763D]' : 'text-amber-700' }}">
                            {{ $formattedPrice }}
                        </p>

                        @if ($price)
                            <p class="mt-1 text-[10px] text-stone-400">
                                Harga awal
                            </p>
                        @endif

                    </td>


                    {{-- STOCK --}}
                    <td class="px-6 py-5">

                        <p class="font-medium text-stone-800">
                            {{ number_format((float) $product->current_stock, 3, ',', '.') }}
                        </p>

                    </td>


                    {{-- STATUS --}}
                    <td class="px-6 py-5">

                        <span class="inline-flex border px-2.5 py-1 text-[10px] font-semibold
                            {{ $product->status === 'active'
                                ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                : 'border-stone-200 bg-stone-100 text-stone-500'
                            }}"
                        >
                            {{ ucfirst($product->status) }}
                        </span>

                    </td>


                    {{-- ACTION --}}
                    <td class="px-6 py-5 text-right">

                        <a
                            href="{{ route('admin.products.edit', $product) }}"
                            class="inline-flex items-center text-xs font-semibold text-stone-700 transition hover:text-[#9A763D]"
                        >
                            Kelola
                            <span class="ml-1 transition-transform group-hover:translate-x-0.5">
                                →
                            </span>
                        </a>

                    </td>

                </tr>


            @empty

                <tr>

                    <td colspan="7" class="px-6 py-16 text-center">

                        <p class="text-sm font-medium text-stone-700">
                            Produk tidak ditemukan
                        </p>

                        <p class="mt-1 text-xs text-stone-400">
                            Coba gunakan nama atau kode produk yang berbeda.
                        </p>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


{{-- MOBILE --}}
<div class="mt-7 space-y-4 lg:hidden">

    <div class="flex items-end justify-between">

        <div>
            <h2 class="text-sm font-semibold text-stone-900">
                Daftar produk
            </h2>

            <p class="mt-1 text-xs text-stone-400">
                {{ $products->total() }} produk
            </p>
        </div>

    </div>


    @forelse ($products as $product)

        @php
            $price = $product->units
                ->flatMap(fn ($unit) => $unit->prices)
                ->sortBy('minimum_quantity')
                ->first();

            $formattedPrice = $price?->selling_price !== null
                ? 'Rp ' . number_format((float) $price->selling_price, 0, ',', '.')
                : 'Belum diatur';
        @endphp


        <article class="border border-stone-200 bg-white">

            {{-- PRODUCT HEADER --}}
            <div class="flex items-start justify-between gap-4 border-b border-stone-200 px-5 py-4">

                <div class="min-w-0">

                    <p class="font-semibold leading-5 text-stone-900">
                        {{ $product->name }}
                    </p>

                    <p class="mt-1 text-xs text-stone-400">
                        {{ $product->legacy_code ?: 'Tanpa kode' }}
                    </p>

                </div>


                <span class="shrink-0 border px-2.5 py-1 text-[10px] font-semibold
                    {{ $product->status === 'active'
                        ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                        : 'border-stone-200 bg-stone-100 text-stone-500'
                    }}"
                >
                    {{ ucfirst($product->status) }}
                </span>

            </div>


            {{-- BASIC INFO --}}
            <div class="grid grid-cols-2 border-b border-stone-100">

                <div class="px-5 py-4">

                    <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                        Kategori
                    </p>

                    <p class="mt-1 text-sm text-stone-700">
                        {{ $product->category?->name ?? '-' }}
                    </p>

                </div>


                <div class="border-l border-stone-100 px-5 py-4">

                    <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                        Unit
                    </p>

                    <p class="mt-1 text-sm font-medium text-stone-700">
                        {{ $product->units->count() }} unit
                    </p>

                </div>

            </div>


            {{-- PRICE + STOCK --}}
            <div class="grid grid-cols-2 border-b border-stone-100">

                <div class="px-5 py-4">

                    <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                        Harga
                    </p>

                    <p class="mt-1 text-sm font-semibold {{ $price ? 'text-[#9A763D]' : 'text-amber-700' }}">
                        {{ $formattedPrice }}
                    </p>

                </div>


                <div class="border-l border-stone-100 px-5 py-4">

                    <p class="text-[10px] font-semibold uppercase tracking-wider text-stone-400">
                        Stok
                    </p>

                    <p class="mt-1 text-sm font-medium text-stone-800">
                        {{ number_format((float) $product->current_stock, 3, ',', '.') }}
                    </p>

                </div>

            </div>


            {{-- ACTION --}}
            <div class="flex items-center justify-between bg-[#FAF9F6] px-5 py-4">

                <p class="text-xs text-stone-400">
                    Kelola detail produk
                </p>

                <a
                    href="{{ route('admin.products.edit', $product) }}"
                    class="inline-flex h-9 items-center bg-[#171614] px-4 text-xs font-semibold text-white transition hover:bg-[#9A763D]"
                >
                    Kelola →
                </a>

            </div>

        </article>


    @empty

        <div class="border border-stone-200 bg-white px-5 py-16 text-center">

            <p class="text-sm font-medium text-stone-700">
                Produk tidak ditemukan
            </p>

            <p class="mt-1 text-xs text-stone-400">
                Coba gunakan nama atau kode produk yang berbeda.
            </p>

        </div>

    @endforelse

</div>


{{-- PAGINATION --}}
{{-- PAGINATION --}}
@if ($products->hasPages())
    <div class="mt-8 flex flex-col gap-4 border-t border-stone-200 pt-5 sm:flex-row sm:items-center sm:justify-between">

        {{-- INFO --}}
        <p class="text-xs text-stone-400">
            Menampilkan
            <span class="font-semibold text-stone-600">
                {{ $products->firstItem() ?? 0 }}
            </span>
            –
            <span class="font-semibold text-stone-600">
                {{ $products->lastItem() ?? 0 }}
            </span>
            dari
            <span class="font-semibold text-stone-600">
                {{ $products->total() }}
            </span>
            produk
        </p>

        {{-- NAVIGATION --}}
        <nav
            class="flex items-center gap-1"
            aria-label="Pagination"
        >

            {{-- PREVIOUS --}}
            @if ($products->onFirstPage())
                <span
                    class="flex h-9 w-9 cursor-not-allowed items-center justify-center border border-stone-200 text-stone-300"
                    aria-disabled="true"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </span>
            @else
                <a
                    href="{{ $products->previousPageUrl() }}"
                    class="flex h-9 w-9 items-center justify-center border border-stone-200 bg-white text-stone-500 transition hover:border-[#B28A45] hover:bg-[#B28A45] hover:text-white"
                    aria-label="Halaman sebelumnya"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </a>
            @endif


            {{-- PAGE NUMBERS --}}
            @php
                $current = $products->currentPage();
                $last = $products->lastPage();

                $pages = [];

                // Selalu tampilkan halaman pertama
                $pages[] = 1;

                // Halaman sekitar current
                for ($i = $current - 1; $i <= $current + 1; $i++) {
                    if ($i > 1 && $i < $last) {
                        $pages[] = $i;
                    }
                }

                // Selalu tampilkan halaman terakhir
                if ($last > 1) {
                    $pages[] = $last;
                }

                $pages = array_values(array_unique($pages));
                sort($pages);
            @endphp


            @foreach ($pages as $index => $page)

                {{-- ELLIPSIS --}}
                @if ($index > 0 && $page > $pages[$index - 1] + 1)
                    <span class="flex h-9 w-7 items-center justify-center text-xs text-stone-400">
                        …
                    </span>
                @endif


                {{-- ACTIVE PAGE --}}
                @if ($page == $current)
                    <span
                        class="flex h-9 min-w-9 items-center justify-center border border-[#B28A45] bg-[#B28A45] px-2 text-xs font-semibold text-white"
                        aria-current="page"
                    >
                        {{ $page }}
                    </span>

                {{-- OTHER PAGE --}}
                @else
                    <a
                        href="{{ $products->url($page) }}"
                        class="flex h-9 min-w-9 items-center justify-center border border-stone-200 bg-white px-2 text-xs font-medium text-stone-600 transition hover:border-[#B28A45] hover:bg-[#B28A45] hover:text-white"
                    >
                        {{ $page }}
                    </a>
                @endif

            @endforeach


            {{-- NEXT --}}
            @if ($products->hasMorePages())
                <a
                    href="{{ $products->nextPageUrl() }}"
                    class="flex h-9 w-9 items-center justify-center border border-stone-200 bg-white text-stone-500 transition hover:border-[#B28A45] hover:bg-[#B28A45] hover:text-white"
                    aria-label="Halaman berikutnya"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </a>
            @else
                <span
                    class="flex h-9 w-9 cursor-not-allowed items-center justify-center border border-stone-200 text-stone-300"
                    aria-disabled="true"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </span>
            @endif

        </nav>
    </div>
@endif

@endsection