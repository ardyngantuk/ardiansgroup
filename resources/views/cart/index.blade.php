<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Keranjang | ARDIAN'S</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com"
        rel="preconnect"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-cream: #f0e7d5;
            --brand-indigo: #212842;
            --brand-indigo-deep: #181d31;
            --brand-gold: #b69259;
            --brand-ink: #25263a;
            --brand-paper: #fcfaf5;
            --brand-soft: #f7f2e8;
            --brand-line: #ddd5c6;
            --brand-muted: #6c6b72;
        }

        body.cart-page {
            background:
                radial-gradient(
                    circle at 85% 0%,
                    rgba(182, 146, 89, 0.07),
                    transparent 30%
                ),
                var(--brand-paper);
            color: var(--brand-ink);
            font-family: "DM Sans", sans-serif;
        }

        .font-display {
            font-family: "Fraunces", serif;
        }

        /* =========================================
           CART ITEM
        ========================================== */

        .cart-product {
            transition:
                background-color .25s ease,
                transform .25s ease,
                box-shadow .25s ease;
        }

        .cart-product:hover {
            background: rgba(247, 242, 232, .55);
        }

        .cart-image {
            background:
                linear-gradient(
                    145deg,
                    #ffffff 0%,
                    #f7f2e8 100%
                );
        }

        /* =========================================
           QUANTITY
        ========================================== */

        .cart-input {
            appearance: none;
            -moz-appearance: textfield;
        }

        .cart-input::-webkit-inner-spin-button,
        .cart-input::-webkit-outer-spin-button {
            opacity: 1;
        }

        .cart-input:focus {
            border-color: var(--brand-gold);
            box-shadow: 0 0 0 3px rgba(182, 146, 89, .10);
        }

        /* =========================================
           SUMMARY
        ========================================== */

        .summary-card {
            background:
                linear-gradient(
                    145deg,
                    #ffffff 0%,
                    #fcfaf5 100%
                );

            box-shadow:
                0 20px 60px rgba(33, 40, 66, .08),
                0 4px 14px rgba(33, 40, 66, .04);
        }

        .checkout-button {
            box-shadow:
                0 12px 24px rgba(33, 40, 66, .15);
        }

        .checkout-button:hover {
            box-shadow:
                0 16px 30px rgba(33, 40, 66, .20);
        }

        /* =========================================
           SMALL DETAILS
        ========================================== */

        .soft-divider {
            border-color: rgba(221, 213, 198, .75);
        }

        /* =========================================
           MOBILE
        ========================================== */

        @media (max-width: 639px) {

            .cart-product {
                padding-top: 18px;
                padding-bottom: 18px;
            }

        }
    </style>

</head>


