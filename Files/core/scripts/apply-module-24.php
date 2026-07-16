<?php

/**
 * Module 24 — Admin React marketplace hub (dashboard + disputes)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 24 — Admin React marketplace hub\n";
echo str_repeat('-', 40) . "\n";

$reactPages = [
    'app/Lib/AdminResource.php',
    'resources/js/Components/Layout/AdminLayout.jsx',
    'resources/js/Pages/Admin/Marketplace/Dashboard.jsx',
    'resources/js/Pages/Admin/Disputes/Index.jsx',
    'resources/js/Pages/Admin/Disputes/Detail.jsx',
];

foreach ($reactPages as $file) {
    echo file_exists(__DIR__ . '/../' . $file) ? "OK  {$file}\n" : "MISSING {$file}\n";
}

$controllers = [
    'app/Http/Controllers/Admin/MarketplaceDashboardController.php',
    'app/Http/Controllers/Admin/DisputeController.php',
];

echo "\nController Inertia migration\n";
foreach ($controllers as $file) {
    $content = file_get_contents(__DIR__ . '/../' . $file);
    $usesInertia = str_contains($content, "Inertia::render('Admin/");
    $stillBridge = str_contains($content, 'InertiaBridge::admin');
    echo ($usesInertia && !$stillBridge) ? "OK  {$file}\n" : "CHECK {$file}\n";
}

Cache::flush();

echo "\nModule 24 deliverables:\n";
echo "  React admin: /admin/marketplace/dashboard\n";
echo "  React admin: /admin/disputes, /admin/disputes/detail/{id}\n";
echo "  Other admin pages remain Blade (categories, users, settings)\n";
echo "\nModule 24 applied.\n";
