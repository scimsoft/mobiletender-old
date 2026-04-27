<?php
return [
    'client_id' => env('PAYPAL_CLIENT_ID', ''),
    'secret' => env('PAYPAL_SECRET', ''),
    'apple_pay' => [
        'merchant_id' => env('PAYPAL_APPLE_MERCHANT_ID', ''),
        'merchant_country' => env('PAYPAL_APPLE_MERCHANT_COUNTRY', 'ES'),
        'merchant_name' => env('PAYPAL_APPLE_MERCHANT_NAME', env('APP_NAME', 'Restaurant')),
    ],
    'google_pay' => [
        'merchant_id' => env('PAYPAL_GOOGLE_MERCHANT_ID', ''),
        'merchant_name' => env('PAYPAL_GOOGLE_MERCHANT_NAME', env('APP_NAME', 'Restaurant')),
    ],
    'settings' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'http.ConnectionTimeOut' => 30,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR',
    ],
];