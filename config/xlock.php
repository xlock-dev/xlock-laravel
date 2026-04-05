<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site Key
    |--------------------------------------------------------------------------
    |
    | Your x-lock site key, starting with "sk_". Obtain this from the
    | x-lock dashboard at https://x-lock.dev.
    |
    */

    'site_key' => env('XLOCK_SITE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | API URL
    |--------------------------------------------------------------------------
    |
    | The x-lock enforcement API endpoint. You should not need to change
    | this unless you are using a self-hosted instance.
    |
    */

    'api_url' => env('XLOCK_API_URL', 'https://api.x-lock.dev'),

    /*
    |--------------------------------------------------------------------------
    | Fail Open
    |--------------------------------------------------------------------------
    |
    | When true, requests will be allowed through if the x-lock API is
    | unreachable or returns an unexpected error. Set to false to block
    | requests when verification cannot be completed.
    |
    */

    'fail_open' => (bool) env('XLOCK_FAIL_OPEN', true),

];
