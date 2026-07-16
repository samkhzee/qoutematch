<?php

/**
 * Module 26 — Admin React: Jobs & Quotes
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 26 — Admin React: Jobs & Quotes\n";
echo str_repeat('-', 40) . "\n";

$reactPages = [
    'resources/js/Pages/Admin/Jobs/Index.jsx',
    'resources/js/Pages/Admin/Jobs/Detail.jsx',
    'resources/js/Pages/Admin/Bids/Index.jsx',
    'resources/js/Pages/Admin/Bids/Detail.jsx',
];

foreach ($reactPages as $file) {
    echo file_exists(__DIR__ . '/../' . $file) ? "OK  {$file}\n" : "MISSING {$file}\n";
}

$controllers = [
    'app/Http/Controllers/Admin/ManageJobController.php',
    'app/Http/Controllers/Admin/ManageBidController.php',
];

echo "\nController Inertia migration\n";
foreach ($controllers as $file) {
    $content = file_get_contents(__DIR__ . '/../' . $file);
    $usesInertia = str_contains($content, "Inertia::render('Admin/");
    $stillBridge = str_contains($content, 'InertiaBridge::admin');
    echo ($usesInertia && !$stillBridge) ? "OK  {$file}\n" : "CHECK {$file}\n";
}

Cache::flush();

echo "\nModule 26 deliverables:\n";
echo "  React admin: /admin/jobs/*, /admin/bids/*\n";
echo "\nModule 26 applied.\n";
