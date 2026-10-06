<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'stock_status')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->enum('stock_status', ['reserved', 'sold', 'released'])->default('reserved')->after('payment_status');
                $table->index('stock_status', 'idx_orders_stock_status');
            });
        }

        DB::statement("UPDATE orders SET stock_status = CASE WHEN payment_status = 'paid' AND order_status IN ('shipping', 'completed') THEN 'sold' ELSE 'reserved' END");

        DB::statement("ALTER TABLE stock_movements MODIFY movement_type ENUM('purchase','sale','reservation','adjustment_in','adjustment_out','return_sale','return_purchase','damaged','initial') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE stock_movements MODIFY movement_type ENUM('purchase','sale','adjustment_in','adjustment_out','return_sale','return_purchase','damaged','initial') NOT NULL");

        Schema::table('orders', function (Blueprint $table): void {
            $table->dropIndex('idx_orders_stock_status');
            $table->dropColumn('stock_status');
        });
    }
};
