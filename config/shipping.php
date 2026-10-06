<?php

return [
    'cod_minimum_order' => (float) env('COD_MINIMUM_ORDER', 50000),
    'free_shipping_minimum_order' => (float) env('FREE_SHIPPING_MINIMUM_ORDER', 150000),
    'zones' => [
        'near' => [
            'label' => 'Dalam 5 km',
            'distance_km' => 5,
            'cost' => 0,
        ],
        'medium' => [
            'label' => '5-10 km',
            'distance_km' => 10,
            'cost' => 10000,
        ],
        'far' => [
            'label' => '10-15 km',
            'distance_km' => 15,
            'cost' => 20000,
        ],
    ],
];
