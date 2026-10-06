@php
	$store = $storefront ?? [];
@endphp
<footer class="mt-16 bg-[#101827] text-[#93A4BC]">
	<div class="mx-auto max-w-7xl px-5 pb-7 pt-12 lg:px-8 lg:pt-14">
		<div class="grid gap-10 md:grid-cols-[1.45fr_.8fr_1.15fr_1.2fr] lg:gap-14">
			<section>
				<a href="{{ route('home') }}" class="inline-block" aria-label="Ardian's beranda">
					<span class="font-display text-[25px] font-bold tracking-[.12em] text-[#F7F0E3]">ARDIAN'S</span>
					<span class="mt-2 block font-sans text-[10px] font-bold uppercase tracking-[.22em] text-[#F0B900]">Sembako wholesale &amp; retail</span>
				</a>
				<p class="mt-5 max-w-sm text-sm leading-[1.65] text-[#93A4BC]">{{ $store['description'] ?? 'Pusat kulakan sembako dan kebutuhan pokok terpercaya.' }}</p>
				<a href="{{ $store['whatsapp_url'] ?? '#' }}" class="mt-6 inline-flex items-center gap-3 text-xs font-medium text-[#93A4BC] transition hover:text-white" target="_blank" rel="noopener noreferrer">
					<span class="grid h-8 w-8 place-items-center rounded-lg bg-[#00896B] text-white" aria-hidden="true">
						<svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4.1A8 8 0 1 1 20 11.5Z"/><path d="M8.5 9.2c.2-.4.4-.4.7-.4h.5c.2 0 .4.1.5.4l.6 1.4c.1.2.1.4-.1.6l-.5.6c.5 1 1.3 1.7 2.3 2.2l.6-.5c.2-.2.4-.2.6-.1l1.4.6c.3.1.4.3.4.5v.5c0 .3 0 .5-.4.7-.5.3-1.1.4-1.7.2-2.3-.7-4.2-2.6-5.3-4.8-.3-.6-.3-1.2 0-1.9l.4-.4Z"/></svg>
					</span>
									WhatsApp {{ $store['whatsapp'] ?? '' }}
				</a>
			</section>

			<nav aria-label="Navigasi footer">
				<h2 class="text-[13px] font-bold uppercase tracking-wide text-[#F7F0E3]">Navigasi</h2>
				<div class="mt-4 space-y-3 text-[13px]">
					<a href="{{ route('home') }}" class="block transition hover:text-white">Beranda Utama</a>
					<a href="{{ route('products.index') }}" class="block transition hover:text-white">Katalog Sembako</a>
					<a href="{{ route('products.index') }}" class="block transition hover:text-white">Tentang Toko</a>
					<a href="{{ route('products.index') }}" class="block transition hover:text-white">Hubungi Kami</a>
					<a href="{{ route('cart.index') }}" class="block transition hover:text-white">Keranjang Belanja</a>
				</div>
			</nav>

			<nav aria-label="Kategori terlaris">
				<h2 class="text-[13px] font-bold uppercase tracking-wide text-[#F7F0E3]">Kategori Terlaris</h2>
				<div class="mt-4 space-y-3 text-[13px]">
					<a href="{{ route('products.index', ['category' => 'beras']) }}" class="block transition hover:text-white">Beras Ramos &amp; IR64 Super</a>
					<a href="{{ route('products.index', ['category' => 'minyak']) }}" class="block transition hover:text-white">Minyak Bimoli, Sania &amp; Fortune</a>
					<a href="{{ route('products.index') }}" class="block transition hover:text-white">Tepung Segitiga Biru &amp; Cakra</a>
					<a href="{{ route('products.index') }}" class="block transition hover:text-white">Gula Pasir Kristal Tebu</a>
					<a href="{{ route('products.index') }}" class="block transition hover:text-white">Mie Instan Indomie &amp; Sedaap</a>
					<a href="{{ route('products.index') }}" class="block transition hover:text-white">Susu Kental Manis &amp; UHT</a>
				</div>
			</nav>

			<section>
				<h2 class="text-[13px] font-bold uppercase tracking-wide text-[#F7F0E3]">Gudang &amp; Toko</h2>
				<div class="mt-4 space-y-3 text-[13px] leading-5">
					<p class="flex gap-3"><svg class="mt-0.5 h-4 w-4 shrink-0 text-[#F0B900]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg><span>{{ $store['address'] ?? '' }}</span></p>
					<p class="flex gap-3"><svg class="mt-0.5 h-4 w-4 shrink-0 text-[#F0B900]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 4h3l2 5-2 1.5a14 14 0 0 0 5.5 5.5L15 14l5 2v3c0 1 0 2 1 2C10.3 20 4 13.7 4 6c0-1 .3-2 1-2Z"/></svg><span>{{ $store['whatsapp'] ?? '' }}</span></p>
					<p class="flex gap-3"><svg class="mt-0.5 h-4 w-4 shrink-0 text-[#F0B900]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 7v5l3 2"/></svg><span>{{ $store['hours'] ?? '' }}</span></p>
					<p class="flex gap-3"><svg class="mt-0.5 h-4 w-4 shrink-0 text-[#F0B900]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg><a href="mailto:{{ $store['email'] ?? '' }}" class="transition hover:text-white">{{ $store['email'] ?? '' }}</a></p>
				</div>
			</section>
		</div>

		<div class="mt-10 flex flex-col gap-4 border-t border-[#243149] pt-6 text-[11px] text-[#637691] sm:flex-row sm:items-center sm:justify-between">
			<p>© {{ date('Y') }} ARDIAN'S SEMBAKO WHOLESALE &amp; RETAIL. Hak Cipta Dilindungi.</p>
			<div class="flex flex-wrap gap-4 sm:gap-5"><span>Sembako Lengkap</span><span aria-hidden="true">·</span><span>Harga Bersaing</span><span aria-hidden="true">·</span><span>Pelayanan Terbaik</span></div>
		</div>
	</div>
</footer>
