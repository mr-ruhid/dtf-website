<?php

return [

    'disk' => 'public',

    'folders' => [
        'order_designs' => [
            'path' => 'orders/designs',
            'label' => 'Order Artwork',
            'icon' => 'fa-file-image',
            'protected' => false,
        ],
        'tmp_uploads' => [
            'path' => 'tmp/uploads',
            'label' => 'Temporary Uploads',
            'icon' => 'fa-clock',
            'protected' => false,
            'auto_cleanup_hours' => 24,
        ],
        'tmp_zips' => [
            'path' => 'tmp-zips',
            'label' => 'Temporary ZIPs',
            'icon' => 'fa-file-zipper',
            'protected' => false,
            'auto_cleanup_hours' => 6,
        ],
        'products' => [
            'path' => 'products',
            'label' => 'Product Images',
            'icon' => 'fa-box',
            'protected' => true,
        ],
        'widgets' => [
            'path' => 'widgets',
            'label' => 'Widget Images',
            'icon' => 'fa-puzzle-piece',
            'protected' => true,
        ],
        'models' => [
            'path' => 'models',
            'label' => 'Model Images',
            'icon' => 'fa-layer-group',
            'protected' => true,
        ],
        'blog' => [
            'path' => 'blog',
            'label' => 'Blog Images',
            'icon' => 'fa-newspaper',
            'protected' => true,
        ],
        'pages' => [
            'path' => 'pages',
            'label' => 'Page Images',
            'icon' => 'fa-file-lines',
            'protected' => true,
        ],
        'gallery' => [
            'path' => 'gallery',
            'label' => 'Gallery',
            'icon' => 'fa-images',
            'protected' => true,
        ],
        'categories' => [
            'path' => 'categories',
            'label' => 'Category Images',
            'icon' => 'fa-folder-tree',
            'protected' => true,
        ],
    ],

    'cleanup' => [
        'order_designs_max_age_days' => 180,
        'order_designs_protected_statuses' => ['pending', 'confirmed', 'processing'],

        'orphan_scan_models' => [
            \App\Models\OrderDesign::class => 'file_path',
            \App\Models\ProductImage::class => 'image',
            \App\Models\Category::class => 'image',
        ],
    ],

];
