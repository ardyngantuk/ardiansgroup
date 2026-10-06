<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminExpenseController extends Controller
{
    public const CATEGORIES = [
        'Operasional',
        'Listrik',
        'Internet',
        'Transportasi',
        'Gaji',
        'ATK',
        'Perawatan',
        'Sewa',
        'Marketing',
        'Administrasi',
        'Lainnya',
    ];

    public const PAYMENT_METHODS = [
        'cash' => 'Kas tunai',
        'bank_transfer' => 'Transfer bank',
        'e_wallet' => 'E-wallet',
    ];

    public function index(Request $request): View
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'category' => ['nullable', 'string', 'in:' . implode(',', self::CATEGORIES)],
            'payment_method' => ['nullable', 'string', 'in:' . implode(',', array_keys(self::PAYMENT_METHODS))],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Expense::with('creator')->latest('expense_date')->latest('id');
        $this->applyFilters($query, $request);

        $monthStart = now()->startOfMonth()->toDateString();
        $monthEnd = now()->toDateString();
        $monthExpenses = Expense::whereBetween('expense_date', [$monthStart, $monthEnd]);

        return view('admin.expenses.index', [
            'expenses' => $query->paginate(15)->withQueryString(),
            'categories' => self::CATEGORIES,
            'paymentMethods' => self::PAYMENT_METHODS,
            'filters' => $request->only(['from', 'to', 'category', 'payment_method', 'q']),
            'monthlyTotal' => (float) (clone $monthExpenses)->sum('amount'),
            'monthlyCount' => (clone $monthExpenses)->count(),
            'monthlyLargest' => (float) ((clone $monthExpenses)->max('amount') ?? 0),
        ]);
    }

    public function create(): View
    {
        return view('admin.expenses.form', [
            'expense' => new Expense(['expense_date' => now()->toDateString(), 'payment_method' => 'cash']),
            'categories' => self::CATEGORIES,
            'paymentMethods' => self::PAYMENT_METHODS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['user_id'] = $request->user()->id;
        $data['expense_number'] = $this->nextExpenseNumber();

        DB::transaction(fn () => Expense::create($data));

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function show(Expense $expense): View
    {
        $expense->load('creator');

        return view('admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense): View
    {
        return view('admin.expenses.form', [
            'expense' => $expense,
            'categories' => self::CATEGORIES,
            'paymentMethods' => self::PAYMENT_METHODS,
        ]);
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $data = $this->validatedData($request);

        DB::transaction(fn () => $expense->update($data));

        return redirect()->route('admin.expenses.show', $expense)->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        DB::transaction(fn () => $expense->delete());

        return redirect()->route('admin.expenses.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    private function applyFilters($query, Request $request): void
    {
        $query
            ->when($request->filled('from'), fn ($builder) => $builder->whereDate('expense_date', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($builder) => $builder->whereDate('expense_date', '<=', $request->date('to')))
            ->when($request->filled('category'), fn ($builder) => $builder->where('category', $request->string('category')))
            ->when($request->filled('payment_method'), fn ($builder) => $builder->where('payment_method', $request->string('payment_method')))
            ->when($request->filled('q'), fn ($builder) => $builder->where(function ($search) use ($request): void {
                $term = '%' . $request->string('q') . '%';
                $search->where('expense_number', 'like', $term)->orWhere('description', 'like', $term);
            }));
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'expense_date' => ['required', 'date_format:Y-m-d'],
            'category' => ['required', 'string', 'in:' . implode(',', self::CATEGORIES)],
            'amount' => ['required', 'numeric', 'decimal:0,2', 'gt:0'],
            'payment_method' => ['required', 'string', 'in:' . implode(',', array_keys(self::PAYMENT_METHODS))],
            'description' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    private function nextExpenseNumber(): string
    {
        return 'EXP-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));
    }
}
