<?php

namespace App\Http\Controllers;

use App\Models\InventoryBatch;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class AdminInventoryController extends Controller
{
    private const STATUSES = ['available', 'low', 'out'];

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'brand' => ['nullable', 'integer', 'exists:brands,id'],
            'status' => ['nullable', 'in:' . implode(',', self::STATUSES)],
        ]);

        $products = Product::query()
            ->select(['id', 'name', 'legacy_code', 'category_id', 'brand_id', 'current_stock', 'minimum_stock', 'status'])
            ->with([
                'category:id,name',
                'brand:id,name',
                'units' => fn ($query) => $query->select(['id', 'product_id', 'unit_name', 'abbreviation', 'unit_level', 'conversion_to_base'])->orderBy('unit_level'),
            ])
            ->where('status', 'active')
            ->when($filters['q'] ?? null, fn ($query, $search) => $query->where(fn ($searchQuery) => $searchQuery->where('name', 'like', "%{$search}%")->orWhere('legacy_code', 'like', "%{$search}%")))
            ->when($filters['category'] ?? null, fn ($query, $category) => $query->where('category_id', $category))
            ->when($filters['brand'] ?? null, fn ($query, $brand) => $query->where('brand_id', $brand))
            ->when(($filters['status'] ?? null) === 'available', fn ($query) => $query->whereColumn('current_stock', '>', 'minimum_stock'))
            ->when(($filters['status'] ?? null) === 'low', fn ($query) => $query->where('current_stock', '>', 0)->whereColumn('current_stock', '<=', 'minimum_stock'))
            ->when(($filters['status'] ?? null) === 'out', fn ($query) => $query->where('current_stock', '<=', 0))
            ->orderBy('name')
            ->paginate(25)
            ->withQueryString();

        return view('admin.inventory.index', [
            'products' => $products,
            'categories' => \App\Models\Category::query()->orderBy('name')->get(['id', 'name']),
            'brands' => \App\Models\Brand::query()->orderBy('name')->get(['id', 'name']),
            'filters' => $filters,
            'summary' => $this->summary(),
            'reconciliationCount' => $this->reconciliationQuery()->count(),
        ]);
    }

    public function show(Product $product): View
    {
        $product->load([
            'category:id,name',
            'brand:id,name',
            'units' => fn ($query) => $query->orderBy('unit_level'),
            'inventoryBatches' => fn ($query) => $query->with('purchaseItem.purchase')->orderBy('received_at')->orderBy('id'),
        ]);

        $movements = StockMovement::query()
            ->with(['productUnit:id,unit_name,abbreviation', 'creator:id,name'])
            ->where('product_id', $product->id)
            ->latest('created_at')
            ->latest('id')
            ->paginate(20, ['*'], 'movements')
            ->withQueryString();
        $batchStock = (float) $product->inventoryBatches->where('status', 'open')->sum('remaining_quantity');

        return view('admin.inventory.show', [
            'product' => $product,
            'movements' => $movements,
            'batchStock' => $batchStock,
            'isReconciled' => abs((float) $product->current_stock - $batchStock) <= 0.001,
        ]);
    }

    public function reconciliation(): View
    {
        return view('admin.inventory.reconciliation', [
            'products' => $this->reconciliationQuery()->paginate(25),
        ]);
    }

    public function adjust(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:adjustment_in,adjustment_out'],
            'product_unit_id' => ['required', 'integer', 'exists:product_units,id'],
            'quantity' => ['required', 'numeric', 'decimal:0,3', 'gt:0'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $movement = DB::transaction(function () use ($data, $product, $request): StockMovement {
            $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);
            $unit = ProductUnit::query()->where('product_id', $lockedProduct->id)->lockForUpdate()->find($data['product_unit_id']);
            if (! $unit) {
                throw ValidationException::withMessages(['product_unit_id' => 'Satuan tidak valid untuk produk ini.']);
            }
            $quantity = (float) $data['quantity'];
            $baseQuantity = $unit->toBaseQuantity($quantity);
            $stockBefore = (float) $lockedProduct->current_stock;

            if ($baseQuantity <= 0) {
                throw ValidationException::withMessages(['quantity' => 'Konversi satuan menghasilkan jumlah dasar yang tidak valid.']);
            }

            if ($data['type'] === 'adjustment_out' && $baseQuantity > $stockBefore) {
                throw ValidationException::withMessages(['quantity' => 'Stok tidak mencukupi untuk penyesuaian keluar.']);
            }

            if ($data['type'] === 'adjustment_in') {
                InventoryBatch::create([
                    'product_id' => $lockedProduct->id,
                    'received_at' => now(),
                    'original_quantity' => $baseQuantity,
                    'remaining_quantity' => $baseQuantity,
                    'unit_cost' => (float) ($unit->cost_price ?? 0) / max((float) $unit->conversion_to_base, 1),
                    'status' => 'open',
                ]);
            } else {
                $this->consumeBatches($lockedProduct, $baseQuantity, (float) ($unit->cost_price ?? 0) / max((float) $unit->conversion_to_base, 1));
            }

            $stockAfter = $data['type'] === 'adjustment_in'
                ? $stockBefore + $baseQuantity
                : $stockBefore - $baseQuantity;
            $lockedProduct->update(['current_stock' => $stockAfter]);

            return StockMovement::create([
                'product_id' => $lockedProduct->id,
                'product_unit_id' => $unit->id,
                'movement_type' => $data['type'],
                'quantity' => $quantity,
                'base_quantity' => $baseQuantity,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'reference_type' => 'product_adjustment',
                'reference_id' => $lockedProduct->id,
                'notes' => 'Alasan: ' . $data['reason'] . (! empty($data['notes']) ? "\n" . $data['notes'] : ''),
                'created_by' => $request->user()->id,
            ]);
        });

        return redirect()->route('admin.stock.show', $product)->with('success', 'Penyesuaian stok berhasil dicatat.');
    }

    private function summary(): array
    {
        $active = Product::query()->where('status', 'active');

        return [
            'total' => (clone $active)->count(),
            'available' => (clone $active)->whereColumn('current_stock', '>', 'minimum_stock')->count(),
            'low' => (clone $active)->where('current_stock', '>', 0)->whereColumn('current_stock', '<=', 'minimum_stock')->count(),
            'out' => (clone $active)->where('current_stock', '<=', 0)->count(),
        ];
    }

    private function reconciliationQuery()
    {
        return Product::query()
            ->select(['products.id', 'products.name', 'products.current_stock'])
            ->where('products.status', 'active')
            ->whereHas('inventoryBatches', fn ($query) => $query->where('status', 'open'))
            ->selectSub(fn ($query) => $query->from('inventory_batches')->selectRaw('COALESCE(SUM(remaining_quantity), 0)')->whereColumn('product_id', 'products.id')->where('status', 'open'), 'batch_stock')
            ->whereRaw('ABS(products.current_stock - (SELECT COALESCE(SUM(remaining_quantity), 0) FROM inventory_batches WHERE inventory_batches.product_id = products.id AND inventory_batches.status = \'open\')) > 0.001');
    }

    private function consumeBatches(Product $product, float $baseQuantity, float $fallbackCost): void
    {
        $batches = InventoryBatch::query()
            ->where('product_id', $product->id)
            ->where('status', 'open')
            ->where('remaining_quantity', '>', 0)
            ->orderBy('received_at')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();
        $batchQuantity = (float) $batches->sum('remaining_quantity');

        if ($batchQuantity < (float) $product->current_stock) {
            $legacyQuantity = (float) $product->current_stock - $batchQuantity;
            $batches->push(InventoryBatch::create([
                'product_id' => $product->id,
                'received_at' => $product->created_at ?? now(),
                'original_quantity' => $legacyQuantity,
                'remaining_quantity' => $legacyQuantity,
                'unit_cost' => $fallbackCost,
                'status' => 'open',
            ]));
        }

        $remaining = $baseQuantity;
        foreach ($batches as $batch) {
            if ($remaining <= 0) {
                break;
            }

            $allocated = min($remaining, (float) $batch->remaining_quantity);
            $batch->update([
                'remaining_quantity' => (float) $batch->remaining_quantity - $allocated,
                'status' => (float) $batch->remaining_quantity - $allocated <= 0 ? 'depleted' : 'open',
            ]);
            $remaining -= $allocated;
        }

        if ($remaining > 0.001) {
            throw ValidationException::withMessages(['quantity' => 'Batch FIFO tidak mencukupi untuk penyesuaian ini.']);
        }
    }
}
