<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $product->name }} | Ardians Group</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="product-detail-page min-h-screen text-slate-900 antialiased">

    @include('partials.store-header')

    @php
        $sellableUnits = $product->units
            ->filter(fn ($unit) => $unit->prices->isNotEmpty());
    @endphp

    <main class="mx-auto max-w-7xl px-4 py-8 lg:px-8 lg:py-12">

        {{-- Breadcrumb --}}
        <div class="mb-6 font-sans text-xs text-slate-500">
            <a
                href="{{ route('products.index') }}"
                class="hover:text-emerald-700"
            >
                Katalog
            </a>

            <span class="mx-2">/</span>

            <span>{{ $product->name }}</span>
        </div>

        <div class="grid gap-10 lg:grid-cols-[.95fr_1.05fr] lg:gap-16">

            {{-- Product Image --}}
            <div class="relative overflow-hidden rounded-2xl bg-[#e8f4ee] p-4">
                <div
                    class="flex min-h-[340px] items-center justify-center overflow-hidden rounded-xl bg-white sm:min-h-[500px]"
                >
                    @if ($product->thumbnail)
                        <img
                            src="{{ asset('storage/' . $product->thumbnail->image_path) }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-contain"
                        >
                    @else
                        <div class="font-display text-8xl font-bold text-emerald-700">
                            {{ strtoupper(substr($product->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <span
                    class="absolute left-8 top-8 rounded-full bg-white px-3 py-1.5 font-sans text-xs font-bold text-emerald-700"
                >
                    {{ $product->isOutOfStock() ? 'Stok habis' : 'Produk tersedia' }}
                </span>
            </div>

            {{-- Product Information --}}
            <section class="pt-2">

                {{-- Status & Product Code --}}
                <div class="flex flex-wrap items-center gap-2 font-sans text-xs text-slate-500">
                    <span
                        class="rounded-full bg-emerald-100 px-3 py-1 font-semibold text-emerald-800"
                    >
                        Aktif
                    </span>

                    <span>
                        Kode {{ $product->legacy_code }}
                    </span>
                </div>

                {{-- Product Name --}}
                <h1
                    class="mt-4 max-w-2xl font-display text-5xl font-bold leading-[.95] tracking-tight text-slate-950"
                >
                    {{ $product->name }}
                </h1>

                {{-- Description --}}
                <p class="mt-5 max-w-xl font-sans text-sm leading-7 text-slate-600">
                    {{ $product->description ?: 'Produk kebutuhan harian dengan pilihan satuan dan harga yang dapat disesuaikan.' }}
                </p>

                {{-- Product Metadata --}}
                <dl class="mt-7 grid grid-cols-3 border-y border-slate-200 py-5 font-sans text-sm">

                    <div>
                        <dt class="text-xs text-slate-400">
                            Kategori
                        </dt>

                        <dd class="mt-1 font-semibold">
                            {{ $product->category?->name ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-400">
                            Merek
                        </dt>

                        <dd class="mt-1 font-semibold">
                            {{ $product->brand?->name ?? '-' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs text-slate-400">
                            Stok
                        </dt>

                        <dd
                            class="mt-1 font-semibold {{ $product->isOutOfStock() ? 'text-rose-600' : 'text-emerald-700' }}"
                        >
                            {{ $product->isOutOfStock() ? 'Habis' : 'Tersedia' }}
                        </dd>
                    </div>

                </dl>

                {{-- Unit List --}}
                <h2
                    class="mt-8 font-sans text-xs font-bold uppercase tracking-[.18em] text-slate-400"
                >
                    Pilih unit pembelian
                </h2>

                <div class="mt-3 divide-y divide-slate-200 rounded-xl border border-slate-200 bg-white">
                    @forelse ($product->units->sortBy('unit_level') as $unit)

                        @php
                            $unitPrice = $unit->prices
                                ->sortBy('minimum_quantity')
                                ->first();

                            $conversionToBase = (float) $unit->conversion_to_base;

                            $unitStock = $conversionToBase > 0
                                ? (float) $product->current_stock / $conversionToBase
                                : 0;
                        @endphp

                        <div class="flex items-center justify-between gap-4 px-4 py-3 font-sans">

                            <div>
                                <p class="text-sm font-semibold">
                                    {{ $unit->unit_name }}
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    {{ rtrim(rtrim(number_format($conversionToBase, 3, ',', '.'), '0'), ',') }}
                                    x unit dasar
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-sm font-bold text-emerald-700">
                                    @if ($unitPrice?->selling_price)
                                        Rp {{ number_format((float) $unitPrice->selling_price, 0, ',', '.') }}
                                    @else
                                        Cek harga
                                    @endif
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    Stok {{ number_format($unitStock, 3, ',', '.') }}
                                </p>
                            </div>

                        </div>

                    @empty
                        <p class="p-5 font-sans text-sm text-slate-500">
                            Unit belum tersedia.
                        </p>
                    @endforelse
                </div>

                {{-- Add To Cart --}}
                @if ($sellableUnits->isNotEmpty())

                    @php
                        $defaultUnit = $sellableUnits
                            ->sortBy('unit_level')
                            ->first();

                        $defaultConversion = (float) $defaultUnit->conversion_to_base;

                        $defaultStock = $defaultConversion > 0
                            ? (float) $product->current_stock / $defaultConversion
                            : 0;
                    @endphp

                    <form
                        method="POST"
                        action="{{ route('cart.add') }}"
                        class="mt-6 rounded-xl border border-emerald-100 bg-emerald-50 p-4"
                    >
                        @csrf

                        <div class="flex flex-col gap-3 sm:flex-row">

                            {{-- Unit --}}
                            <div class="sm:w-44">
                                <label
                                    for="product_unit_id"
                                    class="font-sans text-xs font-semibold text-slate-600"
                                >
                                    Satuan
                                </label>

                                <select
                                    id="product_unit_id"
                                    name="product_unit_id"
                                    class="mt-1 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 font-sans text-sm"
                                >
                                    @foreach ($sellableUnits->sortBy('unit_level') as $unit)

                                        @php
                                            $unitPrice = $unit->prices
                                                ->sortBy('minimum_quantity')
                                                ->first();

                                            $conversionToBase = (float) $unit->conversion_to_base;

                                            $unitStock = $conversionToBase > 0
                                                ? (float) $product->current_stock / $conversionToBase
                                                : 0;
                                        @endphp

                                        <option
                                            value="{{ $unit->id }}"
                                            data-stock="{{ $unitStock }}"
                                            @selected($unit->id === $defaultUnit->id)
                                        >
                                            {{ $unit->unit_name }}
                                            @if ($unitPrice?->selling_price)
                                                · Rp {{ number_format((float) $unitPrice->selling_price, 0, ',', '.') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Quantity --}}
                            <div class="sm:w-28">
                                <label
                                    for="quantity"
                                    class="font-sans text-xs font-semibold text-slate-600"
                                >
                                    Jumlah
                                </label>

                                <input
                                    id="quantity"
                                    name="quantity"
                                    type="number"
                                    min="0.001"
                                    step="0.001"
                                    value="1"
                                    class="mt-1 h-12 w-full rounded-lg border border-slate-300 bg-white px-3 font-sans text-sm"
                                >
                            </div>

                            {{-- Submit --}}
                            <button
                                type="submit"
                                class="mt-auto h-12 flex-1 rounded-lg bg-emerald-700 px-5 font-sans text-sm font-bold text-white transition hover:bg-emerald-800"
                            >
                                Tambah ke keranjang
                            </button>

                        </div>

                        {{-- Selected Unit Stock --}}
                        <p
                            id="unit-stock"
                            class="mt-3 font-sans text-xs text-emerald-800"
                        >
                            Stok tersedia:
                            {{ number_format($defaultStock, 3, ',', '.') }}
                            {{ $defaultUnit->unit_name }}
                        </p>
                    </form>

                @else

                    {{-- No Price --}}
                    <div
                        class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 font-sans text-sm text-amber-800"
                    >
                        Harga produk belum tersedia. Produk dapat dilihat,
                        tetapi belum bisa ditambahkan ke keranjang.
                    </div>

                @endif

            </section>
        </div>
    </main>

    @include('partials.store-footer')

    {{-- Unit Stock Script --}}
    @if ($product->units->isNotEmpty())
        <script>
            const unitSelect = document.getElementById('product_unit_id');
            const unitStock = document.getElementById('unit-stock');

            unitSelect?.addEventListener('change', () => {
                const option = unitSelect.options[unitSelect.selectedIndex];

                unitStock.textContent =
                    `Stok tersedia: ${Number(option.dataset.stock).toLocaleString('id-ID')} ${option.textContent.split(' · ')[0]}`;
            });
        </script>
    @endif

</body>
</html>
