<?php

/**
 * Module 18 — React dashboard migration (conversation, projects, bids, disputes)
 *
 * Converts high-priority buyer/provider Blade bridge pages to native React/Inertia.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 18 — React dashboard migration\n";
echo str_repeat('-', 40) . "\n";

$reactPages = [
    'resources/js/Pages/Buyer/Conversation.jsx',
    'resources/js/Pages/User/Conversation.jsx',
    'resources/js/Pages/Buyer/Projects/Index.jsx',
    'resources/js/Pages/Buyer/Projects/Detail.jsx',
    'resources/js/Pages/User/Projects/Index.jsx',
    'resources/js/Pages/User/Projects/Detail.jsx',
    'resources/js/Pages/User/Projects/Upload.jsx',
    'resources/js/Pages/User/Bids/Index.jsx',
    'resources/js/Pages/Buyer/Disputes/Index.jsx',
    'resources/js/Pages/Buyer/Disputes/Detail.jsx',
    'resources/js/Pages/User/Disputes/Index.jsx',
    'resources/js/Pages/User/Disputes/Detail.jsx',
    'resources/js/Components/Shared/ChatInbox.jsx',
    'resources/js/Components/Shared/ProjectList.jsx',
    'resources/js/Components/Shared/ProjectDetail.jsx',
    'resources/js/Components/Shared/BidList.jsx',
    'resources/js/Components/Shared/DisputeList.jsx',
    'resources/js/Components/Shared/DisputeDetail.jsx',
    'app/Lib/DashboardResource.php',
];

foreach ($reactPages as $file) {
    $path = __DIR__ . '/../' . $file;
    echo file_exists($path) ? "OK  {$file}\n" : "MISSING {$file}\n";
}

$controllers = [
    'app/Http/Controllers/Buyer/ConversationController.php',
    'app/Http/Controllers/User/ConversationController.php',
    'app/Http/Controllers/Buyer/ProjectController.php',
    'app/Http/Controllers/User/ProjectController.php',
    'app/Http/Controllers/User/BidController.php',
    'app/Http/Controllers/Buyer/DisputeController.php',
    'app/Http/Controllers/User/DisputeController.php',
];

echo "\nController Inertia migration\n";
foreach ($controllers as $file) {
    $content = file_get_contents(__DIR__ . '/../' . $file);
    $usesInertia = str_contains($content, 'Inertia::render');
    $stillBridge = (bool) preg_match('/InertiaBridge::(buyer|master)\([\'"]Template::(buyer|user)\.(conversation|project|bid|disputes)/', $content);
    echo ($usesInertia && !$stillBridge) ? "OK  {$file}\n" : "CHECK {$file}\n";
}

echo "\nRunning blueprint verification...\n";
passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/verify-blueprint.php'), $exitCode);

if ($exitCode !== 0) {
    echo "\nBlueprint verification reported failures.\n";
    exit(1);
}

Cache::flush();

echo "\nModule 18 deliverables:\n";
echo "  React chat: /buyer/conversation, /freelancer/conversation\n";
echo "  React projects: /buyer/project/index, /freelancer/project/index\n";
echo "  React bids: /freelancer/bid/index\n";
echo "  React disputes: /buyer/disputes, /freelancer/disputes\n";
echo "\nRemaining Blade bridge: support tickets, withdraw, KYC, payments, profile settings\n";
echo "\nModule 18 applied.\n";