<body class="cart-page min-h-screen antialiased">

    {{-- =========================================================
        NAVBAR
    ========================================================== --}}

    @include('partials.store-header')


    <main class="mx-auto max-w-[1280px] px-4 py-8 sm:px-6 sm:py-10 lg:px-8 lg:py-14">


        {{-- =========================================================
            FLASH MESSAGE
        ========================================================== --}}

        @if (session('success'))

            <div class="mb-8 rounded-2xl border border-emerald-200/70 bg-emerald-50/70 px-4 py-3">

                <div class="flex items-center gap-3">

                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-700 text-sm font-bold text-white"
                    >
                        ✓
                    </span>

                    <p class="text-xs font-semibold text-emerald-800 sm:text-sm">
                        {{ session('success') }}
                    </p>

                </div>

            </div>

        @endif


        @if (session('error'))

            <div class="mb-8 rounded-2xl border border-amber-200/70 bg-amber-50/70 px-4 py-3">

                <div class="flex items-center gap-3">

                    <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[var(--brand-gold)] text-sm font-bold text-white"
                    >
                        !
                    </span>

                    <p class="text-xs font-semibold text-[var(--brand-ink)] sm:text-sm">
                        {{ session('error') }}
                    </p>

                </div>

            </div>

        @endif



        {{-- =========================================================
            PAGE HEADER
        ========================================================== --}}

        <header class="mb-8 sm:mb-10">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <div class="mb-3 flex items-center gap-2">

                        <span class="h-px w-7 bg-[var(--brand-gold)]"></span>

                        <span class="text-[9px] font-bold uppercase tracking-[.28em] text-[var(--brand-gold)]">
                            PESANAN ANDA
                        </span>

                    </div>


                    <h1
                        class="font-display text-4xl font-semibold leading-none tracking-[-.03em] text-[var(--brand-indigo)] sm:text-5xl lg:text-[52px]"
                    >
                        Keranjang
                    </h1>


                    <p class="mt-3 max-w-xl text-xs leading-6 text-[var(--brand-muted)] sm:text-sm">

                        Periksa kembali produk dan jumlah pesanan Anda
                        sebelum melanjutkan ke checkout.

                    </p>

                </div>


                @if (!$items->isEmpty())

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--brand-indigo)] text-[var(--brand-cream)]"
                        >

                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 1.9-1.4L21 8H6"
                                />

                                <circle cx="10" cy="20" r="1"/>

                                <circle cx="18" cy="20" r="1"/>
                            </svg>

                        </div>


                        <div>

                            <p class="text-[9px] font-bold uppercase tracking-[.18em] text-[var(--brand-muted)]">
                                Isi keranjang
                            </p>

                            <p class="mt-0.5 text-sm font-bold text-[var(--brand-indigo)]">

                                {{ $items->count() }}
                                {{ $items->count() === 1 ? 'produk' : 'produk' }}

                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </header>



        {{-- =========================================================
            EMPTY CART
        ========================================================== --}}

        @if ($items->isEmpty())

            <section class="relative overflow-hidden rounded-[28px] border border-[var(--brand-line)] bg-white px-6 py-20 text-center shadow-[0_15px_50px_rgba(33,40,66,.05)] sm:py-28">

                <div
                    class="absolute left-1/2 top-0 h-40 w-40 -translate-x-1/2 -translate-y-1/2 rounded-full bg-[var(--brand-cream)] opacity-60 blur-3xl"
                ></div>


                <div class="relative">

                    <div
                        class="mx-auto flex h-20 w-20 items-center justify-center rounded-[24px] bg-[var(--brand-soft)] text-[var(--brand-indigo)]"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-8 w-8"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 1.9-1.4L21 8H6"
                            />

                            <circle
                                cx="10"
                                cy="20"
                                r="1"
                            />

                            <circle
                                cx="18"
                                cy="20"
                                r="1"
                            />
                        </svg>

                    </div>


                    <p class="mt-7 text-[9px] font-bold uppercase tracking-[.25em] text-[var(--brand-gold)]">
                        ARDIAN'S MARKET
                    </p>


                    <h2 class="mt-2 font-display text-3xl font-semibold text-[var(--brand-indigo)] sm:text-4xl">
                        Keranjang masih kosong
                    </h2>


                    <p class="mx-auto mt-3 max-w-md text-sm leading-6 text-[var(--brand-muted)]">
                        Pilih kebutuhan harian dari katalog ARDIAN'S
                        dan produk pilihan Anda akan muncul di sini.
                    </p>


                    <a
                        href="{{ route('products.index') }}"
                        class="mt-8 inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-[var(--brand-indigo)] px-7 text-xs font-bold text-[var(--brand-cream)] shadow-lg shadow-[rgba(33,40,66,.12)] transition hover:-translate-y-0.5 hover:bg-[var(--brand-indigo-deep)]"
                    >

                        Lihat katalog

                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M5 12h13"/>
                            <path d="m13 6 6 6-6 6"/>
                        </svg>

                    </a>

                </div>

            </section>

        @else


            {{-- =====================================================
                CART CONTENT
            ====================================================== --}}

            <div class="grid items-start gap-8 lg:grid-cols-[minmax(0,1fr)_360px] lg:gap-10">


                {{-- =================================================
                    LEFT — PRODUCTS
                ================================================== --}}

                <section>

                    <div class="mb-4 flex items-end justify-between">

                        <div>

                            <p class="text-[9px] font-bold uppercase tracking-[.2em] text-[var(--brand-gold)]">
                                Daftar belanja
                            </p>

                            <h2 class="mt-1 font-display text-xl font-semibold text-[var(--brand-indigo)] sm:text-2xl">
                                Produk yang dipilih
                            </h2>

                        </div>


                        <span class="rounded-full border border-[var(--brand-line)] bg-white px-3 py-1.5 text-[9px] font-bold uppercase tracking-[.12em] text-[var(--brand-muted)]">

                            {{ $items->count() }} item

                        </span>

                    </div>



                    {{-- =================================================
                        PRODUCT LIST
                    ================================================== --}}

                    <div class="overflow-hidden rounded-[24px] border border-[var(--brand-line)] bg-white shadow-[0_12px_40px_rgba(33,40,66,.04)]">

                        @foreach ($items as $item)

                            <article class="cart-product border-b soft-divider last:border-b-0">

                                <div class="p-4 sm:p-5 lg:p-6">

                                    <div class="grid gap-5 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center">


                                        {{-- =================================
                                            PRODUCT INFO
                                        ================================== --}}

                                        <div class="flex min-w-0 items-center gap-4 sm:gap-5">

                                            {{-- IMAGE --}}

                                            <div class="cart-image flex h-[82px] w-[82px] shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-[var(--brand-line)] sm:h-[94px] sm:w-[94px]">

                                                @if ($item['unit']->product->thumbnail)

                                                    <img
                                                        src="{{ asset('storage/' . $item['unit']->product->thumbnail->image_path) }}"
                                                        alt="{{ $item['unit']->product->name }}"
                                                        class="h-full w-full object-contain mix-blend-multiply"
                                                    >

                                                @else

                                                    <span
                                                        class="font-display text-3xl font-semibold text-[var(--brand-indigo)]"
                                                    >
                                                        {{ strtoupper(substr($item['unit']->product->name, 0, 1)) }}
                                                    </span>

                                                @endif

                                            </div>


                                            {{-- DETAILS --}}

                                            <div class="min-w-0 flex-1">

                                                <div class="flex items-center gap-2">

                                                    <span class="h-1.5 w-1.5 rounded-full bg-[var(--brand-gold)]"></span>

                                                    <p class="truncate text-[8px] font-bold uppercase tracking-[.16em] text-[var(--brand-muted)] sm:text-[9px]">

                                                        {{ $item['unit']->product->category?->name ?? 'Kebutuhan harian' }}

                                                    </p>

                                                </div>


                                                <h3 class="mt-1.5 line-clamp-2 font-display text-base font-semibold leading-snug text-[var(--brand-indigo)] sm:text-lg">

                                                    {{ $item['unit']->product->name }}

                                                </h3>


                                                <div class="mt-2 flex flex-wrap items-center gap-2">

                                                    <span class="rounded-lg bg-[var(--brand-soft)] px-2 py-1 text-[9px] font-semibold text-[var(--brand-muted)]">

                                                        {{ $item['unit']->unit_name }}

                                                    </span>


                                                    <span class="text-[10px] text-[var(--brand-muted)]">
                                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                        / unit
                                                    </span>

                                                </div>

                                            </div>

                                        </div>



                                        {{-- =================================
                                            RIGHT ACTION AREA
                                        ================================== --}}

                                        <div class="flex items-center justify-between gap-5 border-t border-[var(--brand-line)] pt-4 sm:border-0 sm:pt-0">


                                            {{-- QUANTITY --}}

                                            <div>

                                                <p class="mb-1.5 text-[8px] font-bold uppercase tracking-[.15em] text-[var(--brand-muted)]">
                                                    Jumlah
                                                </p>


                                                <form
                                                    method="POST"
                                                    action="{{ route('cart.update', $item['unit']) }}"
                                                    class="flex items-center gap-2"
                                                >

                                                    @csrf

                                                    @method('PATCH')


                                                    <label
                                                        for="quantity-{{ $item['unit']->id }}"
                                                        class="sr-only"
                                                    >
                                                        Jumlah {{ $item['unit']->product->name }}
                                                    </label>


                                                    <input
                                                        id="quantity-{{ $item['unit']->id }}"
                                                        name="quantity"
                                                        type="number"
                                                        min="1"
                                                        step="1"
                                                        inputmode="numeric"
                                                        pattern="[0-9]*"
                                                        value="{{ (int) $item['quantity'] }}"
                                                        class="cart-input h-10 w-[68px] rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-2 text-center text-xs font-bold text-[var(--brand-indigo)] outline-none transition"
                                                    >


                                                    <button
                                                        type="submit"
                                                        class="h-10 rounded-xl border border-[var(--brand-line)] bg-white px-3 text-[9px] font-bold text-[var(--brand-indigo)] transition hover:border-[var(--brand-gold)] hover:bg-[var(--brand-soft)]"
                                                    >
                                                        Ubah
                                                    </button>

                                                </form>

                                            </div>



                                            {{-- SUBTOTAL --}}

                                            <div class="text-right">

                                                <p class="mb-1.5 text-[8px] font-bold uppercase tracking-[.15em] text-[var(--brand-muted)]">
                                                    Subtotal
                                                </p>


                                                <p class="font-display text-lg font-semibold text-[var(--brand-indigo)] sm:text-xl">

                                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}

                                                </p>

                                            </div>

                                        </div>

                                    </div>



                                    {{-- REMOVE --}}

                                    <div class="mt-4 flex justify-end">

                                        <form
                                            method="POST"
                                            action="{{ route('cart.remove', $item['unit']) }}"
                                        >

                                            @csrf

                                            @method('DELETE')


                                            <button
                                                type="submit"
                                                class="group inline-flex items-center gap-1.5 text-[9px] font-semibold text-[var(--brand-muted)] transition hover:text-red-600"
                                            >

                                                <svg
                                                    class="h-3.5 w-3.5 transition group-hover:scale-110"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.6"
                                                >
                                                    <path d="M3 6h18"/>
                                                    <path d="M8 6V4h8v2"/>
                                                    <path d="M19 6l-1 14H6L5 6"/>
                                                </svg>

                                                Hapus dari keranjang

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </article>

                        @endforeach

                    </div>



                    {{-- =================================================
                        CONTINUE SHOPPING
                    ================================================== --}}

                    <div class="mt-5 flex flex-wrap items-center justify-between gap-4">

                        <a
                            href="{{ route('products.index') }}"
                            class="group inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-[.12em] text-[var(--brand-indigo)] transition"
                        >

                            <span class="transition group-hover:-translate-x-1">
                                ←
                            </span>

                            Lanjut belanja

                        </a>


                        <p class="text-[10px] text-[var(--brand-muted)]">
                            Harga mengikuti unit yang dipilih.
                        </p>

                    </div>

                </section>



                {{-- =================================================
                    RIGHT — SUMMARY
                ================================================== --}}

                <aside class="lg:sticky lg:top-24 lg:self-start">

                    <div class="summary-card overflow-hidden rounded-[26px] border border-[var(--brand-line)]">


                        {{-- TOP ACCENT --}}

                        <div class="h-1.5 bg-[var(--brand-indigo)]"></div>


                        <div class="p-6 sm:p-7">


                            {{-- HEADER --}}

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <p class="text-[9px] font-bold uppercase tracking-[.22em] text-[var(--brand-gold)]">
                                        Ringkasan
                                    </p>

                                    <h2 class="mt-1 font-display text-2xl font-semibold text-[var(--brand-indigo)]">
                                        Pesanan
                                    </h2>

                                </div>


                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--brand-soft)] text-[var(--brand-indigo)]">

                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <path d="M6 2h12"/>
                                        <path d="M6 2v5a6 6 0 0 0 12 0V2"/>
                                        <path d="M9 19h6"/>
                                        <path d="M12 13v6"/>
                                    </svg>

                                </div>

                            </div>



                            {{-- ORDER INFO --}}

                            <div class="mt-7 rounded-2xl bg-[var(--brand-soft)] p-4">

                                <div class="flex items-center justify-between">

                                    <span class="text-[10px] text-[var(--brand-muted)]">
                                        Produk
                                    </span>

                                    <span class="text-[10px] font-bold text-[var(--brand-indigo)]">
                                        {{ $items->count() }} item
                                    </span>

                                </div>


                                <div class="mt-3 flex items-center justify-between">

                                    <span class="text-[10px] text-[var(--brand-muted)]">
                                        Subtotal
                                    </span>

                                    <span class="text-xs font-bold text-[var(--brand-indigo)]">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </span>

                                </div>

                            </div>



                            {{-- PRICE --}}

                            <div class="mt-6 space-y-4">

                                <div class="flex items-center justify-between text-sm">

                                    <span class="text-[var(--brand-muted)]">
                                        Subtotal
                                    </span>

                                    <strong class="font-semibold text-[var(--brand-indigo)]">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </strong>

                                </div>


                                <div class="flex items-center justify-between text-sm">

                                    <span class="text-[var(--brand-muted)]">
                                        Pengiriman
                                    </span>

                                    <span class="text-[10px] font-medium text-[var(--brand-muted)]">
                                        Dihitung checkout
                                    </span>

                                </div>

                            </div>



                            {{-- TOTAL --}}

                            <div class="my-6 border-t border-[var(--brand-line)] pt-5">

                                <div class="flex items-end justify-between gap-4">

                                    <div>

                                        <p class="text-[9px] font-bold uppercase tracking-[.17em] text-[var(--brand-muted)]">
                                            Total sementara
                                        </p>

                                        <p class="mt-1 text-[10px] leading-5 text-[var(--brand-muted)]">
                                            Belum termasuk ongkir
                                        </p>

                                    </div>


                                    <strong class="font-display text-2xl font-semibold tracking-tight text-[var(--brand-indigo)] sm:text-[27px]">

                                        Rp {{ number_format($subtotal, 0, ',', '.') }}

                                    </strong>

                                </div>

                            </div>



                            {{-- CHECKOUT --}}

                            <a
                                href="{{ route('checkout') }}"
                                class="checkout-button group flex h-13 min-h-[52px] w-full items-center justify-center gap-3 rounded-2xl bg-[var(--brand-indigo)] px-5 text-xs font-bold text-[var(--brand-cream)] transition duration-300 hover:-translate-y-0.5 hover:bg-[var(--brand-indigo-deep)]"
                            >

                                <span>
                                    Lanjut ke checkout
                                </span>

                                <svg
                                    class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path d="M5 12h14"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>

                            </a>



                            {{-- TRUST INFO --}}

                            <div class="mt-5 flex items-start gap-3 border-t border-[var(--brand-line)] pt-5">

                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[var(--brand-soft)] text-[var(--brand-indigo)]">

                                    <svg
                                        class="h-4 w-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    >
                                        <path
                                            d="M12 3 5 6v5c0 4.5 2.9 8.5 7 10 4.1-1.5 7-5.5 7-10V6l-7-3Z"
                                        />

                                        <path d="m9 12 2 2 4-4"/>
                                    </svg>

                                </div>


                                <div>

                                    <p class="text-[10px] font-bold text-[var(--brand-indigo)]">
                                        Checkout aman
                                    </p>

                                    <p class="mt-0.5 text-[9px] leading-4 text-[var(--brand-muted)]">
                                        Pilihan pembayaran dan ongkir
                                        dapat diperiksa pada tahap checkout.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </aside>

            </div>

        @endif

    </main>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    @include('partials.store-footer')

</body>

</html>