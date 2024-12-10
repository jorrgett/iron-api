<?php
    /*
    |--------------------------------------------------------------------------
    | Default Credetials for Filevault Microseservice
    |--------------------------------------------------------------------------
    |
    */
return [
    'host' => env('FILEVAULT_HOST', null),
    'code' => env('FILEVAULT_CODE', 'ABCOPILOT'),
    'secret_key' => env('FILEVAULT_SECRET_KEY', 'secret_key'),
    'type_id' => env('FILEVAULT_TYPE', 16)
];