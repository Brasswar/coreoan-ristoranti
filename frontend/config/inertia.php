<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Inertia Testing
    |--------------------------------------------------------------------------
    |
    | The values described here are used to configure Inertia's testing
    | functionality. This allows you to conveniently test your Inertia
    | driven applications in a more intuitive manner.
    |
    */

    'testing' => [
        'ensure_pages_exist' => true,
        'page_paths' => [
            resource_path('js/Pages'),
        ],
        'page_extensions' => [
            'js',
            'jsx',
            'ts',
            'tsx',
            'vue',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Server-Side Rendering
    |--------------------------------------------------------------------------
    |
    | These options configures if and how Inertia uses Server Side Rendering
    | to pre-render the initial visits made to your application's pages.
    |
    | You can specify a different SSR bundle path, or even a custom SSR URL.
    |
    | Do note that enabling these options will NOT automatically make SSR work,
    | as a separate Inertia SSR server needs to be running for this to work.
    |
    | For more information, please visit https://inertiajs.com/server-side-rendering
    |
    */

    'ssr' => [
        'enabled' => env('INERTIA_SSR_ENABLED', false),
        'url' => env('INERTIA_SSR_URL', 'http://127.0.0.1:13714'),
        'bundle' => base_path('bootstrap/ssr/ssr.js'),
    ],

];
