<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ottu Payment Gateway Configurations
    |--------------------------------------------------------------------------
    */
    'api_key' => env('OTTU_API_KEY', 'GYj5Na8H.29g9hqNjm11nORQMa2WiZwIBQQ49MdAL'),
    'api_url' => env('OTTU_API_URL', 'https://sandbox.ottu.net/b/checkout/v1/pymt-txn/'),
    'pg_code' => env('OTTU_PG_CODE', 'knet'),
];
