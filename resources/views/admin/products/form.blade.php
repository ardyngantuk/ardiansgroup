@extends('admin.layout')

@section('title', $product->exists ? 'Edit Produk' : 'Tambah Produk')

@section('content')
    <div class="max-w-4xl">
        <a
            href="{{ route('admin.products.index') }}"
            class="text-sm font-semibold text-stone-500 hover:text-amber-700"
        >
            ← Kembali ke produk
        </a>

        <div class="mt-5">
            <p class="text-sm font-semibold text-amber-700">
                Product editor
            </p>

            <h1 class="mt-2 text-4xl font-semibold tracking-tight">
                {{ $product->exists ? 'Perbarui produk' : 'Tambah produk baru' }}
            </h1>

            <p class="mt-3 text-sm text-stone-500">
                Data harga di sini akan langsung dipakai katalog dan checkout customer.
            </p>
        </div>
    </div>

    <form
        method="POST"
        action="{{ $product->exists
            ? route('admin.products.update', $product)
            : route('admin.products.store') }}"
        class="mt-10 grid gap-8 lg:grid-cols-[1fr_300px]"
    >
        @csrf

        @if ($product->exists)
            @method('PUT')
        @endif

        {{-- Informasi Produk --}}
        <section class="border border-stone-200 bg-white p-6 sm:p-8">
            <h2 class="text-lg font-semibold">
                Informasi produk
            </h2>

            <div class="mt-6 grid gap-5 sm:grid-cols-2">
                {{-- Kode Produk --}}
                <div>
                    <label for="legacy_code" class="text-sm font-medium">
                        Kode produk
                    </label>

                    <input
                        id="legacy_code"
                        name="legacy_code"
                        type="text"
                        value="{{ old('legacy_code', $product->legacy_code) }}"
                        required
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Nama Produk --}}
                <div>
                    <label for="name" class="text-sm font-medium">
                        Nama produk
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $product->name) }}"
                        required
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="category_id" class="text-sm font-medium">
                        Kategori
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-4 text-sm"
                    >
                        <option value="">
                            Tanpa kategori
                        </option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(old('category_id', $product->category_id) == $category->id)
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Merek --}}
                <div>
                    <label for="brand_id" class="text-sm font-medium">
                        Merek
                    </label>

                    <select
                        id="brand_id"
                        name="brand_id"
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-4 text-sm"
                    >
                        <option value="">
                            Tanpa merek
                        </option>

                        @foreach ($brands as $brand)
                            <option
                                value="{{ $brand->id }}"
                                @selected(old('brand_id', $product->brand_id) == $brand->id)
                            >
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Departemen --}}
                <div>
                    <label for="department" class="text-sm font-medium">
                        Departemen
                    </label>

                    <input
                        id="department"
                        name="department"
                        type="text"
                        value="{{ old('department', $product->department) }}"
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Rak --}}
                <div>
                    <label for="rack" class="text-sm font-medium">
                        Rak
                    </label>

                    <input
                        id="rack"
                        name="rack"
                        type="text"
                        value="{{ old('rack', $product->rack) }}"
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Stok Saat Ini --}}
                <div>
                    <label for="current_stock" class="text-sm font-medium">
                        Stok saat ini
                    </label>

                    <input
                        id="current_stock"
                        name="current_stock"
                        type="number"
                        step="0.001"
                        min="0"
                        value="{{ old('current_stock', $product->current_stock ?? 0) }}"
                        required
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Stok Minimum --}}
                <div>
                    <label for="minimum_stock" class="text-sm font-medium">
                        Stok minimum
                    </label>

                    <input
                        id="minimum_stock"
                        name="minimum_stock"
                        type="number"
                        step="0.001"
                        min="0"
                        value="{{ old('minimum_stock', $product->minimum_stock ?? 0) }}"
                        required
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Deskripsi --}}
                <div class="sm:col-span-2">
                    <label for="description" class="text-sm font-medium">
                        Deskripsi
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="mt-2 w-full rounded-lg border border-stone-300 px-4 py-3 text-sm"
                    >{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </section>

        {{-- Unit & Harga --}}
        <aside class="h-fit border border-stone-200 bg-white p-6">
            <h2 class="text-lg font-semibold">
                Unit & harga
            </h2>

            <p class="mt-2 text-sm leading-6 text-stone-500">
                Kelola unit utama yang tampil saat customer membeli produk.
            </p>

            @php
                $unit = $product->units
                    ->sortBy('unit_level')
                    ->first();

                $price = $unit?->prices
                    ->sortBy('minimum_quantity')
                    ->first();
            @endphp

            <div class="mt-6 space-y-5">
                {{-- Nama Unit --}}
                <div>
                    <label for="unit_name" class="text-sm font-medium">
                        Nama unit
                    </label>

                    <input
                        id="unit_name"
                        name="unit_name"
                        type="text"
                        value="{{ old('unit_name', $unit?->unit_name) }}"
                        required
                        placeholder="pcs, kg, dus"
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Singkatan --}}
                <div>
                    <label for="abbreviation" class="text-sm font-medium">
                        Singkatan
                    </label>

                    <input
                        id="abbreviation"
                        name="abbreviation"
                        type="text"
                        value="{{ old('abbreviation', $unit?->abbreviation) }}"
                        placeholder="pcs"
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Konversi Unit --}}
                <div>
                    <label for="conversion_to_base" class="text-sm font-medium">
                        Konversi unit dasar
                    </label>

                    <input
                        id="conversion_to_base"
                        name="conversion_to_base"
                        type="number"
                        step="0.000001"
                        min="0.000001"
                        value="{{ old('conversion_to_base', $unit?->conversion_to_base ?? 1) }}"
                        required
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Harga Modal --}}
                <div>
                    <label for="cost_price" class="text-sm font-medium">
                        Harga modal
                    </label>

                    <input
                        id="cost_price"
                        name="cost_price"
                        type="number"
                        step="0.01"
                        min="0"
                        value="{{ old('cost_price', $unit?->cost_price) }}"
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >
                </div>

                {{-- Harga Jual --}}
                <div>
                    <label for="selling_price" class="text-sm font-medium">
                        Harga jual
                    </label>

                    <input
                        id="selling_price"
                        name="selling_price"
                        type="text"
                        inputmode="decimal"
                        placeholder="Contoh: 12.500"
                        value="{{ old('selling_price', $price?->selling_price) }}"
                        required
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 px-4 text-sm"
                    >

                    <p class="mt-1 text-xs text-stone-400">
                        Gunakan angka, titik ribuan, atau koma desimal.
                    </p>
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="text-sm font-medium">
                        Status katalog
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="mt-2 min-h-11 w-full rounded-lg border border-stone-300 bg-white px-4 text-sm"
                    >
                        <option
                            value="active"
                            @selected(old('status', $product->status ?: 'active') === 'active')
                        >
                            Aktif
                        </option>

                        <option
                            value="inactive"
                            @selected(old('status', $product->status) === 'inactive')
                        >
                            Nonaktif
                        </option>
                    </select>
                </div>
            </div>

            <button
                type="submit"
                class="mt-7 min-h-12 w-full rounded-lg bg-stone-900 px-5 text-sm font-semibold text-white hover:bg-amber-700"
            >
                Simpan produk
            </button>
        </aside>
    </form>

    @if ($product->exists)
        {{-- Media Produk --}}
        <section class="mt-8 border border-stone-200 bg-white p-6 sm:p-8">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="text-sm font-semibold text-amber-700">
                        Media produk
                    </p>

                    <h2 class="mt-1 text-xl font-semibold">
                        Foto produk
                    </h2>

                    <p class="mt-2 text-sm text-stone-500">
                        Upload maksimal 10 gambar. Satu gambar dapat dipilih
                        sebagai thumbnail untuk katalog customer.
                    </p>
                </div>
            </div>

            {{-- Upload Gambar --}}
            <form
                method="POST"
                action="{{ route('admin.products.images.upload', $product) }}"
                enctype="multipart/form-data"
                class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-end"
            >
                @csrf

                <div class="flex-1">
                    <label for="images" class="text-sm font-medium">
                        Pilih gambar
                    </label>

                    <input
                        id="images"
                        name="images[]"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                        required
                        class="mt-2 block min-h-11 w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm"
                    >
                </div>

                <button
                    type="submit"
                    class="min-h-11 rounded-lg bg-stone-900 px-5 py-3 text-sm font-semibold text-white hover:bg-amber-700"
                >
                    Upload gambar
                </button>
            </form>

            @if ($product->images->isNotEmpty())
                {{-- Daftar Gambar --}}
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($product->images as $image)
                        <div class="overflow-hidden border border-stone-200 bg-stone-50">
                            <img
                                src="{{ asset('storage/' . $image->image_path) }}"
                                alt="{{ $product->name }}"
                                class="aspect-square w-full object-cover"
                            >

                            <div class="space-y-2 p-3">
                                @if ($image->is_thumbnail)
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                        Thumbnail aktif
                                    </span>
                                @else
                                    <form
                                        method="POST"
                                        action="{{ route('admin.products.images.thumbnail', [$product, $image]) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="text-xs font-semibold text-amber-700 hover:text-amber-900"
                                        >
                                            Jadikan thumbnail
                                        </button>
                                    </form>
                                @endif

                                <form
                                    method="POST"
                                    action="{{ route('admin.products.images.destroy', [$product, $image]) }}"
                                    onsubmit="return confirm('Hapus gambar ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="text-xs font-semibold text-red-600 hover:text-red-800"
                                    >
                                        Hapus gambar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-8 border border-dashed border-stone-300 px-6 py-10 text-center text-sm text-stone-500">
                    Belum ada foto produk.
                </div>
            @endif
        </section>

        {{-- Nonaktifkan Produk --}}
        <form
            method="POST"
            action="{{ route('admin.products.destroy', $product) }}"
            class="mt-6"
        >
            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="text-sm font-semibold text-red-600 hover:text-red-800"
            >
                Nonaktifkan produk
            </button>
        </form>
    @endif
@endsection
