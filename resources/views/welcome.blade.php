<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta
        name="description"
        content="Ardians Store menyediakan kebutuhan sembako harian untuk rumah dan usaha."
    >

    <title>Ardians Store | Kebutuhan Harian Lebih Mudah</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="home-page min-h-screen antialiased">

    @include('partials.store-header')


    <main>

        {{-- =========================================================
            HERO
        ========================================================== --}}
        <section class="bg-[var(--bg)]">

            <div class="mx-auto max-w-7xl px-5 py-6 lg:px-8">

                <div class="fade-up relative mx-auto overflow-hidden rounded-xl border border-[var(--line)] bg-white shadow-sm">

                    <div class="relative aspect-[16/7] overflow-hidden sm:aspect-[21/8]">

                        @foreach ($heroSlides as $index => $slide)

                            <div
                                data-slide="{{ $index }}"
                                class="hero-image absolute inset-0 opacity-0 {{ $index === 0 ? 'opacity-100' : '' }}"
                            >

                                <img
                                    src="{{ $slide['image'] }}"
                                    alt="{{ $slide['title'] }}"
                                    class="h-full w-full object-cover brightness-[.78]"
                                >

                                <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/15 to-transparent"></div>

                                <div class="absolute inset-x-0 bottom-0 p-5 sm:p-8">

                                    <span class="inline-block rounded-md bg-[var(--orange)] px-3 py-1 text-[10px] font-black uppercase tracking-[.1em] text-[var(--ink)]">
                                        {{ $slide['label'] }}
                                    </span>

                                    <h2 class="mt-3 max-w-lg text-2xl font-black leading-tight text-white sm:text-4xl">
                                        {{ $slide['title'] }}
                                    </h2>

                                    <p class="mt-2 max-w-md text-xs leading-5 text-stone-200 sm:text-sm">
                                        {{ $slide['subtitle'] }}
                                    </p>

                                </div>

                            </div>

                        @endforeach

                    </div>


                    <div class="absolute bottom-4 right-4 flex gap-2">

                        <button
                            type="button"
                            data-hero-prev
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/40 bg-black/40 text-white transition hover:bg-[var(--green)]"
                            aria-label="Slide sebelumnya"
                        >
                            ←
                        </button>

                        <button
                            type="button"
                            data-hero-next
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/40 bg-black/40 text-white transition hover:bg-[var(--green)]"
                            aria-label="Slide berikutnya"
                        >
                            →
                        </button>

                    </div>

                </div>


                {{-- HERO CTA --}}
                <div class="fade-up-delay mt-5 flex flex-col gap-3 sm:flex-row">

                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex min-h-11 flex-1 items-center justify-center rounded-lg bg-[var(--green)] px-7 text-sm font-bold text-white transition hover:bg-[var(--green-dark)] sm:flex-none"
                    >
                        Mulai belanja
                        <span class="ml-3">→</span>
                    </a>

                    <a
                        href="#produk-unggulan"
                        class="inline-flex min-h-11 flex-1 items-center justify-center rounded-lg border border-[var(--green)] bg-white px-7 text-sm font-bold text-[var(--green)] transition hover:bg-[var(--green-soft)] sm:flex-none"
                    >
                        Lihat produk pilihan
                    </a>

                </div>

            </div>

        </section>


        {{-- =========================================================
            BENEFITS
        ========================================================== --}}
        <section class="border-y border-[var(--line)] bg-white">

            <div class="mx-auto grid max-w-7xl divide-y divide-[var(--line)] px-5 sm:grid-cols-3 sm:divide-x sm:divide-y-0 lg:px-8">

                <div class="flex items-center gap-3 py-5 sm:px-6 sm:first:pl-0">

                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[var(--green-soft)] text-[var(--green)]">

                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M3 6h18M6 6l1.5 12a2 2 0 0 0 2 1.8h5a2 2 0 0 0 2-1.8L18 6M9 10v6M15 10v6"/>
                        </svg>

                    </span>

                    <div>
                        <p class="text-sm font-bold">
                            Harga transparan
                        </p>

                        <p class="mt-0.5 text-xs leading-5 text-[var(--muted)]">
                            Harga dan satuan produk terlihat jelas.
                        </p>
                    </div>

                </div>


                <div class="flex items-center gap-3 py-5 sm:px-6">

                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#edf2f7] text-[var(--green)]">

                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M2 9h20M6 15h2m4 0h6M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                        </svg>

                    </span>

                    <div>
                        <p class="text-sm font-bold">
                            Checkout praktis
                        </p>

                        <p class="mt-0.5 text-xs leading-5 text-[var(--muted)]">
                            Tidak wajib membuat akun untuk berbelanja.
                        </p>
                    </div>

                </div>


                <div class="flex items-center gap-3 py-5 sm:px-6 sm:last:pr-0">

                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[var(--orange-soft)] text-[#b87520]">

                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M12 2 3 7v6c0 5 4 8.5 9 9 5-.5 9-4 9-9V7l-9-5Z"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>

                    </span>

                    <div>
                        <p class="text-sm font-bold">
                            Pilihan pembayaran
                        </p>

                        <p class="mt-0.5 text-xs leading-5 text-[var(--muted)]">
                            COD, transfer bank, dan QRIS.
                        </p>
                    </div>

                </div>

            </div>

        </section>


{{-- =========================================================
    CATEGORY
========================================================== --}}
<section
    id="kategori"
    class="relative mx-auto max-w-7xl overflow-hidden px-5 py-14 lg:px-8"
>

    {{-- Decorative background --}}
    <div class="pointer-events-none absolute -left-20 top-10 h-44 w-44 rounded-full bg-[#edf5ef] opacity-60 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-20 bottom-0 h-52 w-52 rounded-full bg-[#f5ead7] opacity-50 blur-3xl"></div>


    {{-- HEADER --}}
    <div class="relative flex items-end justify-between gap-5">

        <div>

            <div class="mb-2 flex items-center gap-2">

                <span class="h-1.5 w-7 rounded-full bg-[var(--green)]"></span>

                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[var(--green)]">
                    Jelajahi cepat
                </p>

            </div>

            <h2 class="text-2xl font-black tracking-[-0.025em] text-[var(--ink)] sm:text-3xl">
                Belanja berdasarkan kategori
            </h2>

            <p class="mt-2 max-w-xl text-xs leading-5 text-[var(--muted)] sm:text-sm">
                Temukan kebutuhan harian berdasarkan kategori produk yang tersedia di Ardian's.
            </p>

        </div>


        <a
            href="{{ route('products.index') }}"
            class="hidden shrink-0 items-center gap-2 rounded-full border border-[var(--line)] bg-white px-4 py-2.5 text-xs font-bold text-[var(--green)] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[var(--green)] hover:shadow-md sm:flex"
        >
            Lihat semua
            <span class="text-sm transition-transform duration-200 group-hover:translate-x-0.5">
                →
            </span>
        </a>

    </div>


    @if ($categories->isNotEmpty())

        @php
            /*
            |--------------------------------------------------------------------------
            | CATEGORY DATA
            |--------------------------------------------------------------------------
            */

            $categoryCount = $categories->count();

            /*
            | 6 kategori / halaman
            */
            $perPage = 6;

            $totalPages = (int) ceil($categoryCount / $perPage);
        @endphp


        {{-- CATEGORY GRID --}}
        <div
            id="categoryGrid"
            class="relative mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6"
        >

            @foreach ($categories as $index => $category)

                @php

                    $categoryKey = strtolower(trim($category->name));

                    /*
                    |--------------------------------------------------------------------------
                    | CATEGORY THEME
                    |--------------------------------------------------------------------------
                    */

                    $categoryTheme = match ($categoryKey) {

                        'prkk' => [
                            'bg' => 'bg-[#edf5ef]',
                            'text' => 'text-[#2f6b50]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <path d="M7 8h10l1 11H6L7 8Z"/>
                                    <path d="M9 8V6a3 3 0 0 1 6 0v2"/>
                                    <path d="M9 12h6"/>
                                </svg>
                            ',
                        ],

                        'sklh' => [
                            'bg' => 'bg-[#eef3ef]',
                            'text' => 'text-[#3d7058]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <path d="M8 20h8"/>
                                    <path d="M9 20V7h6v13"/>
                                    <path d="M10 7V5h4v2"/>
                                    <path d="M7 10h2M15 10h2M7 14h2M15 14h2"/>
                                </svg>
                            ',
                        ],

                        'alt' => [
                            'bg' => 'bg-[#edf2f0]',
                            'text' => 'text-[#356d55]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <path d="M6 7h12v13H6z"/>
                                    <path d="M9 7V5h6v2"/>
                                    <path d="M9 11h6M9 15h4"/>
                                </svg>
                            ',
                        ],

                        'atk' => [
                            'bg' => 'bg-[#f0f1ed]',
                            'text' => 'text-[#526b5d]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <path d="M6 5h9v14H6z"/>
                                    <path d="M9 8h3M9 11h3M9 14h2"/>
                                    <path d="M17 7v10"/>
                                    <path d="M17 7l2 2"/>
                                </svg>
                            ',
                        ],

                        'rkk' => [
                            'bg' => 'bg-[#edf5ef]',
                            'text' => 'text-[#367155]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <path d="M4 8l8-4 8 4-8 4-8-4Z"/>
                                    <path d="M4 8v8l8 4 8-4V8"/>
                                    <path d="M12 12v8"/>
                                </svg>
                            ',
                        ],

                        'kbb' => [
                            'bg' => 'bg-[#f2f0e9]',
                            'text' => 'text-[#8a6d35]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <path d="M7 8h10v11H7z"/>
                                    <path d="M9 8V6h6v2"/>
                                    <path d="M9 12h6M9 15h4"/>
                                </svg>
                            ',
                        ],

                        'mnm' => [
                            'bg' => 'bg-[#f2f0e8]',
                            'text' => 'text-[#856b36]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <path d="M5 11h14"/>
                                    <path d="M7 11c0 5 2.5 8 5 8s5-3 5-8"/>
                                    <path d="M9 7c0-1.5 1-2.5 2-2.5S13 5.5 13 7"/>
                                    <path d="M14 7c0-1.5 1-2.5 2-2.5S18 5.5 18 7"/>
                                </svg>
                            ',
                        ],

                        'ksmt' => [
                            'bg' => 'bg-[#eef4ef]',
                            'text' => 'text-[#397157]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <path d="M6 9h12l-1 10H7L6 9Z"/>
                                    <path d="M8 9V7a4 4 0 0 1 8 0v2"/>
                                    <path d="M9 13h6"/>
                                </svg>
                            ',
                        ],

                        'krt' => [
                            'bg' => 'bg-[#eef2ef]',
                            'text' => 'text-[#47705c]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <circle cx="9" cy="19" r="1.5"/>
                                    <circle cx="18" cy="19" r="1.5"/>
                                    <path d="M4 5h3l2 10h9l2-7H7"/>
                                </svg>
                            ',
                        ],

                        'mkn' => [
                            'bg' => 'bg-[#edf4ef]',
                            'text' => 'text-[#316c51]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <circle cx="12" cy="12" r="7"/>
                                    <path d="M8 9h8M8 15h8"/>
                                    <path d="M6 6l-2-2M18 6l2-2"/>
                                </svg>
                            ',
                        ],

                        'obat' => [
                            'bg' => 'bg-[#edf3ef]',
                            'text' => 'text-[#397058]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <rect x="7" y="5" width="10" height="15" rx="2"/>
                                    <path d="M9 5V4h6v1"/>
                                    <path d="M12 9v6M9 12h6"/>
                                </svg>
                            ',
                        ],

                        default => [
                            'bg' => 'bg-[#f1f3ef]',
                            'text' => 'text-[var(--green)]',
                            'icon' => '
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.7" class="h-5 w-5">
                                    <path d="M6 8h12v10a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8Z"/>
                                    <path d="M9 8V6a3 3 0 1 1 6 0v2"/>
                                    <path d="M9 12h6"/>
                                </svg>
                            ',
                        ],
                    };

                    /*
                    |--------------------------------------------------------------------------
                    | Pagination
                    |--------------------------------------------------------------------------
                    */

                    $pageNumber = floor($index / $perPage) + 1;

                @endphp


                {{-- CATEGORY CARD --}}
                <a
                    href="{{ route('products.index', ['category' => $category->slug]) }}"
                    data-category-page="{{ $pageNumber }}"
                    class="category-card group relative flex min-h-[154px] flex-col overflow-hidden rounded-2xl border border-[var(--line)] bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:border-[#b8d1c0] hover:shadow-[0_12px_30px_rgba(34,62,48,.08)]"
                >

                    {{-- subtle corner --}}
                    <div class="pointer-events-none absolute -right-5 -top-5 h-16 w-16 rounded-full bg-[#f5f8f4] transition duration-300 group-hover:scale-125"></div>


                    {{-- ICON --}}
                    <span
                        class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $categoryTheme['bg'] }} {{ $categoryTheme['text'] }} transition duration-300 group-hover:scale-105"
                    >
                        {!! $categoryTheme['icon'] !!}
                    </span>


                    {{-- CONTENT --}}
                    <div class="relative mt-auto pt-5">

                        <span class="block truncate text-sm font-black tracking-tight text-[var(--ink)] transition group-hover:text-[var(--green)]">
                            {{ $category->name }}
                        </span>

                        <span class="mt-1 block text-[11px] text-[var(--muted)]">
                            {{ number_format($category->products_count, 0, ',', '.') }} produk
                        </span>

                    </div>


                    {{-- ARROW --}}
                    <span
                        class="absolute bottom-4 right-4 flex h-7 w-7 items-center justify-center rounded-full border border-[var(--line)] bg-white text-xs font-bold text-[var(--green)] transition-all duration-300 group-hover:border-[var(--green)] group-hover:bg-[var(--green)] group-hover:text-white"
                    >
                        →
                    </span>

                </a>

            @endforeach

        </div>


        {{-- =====================================================
            CATEGORY PAGINATION
        ====================================================== --}}
        @if ($totalPages > 1)

            <div class="mt-8 flex flex-col items-center gap-3">

                {{-- pagination --}}
                <div
                    class="flex items-center gap-1 rounded-full border border-[var(--line)] bg-white p-1 shadow-[0_4px_20px_rgba(30,50,40,.05)]"
                >

                    {{-- PREVIOUS --}}
                    <button
                        type="button"
                        id="categoryPrev"
                        class="category-page-btn flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold text-[var(--muted)] transition hover:bg-[#edf5ef] hover:text-[var(--green)] disabled:cursor-not-allowed disabled:opacity-30"
                        aria-label="Kategori sebelumnya"
                    >
                        ←
                    </button>


                    {{-- PAGE NUMBERS --}}
                    <div
                        id="categoryPagination"
                        class="flex items-center gap-1"
                    ></div>


                    {{-- NEXT --}}
                    <button
                        type="button"
                        id="categoryNext"
                        class="category-page-btn flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold text-[var(--muted)] transition hover:bg-[#edf5ef] hover:text-[var(--green)] disabled:cursor-not-allowed disabled:opacity-30"
                        aria-label="Kategori berikutnya"
                    >
                        →
                    </button>

                </div>


                {{-- INFO --}}
                <p
                    id="categoryPaginationInfo"
                    class="text-[10px] font-medium text-[var(--muted)]"
                ></p>

            </div>

        @endif


    @else

        <a
            href="{{ route('products.index') }}"
            class="mt-8 block rounded-2xl border border-dashed border-[var(--line)] bg-white p-7 text-sm text-[var(--muted)] transition hover:border-[var(--green)]"
        >
            Lihat seluruh kebutuhan harian di katalog produk
            <span class="ml-1 font-bold text-[var(--green)]">→</span>
        </a>

    @endif

