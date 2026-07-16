<?php

/**
 * Module 25 — Production verification & Phase 2 closure (modules 21–25)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 25 — Production verification & Phase 2 closure\n";
echo str_repeat('-', 40) . "\n";

for ($i = 21; $i <= 25; $i++) {
    $path = __DIR__ . "/apply-module-{$i}.php";
    echo file_exists($path) ? "OK  apply-module-{$i}.php\n" : "MISSING apply-module-{$i}.php\n";
}

echo "\nRunning full blueprint verification (modules 1–25)...\n";
passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/verify-blueprint.php'), $exitCode);

if ($exitCode !== 0) {
    echo "\nBlueprint verification reported failures.\n";
    exit(1);
}

Cache::flush();

echo "\nModule 25 deliverables:\n";
echo "  Extended verify-blueprint.php for modules 21–25\n";
echo "  Phase 2 post-MVP features documented in BLUEPRINT_IMPLEMENTATION.md\n";
echo "\nProduction checklist:\n";
echo "  php artisan migrate --force\n";
echo "  php scripts/apply-module-21.php … apply-module-25.php\n";
echo "  npm run build\n";
echo "  php artisan config:cache && php artisan route:cache\n";
echo "\nModule 25 applied.\n";
