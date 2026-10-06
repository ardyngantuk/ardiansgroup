<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Services\StorefrontSettings;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Tests\TestCase;

class AdminSettingsFeatureTest extends TestCase
{
    protected function tearDown(): void
    {
        Setting::query()->delete();
        Setting::ensureDefaults();

        parent::tearDown();
    }

    public function test_admin_can_save_store_and_checkout_settings(): void
    {
        $admin = User::create([
            'name' => 'Settings Admin',
            'email' => 'settings-' . uniqid() . '@example.test',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)->put(route('admin.settings.update'), [
            'store_name' => "ARDIAN'S TEST",
            'store_tagline' => 'Sembako untuk semua',
            'store_whatsapp' => '081234567890',
            'store_email' => 'toko@example.test',
            'store_address' => 'Alamat toko',
            'store_description' => 'Deskripsi toko',
            'store_hours' => '08.00 - 21.00',
            'guest_checkout' => '0',
            'minimum_order' => '75000',
            'cod_enabled' => '0',
            'manual_transfer_enabled' => '1',
            'bank_name' => 'Bank Test',
            'account_number' => '1234567890',
            'account_holder' => 'Ardian',
            'shipping_enabled' => '1',
            'default_shipping_cost' => '5000',
            'free_shipping_minimum' => '150000',
            'orders_notification' => '1',
            'payments_notification' => '1',
            'low_stock_notification' => '0',
        ])->assertRedirect();

        $this->assertSame("ARDIAN'S TEST", Setting::getValue('store.name'));
        $this->assertFalse(Setting::getValue('order.guest_checkout'));
        $this->assertFalse(Setting::getValue('payment.cod_enabled'));
        $this->assertSame(75000.0, Setting::getValue('order.minimum_order'));
        $this->assertSame(5000.0, Setting::getValue('shipping.default_cost'));
    }

    public function test_guest_checkout_setting_blocks_guest_checkout(): void
    {
        Setting::ensureDefaults();
        Setting::setValue('order.guest_checkout', false);

        $this->get(route('checkout'))->assertRedirect(route('cart.index'))->assertSessionHas('error');
    }

    public function test_storefront_reads_updated_store_profile_and_payment_settings(): void
    {
        Setting::setMany([
            'store.name' => 'Toko Terbaru',
            'store.tagline' => 'Belanja lebih mudah',
            'store.email' => 'baru@example.test',
            'store.whatsapp' => '628123456789',
            'store.address' => 'Alamat terbaru',
            'payment.bank_name' => 'BCA',
            'payment.account_number' => '1234567890',
            'payment.account_holder' => "ARDIAN'S",
            'payment.cod_enabled' => false,
            'payment.manual_transfer_enabled' => true,
        ]);

        $response = $this->get(route('home'));
        $response->assertOk()->assertSee('Toko Terbaru')->assertSee('baru@example.test')->assertSee('Alamat terbaru');

        $storefront = app(StorefrontSettings::class)->data();
        $this->assertFalse($storefront['payment']['cod_enabled']);
        $this->assertTrue($storefront['payment']['transfer_enabled']);
        $this->assertSame('BCA', $storefront['payment']['bank_name']);
        $this->assertSame('1234567890', $storefront['payment']['account_number']);
        $this->assertSame("ARDIAN'S", $storefront['payment']['account_holder']);
    }

    public function test_logo_path_remains_relative_and_storefront_url_has_no_dev_tunnel(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('settings/test-logo.png', 'logo');
        Setting::setValue('store.logo', 'settings/test-logo.png');

        $this->assertSame('settings/test-logo.png', Setting::getValue('store.logo'));
        $this->assertStringNotContainsString('devtunnels.ms', Setting::logoUrl());
        $this->assertStringNotContainsString('C:', Setting::logoUrl());
    }

    public function test_account_password_requires_current_password(): void
    {
        $admin = User::create([
            'name' => 'Password Admin',
            'email' => 'password-' . uniqid() . '@example.test',
            'password' => 'password',
            'role' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin)->put(route('admin.settings.password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors('current_password');
    }

    public function test_logo_url_falls_back_when_setting_is_empty_or_file_is_missing(): void
    {
        $original = Setting::getValue('store.logo');

        try {
            Setting::setValue('store.logo', null);
            $this->assertSame(asset('favicon.ico'), Setting::logoUrl());

            Setting::setValue('store.logo', 'settings/file-that-does-not-exist.png');
            $this->assertSame(asset('favicon.ico'), Setting::logoUrl());
        } finally {
            Setting::setValue('store.logo', $original);
        }
    }
}
