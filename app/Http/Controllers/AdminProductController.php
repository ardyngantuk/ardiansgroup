<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $products = Product::with(['category', 'units.prices'])
            ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('legacy_code', 'like', "%{$search}%")))
            ->orderByDesc('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.products.index', compact('products', 'search'));
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product(), 'categories' => Category::orderBy('name')->get(), 'brands' => Brand::orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $product = DB::transaction(fn () => $this->saveProduct(new Product(), $data));

        return redirect()->route('admin.products.edit', $product)->with('success', 'Produk berhasil dibuat.');
    }

    public function edit(Product $product): View
    {
        $product->load(['units.prices', 'images']);
        return view('admin.products.form', ['product' => $product, 'categories' => Category::orderBy('name')->get(), 'brands' => Brand::orderBy('name')->get()]);
    }

    public function uploadImages(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $hasThumbnail = $product->images()->where('is_thumbnail', true)->exists();
        $sortOrder = (int) ($product->images()->max('sort_order') ?? 0);

        foreach ($data['images'] as $image) {
            $path = $image->store('products/' . $product->id, 'public');
            $sortOrder++;
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_thumbnail' => ! $hasThumbnail,
                'sort_order' => $sortOrder,
            ]);
            $hasThumbnail = true;
        }

        return back()->with('success', 'Gambar produk berhasil diunggah.');
    }

    public function setThumbnail(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        DB::transaction(function () use ($product, $image) {
            $product->images()->update(['is_thumbnail' => false]);
            $image->update(['is_thumbnail' => true]);
        });

        return back()->with('success', 'Thumbnail produk berhasil diubah.');
    }

    public function deleteImage(Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);
        $wasThumbnail = $image->is_thumbnail;
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        if ($wasThumbnail && ($replacement = $product->images()->first())) {
            $replacement->update(['is_thumbnail' => true]);
        }

        return back()->with('success', 'Gambar produk berhasil dihapus.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product);
        DB::transaction(fn () => $this->saveProduct($product, $data));

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->update(['status' => 'inactive']);
        return back()->with('success', 'Produk dinonaktifkan dan tetap tersimpan.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $request->merge([
            'selling_price' => $this->normalizeMoney($request->input('selling_price')),
        ]);

        return $request->validate([
            'legacy_code' => ['required', 'string', 'max:100', 'unique:products,legacy_code,' . ($product?->id ?? 'NULL')],
            'name' => ['required', 'string', 'max:200'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'department' => ['nullable', 'string', 'max:100'],
            'rack' => ['nullable', 'string', 'max:100'],
            'current_stock' => [$product?->exists ? 'nullable' : 'required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'unit_name' => ['required', 'string', 'max:60'],
            'abbreviation' => ['nullable', 'string', 'max:30'],
            'conversion_to_base' => ['required', 'numeric', 'gt:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['required_if:status,active', 'nullable', 'numeric', 'gt:0'],
        ]);
    }

    private function saveProduct(Product $product, array $data): Product
    {
        $product->fill([
            'legacy_code' => $data['legacy_code'], 'name' => $data['name'], 'category_id' => $data['category_id'] ?? null,
            'brand_id' => $data['brand_id'] ?? null, 'department' => $data['department'] ?? null, 'rack' => $data['rack'] ?? null,
            'current_stock' => $product->exists ? $product->current_stock : $data['current_stock'], 'minimum_stock' => $data['minimum_stock'],
            'initial_stock' => $product->exists ? $product->initial_stock : $data['current_stock'],
            'description' => $data['description'] ?? null, 'status' => $data['status'],
        ])->save();

        $unit = $product->units()->firstOrNew(['unit_level' => 1]);
        $unit->fill(['unit_name' => $data['unit_name'], 'abbreviation' => $data['abbreviation'] ?? $data['unit_name'], 'conversion_to_base' => $data['conversion_to_base'], 'cost_price' => $data['cost_price'] ?? null, 'default_selling_price' => $data['selling_price'] ?? null]);
        $product->units()->save($unit);

        if ($data['selling_price'] === null && $product->status === 'active') {
            throw ValidationException::withMessages([
                'selling_price' => 'Harga jual wajib diisi untuk produk aktif agar dapat dibeli customer.',
            ]);
        }

        if ($data['selling_price'] !== null) {
            $unit->prices()->updateOrCreate(
                ['tier' => 1, 'minimum_quantity' => 1],
                ['selling_price' => $data['selling_price']],
            );
        }

        return $product;
    }

    private function normalizeMoney(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        $value = preg_replace('/\s+/', '', (string) $value);
        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } elseif (preg_match('/^\d{1,3}(\.\d{3})+$/', $value)) {
            $value = str_replace('.', '', $value);
        } elseif (str_contains($value, ',')) {
            $value = str_replace(',', '.', $value);
        }

        return $value;
    }
}