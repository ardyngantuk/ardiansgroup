<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Expense;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function module(string $module): View|RedirectResponse
    {
        $modules = [
            'pembayaran' => ['section' => 'Penjualan', 'title' => 'Pembayaran'],
            'pelanggan' => ['section' => 'Penjualan', 'title' => 'Pelanggan'],
            'kategori' => ['section' => 'Produk', 'title' => 'Kategori'],
            'brand' => ['section' => 'Produk', 'title' => 'Brand'],
            'satuan' => ['section' => 'Produk', 'title' => 'Satuan'],
            'barcode' => ['section' => 'Produk', 'title' => 'Barcode'],
            'stok' => ['section' => 'Inventory', 'title' => 'Stok'],
            'mutasi-stok' => ['section' => 'Inventory', 'title' => 'Mutasi Stok'],
            'batch-fifo' => ['section' => 'Inventory', 'title' => 'Batch FIFO'],
            'stok-minimum' => ['section' => 'Inventory', 'title' => 'Stok Minimum'],
            'pembelian' => ['section' => 'Pembelian', 'title' => 'Pembelian'],
            'supplier' => ['section' => 'Pembelian', 'title' => 'Supplier'],
            'hpp' => ['section' => 'Keuangan', 'title' => 'HPP'],
            'pengeluaran' => ['section' => 'Keuangan', 'title' => 'Pengeluaran'],
            'keuangan' => ['section' => 'Keuangan', 'title' => 'Keuangan'],
            'laporan-penjualan' => ['section' => 'Laporan', 'title' => 'Laporan Penjualan'],
            'laporan-profit' => ['section' => 'Laporan', 'title' => 'Laporan Profit'],
            'laporan-inventory' => ['section' => 'Laporan', 'title' => 'Laporan Inventory'],
            'laporan-pembelian' => ['section' => 'Laporan', 'title' => 'Laporan Pembelian'],
            'laporan-keuangan' => ['section' => 'Laporan', 'title' => 'Laporan Keuangan'],
            'pengaturan' => ['section' => 'Sistem', 'title' => 'Pengaturan'],
        ];

        if (in_array($module, ['kategori', 'brand', 'satuan'], true)) {
            return redirect()->route('admin.master.index', $module);
        }

        if ($module === 'supplier') {
            return redirect()->route('admin.suppliers.index');
        }

        if ($module === 'pembayaran') {
            return redirect()->route('admin.payments.index');
        }

        if ($module === 'pengeluaran') {
            return redirect()->route('admin.expenses.index');
        }

        if ($module === 'pengaturan') {
            return redirect()->route('admin.settings.index');
        }

        if (in_array($module, ['stok', 'stok-minimum', 'mutasi-stok', 'batch-fifo'], true)) {
            return redirect()->route('admin.stock.index', ['status' => $module === 'stok-minimum' ? 'low' : null]);
        }

        if (str_starts_with($module, 'laporan-')) {
            return redirect()->route('admin.reports.show', $module);
        }

        abort_unless(isset($modules[$module]), 404);

        return view('admin.module', $modules[$module]);
    }

    public function index(): View
    {
        $today = now();
        $weekStart = $today->copy()->subDays(6)->startOfDay();
        $monthStart = $today->copy()->startOfMonth();
        $paidOrderScope = fn ($query) => $query->where('payment_status', 'paid')->where('order_status', '!=', 'cancelled');
        $weekOrders = Order::query()
            ->whereBetween('order_date', [$weekStart, $today])
            ->where('order_status', '!=', 'cancelled')
            ->get(['order_date', 'total', 'payment_status']);
        $statusCounts = Order::query()->selectRaw('order_status, COUNT(*) as total')->groupBy('order_status')->pluck('total', 'order_status');
        $topProducts = OrderItem::query()
            ->selectRaw('product_id, product_name, SUM(quantity) as total_quantity, SUM(subtotal) as revenue')
            ->whereHas('order', $paidOrderScope)
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();
        $dailySales = collect(range(6, 0))->map(function (int $daysAgo) use ($today, $weekOrders): array {
            $date = $today->copy()->subDays($daysAgo);
            $orders = $weekOrders->filter(fn ($order) => $order->order_date->isSameDay($date));

            return [
                'label' => $date->translatedFormat('D'),
                'date' => $date->format('d M'),
                'orders' => $orders->count(),
                'revenue' => (float) $orders->where('payment_status', 'paid')->sum('total'),
            ];
        });

        return view('admin.dashboard', [
            'products' => Product::count(),
            'activeProducts' => Product::where('status', 'active')->count(),
            'orders' => Order::count(),
            'pendingOrders' => Order::whereIn('order_status', ['pending', 'confirmed', 'processing'])->count(),
            'customers' => \App\Models\Customer::count(),
            'team' => User::where('status', 'active')->count(),
            'recentOrders' => Order::with('customer')->latest('order_date')->limit(8)->get(),
            'monthlyRevenue' => Order::where($paidOrderScope)->where('order_date', '>=', $monthStart)->sum('total'),
            'monthlyOrders' => Order::where('order_date', '>=', $monthStart)->where('order_status', '!=', 'cancelled')->count(),
            'monthlyExpenses' => Expense::whereBetween('expense_date', [$monthStart->toDateString(), $today->toDateString()])->sum('amount'),
            'pendingPayments' => Order::where('payment_status', 'pending')->where('order_status', '!=', 'cancelled')->count(),
            'lowStockProducts' => Product::where('status', 'active')->whereColumn('current_stock', '<=', 'minimum_stock')->count(),
            'outOfStockProducts' => Product::where('status', 'active')->where('current_stock', '<=', 0)->count(),
            'statusCounts' => $statusCounts,
            'dailySales' => $dailySales,
            'topProducts' => $topProducts,
        ]);
    }

    public function newOrders(Request $request): JsonResponse
    {
        $since = max(0, (int) $request->integer('since'));
        $orders = Order::with('customer')
            ->where('id', '>', $since)
            ->where('order_status', '!=', 'cancelled')
            ->orderBy('id')
            ->limit(20)
            ->get();

        return response()->json([
            'orders' => $orders->map(fn (Order $order): array => [
                'id' => $order->id,
                'number' => $order->order_number,
                'customer' => $order->customer?->name ?? 'Pelanggan',
                'total' => (float) $order->total,
                'url' => route('admin.orders.index'),
            ])->values(),
        ]);
    }
}