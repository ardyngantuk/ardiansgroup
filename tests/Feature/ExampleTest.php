<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_product_catalog_filters_by_category(): void
    {
        Schema::create('categories', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('brands', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('products', function ($table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->string('name');
            $table->string('status')->default('active');
            $table->decimal('current_stock', 10, 3)->default(0);
            $table->timestamps();
        });

        Schema::create('product_units', function ($table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('unit_name');
            $table->integer('unit_level')->default(1);
            $table->timestamps();
        });

        Schema::create('product_prices', function ($table) {
            $table->id();
            $table->unsignedBigInteger('product_unit_id');
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->decimal('minimum_quantity', 10, 3)->default(1);
            $table->timestamps();
        });

        Schema::create('product_images', function ($table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('image_path')->nullable();
            $table->boolean('is_thumbnail')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        $berasId = DB::table('categories')->insertGetId([
            'name' => 'Beras',
            'slug' => 'beras',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $minyakId = DB::table('categories')->insertGetId([
            'name' => 'Minyak',
            'slug' => 'minyak',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('products')->insert([
            [
                'category_id' => $berasId,
                'brand_id' => 1,
                'name' => 'Beras Premium 5kg',
                'status' => 'active',
                'current_stock' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => $minyakId,
                'brand_id' => 1,
                'name' => 'Minyak Goreng 2L',
                'status' => 'active',
                'current_stock' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('brands')->insert([
            ['name' => 'Brand Demo', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $productUnits = [
            ['product_id' => 1, 'unit_name' => 'Karung', 'unit_level' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 2, 'unit_name' => 'Botol', 'unit_level' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('product_units')->insert($productUnits);

        DB::table('product_prices')->insert([
            ['product_unit_id' => 1, 'selling_price' => 250000, 'minimum_quantity' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['product_unit_id' => 2, 'selling_price' => 18000, 'minimum_quantity' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('product_images')->insert([
            ['product_id' => 1, 'image_path' => 'sample/beras.jpg', 'is_thumbnail' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 2, 'image_path' => 'sample/minyak.jpg', 'is_thumbnail' => true, 'sort_order' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $response = $this->get('/produk?category=beras');

        $response->assertOk();
        $response->assertSee('Beras Premium 5kg');
        $response->assertDontSee('Minyak Goreng 2L');
    }
}
