<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Health Check Token
    |--------------------------------------------------------------------------
    |
    | When set, requests to /health must include this token via the
    | X-Health-Token header or the "token" query parameter.
    |
    */

    'token' => env('HEALTH_CHECK_TOKEN'),

];
