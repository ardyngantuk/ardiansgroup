<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Pesanan Saya | Ardians Store</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,600;700&display=swap"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="orders-page min-h-screen antialiased">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    @include('partials.store-header')


    {{-- =========================================================
        MAIN
    ========================================================== --}}
    <main class="mx-auto max-w-[1180px] px-5 py-10 sm:px-7 lg:px-10 lg:py-16">

        {{-- =====================================================
            PAGE INTRO
        ====================================================== --}}
        <section class="border-b border-[var(--brand-line)] pb-9">

            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">

                <div class="max-w-2xl">

                    <p class="mb-3 text-[11px] font-bold uppercase tracking-[0.22em] text-[var(--brand-gold)]">
                        Order Tracking
                    </p>

                    <h1
                        class="font-display text-[38px] font-semibold leading-[1.08] tracking-[-0.025em] text-[var(--brand-indigo)] sm:text-[46px]"
                    >
                        Pesanan Saya
                    </h1>

                    <p class="mt-4 max-w-xl text-[14px] leading-6 text-[var(--brand-muted)]">
                        Lacak pesanan ARDIAN'S dengan nomor order dan token tracking
                        yang diberikan setelah checkout.
                    </p>

                </div>

                <div class="hidden shrink-0 sm:block">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-full border border-[var(--brand-line)] text-[var(--brand-gold)]"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4l2.5 2.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                            />
                        </svg>
                    </div>
                </div>

            </div>

        </section>


        {{-- =====================================================
            TRACKING FORM
        ====================================================== --}}
        @php
            $whatsappNumber = preg_replace('/\D+/', '', (string) ($storefront['whatsapp'] ?? ''));
        @endphp
        <section class="py-9">

            <div class="max-w-[900px]">

                <div class="mb-5">
                    <h2 class="text-[15px] font-bold text-[var(--brand-indigo)]">
                        Lacak pesanan
                    </h2>

                    <p class="mt-1 text-[13px] text-[var(--brand-muted)]">
                        Masukkan informasi dari halaman konfirmasi checkout.
                    </p>
                </div>


                <form
                    method="GET"
                    class="grid gap-3 lg:grid-cols-[1fr_1.15fr_auto]"
                >

                    {{-- Order Number --}}
                    <div class="relative">

                        <label
                            for="order_number"
                            class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[var(--brand-muted)]"
                        >
                            Nomor Order
                        </label>

                        <input
                            id="order_number"
                            name="order_number"
                            value="{{ $orderNumber }}"
                            required
                            autocomplete="off"
                            placeholder="Contoh: ORD-XXXX"
                            class="orders-input h-[50px] w-full rounded-lg border border-[var(--brand-line)] bg-white px-4 text-[13px] text-[var(--brand-ink)] outline-none transition"
                        >

                    </div>


                    {{-- Token --}}
                    <div>

                        <label
                            for="token"
                            class="mb-2 block text-[11px] font-bold uppercase tracking-[0.12em] text-[var(--brand-muted)]"
                        >
                            Token Tracking
                        </label>

                        <input
                            id="token"
                            name="token"
                            type="password"
                            required
                            autocomplete="off"
                            placeholder="Masukkan token tracking"
                            class="orders-input h-[50px] w-full rounded-lg border border-[var(--brand-line)] bg-white px-4 text-[13px] text-[var(--brand-ink)] outline-none transition"
                        >

                    </div>


                    {{-- Submit --}}
                    <div class="lg:self-end">

                        <button
                            type="submit"
                            class="flex h-[50px] w-full items-center justify-center gap-2 rounded-lg bg-[var(--brand-indigo)] px-6 text-[13px] font-bold text-white transition hover:bg-[var(--brand-indigo-deep)] lg:w-auto"
                        >

                            <span>Lihat Pesanan</span>

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 12h14M13 6l6 6-6 6"
                                />
                            </svg>

                        </button>

                    </div>

                </form>

                @if (!empty($lookupError))
                    <p class="mt-4 border border-rose-200 bg-rose-50 px-4 py-3 text-[13px] text-rose-700">{{ $lookupError }}</p>
                @endif


                <p class="mt-3 flex items-center gap-1.5 text-[11px] text-[#8b8982]">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-3.5 w-3.5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 17v.01M12 7v6"
                        />
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        />
                    </svg>

                    Order yang pernah dibuka di perangkat ini akan tersimpan otomatis.

                </p>

                <a
                    id="forgot-token-link"
                    href="{{ $whatsappNumber ? 'https://wa.me/' . $whatsappNumber : '#' }}"
                    class="mt-4 inline-flex text-[12px] font-bold text-[var(--brand-indigo)] underline decoration-[var(--brand-gold)] underline-offset-4 transition hover:text-[var(--brand-gold)]"
                >
                    Lupa token tracking? Hubungi Admin ARDIAN'S →
                </a>

            </div>

        </section>


        {{-- =====================================================
            SAVED ORDERS
        ====================================================== --}}
        <section class="border-t border-[var(--brand-line)] pt-9">

            <div class="flex items-end justify-between gap-4">

                <div>

                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[var(--brand-gold)]">
                        Riwayat akses
                    </p>

                    <h2 class="mt-2 text-[20px] font-bold tracking-[-0.01em] text-[var(--brand-indigo)]">
                        Pesanan tersimpan
                    </h2>

                    <p class="mt-1 text-[13px] text-[var(--brand-muted)]">
                        Order yang sebelumnya dibuka pada perangkat ini.
                    </p>

                </div>

            </div>


            {{-- Saved orders container --}}
            <div
                id="saved-orders"
                class="mt-6 max-w-[900px] overflow-hidden border-y border-[var(--brand-line)]"
            >

                <div class="flex items-center gap-3 py-6 text-[13px] text-[var(--brand-muted)]">

                    <span
                        class="h-4 w-4 animate-spin rounded-full border-2 border-[var(--brand-line)] border-t-[var(--brand-gold)]"
                    ></span>

                    Memuat order tersimpan...

                </div>

            </div>

            <button id="clear-saved-orders" type="button" class="mt-4 hidden text-[11px] font-bold text-[var(--brand-muted)] underline underline-offset-4 hover:text-[var(--brand-indigo)]">
                Hapus riwayat di perangkat ini
            </button>

        </section>


        {{-- =====================================================
            SMALL SECURITY NOTE
        ====================================================== --}}
        <div class="mt-10 flex max-w-[900px] items-start gap-3 text-[11px] leading-5 text-[#8b8982]">

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="mt-0.5 h-4 w-4 shrink-0 text-[var(--brand-gold)]"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.6"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M7 10V8a5 5 0 0 1 10 0v2M6 10h12v10H6V10Z"
                />
            </svg>

            <p>
                Token tracking bersifat pribadi. Jangan membagikan token kepada orang lain
                karena token digunakan untuk mengakses detail pesanan.
            </p>

        </div>

    </main>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}
    @include('partials.store-footer')


    {{-- =========================================================
        SAVED ORDERS SCRIPT
    ========================================================== --}}
    <script>
        (() => {

            let savedOrders = [];

            try {
                const parsed = JSON.parse(
                    localStorage.getItem('ardians_saved_orders') || '[]'
                );

                if (Array.isArray(parsed)) {
                    const uniqueOrders = new Map();
                    parsed.forEach((order) => {
                        if (order && order.orderNumber && order.token && order.url) {
                            uniqueOrders.set(order.orderNumber, {
                                orderNumber: String(order.orderNumber),
                                token: String(order.token),
                                url: String(order.url),
                            });
                        }
                    });
                    savedOrders = Array.from(uniqueOrders.values()).slice(0, 10);
                    localStorage.setItem('ardians_saved_orders', JSON.stringify(savedOrders));
                }
            } catch (error) {
                localStorage.removeItem('ardians_saved_orders');
            }

            const container = document.getElementById('saved-orders');

            if (!container) return;

            document.getElementById('forgot-token-link')?.addEventListener('click', (event) => {
                if (!{{ $whatsappNumber ? 'true' : 'false' }}) {
                    event.preventDefault();
                    return;
                }

                const orderNumber = document.getElementById('order_number')?.value.trim();
                const message = orderNumber
                    ? `Halo Admin ARDIAN'S, saya ingin bantuan mengakses pesanan saya.\n\nNomor Order: ${orderNumber}\n\nSaya lupa token tracking. Mohon bantuan untuk pengecekan.`
                    : `Halo Admin ARDIAN'S, saya lupa token tracking pesanan saya. Mohon bantuan untuk pengecekan.`;
                event.currentTarget.href = `https://wa.me/{{ $whatsappNumber }}?text=${encodeURIComponent(message)}`;
            });


            /* =====================================================
               EMPTY STATE
            ====================================================== */

            if (!Array.isArray(savedOrders) || !savedOrders.length) {

                container.innerHTML = `
                    <div class="py-8">

                        <div class="flex items-start gap-4">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand-gold)]">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.6"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6 3h12v18H6z"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        d="M9 7h6M9 11h6M9 15h4"
                                    />
                                </svg>

                            </div>

                            <div>

                                <p class="text-[14px] font-semibold text-[var(--brand-indigo)]">
                                    Belum ada pesanan tersimpan di perangkat ini
                                </p>

                                <p class="mt-1 text-[12px] leading-5 text-[var(--brand-muted)]">
                                    Gunakan form lacak pesanan di atas dengan nomor order dan token tracking. History ini hanya shortcut browser.
                                </p>

                            </div>

                        </div>

                    </div>
                `;

                return;
            }


            /* =====================================================
               ESCAPE HTML
            ====================================================== */

            const escapeHtml = (value) => {

                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');

            };


            /* =====================================================
               RENDER SAVED ORDERS
            ====================================================== */

            container.innerHTML = savedOrders
                .map(order => {

                    const orderNumber = escapeHtml(
                        order.orderNumber || 'Order'
                    );

                    const url = String(order.url || '#');

                    const token = encodeURIComponent(
                        order.token || ''
                    );

                    return `
                        <a
                            href="${url}?token=${token}"
                            class="saved-order-row group flex items-center justify-between gap-5 py-5"
                        >

                            <div class="min-w-0">

                                <div class="flex items-center gap-3">

                                    <span
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--brand-soft)] text-[var(--brand-indigo)]"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6 3h12v18H6z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                d="M9 7h6M9 11h4"
                                            />
                                        </svg>

                                    </span>


                                    <div class="min-w-0">

                                        <p class="truncate text-[14px] font-bold text-[var(--brand-indigo)]">
                                            ${orderNumber}
                                        </p>

                                        <p class="mt-0.5 text-[11px] text-[var(--brand-muted)]">
                                            Tracking tersimpan di perangkat ini
                                        </p>

                                    </div>

                                </div>

                            </div>


                            <div class="flex shrink-0 items-center gap-2 text-[12px] font-bold text-[var(--brand-indigo)]">

                                <span class="hidden sm:inline">
                                    Lihat tracking
                                </span>

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="order-arrow h-4 w-4 text-[var(--brand-gold)]"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 12h14M13 6l6 6-6 6"
                                    />
                                </svg>

                            </div>

                        </a>
                    `;

                })
                .join('');

            document.getElementById('clear-saved-orders')?.classList.remove('hidden');

            document.getElementById('clear-saved-orders')?.addEventListener('click', () => {
                localStorage.removeItem('ardians_saved_orders');
                window.location.reload();
            });


        })();
    </script>

</body>

</html>