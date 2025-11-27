<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | Aquí defines el driver por defecto que Laravel usará para hashing.
    | Lo cambiamos a "double" para usar tu clase DoubleHash.
    |
    */

    'default' => env('HASH_DRIVER', 'double'),

    /*
    |--------------------------------------------------------------------------
    | Hashing Drivers
    |--------------------------------------------------------------------------
    |
    | Configuración de los drivers que pueden usarse.
    | Puedes agregar el driver "double" en AppServiceProvider usando Hash::extend()
    |
    */

    'drivers' => [

        'bcrypt' => [
            'rounds' => 4,
        ],

        'argon' => [
            'memory' => 1024,
            'threads' => 2,
            'time' => 2,
        ],

        'argon2id' => [
            'memory' => 1024,
            'threads' => 2,
            'time' => 2,
        ],

        // Nuestro driver custom "double"
        'double' => [
            // No se necesitan opciones aquí, se define en AppServiceProvider
        ],

    ],

];
