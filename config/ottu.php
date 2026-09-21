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
    'pg_codes' => env('OTTU_PG_CODES', 'knet,credit-card'),

    /*
    |--------------------------------------------------------------------------
    | Brevo / Mail Configurations for Payment Invoices
    |--------------------------------------------------------------------------
    */
    'mail' => [
        'brevo_smtp_host'     => env('BREVO_SMTP_HOST', 'smtp-relay.brevo.com'),
        'brevo_smtp_port'     => env('BREVO_SMTP_PORT', 587),
        'brevo_smtp_username' => env('BREVO_SMTP_USERNAME', 'aebc32001@smtp-brevo.com'),
        'brevo_smtp_password' => env('BREVO_SMTP_PASSWORD', ''),
        'from_address'        => env('OTTU_MAIL_FROM_ADDRESS', 'no-reply@fiktahadi.com'),
        'from_name'           => env('OTTU_MAIL_FROM_NAME', 'فيك تحدي'),
    ],
];
