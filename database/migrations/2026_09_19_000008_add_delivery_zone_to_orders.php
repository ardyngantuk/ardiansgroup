<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'delivery_zone')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->string('delivery_zone', 30)->nullable()->after('delivery_address');
                $table->decimal('delivery_distance_km', 8, 2)->nullable()->after('delivery_zone');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'delivery_zone')) {
            Schema::table('orders', function (Blueprint $table): void {
                $table->dropColumn(['delivery_zone', 'delivery_distance_km']);
            });
        }
    }
};
