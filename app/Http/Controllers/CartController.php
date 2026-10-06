<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\InventoryBatch;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderActivity;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\SaleBatchAllocation;
use App\Models\StockMovement;
use App\Models\Setting;
use App\Services\ShippingCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private ShippingCalculator $shippingCalculator)
    {
    }

    public function index(Request $request): View
    {
        $cart = $this->cartDetails($request);

        return view('cart.index', $cart);
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_unit_id' => ['required', 'integer', 'exists:product_units,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $unit = ProductUnit::with('product')->findOrFail($data['product_unit_id']);

        abort_unless($unit->product?->status === 'active', 404);
        abort_unless($unit->prices()->exists(), 422, 'Harga produk belum tersedia.');

        $cart = $request->session()->get('cart', []);
        $newQuantity = ($cart[(string) $unit->id] ?? 0) + (int) $data['quantity'];
        if ($newQuantity > $this->availableQuantity($unit)) {
            return back()->withInput()->with('error', 'Jumlah melebihi stok tersedia untuk satuan ' . $unit->unit_name . '.');
        }

        $key = (string) $unit->id;
        $cart[$key] = ($cart[$key] ?? 0) + (int) $data['quantity'];
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, ProductUnit $unit): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ((int) $data['quantity'] > $this->availableQuantity($unit->load('product'))) {
            return back()->withInput()->with('error', 'Jumlah melebihi stok tersedia untuk satuan ' . $unit->unit_name . '.');
        }

        $cart = $request->session()->get('cart', []);
        $cart[(string) $unit->id] = (int) $data['quantity'];
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Jumlah keranjang diperbarui.');
    }

    public function remove(Request $request, ProductUnit $unit): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[(string) $unit->id]);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        if (! Setting::getValue('order.guest_checkout', true) && ! $request->user()) {
            return redirect()->route('cart.index')->with('error', 'Checkout guest sedang dinonaktifkan oleh admin.');
        }

        $cart = $this->cartDetails($request);

        if ($cart['items']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        foreach ($cart['items'] as $item) {
            if ($item['quantity'] > $this->availableQuantity($item['unit'])) {
                return redirect()->route('cart.index')->with('error', 'Stok ' . $item['unit']->product->name . ' berubah. Periksa jumlah keranjang Anda.');
            }
        }

        $minimumOrder = (float) Setting::getValue('order.minimum_order', 0);
        if ($minimumOrder > 0 && $cart['subtotal'] < $minimumOrder) {
            return redirect()->route('cart.index')->with('error', 'Minimum pembelian adalah Rp ' . number_format($minimumOrder, 0, ',', '.') . '.');
        }

        $shippingZones = $this->shippingCalculator->zones();
        $freeShippingMinimum = (float) Setting::getValue('shipping.free_minimum', config('shipping.free_shipping_minimum_order', 150000));

        return view('cart.checkout', [...$cart, 'shippingZones' => $shippingZones, 'freeShippingMinimum' => $freeShippingMinimum]);
    }

    public function store(Request $request): RedirectResponse
    {
        $paymentMethods = ['bank_transfer', 'qris'];
        if (Setting::getValue('payment.cod_enabled', true)) {
            $paymentMethods[] = 'cod';
        }
        if (! Setting::getValue('payment.manual_transfer_enabled', true)) {
            $paymentMethods = array_values(array_diff($paymentMethods, ['bank_transfer', 'qris']));
        }

        if ($paymentMethods === []) {
            throw ValidationException::withMessages(['payment_method' => 'Belum ada metode pembayaran yang aktif.']);
        }

        $shippingZones = array_keys($this->shippingCalculator->zones());
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:190'],
            'address' => ['required', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'delivery_zone' => ['nullable', 'string', Rule::in($shippingZones)],
            'payment_method' => ['required', Rule::in($paymentMethods)],
            'customer_note' => ['nullable', 'string'],
        ]);

        $cart = $this->cartDetails($request);

        if ($cart['items']->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $minimumOrder = (float) Setting::getValue('order.minimum_order', 0);
        if ($minimumOrder > 0 && $cart['subtotal'] < $minimumOrder) {
            throw ValidationException::withMessages(['payment_method' => 'Minimum pembelian adalah Rp ' . number_format($minimumOrder, 0, ',', '.') . '.']);
        }

        $deliveryZone = $data['delivery_zone'] ?? 'near';
        if ($data['payment_method'] === 'cod' && $cart['subtotal'] < $this->shippingCalculator->codMinimumOrder()) {
            throw ValidationException::withMessages([
                'payment_method' => 'Minimal pembelian untuk COD adalah Rp ' . number_format($this->shippingCalculator->codMinimumOrder(), 0, ',', '.') . '.',
            ]);
        }

        $shipping = $this->shippingCalculator->calculate((float) $cart['subtotal'], $deliveryZone);

        $order = DB::transaction(function () use ($data, $cart, $shipping) {
            $customer = Customer::firstOrNew(['phone' => $data['phone']]);
            $customer->fill([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'address' => $data['address'],
                'city' => $data['city'] ?? null,
                'status' => 'active',
            ])->save();

            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'tracking_token' => Str::random(64),
                'customer_id' => $customer->id,
                'order_date' => now(),
                'subtotal' => $cart['subtotal'],
                'discount' => 0,
                'shipping_cost' => $shipping['cost'],
                'total' => (float) $cart['subtotal'] + $shipping['cost'],
                'order_status' => 'pending',
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'stock_status' => 'reserved',
                'delivery_address' => $data['address'],
                'delivery_zone' => $shipping['key'],
                'delivery_distance_km' => $shipping['distance_km'],
                'customer_note' => $data['customer_note'] ?? null,
            ]);

            foreach ($cart['items'] as $item) {
                $unit = ProductUnit::with('product')->findOrFail($item['unit']->id);
                $product = $unit->product()->lockForUpdate()->firstOrFail();
                $baseQuantity = (float) $item['quantity'] * (float) $unit->conversion_to_base;
                $stockBefore = (float) $product->current_stock;

                if ($baseQuantity > $stockBefore) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Stok ' . $product->name . ' tidak mencukupi. Silakan perbarui keranjang.',
                    ]);
                }

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_unit_id' => $unit->id,
                    'product_name' => $product->name,
                    'unit_name' => $unit->unit_name,
                    'quantity' => $item['quantity'],
                    'base_quantity' => $baseQuantity,
                    'selling_price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'hpp' => 0,
                    'total_hpp' => 0,
                    'profit' => 0,
                ]);

                $totalHpp = $this->allocateFifo($product, $unit, $orderItem, $baseQuantity);
                $hpp = $baseQuantity > 0 ? $totalHpp / $baseQuantity : 0;
                $orderItem->update([
                    'hpp' => $hpp,
                    'total_hpp' => $totalHpp,
                    'profit' => (float) $item['subtotal'] - $totalHpp,
                ]);

                $product->update(['current_stock' => $stockBefore - $baseQuantity]);
                StockMovement::create([
                    'product_id' => $product->id,
                    'product_unit_id' => $unit->id,
                    'movement_type' => 'reservation',
                    'quantity' => $item['quantity'],
                    'base_quantity' => $baseQuantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockBefore - $baseQuantity,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'notes' => 'Reservasi stok untuk order ' . $order->order_number,
                ]);
            }

            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $data['payment_method'],
                'amount' => $order->total,
                'status' => 'pending',
                'notes' => $data['payment_method'] === 'cod'
                    ? 'COD - pembayaran belum diterima.'
                    : 'Menunggu pembayaran customer.',
            ]);

            OrderActivity::create([
                'order_id' => $order->id,
                'event' => 'order_created',
                'to_status' => 'pending',
                'actor_type' => 'customer',
                'description' => 'Order dibuat melalui checkout customer.',
            ]);

            return $order;
        });

        $request->session()->forget('cart');

        return redirect()->route('customer.orders.show', [$order, 'token' => $order->tracking_token])->with('success', 'Pesanan ' . $order->order_number . ' berhasil dibuat.');
    }

    private function cartDetails(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        $productRelations = ['product.category', 'prices'];
        if (Schema::hasTable('product_images')) {
            $productRelations[] = 'product.thumbnail';
        }

        $units = ProductUnit::with($productRelations)->whereIn('id', array_keys($cart))->get()->keyBy('id');
        $items = collect();
        $subtotal = 0;

        foreach ($cart as $unitId => $quantity) {
            $unit = $units->get((int) $unitId);

            if (!$unit || $unit->product?->status !== 'active' || (float) $quantity <= 0) {
                continue;
            }

            $priceRow = $unit->prices
                ->where('minimum_quantity', '<=', $quantity)
                ->sortByDesc('minimum_quantity')
                ->first() ?? $unit->prices->sortBy('minimum_quantity')->first();

            if (!$priceRow || $priceRow->selling_price === null) {
                continue;
            }

            $lineSubtotal = (float) $priceRow->selling_price * (float) $quantity;
            $subtotal += $lineSubtotal;
            $items->push([
                'unit' => $unit,
                'quantity' => (int) $quantity,
                'price' => (float) $priceRow->selling_price,
                'subtotal' => $lineSubtotal,
            ]);
        }

        return compact('items', 'subtotal');
    }

    private function availableQuantity(ProductUnit $unit): float
    {
        $conversion = (float) $unit->conversion_to_base;

        return $conversion > 0 ? max(0, (float) $unit->product?->current_stock / $conversion) : 0;
    }

    private function allocateFifo(Product $product, ProductUnit $unit, OrderItem $orderItem, float $baseQuantity): float
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
            $legacyBatch = InventoryBatch::create([
                'product_id' => $product->id,
                'received_at' => $product->created_at ?? now(),
                'original_quantity' => $legacyQuantity,
                'remaining_quantity' => $legacyQuantity,
                'unit_cost' => (float) ($unit->cost_price ?? 0),
                'status' => 'open',
            ]);
            $batches->push($legacyBatch);
        }

        $remaining = $baseQuantity;
        $totalHpp = 0;
        foreach ($batches as $batch) {
            if ($remaining <= 0) {
                break;
            }

            $allocated = min($remaining, (float) $batch->remaining_quantity);
            if ($allocated <= 0) {
                continue;
            }

            $batch->remaining_quantity = (float) $batch->remaining_quantity - $allocated;
            $batch->status = (float) $batch->remaining_quantity <= 0 ? 'depleted' : 'open';
            $batch->save();

            $unitCost = (float) $batch->unit_cost;
            SaleBatchAllocation::create([
                'order_item_id' => $orderItem->id,
                'inventory_batch_id' => $batch->id,
                'quantity' => $allocated,
                'unit_cost' => $unitCost,
                'total_cost' => $allocated * $unitCost,
            ]);

            $remaining -= $allocated;
            $totalHpp += $allocated * $unitCost;
        }

        if ($remaining > 0) {
            throw ValidationException::withMessages([
                'quantity' => 'Batch FIFO tidak mencukupi untuk ' . $product->name . '. Silakan hubungi admin.',
            ]);
        }

        return $totalHpp;
    }
}