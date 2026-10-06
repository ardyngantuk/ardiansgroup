<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Validation\ValidationException;

class ShippingCalculator
{
    public function zones(): array
    {
        if (! Setting::getValue('shipping.enabled', true)) {
            return ['pickup' => ['label' => 'Pengambilan di toko', 'distance_km' => 0, 'cost' => 0]];
        }

        return config('shipping.zones', []);
    }

    public function calculate(float $subtotal, string $zoneKey): array
    {
        $zone = $this->zones()[$zoneKey] ?? null;
        if (! $zone) {
            throw ValidationException::withMessages([
                'delivery_zone' => 'Zona pengiriman tidak tersedia.',
            ]);
        }

        if ($zoneKey === 'pickup') {
            return ['key' => 'pickup', 'label' => 'Pengambilan di toko', 'distance_km' => 0, 'cost' => 0, 'regular_cost' => 0, 'is_free' => true, 'free_minimum_order' => 0];
        }

        $freeMinimum = (float) Setting::getValue('shipping.free_minimum', config('shipping.free_shipping_minimum_order', 150000));
        $configuredCost = (float) Setting::getValue('shipping.default_cost', 0);
        $zoneCost = $configuredCost > 0 ? $configuredCost : (float) $zone['cost'];
        $isFree = $zoneKey === 'near' && $subtotal >= $freeMinimum;

        return [
            'key' => $zoneKey,
            'label' => $zone['label'],
            'distance_km' => (float) $zone['distance_km'],
            'cost' => $isFree ? 0 : $zoneCost,
            'regular_cost' => $zoneCost,
            'is_free' => $isFree,
            'free_minimum_order' => $freeMinimum,
        ];
    }

    public function codMinimumOrder(): float
    {
        return (float) config('shipping.cod_minimum_order', 50000);
    }
}
