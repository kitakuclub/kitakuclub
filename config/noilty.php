<?php

declare(strict_types=1);

return [

    'services' => [

        'kodik' => [
            'key' => env('KODIK_API_KEY'),
            'endpoint' => env('KODIK_API_ENDPOINT', 'https://kodik-api.com'),
        ]

    ]

];
