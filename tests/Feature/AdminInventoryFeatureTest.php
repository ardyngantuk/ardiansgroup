<?php

namespace Tests\Feature;

use App\Models\InventoryBatch;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminInventoryFeatureTest extends TestCase
{
    private array $created = [];

    protected function tearDown(): void
    {
        if ($this->created !== []) {
            DB::table('stock_movements')->whereIn('product_id', $this->created['product_ids'] ?? [])->delete();
            DB::table('inventory_batches')->whereIn('product_id', $this->created['product_ids'] ?? [])->delete();
            DB::table('product_units')->whereIn('id', $this->created['unit_ids'] ?? [])->delete();
            DB::table('products')->whereIn('id', $this->created['product_ids'] ?? [])->delete();
            DB::table('users')->whereIn('id', $this->created['user_ids'] ?? [])->delete();
        }

        parent::tearDown();
    }

    public function test_stock_index_reports_summary_and_status_filters(): void
    {
        $admin = $this->createAdmin();
        $this->createProduct('Aman', 100, 20);
        $this->createProduct('Menipis', 20, 20);
        $this->createProduct('Habis', 0, 20);

        $this->actingAs($admin)->get(route('admin.stock.index'))->assertOk()->assertSee('Total produk')->assertSee('3');
        $this->actingAs($admin)->get(route('admin.stock.index', ['status' => 'low']))->assertOk()->assertSee('Menipis')->assertDontSee('Aman');
        $this->actingAs($admin)->get(route('admin.stock.index', ['status' => 'out']))->assertOk()->assertSee('Habis')->assertDontSee('Aman');
    }

    public function test_adjustment_in_converts_unit_creates_batch_and_movement(): void
    {
        $admin = $this->createAdmin();
        [$product, $unit] = $this->createProduct('Minyak', 10, 2, 24, 10000);

        $this->actingAs($admin)->post(route('admin.stock.adjustments.store', $product), [
            'type' => 'adjustment_in', 'product_unit_id' => $unit->id, 'quantity' => 2, 'reason' => 'Selisih stok fisik',
        ])->assertRedirect(route('admin.stock.show', $product));

        $product->refresh();
        $this->assertSame(58.0, (float) $product->current_stock);
        $batch = InventoryBatch::where('product_id', $product->id)->latest('id')->firstOrFail();
        $this->assertSame(48.0, (float) $batch->remaining_quantity);
        $this->assertDatabaseHas('stock_movements', ['product_id' => $product->id, 'movement_type' => 'adjustment_in', 'base_quantity' => 48, 'created_by' => $admin->id]);
    }

    public function test_adjustment_out_consumes_fifo_batches_without_negative_stock(): void
    {
        $admin = $this->createAdmin();
        [$product, $unit] = $this->createProduct('Gula', 200, 20, 1, 10000);
        $first = InventoryBatch::create(['product_id' => $product->id, 'received_at' => now()->subDays(2), 'original_quantity' => 100, 'remaining_quantity' => 100, 'unit_cost' => 10000, 'status' => 'open']);
        $second = InventoryBatch::create(['product_id' => $product->id, 'received_at' => now()->subDay(), 'original_quantity' => 100, 'remaining_quantity' => 100, 'unit_cost' => 11000, 'status' => 'open']);
        $this->created['batch_ids'] = [$first->id, $second->id];

        $this->actingAs($admin)->post(route('admin.stock.adjustments.store', $product), [
            'type' => 'adjustment_out', 'product_unit_id' => $unit->id, 'quantity' => 120, 'reason' => 'Stok fisik kurang',
        ])->assertRedirect();

        $this->assertSame(0.0, (float) $first->refresh()->remaining_quantity);
        $this->assertSame(80.0, (float) $second->refresh()->remaining_quantity);
        $this->assertSame(80.0, (float) $product->refresh()->current_stock);
        $this->assertGreaterThanOrEqual(0, (float) $product->current_stock);
        $this->assertDatabaseHas('stock_movements', ['product_id' => $product->id, 'movement_type' => 'adjustment_out', 'base_quantity' => 120]);
    }

    public function test_reconciliation_page_reports_batch_difference(): void
    {
        $admin = $this->createAdmin();
        [$product] = $this->createProduct('Beras', 100, 10);
        $batch = InventoryBatch::create(['product_id' => $product->id, 'received_at' => now(), 'original_quantity' => 95, 'remaining_quantity' => 95, 'unit_cost' => 12000, 'status' => 'open']);
        $this->created['batch_ids'][] = $batch->id;

        $this->actingAs($admin)->get(route('admin.stock.reconciliation'))->assertOk()->assertSee('Beras')->assertSee('Data tidak diubah otomatis');
    }

    public function test_adjustment_rejects_invalid_quantity_and_other_product_unit(): void
    {
        $admin = $this->createAdmin();
        [$product] = $this->createProduct('Tepung', 20, 5);
        [, $otherUnit] = $this->createProduct('Garam', 20, 5);

        $this->actingAs($admin)->from(route('admin.stock.show', $product))->post(route('admin.stock.adjustments.store', $product), [
            'type' => 'adjustment_out', 'product_unit_id' => $otherUnit->id, 'quantity' => 0, 'reason' => '',
        ])->assertSessionHasErrors(['quantity', 'reason']);

        $this->actingAs($admin)->from(route('admin.stock.show', $product))->post(route('admin.stock.adjustments.store', $product), [
            'type' => 'adjustment_out', 'product_unit_id' => $otherUnit->id, 'quantity' => 1, 'reason' => 'Unit tidak sesuai',
        ])->assertSessionHasErrors('product_unit_id');
    }

    private function createAdmin(): User
    {
        $user = User::create(['name' => 'Inventory Admin', 'email' => 'inventory-' . uniqid() . '@example.test', 'password' => 'password', 'role' => 'admin', 'status' => 'active']);
        $this->created['user_ids'][] = $user->id;
        return $user;
    }

    private function createProduct(string $name, float $stock, float $minimum, float $conversion = 1, float $cost = 100): array
    {
        $product = Product::create(['legacy_code' => 'INV-' . uniqid(), 'name' => $name, 'current_stock' => $stock, 'initial_stock' => $stock, 'minimum_stock' => $minimum, 'status' => 'active']);
        $unit = ProductUnit::create(['product_id' => $product->id, 'unit_level' => 1, 'unit_name' => 'PCS', 'abbreviation' => 'PCS', 'conversion_to_base' => $conversion, 'cost_price' => $cost, 'default_selling_price' => $cost * 2]);
        $this->created['product_ids'][] = $product->id;
        $this->created['unit_ids'][] = $unit->id;
        return [$product, $unit];
    }
}
