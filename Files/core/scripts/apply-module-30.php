<?php

/**
 * Module 30 — Production verification & Phase 3 closure (modules 26–30)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 30 — Production verification & Phase 3 closure\n";
echo str_repeat('-', 40) . "\n";

for ($i = 26; $i <= 30; $i++) {
    $path = __DIR__ . "/apply-module-{$i}.php";
    echo file_exists($path) ? "OK  apply-module-{$i}.php\n" : "MISSING apply-module-{$i}.php\n";
}

echo "\nRunning full blueprint verification (modules 1–30)...\n";
passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/verify-blueprint.php'), $exitCode);

if ($exitCode !== 0) {
    echo "\nBlueprint verification reported failures.\n";
    exit(1);
}

Cache::flush();

echo "\nModule 30 deliverables:\n";
echo "  Extended verify-blueprint.php for modules 26–30\n";
echo "  Phase 3 admin moderation + match score UI documented\n";
echo "\nProduction checklist:\n";
echo "  php artisan migrate --force\n";
echo "  php scripts/apply-module-26.php … apply-module-30.php\n";
echo "  npm run build\n";
echo "  php artisan config:cache && php artisan route:cache\n";
echo "\nModule 30 applied.\n";
