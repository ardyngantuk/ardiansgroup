<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') | Ardians Group</title><script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-page min-h-screen antialiased">
    @php
        $adminNavigation = [
            ['label' => 'Dashboard', 'single' => true, 'items' => [['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')]]],
            ['label' => 'Penjualan', 'items' => [
                ['label' => 'Pesanan', 'url' => route('admin.orders.index'), 'active' => request()->routeIs('admin.orders.*')],
                ['label' => 'Pembayaran', 'url' => route('admin.payments.index'), 'active' => request()->routeIs('admin.payments.*')],
                ['label' => 'Pelanggan', 'url' => route('admin.module', 'pelanggan'), 'active' => request()->routeIs('admin.module') && request()->route('module') === 'pelanggan'],
                ['label' => 'Log Aktivitas', 'url' => route('admin.activities.index'), 'active' => request()->routeIs('admin.activities.*')],
            ]],
            ['label' => 'Produk', 'items' => [
                ['label' => 'Katalog', 'url' => route('admin.products.index'), 'active' => request()->routeIs('admin.products.*')],
                ['label' => 'Kategori', 'url' => route('admin.module', 'kategori'), 'active' => request()->routeIs('admin.master.*') && request()->route('module') === 'kategori'], ['label' => 'Brand', 'url' => route('admin.module', 'brand'), 'active' => request()->routeIs('admin.master.*') && request()->route('module') === 'brand'],
                ['label' => 'Satuan', 'url' => route('admin.module', 'satuan'), 'active' => request()->routeIs('admin.master.*') && request()->route('module') === 'satuan'], ['label' => 'Barcode', 'url' => route('admin.module', 'barcode')],
            ]],
            ['label' => 'Operasional', 'items' => [
                ['label' => 'Stok', 'url' => route('admin.stock.index'), 'active' => request()->routeIs('admin.stock.*')],
                ['label' => 'Pembelian', 'url' => route('admin.module', 'pembelian'), 'active' => request()->routeIs('admin.module') && request()->route('module') === 'pembelian'],
                ['label' => 'Supplier', 'url' => route('admin.suppliers.index'), 'active' => request()->routeIs('admin.suppliers.*')],
            ]],
            ['label' => 'Keuangan', 'items' => [
                ['label' => 'Keuangan', 'url' => route('admin.module', 'keuangan'), 'active' => request()->routeIs('admin.module') && request()->route('module') === 'keuangan'],
                ['label' => 'Pengeluaran', 'url' => route('admin.expenses.index'), 'active' => request()->routeIs('admin.expenses.*')],
            ]],
            ['label' => 'Laporan', 'single' => true, 'items' => [['label' => 'Laporan', 'url' => route('admin.module', 'laporan-penjualan'), 'active' => request()->routeIs('admin.reports.*')]]],
            ['label' => 'Pengaturan', 'single' => true, 'items' => [['label' => 'Pengaturan', 'url' => route('admin.settings.index'), 'active' => request()->routeIs('admin.settings.*')]]],
        ];
    @endphp
    <aside class="admin-sidebar fixed inset-y-0 left-0 hidden w-72 overflow-y-auto border-r border-white/10 text-stone-300 lg:block">
        <div class="flex items-center gap-3 px-7 py-8"><span class="brand-mark" aria-hidden="true">A</span><div><p class="font-serif text-lg font-bold text-[#F0E7D5]">Ardians Group</p><p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-[#B69259]">Operations console</p></div></div>
        <nav class="space-y-5 px-3 pb-8 text-sm">
            @foreach ($adminNavigation as $group)
                <div>
                    @if ($group['single'] ?? false)
                        @php
                            $item = $group['items'][0];
                        @endphp
                        <a href="{{ $item['url'] }}" class="nav-link block rounded-lg px-4 py-2.5 transition {{ ($item['active'] ?? false) ? 'nav-active font-semibold' : 'text-stone-400' }}">{{ $item['label'] }}</a>
                    @else
                    <p class="mb-2 px-4 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-600">{{ $group['label'] }}</p>
                    <div class="space-y-1">
                        @foreach ($group['items'] as $item)
                            <a href="{{ $item['url'] }}" class="nav-link block rounded-lg px-4 py-2.5 transition {{ ($item['active'] ?? false) ? 'nav-active font-semibold' : 'text-stone-400' }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                    @endif
                </div>
            @endforeach
            <a href="{{ route('products.index') }}" class="nav-link block rounded-lg border border-white/10 px-4 py-3 text-stone-400 transition">Lihat katalog →</a>
        </nav>
    </aside>
    <div class="lg:pl-64">
        <header class="admin-topbar border-b"><div class="flex items-center justify-between gap-4 px-5 py-5 lg:px-10"><div><p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#9A763D]">Admin workspace</p><p class="mt-1 text-sm text-stone-500">{{ auth()->user()->name }} · {{ ucfirst(auth()->user()->role) }}</p></div><div class="flex items-center gap-4"><button id="admin-notification-toggle" type="button" class="inline-flex items-center gap-2 border border-stone-300 bg-white px-3 py-2 text-xs font-semibold text-stone-600 transition hover:border-[#9A763D] hover:text-[#9A763D]" aria-pressed="false" title="Aktifkan suara dan notifikasi pesanan"><span aria-hidden="true">🔔</span><span id="admin-notification-label" class="hidden sm:inline">Aktifkan notifikasi</span><span id="admin-notification-count" class="hidden min-w-5 rounded-full bg-[#9A763D] px-1.5 py-0.5 text-[10px] text-white">0</span></button><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="text-sm font-semibold text-stone-500 hover:text-[#212842]">Keluar</button></form></div></div></header>
        <details class="border-b border-stone-200 bg-white px-5 py-3 lg:hidden"><summary class="cursor-pointer list-none text-sm font-semibold text-stone-700">Buka menu admin <span class="float-right text-amber-700">☰</span></summary><nav class="mt-4 grid gap-1 pb-2 sm:grid-cols-2">@foreach ($adminNavigation as $group) @foreach ($group['items'] as $item)<a href="{{ $item['url'] }}" class="rounded-md px-3 py-2 text-sm {{ ($item['active'] ?? false) ? 'bg-amber-50 font-semibold text-amber-800' : 'text-stone-600 hover:bg-stone-100' }}">{{ ($group['single'] ?? false) ? $item['label'] : $group['label'].' · '.$item['label'] }}</a>@endforeach @endforeach</nav></details>
        <main class="mx-auto max-w-7xl px-5 py-8 lg:px-10 lg:py-10">
            @if (session('success'))<div class="mb-6 border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>@endif
            @if ($errors->any())<div class="mb-6 border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $errors->first() }}</div>@endif
            @yield('content')
        </main>
    </div>
    <div id="admin-order-toast" class="fixed right-5 top-5 z-50 hidden w-[min(22rem,calc(100vw-2.5rem))] border border-[#B28A45] bg-stone-900 p-4 text-white shadow-xl" role="status" aria-live="polite">
        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-[#E0C47A]">Pesanan baru</p>
        <p id="admin-order-toast-text" class="mt-2 text-sm leading-5"></p>
        <a id="admin-order-toast-link" href="{{ route('admin.orders.index') }}" class="mt-3 inline-block text-xs font-semibold text-[#E0C47A] hover:text-white">Buka daftar pesanan →</a>
    </div>
    <div id="admin-order-notification-config" data-url="{{ route('admin.notifications.orders') }}" data-orders-enabled="{{ \App\Models\Setting::getValue('notification.orders', true) ? '1' : '0' }}" hidden></div>
</body>
</html>
