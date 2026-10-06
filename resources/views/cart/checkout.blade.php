<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Checkout | Ardians Store</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-cream: #f0e7d5;
            --brand-indigo: #212842;
            --brand-gold: #b69259;
            --brand-ink: #25263a;
            --brand-paper: #fcfaf5;
            --brand-soft: #f7f2e8;
            --brand-line: #ddd5c6;
            --brand-muted: #6c6b72;
            --brand-indigo-soft: #303750;
            --brand-indigo-deep: #181d31;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background:
                radial-gradient(
                    circle at top left,
                    rgba(240, 231, 213, .55),
                    transparent 28%
                ),
                #f8f7f3;
            color: var(--brand-ink);
        }

        .font-display {
            font-family: 'Fraunces', serif;
        }

        .checkout-shell {
            background: rgba(252, 250, 245, .82);
            backdrop-filter: blur(10px);
        }

        .checkout-card {
            background: rgba(255, 255, 255, .88);
            border: 1px solid rgba(221, 213, 198, .9);
            box-shadow:
                0 20px 60px rgba(33, 40, 66, .055);
        }

        .input-premium {
            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background-color .2s ease;
        }

        .input-premium:focus {
            border-color: var(--brand-gold);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(182, 146, 89, .10);
        }

        .payment-select {
            appearance: none;
            background-image:
                linear-gradient(45deg, transparent 50%, #8c8b88 50%),
                linear-gradient(135deg, #8c8b88 50%, transparent 50%);
            background-position:
                calc(100% - 17px) 50%,
                calc(100% - 12px) 50%;
            background-size: 5px 5px, 5px 5px;
            background-repeat: no-repeat;
            padding-right: 42px;
        }

        .summary-shadow {
            box-shadow:
                0 25px 70px rgba(33, 40, 66, .08);
        }

        .gold-line {
            background: linear-gradient(
                90deg,
                var(--brand-gold),
                rgba(182, 146, 89, .15)
            );
        }

        .product-mini-image {
            background:
                linear-gradient(
                    145deg,
                    #f4f0e7,
                    #ebe5d8
                );
        }

        @media (max-width: 767px) {
            .checkout-card {
                box-shadow: 0 12px 35px rgba(33, 40, 66, .045);
            }
        }
    </style>

</head>


<body class="checkout-page min-h-screen antialiased">

    @include('partials.store-header')


    <main class="mx-auto max-w-[1380px] px-4 pb-20 pt-7 sm:px-6 lg:px-8 lg:pb-28 lg:pt-10">


        {{-- =========================================================
            PAGE INTRO
        ========================================================== --}}

        <section class="mb-8 lg:mb-10">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">

                <div>

                    <div class="mb-3 flex items-center gap-2">

                        <span class="h-px w-8 bg-[var(--brand-gold)]"></span>

                        <span class="text-[9px] font-bold uppercase tracking-[.28em] text-[var(--brand-gold)]">
                            ARDIAN'S CHECKOUT
                        </span>

                    </div>


                    <h1
                        class="font-display text-[34px] font-semibold leading-[1.05] tracking-[-.025em] text-[var(--brand-indigo)] sm:text-[44px] lg:text-[52px]"
                    >
                        Selesaikan pesanan
                    </h1>


                    <p class="mt-3 max-w-xl text-sm leading-6 text-[var(--brand-muted)]">
                        Lengkapi informasi pengiriman dan pembayaran,
                        lalu periksa kembali pesanan Anda sebelum dikirim.
                    </p>

                </div>


                {{-- Step Indicator --}}

                <div class="hidden items-center gap-3 sm:flex">

                    <div class="flex items-center gap-2">

                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--brand-indigo)] text-[11px] font-bold text-[var(--brand-cream)]"
                        >
                            01
                        </span>

                        <span class="text-[10px] font-bold uppercase tracking-[.12em] text-[var(--brand-indigo)]">
                            Detail
                        </span>

                    </div>


                    <span class="h-px w-8 bg-[var(--brand-line)]"></span>


                    <div class="flex items-center gap-2">

                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-[var(--brand-line)] bg-white text-[11px] font-bold text-[var(--brand-muted)]"
                        >
                            02
                        </span>

                        <span class="text-[10px] font-bold uppercase tracking-[.12em] text-[var(--brand-muted)]">
                            Review
                        </span>

                    </div>

                </div>

            </div>

        </section>



        {{-- =========================================================
            VALIDATION ERROR
        ========================================================== --}}

        @if ($errors->any())

            <div
                class="mb-7 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 text-sm text-rose-800"
            >

                <span
                    class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-rose-100 text-xs font-bold"
                >
                    !
                </span>

                <div>

                    <p class="font-bold">
                        Pesanan belum dapat diproses
                    </p>

                    <p class="mt-1 text-xs leading-5 text-rose-700">
                        {{ $errors->first() }}
                    </p>

                </div>

            </div>

        @endif



        {{-- =========================================================
            CHECKOUT FORM
        ========================================================== --}}

        <form
            method="POST"
            action="{{ route('checkout.store') }}"
            class="grid gap-7 lg:grid-cols-[minmax(0,1fr)_390px] lg:items-start lg:gap-9"
        >

            @csrf



            {{-- =====================================================
                LEFT SIDE
            ====================================================== --}}

            <div class="space-y-7">


                {{-- =================================================
                    CUSTOMER INFORMATION
                ================================================== --}}

                <section class="checkout-card overflow-hidden rounded-[24px]">

                    {{-- Header --}}

                    <div class="border-b border-[var(--brand-line)] px-5 py-5 sm:px-7 sm:py-6">

                        <div class="flex items-center gap-4">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--brand-indigo)] text-[11px] font-bold text-[var(--brand-cream)]"
                            >
                                01
                            </div>


                            <div>

                                <p class="text-[9px] font-bold uppercase tracking-[.22em] text-[var(--brand-gold)]">
                                    Informasi penerima
                                </p>

                                <h2 class="mt-1 font-display text-xl font-semibold text-[var(--brand-indigo)] sm:text-2xl">
                                    Detail pelanggan
                                </h2>

                            </div>

                        </div>

                    </div>



                    {{-- Form Body --}}

                    <div class="px-5 py-6 sm:px-7 sm:py-8">

                        <div class="grid gap-5 sm:grid-cols-2">


                            {{-- Name --}}

                            <div class="sm:col-span-2">

                                <label
                                    for="name"
                                    class="text-xs font-bold text-[var(--brand-ink)]"
                                >
                                    Nama lengkap
                                </label>

                                <input
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autocomplete="name"
                                    placeholder="Masukkan nama penerima"
                                    class="input-premium mt-2 h-12 w-full rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-4 text-sm text-[var(--brand-ink)] outline-none placeholder:text-[#aaa8a1]"
                                >

                            </div>



                            {{-- Phone --}}

                            <div>

                                <label
                                    for="phone"
                                    class="text-xs font-bold text-[var(--brand-ink)]"
                                >
                                    Nomor telepon
                                </label>

                                <input
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    autocomplete="tel"
                                    placeholder="08xxxxxxxxxx"
                                    class="input-premium mt-2 h-12 w-full rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-4 text-sm text-[var(--brand-ink)] outline-none placeholder:text-[#aaa8a1]"
                                >

                            </div>



                            {{-- Email --}}

                            <div>

                                <label
                                    for="email"
                                    class="text-xs font-bold text-[var(--brand-ink)]"
                                >

                                    Email

                                    <span class="font-normal text-[#aaa8a1]">
                                        · opsional
                                    </span>

                                </label>

                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    placeholder="nama@email.com"
                                    class="input-premium mt-2 h-12 w-full rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-4 text-sm text-[var(--brand-ink)] outline-none placeholder:text-[#aaa8a1]"
                                >

                            </div>



                            {{-- City --}}

                            <div class="sm:col-span-2">

                                <label
                                    for="city"
                                    class="text-xs font-bold text-[var(--brand-ink)]"
                                >
                                    Kota / area
                                </label>

                                <input
                                    id="city"
                                    name="city"
                                    value="{{ old('city') }}"
                                    autocomplete="address-level2"
                                    placeholder="Contoh: Pemalang"
                                    class="input-premium mt-2 h-12 w-full rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-4 text-sm text-[var(--brand-ink)] outline-none placeholder:text-[#aaa8a1]"
                                >

                            </div>



                            {{-- Address --}}

                            <div class="sm:col-span-2">

                                <label
                                    for="address"
                                    class="text-xs font-bold text-[var(--brand-ink)]"
                                >
                                    Alamat lengkap
                                </label>

                                <textarea
                                    id="address"
                                    name="address"
                                    required
                                    rows="4"
                                    autocomplete="street-address"
                                    placeholder="Nama jalan, nomor rumah, RT/RW, desa/kelurahan, kecamatan..."
                                    class="input-premium mt-2 w-full resize-none rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-4 py-3 text-sm leading-6 text-[var(--brand-ink)] outline-none placeholder:text-[#aaa8a1]"
                                >{{ old('address') }}</textarea>

                            </div>



                            {{-- Delivery Zone --}}

                            <div class="sm:col-span-2">

                                <label
                                    for="delivery_zone"
                                    class="text-xs font-bold text-[var(--brand-ink)]"
                                >
                                    Area pengiriman
                                </label>


                                <div class="relative">

                                    <select
                                        id="delivery_zone"
                                        name="delivery_zone"
                                        class="payment-select input-premium mt-2 h-12 w-full rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-4 text-sm text-[var(--brand-ink)] outline-none"
                                    >

                                        @foreach ($shippingZones as $key => $zone)

                                            <option
                                                value="{{ $key }}"
                                                data-cost="{{ $zone['cost'] }}"
                                                @selected(old('delivery_zone', 'near') === $key)
                                            >

                                                {{ $zone['label'] }}

                                                @if ($zone['cost'] > 0)
                                                    · Rp {{ number_format($zone['cost'], 0, ',', '.') }}
                                                @else
                                                    · Gratis*
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                <div class="mt-2 flex items-start gap-2">

                                    <svg
                                        class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[var(--brand-gold)]"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 10v6"/>
                                        <path d="M12 7h.01"/>
                                    </svg>

                                    <p class="text-[10px] leading-5 text-[var(--brand-muted)]">
                                        Gratis ongkir dalam 5 km untuk minimum belanja
                                        Rp {{ number_format($freeShippingMinimum, 0, ',', '.') }}.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                {{-- =================================================
                    PAYMENT
                ================================================== --}}

                <section class="checkout-card overflow-hidden rounded-[24px]">

                    <div class="border-b border-[var(--brand-line)] px-5 py-5 sm:px-7 sm:py-6">

                        <div class="flex items-center gap-4">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--brand-cream)] text-[11px] font-bold text-[var(--brand-indigo)]"
                            >
                                02
                            </div>


                            <div>

                                <p class="text-[9px] font-bold uppercase tracking-[.22em] text-[var(--brand-gold)]">
                                    Pembayaran
                                </p>

                                <h2 class="mt-1 font-display text-xl font-semibold text-[var(--brand-indigo)] sm:text-2xl">
                                    Pilih metode pembayaran
                                </h2>

                            </div>

                        </div>

                    </div>



                    <div class="px-5 py-6 sm:px-7 sm:py-8">

                        <label
                            for="payment_method"
                            class="text-xs font-bold text-[var(--brand-ink)]"
                        >
                            Metode pembayaran
                        </label>


                        <select
                            id="payment_method"
                            name="payment_method"
                            required
                            class="payment-select input-premium mt-2 h-12 w-full rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-4 text-sm text-[var(--brand-ink)] outline-none"
                        >

                            @if ($storefront['payment']['cod_enabled'] ?? false)

                                <option
                                    value="cod"
                                    @selected(old('payment_method') === 'cod')
                                >
                                    COD · Bayar saat diterima
                                </option>

                            @endif


                            @if ($storefront['payment']['transfer_enabled'] ?? false)

                                <option
                                    value="bank_transfer"
                                    @selected(old('payment_method') === 'bank_transfer')
                                >
                                    Transfer bank · Konfirmasi manual
                                </option>


                                <option
                                    value="qris"
                                    @selected(old('payment_method') === 'qris')
                                >
                                    QRIS · Scan untuk membayar
                                </option>

                            @endif

                        </select>



                        @if ($storefront['payment']['cod_enabled'] ?? false)

                            <div class="mt-3 flex items-start gap-2">

                                <span
                                    class="mt-0.5 flex h-4 w-4 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[8px] font-bold text-[var(--brand-gold)]"
                                >
                                    i
                                </span>

                                <p class="text-[10px] leading-5 text-[var(--brand-muted)]">

                                    COD membutuhkan minimum pembelian
                                    Rp {{ number_format((float) config('shipping.cod_minimum_order'), 0, ',', '.') }}.

                                </p>

                            </div>

                        @endif



                        {{-- Bank information --}}

                        @if (
                            ($storefront['payment']['transfer_enabled'] ?? false)
                            &&
                            ($storefront['payment']['bank_name'] || $storefront['payment']['account_number'])
                        )

                            <div
                                class="mt-5 overflow-hidden rounded-2xl border border-[var(--brand-line)] bg-[var(--brand-paper)]"
                            >

                                <div class="flex items-center justify-between border-b border-[var(--brand-line)] px-4 py-3">

                                    <div class="flex items-center gap-3">

                                        <span
                                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-[var(--brand-indigo)] text-[10px] font-bold text-[var(--brand-cream)]"
                                        >
                                            Rp
                                        </span>

                                        <div>

                                            <p class="text-[9px] font-bold uppercase tracking-[.16em] text-[var(--brand-gold)]">
                                                Rekening pembayaran
                                            </p>

                                            <p class="mt-0.5 text-xs font-bold text-[var(--brand-indigo)]">
                                                {{ $storefront['payment']['bank_name'] ?: 'Bank' }}
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                <div class="px-4 py-4">

                                    <p class="font-display text-lg font-semibold tracking-wide text-[var(--brand-indigo)]">
                                        {{ $storefront['payment']['account_number'] ?: '-' }}
                                    </p>

                                    <p class="mt-1 text-[10px] text-[var(--brand-muted)]">
                                        a.n. {{ $storefront['payment']['account_holder'] ?: '-' }}
                                    </p>

                                </div>

                            </div>

                        @endif

                    </div>

                </section>



                {{-- =================================================
                    CUSTOMER NOTE
                ================================================== --}}

                <section class="checkout-card rounded-[24px] p-5 sm:p-7">

                    <div class="flex items-start gap-4">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand-indigo)]"
                        >

                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <path d="M20 15.5A3.5 3.5 0 0 1 16.5 19H10l-5 3v-6.5A3.5 3.5 0 0 1 2 12V7.5A3.5 3.5 0 0 1 5.5 4h11A3.5 3.5 0 0 1 20 7.5v8Z"/>
                            </svg>

                        </div>


                        <div class="min-w-0 flex-1">

                            <label
                                for="customer_note"
                                class="text-sm font-bold text-[var(--brand-indigo)]"
                            >
                                Catatan pesanan

                                <span class="font-normal text-[var(--brand-muted)]">
                                    · opsional
                                </span>

                            </label>


                            <p class="mt-1 text-[10px] leading-5 text-[var(--brand-muted)]">
                                Tambahkan informasi khusus untuk pesanan Anda.
                            </p>


                            <textarea
                                id="customer_note"
                                name="customer_note"
                                rows="3"
                                placeholder="Contoh: titip di depan rumah..."
                                class="input-premium mt-4 w-full resize-none rounded-xl border border-[var(--brand-line)] bg-[var(--brand-paper)] px-4 py-3 text-sm leading-6 outline-none placeholder:text-[#aaa8a1]"
                            >{{ old('customer_note') }}</textarea>

                        </div>

                    </div>

                </section>

            </div>



            {{-- =====================================================
                RIGHT SIDE — ORDER SUMMARY
            ====================================================== --}}

            <aside class="lg:sticky lg:top-24">


                <div class="summary-shadow overflow-hidden rounded-[26px] border border-[var(--brand-line)] bg-[var(--brand-paper)]">


                    {{-- Summary Header --}}

                    <div class="relative px-5 pb-5 pt-6 sm:px-6">

                        <div class="absolute left-0 right-0 top-0 h-1 gold-line"></div>


                        <div class="flex items-end justify-between gap-4">

                            <div>

                                <p class="text-[9px] font-bold uppercase tracking-[.22em] text-[var(--brand-gold)]">
                                    Your order
                                </p>

                                <h2 class="mt-1 font-display text-2xl font-semibold text-[var(--brand-indigo)]">
                                    Ringkasan pesanan
                                </h2>

                            </div>


                            <span
                                class="rounded-full border border-[var(--brand-line)] bg-white px-3 py-1.5 text-[9px] font-bold uppercase tracking-[.12em] text-[var(--brand-muted)]"
                            >
                                {{ $items->count() }} item
                            </span>

                        </div>

                    </div>



                    {{-- Products --}}

                    <div class="max-h-[330px] overflow-y-auto border-y border-[var(--brand-line)] px-5 py-4 sm:px-6">

                        <div class="space-y-4">

                            @foreach ($items as $item)

                                <div class="flex gap-3">


                                    {{-- Product image --}}

                                    @if ($item['unit']->product->thumbnail)

                                        <div class="product-mini-image h-14 w-14 shrink-0 overflow-hidden rounded-xl border border-[var(--brand-line)]">

                                            <img
                                                src="{{ asset('storage/' . $item['unit']->product->thumbnail->image_path) }}"
                                                alt="{{ $item['unit']->product->name }}"
                                                class="h-full w-full object-contain mix-blend-multiply"
                                            >

                                        </div>

                                    @else

                                        <div
                                            class="product-mini-image flex h-14 w-14 shrink-0 items-center justify-center rounded-xl border border-[var(--brand-line)] font-display text-lg font-semibold text-[var(--brand-indigo)]"
                                        >

                                            {{ strtoupper(substr($item['unit']->product->name, 0, 1)) }}

                                        </div>

                                    @endif



                                    {{-- Product info --}}

                                    <div class="min-w-0 flex-1">

                                        <div class="flex items-start justify-between gap-3">

                                            <div class="min-w-0">

                                                <p class="line-clamp-2 text-xs font-bold leading-5 text-[var(--brand-indigo)]">
                                                    {{ $item['unit']->product->name }}
                                                </p>


                                                <p class="mt-0.5 text-[9px] text-[var(--brand-muted)]">

                                                    {{ $item['unit']->unit_name }}

                                                    ·

                                                    {{ (int) $item['quantity'] }}x

                                                </p>

                                            </div>


                                            <p class="shrink-0 text-xs font-bold text-[var(--brand-indigo)]">

                                                Rp {{ number_format($item['subtotal'], 0, ',', '.') }}

                                            </p>

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>



                    {{-- Totals --}}

                    <div class="px-5 py-5 sm:px-6">

                        <div class="space-y-3 text-xs">


                            {{-- Subtotal --}}

                            <div class="flex items-center justify-between">

                                <span class="text-[var(--brand-muted)]">
                                    Subtotal
                                </span>

                                <strong class="font-semibold text-[var(--brand-indigo)]">

                                    Rp {{ number_format($subtotal, 0, ',', '.') }}

                                </strong>

                            </div>



                            {{-- Shipping --}}

                            <div class="flex items-center justify-between">

                                <span class="text-[var(--brand-muted)]">
                                    Pengiriman
                                </span>

                                <strong
                                    id="shipping-cost"
                                    class="font-semibold text-[var(--brand-indigo)]"
                                >
                                    -
                                </strong>

                            </div>

                        </div>



                        {{-- Total --}}

                        <div class="mt-5 border-t border-[var(--brand-line)] pt-5">

                            <div class="flex items-end justify-between gap-4">

                                <div>

                                    <p class="text-[9px] font-bold uppercase tracking-[.18em] text-[var(--brand-muted)]">
                                        Total pembayaran
                                    </p>

                                    <p class="mt-1 text-[10px] text-[var(--brand-muted)]">
                                        Sudah termasuk perhitungan ongkir
                                    </p>

                                </div>


                                <strong
                                    id="order-total"
                                    class="font-display text-[23px] font-semibold tracking-tight text-[var(--brand-indigo)]"
                                >

                                    Rp {{ number_format($subtotal, 0, ',', '.') }}

                                </strong>

                            </div>

                        </div>



                        {{-- Checkout button --}}

                        <button
                            type="submit"
                            class="mt-6 flex h-13 w-full items-center justify-center gap-3 rounded-xl bg-[var(--brand-indigo)] px-5 py-4 text-sm font-bold text-[var(--brand-cream)] shadow-lg shadow-[#212842]/10 transition hover:-translate-y-0.5 hover:bg-[var(--brand-indigo-deep)]"
                        >

                            <span>
                                Buat pesanan
                            </span>

                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path d="M5 12h14"/>
                                <path d="m13 6 6 6-6 6"/>
                            </svg>

                        </button>



                        {{-- Security note --}}

                        <div class="mt-4 flex items-center justify-center gap-2">

                            <svg
                                class="h-3.5 w-3.5 text-[var(--brand-gold)]"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <rect
                                    x="5"
                                    y="10"
                                    width="14"
                                    height="10"
                                    rx="2"
                                />

                                <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                            </svg>

                            <p class="text-[9px] text-[var(--brand-muted)]">
                                Data pesanan diproses secara aman.
                            </p>

                        </div>

                    </div>

                </div>



                {{-- Back to cart --}}

                <a
                    href="{{ route('cart.index') }}"
                    class="mt-4 flex items-center justify-center gap-2 text-[10px] font-bold uppercase tracking-[.14em] text-[var(--brand-muted)] transition hover:text-[var(--brand-indigo)]"
                >

                    <span>←</span>

                    Kembali ke keranjang

                </a>

            </aside>

        </form>

    </main>


    @include('partials.store-footer')



    {{-- =========================================================
        SHIPPING CALCULATION
        LOGIC TETAP
    ========================================================== --}}

    <script>

        const zoneSelect =
            document.getElementById('delivery_zone');

        const shippingCost =
            document.getElementById('shipping-cost');

        const orderTotal =
            document.getElementById('order-total');


        const subtotal =
            {{ (float) $subtotal }};


        const freeMinimum =
            {{ (float) $freeShippingMinimum }};



        const money = (value) => {

            return `Rp ${Number(value).toLocaleString('id-ID')}`;

        };



        const updateShipping = () => {

            const option =
                zoneSelect?.options[
                    zoneSelect.selectedIndex
                ];


            if (!option) {
                return;
            }


            const regularCost =
                Number(
                    option.dataset.cost || 0
                );


            const cost =
                option.value === 'near'
                &&
                subtotal >= freeMinimum

                    ? 0

                    : regularCost;



            shippingCost.textContent =
                cost

                    ? money(cost)

                    : 'Gratis';



            orderTotal.textContent =
                money(
                    subtotal + cost
                );

        };



        zoneSelect?.addEventListener(
            'change',
            updateShipping
        );


        updateShipping();

    </script>


</body>

</html>