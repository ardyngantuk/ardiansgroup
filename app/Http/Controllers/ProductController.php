<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function home(): View
    {
        $featuredProducts = collect();
        $categories = collect();
        $heroSlides = $this->heroSlides();

        if (Schema::hasTable('products')) {
            $featuredProducts = Product::query()
                ->where('status', 'active')
                ->where('current_stock', '>', 0)
                ->with(['category', 'brand', 'units', 'prices', 'thumbnail'])
                ->orderByDesc('current_stock')
                ->orderBy('name')
                ->take(8)
                ->get();
        }

        if (Schema::hasTable('categories') && Schema::hasTable('products')) {
            $categories = Category::query()
                ->where('status', 'active')
                ->withCount(['products' => fn ($query) => $query->where('status', 'active')->where('current_stock', '>', 0)])
                ->orderBy('name')
                ->take(6)
                ->get();
        }

        return view('welcome', compact('featuredProducts', 'categories', 'heroSlides'));
    }

    private function heroSlides(): array
    {
        return [
            [
                'label' => 'Promo Mingguan',
                'title' => 'Hemat belanja beras & sembako',
                'subtitle' => 'Diskon praktis untuk stok harian rumah tangga',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'label' => 'Siap Usaha',
                'title' => 'Stok lengkap untuk warung Anda',
                'subtitle' => 'Harga bersaing untuk kebutuhan usaha kecil',
                'image' => 'https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'label' => 'Fresh & Sehat',
                'title' => 'Pilihan segar untuk keluarga',
                'subtitle' => 'Beli kebutuhan harian tanpa antre panjang',
                'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=1200&q=80',
            ],
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $categorySlug = trim((string) $request->query('category', ''));
        $selectedCategory = null;

        if ($categorySlug !== '') {
            $selectedCategory = Category::query()
                ->where('status', 'active')
                ->where(function ($query) use ($categorySlug) {
                    $query->where('slug', $categorySlug)
                        ->orWhere('id', $categorySlug);
                })
                ->first();
        }

        $categories = Category::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('status', 'active')
            ->where('current_stock', '>', 0)
            ->when($selectedCategory, fn ($query) => $query->where('category_id', $selectedCategory->id))
            ->with(['category', 'brand', 'units', 'prices', 'thumbnail'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('legacy_code', 'like', "%{$search}%")
                        ->orWhereHas('barcodes', function ($query) use ($search) {
                            $query->where('barcode', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(24)
            ->withQueryString();

        return view('products.index', compact('products', 'search', 'categories', 'selectedCategory'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->status === 'active', 404);

        $product->load(['category', 'brand', 'units.prices', 'units.barcodes', 'images', 'thumbnail']);

        return view('products.show', compact('product'));
    }
}