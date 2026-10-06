<?php

namespace Tests\Feature;

use App\Models\InventoryBatch;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class OrderWorkflowFeatureTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createBusinessSchema();
        foreach (['order_activities', 'sale_batch_allocations', 'stock_movements', 'payments', 'order_items', 'orders', 'inventory_batches', 'product_prices', 'product_units', 'products', 'customers'] as $table) {
            Schema::getConnection()->table($table)->delete();
        }
    }

    public function test_checkout_creates_order_payment_and_activity(): void
    {
        [$product, $unit] = $this->createSellableProduct();
        InventoryBatch::create(['product_id' => $product->id, 'received_at' => now()->subDay(), 'original_quantity' => 10, 'remaining_quantity' => 10, 'unit_cost' => 100, 'status' => 'open']);
        session(['cart' => [(string) $unit->id => 2]]);

        $response = $this->post(route('checkout.store'), [
            'name' => 'Test Customer', 'phone' => '081234567890', 'address' => 'Alamat test', 'payment_method' => 'cod',
        ]);

        $response->assertRedirect();
        $order = Order::firstOrFail();
        $this->assertSame('reserved', $order->stock_status);
        $this->assertSame('pending', $order->payment_status);
        $this->assertDatabaseHas('payments', ['order_id' => $order->id, 'status' => 'pending']);
        $this->assertDatabaseHas('order_activities', ['order_id' => $order->id, 'event' => 'order_created']);
    }

    public function test_checkout_rejects_quantity_above_locked_stock(): void
    {
        [$product, $unit] = $this->createSellableProduct(1);
        InventoryBatch::create(['product_id' => $product->id, 'received_at' => now(), 'original_quantity' => 1, 'remaining_quantity' => 1, 'unit_cost' => 100, 'status' => 'open']);
        session(['cart' => [(string) $unit->id => 2]]);

        $this->post(route('checkout.store'), [
            'name' => 'Test Customer', 'phone' => '081234567891', 'address' => 'Alamat test', 'payment_method' => 'cod',
        ])->assertSessionHasErrors('quantity');

        $this->assertSame(1.0, (float) Product::find($product->id)->current_stock);
        $this->assertSame(0, Order::count());
    }

    public function test_cod_requires_minimum_order_value(): void
    {
        config(['shipping.cod_minimum_order' => 50000]);
        [$product, $unit] = $this->createSellableProduct();
        InventoryBatch::create(['product_id' => $product->id, 'received_at' => now(), 'original_quantity' => 10, 'remaining_quantity' => 10, 'unit_cost' => 100, 'status' => 'open']);
        session(['cart' => [(string) $unit->id => 2]]);

        $this->post(route('checkout.store'), [
            'name' => 'COD Customer', 'phone' => '081234567895', 'address' => 'Alamat COD', 'payment_method' => 'cod',
        ])->assertSessionHasErrors('payment_method');

        $this->assertSame(0, Order::count());
        $this->assertSame(10.0, (float) Product::find($product->id)->current_stock);
    }

    public function test_shipping_zone_is_added_to_order_total(): void
    {
        config(['shipping.cod_minimum_order' => 0]);
        [$product, $unit] = $this->createSellableProduct();
        InventoryBatch::create(['product_id' => $product->id, 'received_at' => now(), 'original_quantity' => 10, 'remaining_quantity' => 10, 'unit_cost' => 100, 'status' => 'open']);
        session(['cart' => [(string) $unit->id => 2]]);

        $this->post(route('checkout.store'), [
            'name' => 'Shipping Customer', 'phone' => '081234567896', 'address' => 'Alamat medium', 'payment_method' => 'cod', 'delivery_zone' => 'medium',
        ])->assertRedirect();

        $order = Order::firstOrFail();
        $this->assertSame(1000.0, (float) $order->subtotal);
        $this->assertSame(10000.0, (float) $order->shipping_cost);
        $this->assertSame(11000.0, (float) $order->total);
        $this->assertSame('medium', $order->delivery_zone);
        $this->assertSame(10.0, (float) $order->delivery_distance_km);
    }

    public function test_checkout_recalculates_price_from_database(): void
    {
        [$product, $unit] = $this->createSellableProduct();
        InventoryBatch::create(['product_id' => $product->id, 'received_at' => now(), 'original_quantity' => 10, 'remaining_quantity' => 10, 'unit_cost' => 100, 'status' => 'open']);
        session(['cart' => [(string) $unit->id => 2]]);

        ProductPrice::query()->where('product_unit_id', $unit->id)->update(['selling_price' => 750]);

        $this->post(route('checkout.store'), [
            'name' => 'Price Change Customer', 'phone' => '081234567893', 'address' => 'Alamat harga', 'payment_method' => 'cod',
        ])->assertRedirect();

        $order = Order::firstOrFail();
        $this->assertSame(1500.0, (float) $order->subtotal);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'selling_price' => 750, 'subtotal' => 1500]);
    }

    public function test_repeated_checkout_submission_reuses_existing_order(): void
    {
        [$product, $unit] = $this->createSellableProduct();
        InventoryBatch::create(['product_id' => $product->id, 'received_at' => now(), 'original_quantity' => 10, 'remaining_quantity' => 10, 'unit_cost' => 100, 'status' => 'open']);
        session(['cart' => [(string) $unit->id => 2]]);

        $this->get(route('checkout'))->assertOk();
        $checkoutData = [
            'name' => 'Repeated Customer', 'phone' => '081234567894', 'address' => 'Alamat berulang', 'payment_method' => 'cod',
        ];

        $this->post(route('checkout.store'), $checkoutData)->assertRedirect();
        $this->post(route('checkout.store'), $checkoutData)->assertRedirect();

        $this->assertSame(1, Order::count());
        $this->assertSame(1, StockMovement::where('movement_type', 'reservation')->count());
    }

    public function test_fifo_allocation_uses_oldest_batch_and_reduces_stock(): void
    {
        [$product, $unit] = $this->createSellableProduct(10);
        $oldest = InventoryBatch::create(['product_id' => $product->id, 'received_at' => now()->subDays(2), 'original_quantity' => 4, 'remaining_quantity' => 4, 'unit_cost' => 100, 'status' => 'open']);
        $newest = InventoryBatch::create(['product_id' => $product->id, 'received_at' => now()->subDay(), 'original_quantity' => 6, 'remaining_quantity' => 6, 'unit_cost' => 200, 'status' => 'open']);
        session(['cart' => [(string) $unit->id => 5]]);

        $this->post(route('checkout.store'), [
            'name' => 'FIFO Customer', 'phone' => '081234567892', 'address' => 'Alamat FIFO', 'payment_method' => 'bank_transfer',
        ])->assertRedirect();

        $this->assertSame(0.0, (float) InventoryBatch::find($oldest->id)->remaining_quantity);
        $this->assertSame(5.0, (float) InventoryBatch::find($newest->id)->remaining_quantity);
        $this->assertDatabaseHas('sale_batch_allocations', ['inventory_batch_id' => $oldest->id, 'quantity' => 4]);
        $this->assertDatabaseHas('sale_batch_allocations', ['inventory_batch_id' => $newest->id, 'quantity' => 1]);
        $this->assertDatabaseHas('stock_movements', ['movement_type' => 'reservation', 'base_quantity' => 5]);
    }

    private function createSellableProduct(float $stock = 10): array
    {
        $product = Product::create(['legacy_code' => 'TEST-' . uniqid(), 'name' => 'Produk Test', 'current_stock' => $stock, 'initial_stock' => $stock, 'minimum_stock' => 0, 'status' => 'active']);
        $unit = ProductUnit::create(['product_id' => $product->id, 'unit_level' => 1, 'unit_name' => 'PCS', 'conversion_to_base' => 1, 'cost_price' => 100, 'default_selling_price' => 500]);
        ProductPrice::create(['product_unit_id' => $unit->id, 'tier' => 1, 'minimum_quantity' => 1, 'selling_price' => 500]);

        return [$product, $unit];
    }

    private function createBusinessSchema(): void
    {
        if (Schema::hasTable('products')) {
            return;
        }

        Schema::create('customers', function (Blueprint $table): void { $table->id(); $table->string('name', 150); $table->string('phone', 30); $table->string('email', 190)->nullable(); $table->text('address')->nullable(); $table->string('city', 100)->nullable(); $table->string('status', 20)->default('active'); $table->timestamps(); });
        Schema::create('products', function (Blueprint $table): void { $table->id(); $table->string('legacy_code', 100); $table->string('name', 200); $table->decimal('initial_stock', 18, 3); $table->decimal('minimum_stock', 18, 3); $table->decimal('current_stock', 18, 3); $table->string('status', 20)->default('active'); $table->timestamps(); });
        Schema::create('product_units', function (Blueprint $table): void { $table->id(); $table->unsignedBigInteger('product_id'); $table->string('unit_name', 60); $table->unsignedInteger('unit_level'); $table->decimal('conversion_to_base', 18, 3); $table->decimal('cost_price', 18, 2); $table->decimal('default_selling_price', 18, 2); $table->timestamps(); });
        Schema::create('product_prices', function (Blueprint $table): void { $table->id(); $table->unsignedBigInteger('product_unit_id'); $table->unsignedInteger('tier'); $table->unsignedInteger('minimum_quantity'); $table->decimal('selling_price', 18, 2); $table->timestamps(); });
        Schema::create('orders', function (Blueprint $table): void { $table->id(); $table->string('order_number', 60); $table->string('tracking_token', 64); $table->uuid('checkout_token')->nullable()->unique(); $table->unsignedBigInteger('customer_id'); $table->dateTime('order_date'); $table->decimal('subtotal', 18, 2); $table->decimal('discount', 18, 2); $table->decimal('shipping_cost', 18, 2); $table->decimal('total', 18, 2); $table->string('order_status', 30); $table->string('payment_method', 30); $table->string('payment_status', 30); $table->string('stock_status', 30); $table->text('delivery_address'); $table->string('delivery_zone', 30)->nullable(); $table->decimal('delivery_distance_km', 8, 2)->nullable(); $table->text('customer_note')->nullable(); $table->text('admin_note')->nullable(); $table->timestamps(); });
        Schema::create('order_items', function (Blueprint $table): void { $table->id(); $table->unsignedBigInteger('order_id'); $table->unsignedBigInteger('product_id'); $table->unsignedBigInteger('product_unit_id'); $table->string('product_name', 200); $table->string('unit_name', 60); $table->decimal('quantity', 18, 3); $table->decimal('base_quantity', 18, 3); $table->decimal('selling_price', 18, 2); $table->decimal('subtotal', 18, 2); $table->decimal('hpp', 18, 2); $table->decimal('total_hpp', 18, 2); $table->decimal('profit', 18, 2); $table->timestamp('created_at')->nullable(); });
        Schema::create('payments', function (Blueprint $table): void { $table->id(); $table->unsignedBigInteger('order_id'); $table->string('payment_method', 30); $table->decimal('amount', 18, 2); $table->string('status', 30); $table->string('transaction_reference', 150)->nullable(); $table->dateTime('paid_at')->nullable(); $table->text('notes')->nullable(); $table->timestamps(); });
        Schema::create('inventory_batches', function (Blueprint $table): void { $table->id(); $table->unsignedBigInteger('product_id'); $table->dateTime('received_at'); $table->decimal('original_quantity', 18, 3); $table->decimal('remaining_quantity', 18, 3); $table->decimal('unit_cost', 18, 2); $table->string('status', 20); $table->timestamps(); });
        Schema::create('sale_batch_allocations', function (Blueprint $table): void { $table->id(); $table->unsignedBigInteger('order_item_id'); $table->unsignedBigInteger('inventory_batch_id'); $table->decimal('quantity', 18, 3); $table->decimal('unit_cost', 18, 2); $table->decimal('total_cost', 18, 2); $table->timestamp('created_at')->nullable(); });
        Schema::create('stock_movements', function (Blueprint $table): void { $table->id(); $table->unsignedBigInteger('product_id'); $table->unsignedBigInteger('product_unit_id')->nullable(); $table->string('movement_type', 30); $table->decimal('quantity', 18, 3); $table->decimal('base_quantity', 18, 3); $table->decimal('stock_before', 18, 3); $table->decimal('stock_after', 18, 3); $table->string('reference_type', 50)->nullable(); $table->unsignedBigInteger('reference_id')->nullable(); $table->text('notes')->nullable(); $table->unsignedBigInteger('created_by')->nullable(); $table->timestamp('created_at')->nullable(); });
        Schema::create('order_activities', function (Blueprint $table): void { $table->id(); $table->unsignedBigInteger('order_id'); $table->unsignedBigInteger('payment_id')->nullable(); $table->string('event', 50); $table->string('from_status', 30)->nullable(); $table->string('to_status', 30)->nullable(); $table->string('actor_type', 30)->nullable(); $table->unsignedBigInteger('actor_id')->nullable(); $table->text('description')->nullable(); $table->json('metadata')->nullable(); $table->timestamps(); });
    }
}
