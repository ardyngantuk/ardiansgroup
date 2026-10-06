<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Tests\TestCase;

class AdminExpenseFeatureTest extends TestCase
{
    public function test_guest_cannot_access_expenses(): void
    {
        $this->get(route('admin.expenses.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_expense_without_inventory_side_effects(): void
    {
        $admin = User::create([
            'name' => 'Expense Admin',
            'email' => 'expense-admin-' . uniqid() . '@example.test',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);
        $stockMovements = \DB::table('stock_movements')->count();
        $purchases = \DB::table('purchases')->count();
        $batches = \DB::table('inventory_batches')->count();

        $this->actingAs($admin)->post(route('admin.expenses.store'), [
            'expense_date' => now()->toDateString(),
            'category' => 'Listrik',
            'amount' => '500000',
            'payment_method' => 'cash',
            'description' => 'Listrik toko',
            'notes' => 'Pembayaran bulanan',
        ])->assertRedirect(route('admin.expenses.index'));

        $expense = Expense::query()->where('user_id', $admin->id)->latest('id')->firstOrFail();
        $this->assertSame('Listrik', $expense->category);
        $this->assertSame(500000.0, (float) $expense->amount);
        $this->assertSame($stockMovements, \DB::table('stock_movements')->count());
        $this->assertSame($purchases, \DB::table('purchases')->count());
        $this->assertSame($batches, \DB::table('inventory_batches')->count());
    }

    public function test_expense_rejects_non_positive_amount(): void
    {
        $admin = User::create([
            'name' => 'Validation Admin',
            'email' => 'validation-admin-' . uniqid() . '@example.test',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)->from(route('admin.expenses.create'))->post(route('admin.expenses.store'), [
            'expense_date' => now()->toDateString(),
            'category' => 'Operasional',
            'amount' => '-1',
            'payment_method' => 'cash',
        ])->assertSessionHasErrors('amount');
    }
}
