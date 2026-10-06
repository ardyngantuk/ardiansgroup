<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('tracking_token', 64)->nullable()->after('order_number');
            $table->unique('tracking_token', 'uq_orders_tracking_token');
        });

        DB::table('orders')->whereNull('tracking_token')->orderBy('id')->eachById(function ($order): void {
            do {
                $token = bin2hex(random_bytes(32));
            } while (DB::table('orders')->where('tracking_token', $token)->exists());

            DB::table('orders')->where('id', $order->id)->update(['tracking_token' => $token]);
        });

        DB::statement('ALTER TABLE orders MODIFY tracking_token VARCHAR(64) NOT NULL');
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropUnique('uq_orders_tracking_token');
            $table->dropColumn('tracking_token');
        });
    }
};
