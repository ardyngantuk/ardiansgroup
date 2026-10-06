<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Produk | ARDIAN'S Sembako Wholesale & Retail</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="products-page min-h-screen antialiased">

    {{-- =========================================================
        NAVBAR
    ========================================================== --}}

    @include('partials.store-header')


    <main class="pb-20">


        {{-- =========================================================
            HERO SLIDER
        ========================================================== --}}

        <section class="px-3 pt-3 sm:px-5 lg:px-6">

            <div
                id="productsHero"
                class="products-hero relative mx-auto max-w-[1440px] overflow-hidden rounded-[20px] sm:rounded-[22px]"
            >


                {{-- =================================================
                    SLIDE 1
                ================================================== --}}

                <div class="products-hero-slide active">

                    <div class="relative min-h-[300px] overflow-hidden sm:min-h-[350px] lg:min-h-[410px]">

                        <img
                            src="{{ file_exists(public_path('images/asset banner.png')) ? asset('images/asset banner.png') : asset('favicon.ico') }}"
                            alt="ARDIAN'S Market"
                            class="products-hero-image"
                            onerror="this.onerror=null;this.src='{{ asset('favicon.ico') }}';"
                        >

                        <div class="products-hero-overlay"></div>


                        <div
                            class="products-hero-content mx-auto flex min-h-[300px] max-w-7xl items-center px-6 py-12 sm:min-h-[350px] sm:px-10 lg:min-h-[410px] lg:px-14"
                        >

                            <div class="hero-copy max-w-xl">

                                <div class="mb-4 flex items-center gap-2">

                                    <span class="h-px w-7 bg-[var(--brand-gold)]"></span>

                                    <span class="text-[9px] font-bold uppercase tracking-[.28em] text-[var(--brand-gold)]">
                                        ARDIAN'S MARKET
                                    </span>

                                </div>


                                <h1
                                    class="font-display text-3xl font-semibold leading-[1.06] tracking-tight text-[var(--brand-cream)] sm:text-5xl lg:text-6xl"
                                >
                                    Semua kebutuhan,
                                    <br>
                                    dalam satu tempat.
                                </h1>


                                <p class="mt-5 max-w-lg text-xs leading-6 text-white/75 sm:text-sm">
                                    Temukan sembako dan kebutuhan harian
                                    untuk rumah, usaha, maupun toko Anda
                                    dengan mudah.
                                </p>


                                <div class="mt-7 flex flex-wrap gap-3">

                                    <a
                                        href="#produk"
                                        class="inline-flex items-center gap-2 rounded-xl bg-[var(--brand-gold)] px-5 py-3 text-[11px] font-bold text-[var(--brand-indigo)] shadow-lg shadow-black/10 transition hover:-translate-y-0.5 hover:bg-[#c6a15b]"
                                    >

                                        Belanja sekarang

                                        <svg
                                            class="h-3.5 w-3.5"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path d="M5 12h13"/>
                                            <path d="m13 6 6 6-6 6"/>
                                        </svg>

                                    </a>


                                    <a
                                        href="#kategori"
                                        class="inline-flex items-center rounded-xl border border-white/25 bg-white/5 px-5 py-3 text-[11px] font-bold text-white backdrop-blur-sm transition hover:bg-white/10"
                                    >
                                        Jelajahi kategori
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                    SLIDE 2
                ================================================== --}}

                <div class="products-hero-slide">

                    <div class="relative min-h-[300px] overflow-hidden sm:min-h-[350px] lg:min-h-[410px]">

                        <img
                            src="{{ file_exists(public_path('images/asset banner 2.png')) ? asset('images/asset banner 2.png') : asset('favicon.ico') }}"
                            alt="Kebutuhan rumah dan usaha"
                            class="products-hero-image"
                            onerror="this.onerror=null;this.src='{{ asset('favicon.ico') }}';"
                        >

                        <div class="products-hero-overlay"></div>


                        <div
                            class="products-hero-content mx-auto flex min-h-[300px] max-w-7xl items-center px-6 py-12 sm:min-h-[350px] sm:px-10 lg:min-h-[410px] lg:px-14"
                        >

                            <div class="hero-copy max-w-xl">

                                <div class="mb-3 flex items-center gap-2">

                                    <span class="h-px w-7 bg-[var(--brand-gold)]"></span>

                                    <span class="text-[9px] font-bold uppercase tracking-[.28em] text-[var(--brand-gold)]">
                                        UNTUK RUMAH & USAHA
                                    </span>

                                </div>


                                <h2
                                    class="font-display text-3xl font-semibold leading-[1.06] tracking-tight text-[var(--brand-cream)] sm:text-5xl lg:text-6xl"
                                >
                                    Belanja lebih
                                    <br>
                                    praktis setiap hari.
                                </h2>


                                <p class="mt-5 max-w-lg text-xs leading-6 text-white/75 sm:text-sm">
                                    Pilih produk, cek harga, dan temukan
                                    kebutuhan harian tanpa harus datang
                                    langsung ke toko.
                                </p>


                                <div class="mt-7">

                                    <a
                                        href="#produk"
                                        class="inline-flex items-center gap-2 rounded-xl bg-[var(--brand-cream)] px-5 py-3 text-[11px] font-bold text-[var(--brand-indigo)] transition hover:-translate-y-0.5 hover:bg-white"
                                    >

                                        Lihat produk

                                        <svg
                                            class="h-3.5 w-3.5"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path d="M5 12h13"/>
                                            <path d="m13 6 6 6-6 6"/>
                                        </svg>

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                    SLIDE 3
                ================================================== --}}

                <div class="products-hero-slide">

                    <div class="relative min-h-[300px] overflow-hidden sm:min-h-[350px] lg:min-h-[410px]">

                        <img
                            src="{{ file_exists(public_path('images/banner 3.png')) ? asset('images/banner 3.png') : asset('favicon.ico') }}"
                            alt="Belanja kebutuhan harian"
                            class="products-hero-image"
                            onerror="this.onerror=null;this.src='{{ asset('favicon.ico') }}';"
                        >   

                        <div class="products-hero-overlay"></div>


                        <div
                            class="products-hero-content mx-auto flex min-h-[300px] max-w-7xl items-center px-6 py-12 sm:min-h-[350px] sm:px-10 lg:min-h-[410px] lg:px-14"
                        >

                            <div class="hero-copy max-w-xl">

                                <div class="mb-4 flex items-center gap-2">

                                    <span class="h-px w-7 bg-[var(--brand-gold)]"></span>

                                    <span class="text-[9px] font-bold uppercase tracking-[.28em] text-[var(--brand-gold)]">
                                        HARGA TRANSPARAN
                                    </span>

                                </div>


                                <h2
                                    class="font-display text-3xl font-semibold leading-[1.06] tracking-tight text-[var(--brand-cream)] sm:text-5xl lg:text-6xl"
                                >
                                    Cari kebutuhan
                                    <br>
                                    tanpa ribet.
                                </h2>


                                <p class="mt-5 max-w-lg text-xs leading-6 text-white/75 sm:text-sm">
                                    Katalog dirancang sederhana agar Anda
                                    dapat menemukan produk yang dibutuhkan
                                    dengan cepat.
                                </p>


                                <div class="mt-7 flex flex-wrap gap-3">

                                    <span
                                        class="rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-[10px] font-semibold text-white backdrop-blur-sm"
                                    >
                                        ✓ Harga jelas
                                    </span>

                                    <span
                                        class="rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-[10px] font-semibold text-white backdrop-blur-sm"
                                    >
                                        ✓ Produk lengkap
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                    HERO CONTROLS
                ================================================== --}}

                <div class="absolute bottom-5 right-5 z-20 flex items-center gap-2">

                    <button
                        type="button"
                        id="heroPrev"
                        class="hero-arrow"
                        aria-label="Slide sebelumnya"
                    >

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="m15 18-6-6 6-6"/>
                        </svg>

                    </button>


                    <div
                        id="heroDots"
                        class="flex items-center gap-1.5 rounded-full border border-white/10 bg-black/10 px-2.5 py-2 backdrop-blur-md"
                    >

                        <button
                            type="button"
                            class="hero-dot active"
                            data-slide="0"
                            aria-label="Slide 1"
                        ></button>

                        <button
                            type="button"
                            class="hero-dot"
                            data-slide="1"
                            aria-label="Slide 2"
                        ></button>

                        <button
                            type="button"
                            class="hero-dot"
                            data-slide="2"
                            aria-label="Slide 3"
                        ></button>

                    </div>


                    <button
                        type="button"
                        id="heroNext"
                        class="hero-arrow"
                        aria-label="Slide berikutnya"
                    >

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="m9 18 6-6-6-6"/>
                        </svg>

                    </button>

                </div>

            </div>

        </section>



        {{-- =========================================================
            MAIN CONTENT
        ========================================================== --}}

        <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">


            {{-- =====================================================
                SEARCH BAR
                FIX:
                Tidak lagi menggunakan negative margin.
                Hero → 32px → Search
            ====================================================== --}}

            <section class="relative z-30 mt-8">

                <form
                    method="GET"
                    action="{{ route('products.index') }}"
                    class="catalog-toolbar flex flex-col gap-2 rounded-2xl border border-[var(--brand-line)] bg-[var(--brand-paper)] p-2 sm:flex-row"
                >

                    <div
                        class="flex min-w-0 flex-1 items-center gap-3 rounded-xl bg-[var(--brand-soft)] px-4"
                    >

                        <svg
                            class="h-5 w-5 shrink-0 text-[var(--brand-muted)]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>


                        <label
                            for="q"
                            class="sr-only"
                        >
                            Cari produk
                        </label>


                        <input
                            id="q"
                            name="q"
                            value="{{ $search }}"
                            type="search"
                            autocomplete="off"
                            placeholder="Cari nama produk, merek, kode, atau barcode..."
                            class="h-12 min-w-0 flex-1 bg-transparent text-sm text-[var(--brand-ink)] outline-none placeholder:text-[#a2a49f]"
                        >

                    </div>


                    @if(request('category'))

                        <input
                            type="hidden"
                            name="category"
                            value="{{ request('category') }}"
                        >

                    @endif


                    <button
                        type="submit"
                        class="h-12 rounded-xl bg-[var(--brand-indigo)] px-7 text-sm font-bold text-[var(--brand-cream)] transition hover:bg-[var(--brand-indigo-deep)]"
                    >
                        Cari
                    </button>

                </form>

            </section>



            {{-- =====================================================
                CATEGORY
            ====================================================== --}}

            @if ($categories->isNotEmpty())

                @php
                    $activeCategory = request('category');
                @endphp


                <section
                    id="kategori"
                    class="mt-10"
                >

                    <div class="flex items-end justify-between gap-4">

                        <div>

                            <p
                                class="text-[9px] font-bold uppercase tracking-[.24em] text-[var(--brand-gold)]"
                            >
                                Jelajahi katalog
                            </p>

                            <h2
                                class="mt-1 font-display text-2xl font-semibold text-[var(--brand-ink)] sm:text-3xl"
                            >
                                Kategori produk
                            </h2>

                            <p class="mt-1 text-xs text-[var(--brand-muted)]">
                                Temukan kebutuhan berdasarkan kategori.
                            </p>

                        </div>


                        <span
                            class="hidden rounded-full border border-[var(--brand-line)] bg-[var(--brand-paper)] px-3 py-1.5 text-[10px] font-semibold text-[var(--brand-muted)] sm:block"
                        >
                            {{ $categories->count() }} kategori
                        </span>

                    </div>



                    <div class="category-scroll mt-5 overflow-x-auto pb-2">

                        <div class="flex min-w-max gap-2.5">


                            {{-- SEMUA --}}

                            <a
                                href="{{ route('products.index', $search !== '' ? ['q' => $search] : []) }}"
                                class="category-chip flex h-11 items-center gap-2 rounded-xl border px-4 text-xs font-bold
                                {{ !$activeCategory
                                    ? 'active'
                                    : 'border-[var(--brand-line)] bg-[var(--brand-paper)] text-[var(--brand-muted)]' }}"
                            >

                                <span
                                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-black/5"
                                >

                                    <svg
                                        class="h-3.5 w-3.5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <rect x="4" y="4" width="6" height="6" rx="1"/>
                                        <rect x="14" y="4" width="6" height="6" rx="1"/>
                                        <rect x="4" y="14" width="6" height="6" rx="1"/>
                                        <rect x="14" y="14" width="6" height="6" rx="1"/>
                                    </svg>

                                </span>

                                Semua

                            </a>



                            @foreach ($categories as $category)

                                @php

                                    $isActive =
                                        $activeCategory === $category->slug ||
                                        $activeCategory === (string) $category->id;

                                    $categoryKey = strtolower($category->name);

                                    $categoryIcon = match (true) {

                                        str_contains($categoryKey, 'beras') => '
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-3.5 w-3.5">
                                                <path d="M4 11c2.5-3 4.5-4.5 8-4.5S19.5 8 20 11v1.5c0 2.5-1.7 4.5-4 5.5H8c-2.3-1-4-3-4-5.5V11Z"/>
                                                <path d="M8 13h8"/>
                                            </svg>
                                        ',

                                        str_contains($categoryKey, 'minyak') => '
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-3.5 w-3.5">
                                                <path d="M9 4h6l2 4.5V18a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V8.5L9 4Z"/>
                                                <path d="M9 9h6"/>
                                            </svg>
                                        ',

                                        str_contains($categoryKey, 'sabun') ||
                                        str_contains($categoryKey, 'cuci') => '
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-3.5 w-3.5">
                                                <path d="M7 10h10v8a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2v-8Z"/>
                                                <path d="M9 10V7a3 3 0 1 1 6 0v3"/>
                                            </svg>
                                        ',

                                        str_contains($categoryKey, 'sayur') ||
                                        str_contains($categoryKey, 'buah') => '
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-3.5 w-3.5">
                                                <path d="M12 4c2.5 2 4 4 4 7 0 3.5-2 6.5-4 7-2-.5-4-3.5-4-7 0-3 1.5-5 4-7Z"/>
                                                <path d="M12 4c0 2-1 3-3 4"/>
                                            </svg>
                                        ',

                                        default => '
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-3.5 w-3.5">
                                                <path d="M6 8h12v10a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8Z"/>
                                                <path d="M9 8V6a3 3 0 1 1 6 0v2"/>
                                            </svg>
                                        ',
                                    };

                                @endphp


                                <a
                                    href="{{ route('products.index', [
                                        'category' => $category->slug,
                                        'q' => $search ?: null
                                    ]) }}"
                                    class="category-chip flex h-11 items-center gap-2 rounded-xl border px-4 text-xs font-bold
                                    {{ $isActive
                                        ? 'active'
                                        : 'border-[var(--brand-line)] bg-[var(--brand-paper)] text-[var(--brand-muted)]' }}"
                                >

                                    <span
                                        class="flex h-6 w-6 items-center justify-center rounded-lg bg-black/5"
                                    >

                                        {!! $categoryIcon !!}

                                    </span>

                                    {{ $category->name }}

                                </a>

                            @endforeach

                        </div>

                    </div>

                </section>

            @endif



            {{-- =====================================================
                PRODUCT HEADER
            ====================================================== --}}

            <section
                id="produk"
                class="mt-10"
            >

                <div
                    class="flex flex-col gap-4 border-b border-[var(--brand-line)] pb-5 sm:flex-row sm:items-end sm:justify-between"
                >

                    <div>

                        <p
                            class="text-[9px] font-bold uppercase tracking-[.2em] text-[var(--brand-gold)]"
                        >

                            {{ $search
                                ? 'Hasil pencarian'
                                : ($selectedCategory
                                    ? 'Kategori terpilih'
                                    : 'Katalog produk') }}

                        </p>


                        <h2
                            class="mt-1 font-display text-2xl font-semibold text-[var(--brand-ink)] sm:text-3xl"
                        >

                            {{ $search
                                ? 'Produk yang sesuai'
                                : ($selectedCategory
                                    ? $selectedCategory->name
                                    : 'Pilihan kebutuhan harian') }}

                        </h2>


                        <p class="mt-1 text-xs text-[var(--brand-muted)]">

                            Menampilkan

                            <span class="font-bold text-[var(--brand-ink)]">
                                {{ $products->count() }}
                            </span>

                            dari

                            <span class="font-bold text-[var(--brand-ink)]">
                                {{ number_format($products->total(), 0, ',', '.') }}
                            </span>

                            produk.

                        </p>

                    </div>


                    <div class="flex items-center gap-2">

                        <span class="text-[10px] text-[var(--brand-muted)]">
                            Halaman
                        </span>

                        <span
                            class="rounded-lg bg-[var(--brand-indigo)] px-2.5 py-1.5 text-[10px] font-bold text-[var(--brand-cream)]"
                        >
                            {{ $products->currentPage() }}
                        </span>

                        <span class="text-[10px] text-[var(--brand-muted)]">
                            dari {{ $products->lastPage() }}
                        </span>

                    </div>

                </div>

            </section>



            {{-- =====================================================
                PRODUCTS
            ====================================================== --}}

            @if ($products->isEmpty())

                {{-- EMPTY STATE --}}

                <section
                    class="mt-7 rounded-3xl border border-dashed border-[var(--brand-line)] bg-[var(--brand-paper)] px-6 py-20 text-center"
                >

                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--brand-soft)] text-[var(--brand-indigo)]"
                    >

                        <svg
                            class="h-7 w-7"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m20 20-4-4"/>
                        </svg>

                    </div>


                    <h2
                        class="mt-5 font-display text-2xl font-semibold text-[var(--brand-ink)]"
                    >
                        Produk tidak ditemukan
                    </h2>


                    <p class="mt-2 text-sm text-[var(--brand-muted)]">
                        Coba gunakan nama, kode, atau barcode yang berbeda.
                    </p>


                    <a
                        href="{{ route('products.index') }}"
                        class="mt-6 inline-flex rounded-xl bg-[var(--brand-indigo)] px-5 py-3 text-xs font-bold text-[var(--brand-cream)] transition hover:bg-[var(--brand-indigo-deep)]"
                    >
                        Lihat semua produk
                    </a>

                </section>

            @else

                {{-- =================================================
                    PRODUCT GRID
                ================================================== --}}

                <section
                    class="mt-7 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4 xl:grid-cols-5"
                >

                    @foreach ($products as $product)

                        @php

                            $price = $product->prices
                                ->sortBy('minimum_quantity')
                                ->first();

                            $unit = $product->units
                                ->sortBy('unit_level')
                                ->first();

                            $available = (float) $product->current_stock > 0;

                        @endphp


                        <a
                            href="{{ route('products.show', $product) }}"
                            class="product-card group flex min-w-0 flex-col overflow-hidden rounded-2xl border border-[var(--brand-line)] bg-[var(--brand-paper)] focus:outline-none focus:ring-4 focus:ring-[#d8e4df]"
                        >


                            {{-- IMAGE --}}

                            <div class="product-image-wrap relative">

                                {{-- STATUS --}}

                                <div
                                    class="absolute left-2.5 top-2.5 z-10 sm:left-3 sm:top-3"
                                >

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-white/95 px-2 py-1 text-[7px] font-bold uppercase tracking-[.06em] shadow-sm backdrop-blur sm:px-2.5 sm:py-1.5 sm:text-[8px]"
                                    >

                                        <span
                                            class="h-1.5 w-1.5 rounded-full
                                            {{ $available
                                                ? 'bg-emerald-600'
                                                : 'bg-rose-600' }}"
                                        ></span>

                                        <span
                                            class="{{ $available ? 'text-emerald-700' : 'text-rose-700' }}"
                                        >
                                            {{ $available ? 'Tersedia' : 'Habis' }}
                                        </span>

                                    </span>

                                </div>


                                {{-- PRODUCT IMAGE --}}

                                @if ($product->thumbnail)

                                    <img
                                        src="{{ asset('storage/' . $product->thumbnail->image_path) }}"
                                        alt="{{ $product->name }}"
                                        loading="lazy"
                                        class="product-image aspect-square w-full object-contain mix-blend-multiply"
                                    >

                                @else

                                    <div
                                        class="product-image flex aspect-square items-center justify-center bg-gradient-to-br from-[#e9eee8] to-[#dce7df] font-display text-5xl font-semibold text-[var(--brand-indigo)] sm:text-6xl"
                                    >
                                        {{ strtoupper(substr($product->name, 0, 1)) }}
                                    </div>

                                @endif

                            </div>



                            {{-- CONTENT --}}

                            <div class="flex flex-1 flex-col p-3 sm:p-4">


                                {{-- BRAND / CATEGORY --}}

                                <div class="flex items-center justify-between gap-2">

                                    <p
                                        class="min-w-0 truncate text-[8px] font-bold uppercase tracking-[.12em] text-[#92948f] sm:text-[9px]"
                                    >

                                        {{ $product->brand?->name
                                            ?? ($product->category?->name ?? 'Kebutuhan harian') }}

                                    </p>

                                </div>


                                {{-- PRODUCT NAME --}}

                                <h3
                                    class="mt-1.5 line-clamp-2 min-h-[38px] font-display text-[14px] font-semibold leading-[1.25] text-[var(--brand-ink)] transition group-hover:text-[var(--brand-gold)] sm:min-h-[42px] sm:text-[16px]"
                                >
                                    {{ $product->name }}
                                </h3>


                                {{-- META --}}

                                <div
                                    class="mt-2 flex min-w-0 items-center gap-1.5 overflow-hidden text-[8px] text-[var(--brand-muted)] sm:text-[9px]"
                                >

                                    @if($product->brand)

                                        <span
                                            class="max-w-[55%] truncate rounded-md bg-[var(--brand-soft)] px-1.5 py-1 sm:px-2"
                                        >
                                            {{ $product->brand->name }}
                                        </span>

                                    @endif


                                    @if($unit)

                                        <span
                                            class="shrink-0 rounded-md bg-[var(--brand-soft)] px-1.5 py-1 sm:px-2"
                                        >
                                            {{ $unit->unit_name }}
                                        </span>

                                    @endif

                                </div>


                                {{-- PRICE --}}

                                <div
                                    class="mt-4 border-t border-[var(--brand-line)] pt-3"
                                >

                                    <p class="text-[8px] text-[#9a9b96] sm:text-[9px]">
                                        Harga mulai
                                    </p>


                                    <div
                                        class="mt-0.5 flex items-end justify-between gap-2"
                                    >

                                        <div class="min-w-0">

                                            <p
                                                class="truncate font-display text-[15px] font-semibold text-[var(--brand-indigo)] sm:text-lg"
                                            >

                                                {{ $price?->selling_price
                                                    ? 'Rp ' . number_format((float) $price->selling_price, 0, ',', '.')
                                                    : 'Cek harga' }}

                                            </p>


                                            @if($unit)

                                                <p
                                                    class="mt-0.5 text-[8px] text-[#9a9b96] sm:text-[9px]"
                                                >
                                                    / {{ $unit->unit_name }}
                                                </p>

                                            @endif

                                        </div>


                                        {{-- DETAIL BUTTON --}}

                                        <span
                                            class="product-detail-button inline-flex shrink-0 items-center gap-1 rounded-lg bg-[var(--brand-indigo)] px-2.5 py-2 text-[8px] font-bold text-[var(--brand-cream)] sm:px-3 sm:text-[9px]"
                                        >

                                            Lihat

                                            <svg
                                                class="h-3 w-3"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path d="M5 12h13"/>
                                                <path d="m13 6 6 6-6 6"/>
                                            </svg>

                                        </span>

                                    </div>

                                </div>

                            </div>

                        </a>

                    @endforeach

                </section>



                {{-- =================================================
                    PAGINATION
                ================================================== --}}

                @if ($products->hasPages())

                    <section
                        class="mt-10 border-t border-[var(--brand-line)] pt-7"
                    >

                        <div
                            class="flex flex-col items-center gap-5 sm:flex-row sm:justify-between"
                        >


                            {{-- INFO --}}

                            <p class="text-[10px] text-[var(--brand-muted)]">

                                Menampilkan

                                <span class="font-bold text-[var(--brand-ink)]">
                                    {{ $products->firstItem() }}
                                </span>

                                –

                                <span class="font-bold text-[var(--brand-ink)]">
                                    {{ $products->lastItem() }}
                                </span>

                                dari

                                <span class="font-bold text-[var(--brand-ink)]">
                                    {{ number_format($products->total(), 0, ',', '.') }}
                                </span>

                                produk

                            </p>



                            {{-- PAGINATION --}}

                            <div class="flex items-center gap-1.5">


                                {{-- PREVIOUS --}}

                                @if ($products->onFirstPage())

                                    <span
                                        class="pagination-disabled flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-xl border border-[var(--brand-line)] bg-[var(--brand-soft)] text-[#c3c4c0]"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path d="m15 18-6-6 6-6"/>
                                        </svg>

                                    </span>

                                @else

                                    <a
                                        href="{{ $products->withQueryString()->previousPageUrl() }}"
                                        class="pagination-item flex h-9 w-9 items-center justify-center rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] text-[var(--brand-ink)]"
                                        aria-label="Halaman sebelumnya"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path d="m15 18-6-6 6-6"/>
                                        </svg>

                                    </a>

                                @endif



                                {{-- PAGE NUMBERS --}}

                                @foreach ($products->withQueryString()->getUrlRange(
                                    max(1, $products->currentPage() - 2),
                                    min($products->lastPage(), $products->currentPage() + 2)
                                ) as $page => $url)

                                    @if ($page == $products->currentPage())

                                        <span
                                            class="flex h-9 min-w-9 items-center justify-center rounded-xl bg-[var(--brand-indigo)] px-3 text-xs font-bold text-[var(--brand-cream)] shadow-sm"
                                        >
                                            {{ $page }}
                                        </span>

                                    @else

                                        <a
                                            href="{{ $url }}"
                                            class="pagination-item flex h-9 min-w-9 items-center justify-center rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-3 text-xs font-bold text-[#59645e]"
                                        >
                                            {{ $page }}
                                        </a>

                                    @endif

                                @endforeach



                                {{-- NEXT --}}

                                @if ($products->hasMorePages())

                                    <a
                                        href="{{ $products->withQueryString()->nextPageUrl() }}"
                                        class="pagination-item flex h-9 w-9 items-center justify-center rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] text-[var(--brand-ink)]"
                                        aria-label="Halaman berikutnya"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path d="m9 18 6-6-6-6"/>
                                        </svg>

                                    </a>

                                @else

                                    <span
                                        class="pagination-disabled flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-xl border border-[var(--brand-line)] bg-[var(--brand-soft)] text-[#c3c4c0]"
                                    >

                                        <svg
                                            class="h-4 w-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path d="m9 18 6-6-6-6"/>
                                        </svg>

                                    </span>

                                @endif

                            </div>

                        </div>

                    </section>

                @endif

            @endif

        </div>

    </main>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    @include('partials.store-footer')



    {{-- =========================================================
        HERO SLIDER JAVASCRIPT
    ========================================================== --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const hero = document.getElementById('productsHero');

            if (!hero) {
                return;
            }

            const slides =
                hero.querySelectorAll('.products-hero-slide');

            const dots =
                hero.querySelectorAll('.hero-dot');

            const prevButton =
                document.getElementById('heroPrev');

            const nextButton =
                document.getElementById('heroNext');


            if (!slides.length) {
                return;
            }


            let currentSlide = 0;
            let autoplay;



            /* =====================================================
               SHOW SLIDE
            ====================================================== */

            function showSlide(index) {

                currentSlide =
                    (index + slides.length) % slides.length;


                slides.forEach(function (slide, i) {

                    slide.classList.toggle(
                        'active',
                        i === currentSlide
                    );

                });


                dots.forEach(function (dot, i) {

                    dot.classList.toggle(
                        'active',
                        i === currentSlide
                    );

                });

            }



            /* =====================================================
               NEXT
            ====================================================== */

            function nextSlide() {

                showSlide(currentSlide + 1);

            }



            /* =====================================================
               PREVIOUS
            ====================================================== */

            function previousSlide() {

                showSlide(currentSlide - 1);

            }



            /* =====================================================
               AUTOPLAY
            ====================================================== */

            function startAutoplay() {

                clearInterval(autoplay);

                autoplay = setInterval(function () {

                    nextSlide();

                }, 5000);

            }



            /* =====================================================
               BUTTON EVENTS
            ====================================================== */

            if (nextButton) {

                nextButton.addEventListener(
                    'click',
                    function () {

                        nextSlide();

                        startAutoplay();

                    }
                );

            }


            if (prevButton) {

                prevButton.addEventListener(
                    'click',
                    function () {

                        previousSlide();

                        startAutoplay();

                    }
                );

            }



            /* =====================================================
               DOT EVENTS
            ====================================================== */

            dots.forEach(function (dot) {

                dot.addEventListener(
                    'click',
                    function () {

                        const index =
                            parseInt(
                                dot.dataset.slide,
                                10
                            );

                        showSlide(index);

                        startAutoplay();

                    }
                );

            });



            /* =====================================================
               PAUSE WHEN HOVER
            ====================================================== */

            hero.addEventListener(
                'mouseenter',
                function () {

                    clearInterval(autoplay);

                }
            );


            hero.addEventListener(
                'mouseleave',
                function () {

                    startAutoplay();

                }
            );



            /* =====================================================
               SWIPE MOBILE
            ====================================================== */

            let touchStartX = 0;
            let touchEndX = 0;


            hero.addEventListener(
                'touchstart',
                function (event) {

                    touchStartX =
                        event.changedTouches[0].screenX;

                },
                {
                    passive: true
                }
            );


            hero.addEventListener(
                'touchend',
                function (event) {

                    touchEndX =
                        event.changedTouches[0].screenX;


                    const distance =
                        touchEndX - touchStartX;


                    if (Math.abs(distance) < 50) {
                        return;
                    }


                    if (distance < 0) {

                        nextSlide();

                    } else {

                        previousSlide();

                    }


                    startAutoplay();

                },
                {
                    passive: true
                }
            );



            /* =====================================================
               START
            ====================================================== */

            showSlide(0);

            startAutoplay();

        });

    </script>

</body>

</html>