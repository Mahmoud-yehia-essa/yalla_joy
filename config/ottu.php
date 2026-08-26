<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ottu Payment Gateway Configurations
    |--------------------------------------------------------------------------
    */
    'api_key'  => env('OTTU_API_KEY', 'KSK2Iuqw.mowuSwOTIq6ZDT48FvQvW0GaaQPwFjIy'),
    'api_url'  => env('OTTU_API_URL', 'https://pay.pikw.com/b/checkout/v1/pymt-txn/'),
    'pg_code'  => env('OTTU_PG_CODE', 'knet'),
    'pg_codes' => env('OTTU_PG_CODES', 'knet'),
];