</section>


{{-- =========================================================
    CATEGORY PAGINATION SCRIPT
========================================================== --}}
@if ($categories->isNotEmpty() && $totalPages > 1)

<script>
document.addEventListener('DOMContentLoaded', function () {

    const cards = Array.from(
        document.querySelectorAll('.category-card')
    );

    const pagination = document.getElementById(
        'categoryPagination'
    );

    const prevButton = document.getElementById(
        'categoryPrev'
    );

    const nextButton = document.getElementById(
        'categoryNext'
    );

    const info = document.getElementById(
        'categoryPaginationInfo'
    );

    const perPage = {{ $perPage }};

    const totalItems = cards.length;

    const totalPages = Math.ceil(
        totalItems / perPage
    );

    let currentPage = 1;


    /*
    |--------------------------------------------------------------------------
    | RENDER CATEGORY
    |--------------------------------------------------------------------------
    */

    function renderCategories() {

        const start = (currentPage - 1) * perPage;

        const end = start + perPage;


        cards.forEach((card, index) => {

            const visible =
                index >= start &&
                index < end;

            card.classList.toggle(
                'hidden',
                !visible
            );

        });


        /*
        |--------------------------------------------------------------------------
        | BUTTON STATE
        |--------------------------------------------------------------------------
        */

        prevButton.disabled =
            currentPage === 1;

        nextButton.disabled =
            currentPage === totalPages;


        /*
        |--------------------------------------------------------------------------
        | PAGINATION NUMBER
        |--------------------------------------------------------------------------
        */

        pagination.innerHTML = '';


        for (
            let page = 1;
            page <= totalPages;
            page++
        ) {

            const button =
                document.createElement('button');

            button.type = 'button';

            button.textContent = page;

            button.className = `
                flex h-9 w-9
                items-center justify-center
                rounded-full
                text-xs font-bold
                transition-all duration-200
                ${
                    page === currentPage
                        ? 'bg-[var(--green)] text-white shadow-sm'
                        : 'text-[var(--muted)] hover:bg-[#edf5ef] hover:text-[var(--green)]'
                }
            `;

            button.setAttribute(
                'aria-label',
                `Halaman ${page}`
            );


            button.addEventListener(
                'click',
                function () {

                    currentPage = page;

                    renderCategories();

                    scrollToCategory();

                }
            );


            pagination.appendChild(button);

        }


        /*
        |--------------------------------------------------------------------------
        | INFO
        |--------------------------------------------------------------------------
        */

        const from =
            totalItems === 0
                ? 0
                : start + 1;

        const to =
            Math.min(
                end,
                totalItems
            );


        info.textContent =
            `Menampilkan ${from}–${to} dari ${totalItems} kategori`;

    }


    /*
    |--------------------------------------------------------------------------
    | PREVIOUS
    |--------------------------------------------------------------------------
    */

    prevButton.addEventListener(
        'click',
        function () {

            if (currentPage > 1) {

                currentPage--;

                renderCategories();

                scrollToCategory();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | NEXT
    |--------------------------------------------------------------------------
    */

    nextButton.addEventListener(
        'click',
        function () {

            if (currentPage < totalPages) {

                currentPage++;

                renderCategories();

                scrollToCategory();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | SCROLL
    |--------------------------------------------------------------------------
    */

    function scrollToCategory() {

        const section =
            document.getElementById('kategori');

        if (!section) return;


        const top =
            section.getBoundingClientRect().top +
            window.scrollY -
            80;


        window.scrollTo({
            top: top,
            behavior: 'smooth'
        });

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL
    |--------------------------------------------------------------------------
    */

    renderCategories();

});
</script>

@endif

        {{-- =========================================================
            SHOP BY NEED
        ========================================================== --}}
        <section class="bg-white py-12">

            <div class="mx-auto max-w-7xl px-5 lg:px-8">

                <div class="flex items-end justify-between gap-5">

                    <div>

                        <p class="text-xs font-black uppercase tracking-[.14em] text-[var(--green)]">
                            Temukan kebutuhan
                        </p>

                        <h2 class="mt-1.5 text-2xl font-black sm:text-3xl">
                            Belanja lebih cepat
                        </h2>

                        <p class="mt-2 max-w-xl text-sm leading-6 text-[var(--muted)]">
                            Mulai dari kebutuhan dapur sampai stok untuk usaha,
                            pilih berdasarkan apa yang sedang Anda butuhkan.
                        </p>

                    </div>


                    <a
                        href="{{ route('products.index') }}"
                        class="hidden text-sm font-bold text-[var(--green)] sm:block"
                    >
                        Semua produk →
                    </a>

                </div>


                <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                    <a
                        href="{{ route('products.index') }}"
                        class="shop-block group min-h-[210px] rounded-xl bg-[#edf5ef] p-6"
                    >

                        <p class="text-[10px] font-black uppercase tracking-[.14em] text-[var(--green)]">
                            Untuk rumah
                        </p>

                        <h3 class="mt-4 text-xl font-black">
                            Kebutuhan dapur
                        </h3>

                        <p class="mt-2 max-w-[200px] text-xs leading-5 text-[#536258]">
                            Beras, minyak, gula dan kebutuhan pokok
                            untuk persediaan rumah.
                        </p>

                        <span class="mt-6 block text-sm font-bold text-[var(--green)]">
                            Jelajahi →
                        </span>

                    </a>


                    <a
                        href="{{ route('products.index') }}"
                        class="shop-block group min-h-[210px] rounded-xl bg-[#f0f2f1] p-6"
                    >

                        <p class="text-[10px] font-black uppercase tracking-[.14em] text-[var(--green)]">
                            Untuk keluarga
                        </p>

                        <h3 class="mt-4 text-xl font-black">
                            Kebutuhan harian
                        </h3>

                        <p class="mt-2 max-w-[200px] text-xs leading-5 text-[#59605b]">
                            Produk yang membantu aktivitas rumah
                            setiap hari.
                        </p>

                        <span class="mt-6 block text-sm font-bold text-[var(--green)]">
                            Jelajahi →
                        </span>

                    </a>


                    <a
                        href="{{ route('products.index') }}"
                        class="shop-block group min-h-[210px] rounded-xl bg-[#fff1dc] p-6"
                    >

                        <p class="text-[10px] font-black uppercase tracking-[.14em] text-[#b87520]">
                            Untuk usaha
                        </p>

                        <h3 class="mt-4 text-xl font-black">
                            Stok dagangan
                        </h3>

                        <p class="mt-2 max-w-[200px] text-xs leading-5 text-[#685a48]">
                            Pilihan produk untuk warung dan
                            usaha kecil.
                        </p>

                        <span class="mt-6 block text-sm font-bold text-[#b87520]">
                            Jelajahi →
                        </span>

                    </a>


                    <a
                        href="{{ route('products.index') }}"
                        class="shop-block group min-h-[210px] rounded-xl bg-[var(--green)] p-6 text-white"
                    >

                        <p class="text-[10px] font-black uppercase tracking-[.14em] text-[#f2c77c]">
                            Selengkapnya
                        </p>

                        <h3 class="mt-4 text-xl font-black">
                            Lihat katalog
                        </h3>

                        <p class="mt-2 max-w-[200px] text-xs leading-5 text-white/80">
                            Jelajahi seluruh produk yang tersedia
                            di Ardians Store.
                        </p>

                        <span class="mt-6 block text-sm font-bold text-[#f2c77c]">
                            Buka katalog →
                        </span>

                    </a>

                </div>

            </div>

        </section>


        {{-- =========================================================
            FEATURED PRODUCTS
        ========================================================== --}}
        <section
            id="produk-unggulan"
            class="bg-[var(--bg)] py-12"
        >

            <div class="mx-auto max-w-7xl px-5 lg:px-8">

                <div class="flex items-end justify-between gap-4">

                    <div>

                        <p class="text-xs font-black uppercase tracking-[.14em] text-[var(--green)]">
                            Pilihan hari ini
                        </p>

                        <h2 class="mt-1.5 text-2xl font-black sm:text-3xl">
                            Produk yang siap dibeli
                        </h2>

                        <p class="mt-2 max-w-xl text-sm leading-6 text-[var(--muted)]">
                            Beberapa produk yang sedang tersedia
                            dan bisa langsung Anda lihat detailnya.
                        </p>

                    </div>


                    <a
                        href="{{ route('products.index') }}"
                        class="text-sm font-bold text-[var(--green)] transition hover:text-[var(--green-dark)]"
                    >
                        Lihat katalog →
                    </a>

                </div>


                @if ($featuredProducts->isNotEmpty())

                    <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                        @foreach ($featuredProducts as $product)

                            @php
                                $price = $product->prices
                                    ->sortBy('minimum_quantity')
                                    ->first();

                                $unit = $product->units
                                    ->sortBy('unit_level')
                                    ->first();
                            @endphp


                            <a
                                href="{{ route('products.show', $product) }}"
                                class="product-card group flex min-h-[360px] flex-col rounded-xl border border-[var(--line)] bg-white p-3.5 focus:outline-none focus:ring-2 focus:ring-[var(--green)]"
                            >

                                <div class="relative">

                                    <div class="absolute left-2.5 top-2.5 z-10 rounded-md bg-[var(--orange)] px-2.5 py-1 text-[9px] font-black uppercase tracking-[.08em] text-[var(--ink)]">
                                        Pilihan
                                    </div>


                                    @if ($product->thumbnail)

                                        <img
                                            src="{{ asset('storage/' . $product->thumbnail->image_path) }}"
                                            alt="{{ $product->name }}"
                                            class="h-40 w-full rounded-lg object-cover"
                                            loading="lazy"
                                        >

                                    @else

                                        <div class="flex h-40 items-center justify-center rounded-lg bg-[#edf5ef] text-4xl font-black text-[var(--green)]">
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        </div>

                                    @endif

                                </div>


                                <div class="mt-4 flex-1">

                                    <p class="text-[9px] font-bold uppercase tracking-[.12em] text-[var(--muted)]">
                                        {{ $product->category?->name ?? 'Kebutuhan harian' }}
                                    </p>

                                    <p class="mt-1.5 line-clamp-2 min-h-10 text-sm font-bold leading-5 text-[var(--ink)] group-hover:text-[var(--green)]">
                                        {{ $product->name }}
                                    </p>

                                    <p class="mt-1.5 text-xs text-[var(--muted)]">
                                        {{ $unit ? $unit->unit_name : 'Satuan umum' }}
                                    </p>

                                </div>


                                <div class="mt-4 flex items-end justify-between gap-3 border-t border-[var(--line)] pt-3.5">

                                    <div>

                                        <p class="text-[9px] font-bold uppercase tracking-[.12em] text-[var(--muted)]">
                                            Harga
                                        </p>

                                        <p class="mt-1 text-base font-black text-[var(--green)]">
                                            {{
                                                $price?->selling_price
                                                    ? 'Rp ' . number_format((float) $price->selling_price, 0, ',', '.')
                                                    : 'Cek harga'
                                            }}
                                        </p>

                                    </div>


                                    <span class="rounded-md bg-[var(--green-soft)] px-3 py-1.5 text-xs font-bold text-[var(--green)] group-hover:bg-[var(--green)] group-hover:text-white">
                                        Lihat →
                                    </span>

                                </div>

                            </a>

                        @endforeach

                    </div>

                @else

                    <div class="mt-7 rounded-xl border border-dashed border-[var(--line)] bg-white p-8 text-center text-sm text-[var(--muted)]">
                        Produk unggulan akan tampil di sini setelah inventori tersedia.
                    </div>

                @endif

            </div>

        </section>


        {{-- =========================================================
            WHY ARDIANS STORE
        ========================================================== --}}
        <section class="bg-white py-16">

            <div class="mx-auto max-w-7xl px-5 lg:px-8">

                <div class="grid gap-10 lg:grid-cols-[.78fr_1.22fr] lg:items-center">

                    <div>

                        <p class="text-xs font-black uppercase tracking-[.14em] text-[var(--green)]">
                            Tentang cara kami berbelanja
                        </p>

                        <h2 class="mt-2 text-3xl font-black leading-tight sm:text-4xl">
                            Bukan sekadar
                            <span class="text-[var(--green)]">
                                katalog.
                            </span>
                        </h2>

                        <p class="mt-5 max-w-md text-sm leading-7 text-[var(--muted)]">
                            Ardians Store dibuat supaya kebutuhan harian bisa
                            ditemukan tanpa harus melewati proses yang panjang.
                            Produk, harga, satuan, dan proses pemesanan dibuat
                            sesederhana mungkin.
                        </p>

                        <a
                            href="{{ route('products.index') }}"
                            class="mt-6 inline-flex items-center rounded-lg bg-[var(--green)] px-6 py-3 text-sm font-bold text-white transition hover:bg-[var(--green-dark)]"
                        >
                            Jelajahi produk
                            <span class="ml-3">→</span>
                        </a>

                    </div>


                    <div class="grid gap-3 sm:grid-cols-2">

                        <div class="rounded-xl border border-[var(--line)] bg-[#edf5ef] p-6">

                            <p class="text-3xl font-black text-[var(--green)]">
                                01
                            </p>

                            <h3 class="mt-4 text-base font-bold">
                                Harga lebih jelas
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#536258]">
                                Informasi harga dan satuan produk
                                ditampilkan sebelum Anda membeli.
                            </p>

                        </div>


                        <div class="rounded-xl border border-[var(--line)] bg-[#f0f2f1] p-6">

                            <p class="text-3xl font-black text-[var(--green)]">
                                02
                            </p>

                            <h3 class="mt-4 text-base font-bold">
                                Pilihan kebutuhan
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#59605b]">
                                Temukan berbagai kebutuhan rumah
                                dan usaha dalam satu katalog.
                            </p>

                        </div>


                        <div class="rounded-xl border border-[var(--line)] bg-[#fff1dc] p-6">

                            <p class="text-3xl font-black text-[#b87520]">
                                03
                            </p>

                            <h3 class="mt-4 text-base font-bold">
                                Checkout praktis
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-[#685a48]">
                                Pesan tanpa proses yang berbelit
                                dan tanpa wajib membuat akun.
                            </p>

                        </div>


                        <div class="rounded-xl bg-[var(--green)] p-6 text-white">

                            <p class="text-3xl font-black text-[#f2c77c]">
                                04
                            </p>

                            <h3 class="mt-4 text-base font-bold">
                                Rumah &amp; usaha
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-white/80">
                                Cocok untuk kebutuhan keluarga maupun
                                stok usaha sehari-hari.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </section>


{{-- =========================================================
    HOW TO SHOP
========================================================== --}}
<section class="mx-auto max-w-7xl px-5 py-14 lg:px-8">

    <div class="rounded-2xl border border-[var(--line)] bg-white p-7 sm:p-9">

        {{-- HEADER --}}
        <div class="max-w-xl">

            <div class="flex items-center gap-2">

                <span class="h-1.5 w-7 rounded-full bg-[var(--green)]"></span>

                <p class="text-[10px] font-black uppercase tracking-[.18em] text-[var(--green)]">
                    Cara belanja
                </p>

            </div>

            <h2 class="mt-2 text-2xl font-black tracking-tight text-[var(--ink)] sm:text-3xl">
                Sederhana dari awal sampai selesai.
            </h2>

            <p class="mt-3 text-sm leading-6 text-[var(--muted)]">
                Pilih kebutuhan, isi detail pesanan, lalu tentukan metode
                pembayaran yang paling nyaman untuk Anda.
            </p>

        </div>


        {{-- STEPS --}}
        <div class="mt-9 grid gap-3 md:grid-cols-3">

            {{-- STEP 01 --}}
            <div
                class="group rounded-xl border border-[var(--line)] bg-[var(--bg)] p-5 transition duration-200 hover:-translate-y-0.5 hover:border-[#b8d1c0] hover:bg-[#f7faf7]"
            >

                <div class="flex items-center justify-between">

                    <span class="text-xs font-black tracking-[.12em] text-[var(--green)]">
                        01
                    </span>

                    <span class="text-sm text-[var(--muted)] transition group-hover:translate-x-1">
                        →
                    </span>

                </div>

                <h3 class="mt-7 text-sm font-black text-[var(--ink)]">
                    Pilih kebutuhan
                </h3>

                <p class="mt-2 text-xs leading-5 text-[var(--muted)]">
                    Cari produk, lihat harga dan satuannya,
                    lalu tambahkan ke keranjang.
                </p>

            </div>


            {{-- STEP 02 --}}
            <div
                class="group rounded-xl border border-[var(--line)] bg-[var(--bg)] p-5 transition duration-200 hover:-translate-y-0.5 hover:border-[#b8d1c0] hover:bg-[#f7faf7]"
            >

                <div class="flex items-center justify-between">

                    <span class="text-xs font-black tracking-[.12em] text-[var(--green)]">
                        02
                    </span>

                    <span class="text-sm text-[var(--muted)] transition group-hover:translate-x-1">
                        →
                    </span>

                </div>

                <h3 class="mt-7 text-sm font-black text-[var(--ink)]">
                    Isi detail pesanan
                </h3>

                <p class="mt-2 text-xs leading-5 text-[var(--muted)]">
                    Masukkan nama, nomor telepon, alamat,
                    dan catatan pengiriman.
                </p>

            </div>


            {{-- STEP 03 --}}
            <div
                class="group rounded-xl border border-[var(--line)] bg-[var(--bg)] p-5 transition duration-200 hover:-translate-y-0.5 hover:border-[#d9c29e] hover:bg-[#fffaf1]"
            >

                <div class="flex items-center justify-between">

                    <span class="text-xs font-black tracking-[.12em] text-[#b87520]">
                        03
                    </span>

                    <span class="text-sm text-[var(--muted)] transition group-hover:translate-x-1">
                        →
                    </span>

                </div>

                <h3 class="mt-7 text-sm font-black text-[var(--ink)]">
                    Bayar sesuai pilihan
                </h3>

                <p class="mt-2 text-xs leading-5 text-[var(--muted)]">
                    Pilih COD, transfer bank, atau QRIS
                    untuk menyelesaikan pesanan.
                </p>

            </div>

        </div>

    </div>

</section>


{{-- =========================================================
    SIMPLE CTA / SERVICE INFO
========================================================== --}}
<section class="mx-auto max-w-7xl px-5 pb-14 lg:px-8">

    <div
        class="relative overflow-hidden rounded-2xl border border-[var(--line)] bg-[#f1e7d3] px-7 py-8 sm:px-9 sm:py-9"
    >

        {{-- subtle decoration --}}
        <div
            class="pointer-events-none absolute -right-12 -top-12 h-36 w-36 rounded-full bg-white/40 blur-2xl"
        ></div>


        <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">

            {{-- LEFT --}}
            <div class="max-w-xl">

                <p class="text-[10px] font-black uppercase tracking-[.18em] text-[var(--green)]">
                    Belanja lebih mudah
                </p>

                <h2 class="mt-2 text-2xl font-black tracking-tight text-[var(--ink)] sm:text-3xl">
                    Kebutuhan harian, tanpa perlu datang ke toko.
                </h2>

                <p class="mt-2 max-w-lg text-sm leading-6 text-[var(--muted)]">
                    Pilih kebutuhan Anda dari katalog, pesan dari rumah,
                    dan gunakan metode pembayaran yang paling nyaman.
                </p>

            </div>


            {{-- RIGHT --}}
            <div class="flex shrink-0 flex-col gap-3 sm:flex-row sm:items-center">

                <div class="flex flex-wrap gap-x-5 gap-y-2">

                    <span class="flex items-center gap-2 text-xs font-bold text-[var(--ink)]">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#dcebe1] text-[10px] text-[var(--green)]">
                            ✓
                        </span>
                        Harga transparan
                    </span>

                    <span class="flex items-center gap-2 text-xs font-bold text-[var(--ink)]">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#dcebe1] text-[10px] text-[var(--green)]">
                            ✓
                        </span>
                        Pembayaran fleksibel
                    </span>

                    <span class="flex items-center gap-2 text-xs font-bold text-[var(--ink)]">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-[#dcebe1] text-[10px] text-[var(--green)]">
                            ✓
                        </span>
                        Pesanan praktis
                    </span>

                </div>


                <a
                    href="{{ route('products.index') }}"
                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-[var(--green)] px-5 py-3 text-xs font-black text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-[var(--green-dark)] hover:shadow-md"
                >
                    Mulai belanja
                    <span class="ml-2 text-sm">
                        →
                    </span>
                </a>

            </div>

        </div>

    </div>

</section>

    </main>


    @include('partials.store-footer')


    {{-- =========================================================
        HERO SLIDER SCRIPT
    ========================================================== --}}
    <script>
        (function () {

            const slides = Array.from(
                document.querySelectorAll('[data-slide]')
            );

            const prevButton = document.querySelector('[data-hero-prev]');
            const nextButton = document.querySelector('[data-hero-next]');

            if (!slides.length || !prevButton || !nextButton) {
                return;
            }

            let activeIndex = 0;

            const showSlide = (index) => {

                activeIndex =
                    (index + slides.length) % slides.length;

                slides.forEach((slide, slideIndex) => {

                    slide.classList.toggle(
                        'opacity-100',
                        slideIndex === activeIndex
                    );

                    slide.classList.toggle(
                        'opacity-0',
                        slideIndex !== activeIndex
                    );

                });

            };

            prevButton.addEventListener(
                'click',
                () => showSlide(activeIndex - 1)
            );

            nextButton.addEventListener(
                'click',
                () => showSlide(activeIndex + 1)
            );

            window.setInterval(
                () => showSlide(activeIndex + 1),
                5000
            );

        })();
    </script>

</body>
</html>