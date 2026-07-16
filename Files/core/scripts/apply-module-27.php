<?php

/**
 * Module 27 — Admin React: Verifications & Review moderation
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 27 — Admin React: Verifications & Reviews\n";
echo str_repeat('-', 40) . "\n";

$reactPages = [
    'resources/js/Pages/Admin/Verifications/Index.jsx',
    'resources/js/Pages/Admin/Verifications/Detail.jsx',
    'resources/js/Pages/Admin/Reviews/Index.jsx',
    'resources/js/Pages/Admin/Reviews/Detail.jsx',
];

foreach ($reactPages as $file) {
    echo file_exists(__DIR__ . '/../' . $file) ? "OK  {$file}\n" : "MISSING {$file}\n";
}

$controllers = [
    'app/Http/Controllers/Admin/ProviderVerificationController.php',
    'app/Http/Controllers/Admin/ReviewModerationController.php',
];

echo "\nController Inertia migration\n";
foreach ($controllers as $file) {
    $content = file_get_contents(__DIR__ . '/../' . $file);
    $usesInertia = str_contains($content, "Inertia::render('Admin/");
    $stillBridge = str_contains($content, 'InertiaBridge::admin');
    echo ($usesInertia && !$stillBridge) ? "OK  {$file}\n" : "CHECK {$file}\n";
}

Cache::flush();

echo "\nModule 27 deliverables:\n";
echo "  React admin: /admin/provider-verifications, /admin/reviews\n";
echo "\nModule 27 applied.\n";
