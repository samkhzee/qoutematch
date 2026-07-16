<?php
/** Module 45 — Phase 4 closure (modules 31–45) */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 45 — Phase 4 closure\n";
echo str_repeat('-', 40) . "\n";

for ($i = 31; $i <= 45; $i++) {
    $path = __DIR__ . "/apply-module-{$i}.php";
    echo file_exists($path) ? "OK  apply-module-{$i}.php\n" : "MISSING apply-module-{$i}.php\n";
}

echo "\nRunning full blueprint verification (modules 1–45)...\n";
passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/verify-blueprint.php'), $exitCode);

if ($exitCode !== 0) {
    echo "\nBlueprint verification reported failures.\n";
    exit(1);
}

Cache::flush();

echo "\nModule 45 deliverables:\n";
echo "  Phase 4 admin React migration complete for marketplace operations\n";
echo "  Settings/CMS admin pages intentionally remain Blade\n";
echo "\nProduction checklist:\n";
echo "  php scripts/apply-module-31.php … apply-module-45.php\n";
echo "  npm run build\n";
echo "\nModule 45 applied.\n";
