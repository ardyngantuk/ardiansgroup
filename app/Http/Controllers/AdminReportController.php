<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AdminReportController extends Controller
{
    private const REPORTS = [
        'laporan-penjualan' => ['title' => 'Laporan Penjualan', 'description' => 'Pantau omzet, order, dan produk yang terjual.'],
        'laporan-profit' => ['title' => 'Laporan Profit', 'description' => 'Bandingkan omzet dengan HPP FIFO untuk melihat laba kotor.'],
        'laporan-inventory' => ['title' => 'Laporan Inventory', 'description' => 'Pantau nilai stok, stok minimum, dan pergerakan barang.'],
        'laporan-pembelian' => ['title' => 'Laporan Pembelian', 'description' => 'Pantau pembelian barang dan pemasok dalam periode pilihan.'],
        'laporan-keuangan' => ['title' => 'Laporan Keuangan', 'description' => 'Ringkas arus pemasukan, pembelian, pengeluaran, dan saldo operasional.'],
    ];

    public function show(Request $request, string $report): View
    {
        abort_unless(isset(self::REPORTS[$report]), 404);
        [$from, $to] = $this->period($request);
        $data = match ($report) {
            'laporan-penjualan' => $this->sales($from, $to),
            'laporan-profit' => $this->profit($from, $to),
            'laporan-inventory' => $this->inventory($from, $to),
            'laporan-pembelian' => $this->purchases($from, $to),
            'laporan-keuangan' => $this->finance($from, $to),
        };

        return view('admin.reports.show', [
            'report' => $report,
            'config' => self::REPORTS[$report],
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            ...$data,
        ]);
    }

    private function period(Request $request): array
    {
        $to = Carbon::parse($request->input('to', now()->toDateString()))->endOfDay();
        $from = Carbon::parse($request->input('from', $to->copy()->subDays(29)->toDateString()))->startOfDay();

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $to];
    }

    private function sales(Carbon $from, Carbon $to): array
    {
        $orders = Order::with('customer')->whereBetween('order_date', [$from, $to])->where('order_status', '!=', 'cancelled')->latest('order_date')->get();
        $paidOrders = $orders->where('payment_status', 'paid');
        $daily = collect(range(0, min(29, $from->diffInDays($to))))->map(function (int $offset) use ($from, $orders): array {
            $date = $from->copy()->addDays($offset);
            $dayOrders = $orders->filter(fn ($order) => $order->order_date->isSameDay($date));
            return ['label' => $date->format('d M'), 'orders' => $dayOrders->count(), 'revenue' => (float) $dayOrders->where('payment_status', 'paid')->sum('total')];
        });

        return ['kpis' => [['label' => 'Omzet terbayar', 'value' => $this->money($paidOrders->sum('total')), 'note' => $paidOrders->count() . ' order paid'], ['label' => 'Total order', 'value' => number_format($orders->count(), 0, ',', '.'), 'note' => $orders->where('payment_status', 'pending')->count() . ' masih pending'], ['label' => 'Nilai rata-rata', 'value' => $paidOrders->count() ? $this->money($paidOrders->avg('total')) : 'Rp 0', 'note' => 'per order paid']], 'daily' => $daily, 'rows' => $orders, 'rowType' => 'sales'];
    }

    private function profit(Carbon $from, Carbon $to): array
    {
        $items = OrderItem::with('order')->whereHas('order', fn ($query) => $query->whereBetween('order_date', [$from, $to])->where('payment_status', 'paid')->where('order_status', '!=', 'cancelled'))->get();
        $revenue = (float) $items->sum('subtotal');
        $hpp = (float) $items->sum('total_hpp');
        $profit = (float) $items->sum('profit');
        $top = $items->groupBy('product_name')->map(fn ($group) => ['name' => $group->first()->product_name, 'revenue' => (float) $group->sum('subtotal'), 'profit' => (float) $group->sum('profit')])->sortByDesc('revenue')->take(8);

        return ['kpis' => [['label' => 'Omzet', 'value' => $this->money($revenue), 'note' => 'order paid'], ['label' => 'HPP FIFO', 'value' => $this->money($hpp), 'note' => 'modal teralokasi'], ['label' => 'Laba kotor', 'value' => $this->money($profit), 'note' => $revenue > 0 ? number_format(($profit / $revenue) * 100, 1, ',', '.') . '% margin' : '0% margin']], 'daily' => collect(), 'rows' => $top, 'rowType' => 'profit'];
    }

    private function inventory(Carbon $from, Carbon $to): array
    {
        $products = Product::query()->with('units')->where('status', 'active')->orderByRaw('CASE WHEN current_stock <= 0 THEN 0 WHEN current_stock <= minimum_stock THEN 1 ELSE 2 END')->orderBy('name')->get();
        $movements = StockMovement::with('product')->whereBetween('created_at', [$from, $to])->latest('created_at')->limit(20)->get();
        $value = (float) $products->sum(fn ($product) => (float) $product->current_stock * (float) ($product->units->first()?->cost_price ?? 0));

        return ['kpis' => [['label' => 'Produk aktif', 'value' => number_format($products->count(), 0, ',', '.'), 'note' => 'dalam katalog'], ['label' => 'Stok kritis', 'value' => number_format($products->filter(fn ($product) => (float) $product->current_stock <= (float) $product->minimum_stock)->count(), 0, ',', '.'), 'note' => 'perlu perhatian'], ['label' => 'Nilai stok', 'value' => $this->money($value), 'note' => 'estimasi dari harga modal']], 'daily' => collect(), 'rows' => $products, 'movements' => $movements, 'rowType' => 'inventory'];
    }

    private function purchases(Carbon $from, Carbon $to): array
    {
        $purchases = Purchase::with('supplier')->whereBetween('purchase_date', [$from, $to])->latest('purchase_date')->get();
        return ['kpis' => [['label' => 'Total pembelian', 'value' => $this->money($purchases->sum('total')), 'note' => $purchases->count() . ' transaksi'], ['label' => 'Sudah dibayar', 'value' => $this->money($purchases->where('payment_status', 'paid')->sum('total')), 'note' => 'pembayaran supplier'], ['label' => 'Belum dibayar', 'value' => $this->money($purchases->where('payment_status', '!=', 'paid')->sum('total')), 'note' => 'perlu ditindaklanjuti']], 'daily' => collect(), 'rows' => $purchases, 'rowType' => 'purchases'];
    }

    private function finance(Carbon $from, Carbon $to): array
    {
        $revenue = (float) Order::whereBetween('order_date', [$from, $to])->where('payment_status', 'paid')->where('order_status', '!=', 'cancelled')->sum('total');
        $hpp = (float) OrderItem::whereHas('order', fn ($query) => $query->whereBetween('order_date', [$from, $to])->where('payment_status', 'paid')->where('order_status', '!=', 'cancelled'))->sum('total_hpp');
        $expenses = (float) Expense::whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])->sum('amount');
        $grossProfit = $revenue - $hpp;
        $netProfit = $grossProfit - $expenses;

        return ['kpis' => [['label' => 'Omzet', 'value' => $this->money($revenue), 'note' => 'order terbayar'], ['label' => 'HPP', 'value' => $this->money($hpp), 'note' => 'modal FIFO teralokasi'], ['label' => 'Laba kotor', 'value' => $this->money($grossProfit), 'note' => 'omzet dikurangi HPP'], ['label' => 'Pengeluaran', 'value' => $this->money($expenses), 'note' => 'biaya operasional'], ['label' => 'Laba bersih', 'value' => $this->money($netProfit), 'note' => 'laba kotor dikurangi pengeluaran']], 'daily' => collect(), 'rows' => collect(), 'rowType' => 'finance'];
    }

    private function money(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
