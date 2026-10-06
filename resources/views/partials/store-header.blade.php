@php
    $storeName = $storefront['name'] ?? "ARDIAN'S";
    $storeTagline = $storefront['tagline'] ?? 'Sembako & Kebutuhan Harian';
    $storeLogo = $storefront['logo_url'] ?? asset('favicon.ico');
    $storeLogoPath = parse_url($storeLogo, PHP_URL_PATH);

    if (is_string($storeLogoPath) && str_starts_with($storeLogoPath, '/storage/')) {
        $storeLogo = asset(ltrim($storeLogoPath, '/'));
    }
@endphp

{{-- Promo Bar --}}
<div class="store-promo" aria-label="Informasi layanan ARDIAN'S">
    <div class="store-promo__track">
        <span>Belanja kebutuhan harian tanpa ribet</span>
        <span class="store-promo__accent" aria-hidden="true">•</span>

        <span>Bayar COD, Transfer, atau QRIS</span>
        <span class="store-promo__accent" aria-hidden="true">•</span>

        <span>Checkout tanpa wajib login</span>
        <span class="store-promo__accent" aria-hidden="true">•</span>

        <span>Belanja kebutuhan harian tanpa ribet</span>
        <span class="store-promo__accent" aria-hidden="true">•</span>

        <span>Bayar COD, Transfer, atau QRIS</span>
        <span class="store-promo__accent" aria-hidden="true">•</span>

        <span>Checkout tanpa wajib login</span>
        <span class="store-promo__accent" aria-hidden="true">•</span>
    </div>
</div>

{{-- Main Header --}}
<header class="store-header">
    <div class="store-header__inner">

        {{-- Store Logo --}}
        <a
            href="{{ route('home') }}"
            class="store-header__brand"
            aria-label="Ardians Store beranda"
        >
            <img
                src="{{ $storeLogo }}"
                alt="Logo {{ $storeName }}"
                class="store-header__logo"
                onerror="this.onerror=null;this.src='{{ asset('favicon.ico') }}';"
            >

            <span class="store-header__brand-copy">
                <span
                    class="store-header__brand-name"
                >
                    {{ $storeName }}
                </span>

                <span
                    class="store-header__tagline"
                >
                    {{ $storeTagline }}
                </span>
            </span>
        </a>

        {{-- Desktop Search --}}
        <form
            method="GET"
            action="{{ route('products.index') }}"
            class="store-header__search"
        >
            <label for="store-search" class="sr-only">
                Cari produk
            </label>

            <div class="store-header__search-group">
                <div class="store-header__search-field">
                    <span
                        class="store-header__search-icon"
                    >
                        <svg
                            width="17"
                            height="17"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            aria-hidden="true"
                        >
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-4-4"></path>
                        </svg>
                    </span>

                    <input
                        id="store-search"
                        name="q"
                        type="search"
                        placeholder="Cari beras, minyak, sabun..."
                        class="store-header__search-input"
                    >
                </div>

                <button
                    type="submit"
                    class="store-header__search-button"
                >
                    Cari
                </button>
            </div>
        </form>

        {{-- Navigation --}}
        <nav
            id="store-navigation"
            class="store-header__nav"
            aria-label="Navigasi utama"
        >
            <a
                href="{{ route('products.index') }}"
                class="store-header__nav-link"
            >
                Belanja
            </a>

            <a
                href="{{ route('customer.orders.index') }}"
                class="store-header__nav-link"
            >
                Pesanan
            </a>

            <a
                href="{{ route('cart.index') }}"
                class="store-header__cart"
                aria-label="Buka keranjang"
            >
                <svg
                    width="17"
                    height="17"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    aria-hidden="true"
                >
                    <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.6L21 8H6"></path>
                    <circle cx="10" cy="20" r="1"></circle>
                    <circle cx="18" cy="20" r="1"></circle>
                </svg>

                <span class="hidden sm:inline">
                    Keranjang
                </span>
            </a>
        </nav>

        <button
            type="button"
            class="store-header__menu-toggle"
            data-store-menu-toggle
            aria-controls="store-navigation"
            aria-expanded="false"
            aria-label="Buka menu navigasi"
        >
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </button>
    </div>

    {{-- Mobile Search --}}
    <form
        method="GET"
        action="{{ route('products.index') }}"
        class="store-header__mobile-search"
    >
        <label for="mobile-store-search" class="sr-only">
            Cari produk
        </label>

        <input
            id="mobile-store-search"
            name="q"
            type="search"
            placeholder="Cari kebutuhan harian..."
            class="store-header__mobile-search-input"
        >

        <button
            type="submit"
            class="store-header__mobile-search-button"
        >
            Cari
        </button>
    </form>
</header>

{{-- Store Settings Configuration --}}
<div
    id="store-settings-config"
    hidden
    data-cod-enabled="{{ ($storefront['payment']['cod_enabled'] ?? false) ? '1' : '0' }}"
    data-transfer-enabled="{{ ($storefront['payment']['transfer_enabled'] ?? false) ? '1' : '0' }}"
    data-bank-name="{{ $storefront['payment']['bank_name'] ?? '' }}"
    data-account-number="{{ $storefront['payment']['account_number'] ?? '' }}"
    data-account-holder="{{ $storefront['payment']['account_holder'] ?? '' }}"
></div>
