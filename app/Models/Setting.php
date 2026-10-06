<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type'];

    public static function defaults(): array
    {
        return [
            'store.name' => ['value' => "ARDIAN'S", 'group' => 'store', 'type' => 'string'],
            'store.tagline' => ['value' => 'Sembako Wholesale & Retail', 'group' => 'store', 'type' => 'string'],
            'store.whatsapp' => ['value' => '081234567890', 'group' => 'store', 'type' => 'string'],
            'store.email' => ['value' => 'kontak@ardians-sembako.id', 'group' => 'store', 'type' => 'string'],
            'store.address' => ['value' => 'Jl. Menali No. 12, Randudongkal, Pemalang, Jawa Tengah 52964', 'group' => 'store', 'type' => 'string'],
            'store.description' => ['value' => 'Pusat kulakan sembako dan kebutuhan pokok untuk rumah tangga dan usaha.', 'group' => 'store', 'type' => 'string'],
            'store.hours' => ['value' => 'Setiap hari, 07.00 - 21.00 WIB', 'group' => 'store', 'type' => 'string'],
            'store.logo' => ['value' => null, 'group' => 'store', 'type' => 'string'],
            'order.guest_checkout' => ['value' => '1', 'group' => 'order', 'type' => 'boolean'],
            'order.minimum_order' => ['value' => '0', 'group' => 'order', 'type' => 'decimal'],
            'payment.cod_enabled' => ['value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            'payment.manual_transfer_enabled' => ['value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            'payment.bank_name' => ['value' => '', 'group' => 'payment', 'type' => 'string'],
            'payment.account_number' => ['value' => '', 'group' => 'payment', 'type' => 'string'],
            'payment.account_holder' => ['value' => '', 'group' => 'payment', 'type' => 'string'],
            'shipping.enabled' => ['value' => '1', 'group' => 'shipping', 'type' => 'boolean'],
            'shipping.default_cost' => ['value' => '0', 'group' => 'shipping', 'type' => 'decimal'],
            'shipping.free_minimum' => ['value' => (string) config('shipping.free_shipping_minimum_order', 150000), 'group' => 'shipping', 'type' => 'decimal'],
            'notification.orders' => ['value' => '1', 'group' => 'notification', 'type' => 'boolean'],
            'notification.payments' => ['value' => '1', 'group' => 'notification', 'type' => 'boolean'],
            'notification.low_stock' => ['value' => '1', 'group' => 'notification', 'type' => 'boolean'],
        ];
    }

    public static function ensureDefaults(): void
    {
        foreach (self::defaults() as $key => $data) {
            self::query()->firstOrCreate(['key' => $key], $data);
        }
    }

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = self::query()->where('key', $key)->first();
        if (! $setting) {
            return $default ?? (self::defaults()[$key]['value'] ?? null);
        }

        return match ($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'decimal' => (float) $setting->value,
            'integer' => (int) $setting->value,
            default => $setting->value,
        };
    }

    public static function setValue(string $key, mixed $value): void
    {
        $default = self::defaults()[$key] ?? ['group' => 'general', 'type' => 'string'];
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $default['group'], 'type' => $default['type']],
        );
    }

    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            self::setValue($key, $value);
        }
    }

    public static function logoUrl(): string
    {
        $logo = trim((string) self::getValue('store.logo', ''));

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        if ($logo !== '' && $disk->exists($logo)) {
            return $disk->url($logo);
        }

        return asset('favicon.ico');
    }
}
