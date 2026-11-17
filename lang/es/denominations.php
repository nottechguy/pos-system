<?php

return [
    'types' => [
        'bill' => 'Billete',
        'coin' => 'Moneda',
    ],
    // Optional: Friendly labels indexed by exact string or float value of the denomination
    'labels' => [
        '1000.0' => '$1,000 (Billete)',
        '500.0'  => '$500 (Billete)',
        '200.0'  => '$200 (Billete)',
        '100.0'  => '$100 (Billete)',
        '50.0'   => '$50 (Billete)',
        '20.0'   => '$20 (Billete / Moneda)',
        '10.0'   => '$10 (Moneda)',
        '5.0'    => '$5 (Moneda)',
        '2.0'    => '$2 (Moneda)',
        '1.0'    => '$1 (Moneda)',
        '0.5'    => '50¢ (Moneda)',
    ],
];