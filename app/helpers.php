<?php

use App\Services\Storage\StorageAnalyzer;

if (!function_exists('human_size')) {
    function human_size(?int $bytes): string
    {
        if ($bytes === null || $bytes <= 0) {
            return '0 B';
        }

        return app(StorageAnalyzer::class)->humanSize($bytes);
    }
}