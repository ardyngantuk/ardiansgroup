<?php

namespace App\Services;

use App\Models\Setting;

class StorefrontSettings
{
    public function data(): array
    {
        $defaults = Setting::defaults();
        $stored = Setting::query()
            ->whereIn('key', array_keys($defaults))
            ->get()
            ->keyBy('key');

        $value = function (string $key, mixed $fallback = null) use ($defaults, $stored): mixed {
            $setting = $stored->get($key);
            if (! $setting) {
                return $fallback ?? ($defaults[$key]['value'] ?? null);
            }

            return match ($setting->type) {
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'decimal' => (float) $setting->value,
                'integer' => (int) $setting->value,
                default => $setting->value,
            };
        };

        $whatsapp = preg_replace('/\D+/', '', (string) $value('store.whatsapp', ''));

        return [
            'name' => $value('store.name', "ARDIAN'S"),
            'tagline' => $value('store.tagline', 'Sembako Wholesale & Retail'),
            'logo_url' => Setting::logoUrl(),
            'whatsapp' => $value('store.whatsapp', ''),
            'whatsapp_url' => $whatsapp !== '' ? 'https://wa.me/' . $whatsapp : null,
            'email' => $value('store.email', ''),
            'address' => $value('store.address', ''),
            'description' => $value('store.description', ''),
            'hours' => $value('store.hours', ''),
            'payment' => [
                'cod_enabled' => (bool) $value('payment.cod_enabled', true),
                'transfer_enabled' => (bool) $value('payment.manual_transfer_enabled', true),
                'bank_name' => $value('payment.bank_name', ''),
                'account_number' => $value('payment.account_number', ''),
                'account_holder' => $value('payment.account_holder', ''),
            ],
        ];
    }
}
