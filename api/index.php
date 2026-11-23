<?php

// Pastikan direktori penyimpanan di /tmp tersedia untuk Vercel serverless
$storageDirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Teruskan request ke public/index.php Laravel
require __DIR__ . '/../public/index.php';
