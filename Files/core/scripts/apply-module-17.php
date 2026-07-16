<?php

/**
 * Module 17 — MVP polish & mobile responsive (Blueprint §39, §42 #18)
 *
 * - Blueprint verification across modules 1–16
 * - React notification inboxes (buyer + provider)
 * - Mobile dashboard CSS polish
 * - Module 6 matching verifier
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 17 — MVP polish & mobile responsive\n";
echo str_repeat('-', 40) . "\n";

$checks = [
    'resources/js/Pages/User/Notifications.jsx',
    'resources/js/Pages/Buyer/Notifications.jsx',
    'resources/js/Components/Shared/NotificationInbox.jsx',
    'scripts/verify-blueprint.php',
    'scripts/apply-module-6.php',
];

foreach ($checks as $file) {
    $path = __DIR__ . '/../' . $file;
    echo file_exists($path) ? "OK  {$file}\n" : "MISSING {$file}\n";
}

echo "\nRunning blueprint verification...\n";
passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/verify-blueprint.php'), $exitCode);

if ($exitCode !== 0) {
    echo "\nBlueprint verification reported failures — fix migrations/scripts above.\n";
    exit(1);
}

echo "\nRunning Module 6 verifier...\n";
passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/apply-module-6.php'), $m6);

Cache::flush();

echo "\nModule 17 deliverables:\n";
echo "  React notifications: /freelancer/notifications, /buyer/notifications\n";
echo "  Mobile CSS: custom.css (Module 17 dashboard block)\n";
echo "  Health check: php scripts/verify-blueprint.php\n";
echo "  Matching check: php scripts/apply-module-6.php\n";
echo "\nRemaining (post-MVP): conversation, projects, bids Blade → React\n";
echo "\nModule 17 applied.\n";
