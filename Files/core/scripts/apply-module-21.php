<?php

/**
 * Module 21 — Gateway checkout React + user bridge cleanup (Phase 2)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 21 — Gateway checkout React + bridge cleanup\n";
echo str_repeat('-', 40) . "\n";

$files = [
    'resources/js/Pages/Shared/GatewayCheckout.jsx',
    'app/Lib/PaymentResource.php',
];

foreach ($files as $file) {
    echo file_exists(__DIR__ . '/../' . $file) ? "OK  {$file}\n" : "MISSING {$file}\n";
}

$controllers = [
    'app/Http/Controllers/Gateway/PaymentController.php',
    'app/Http/Controllers/User/MonetisationPaymentController.php',
    'app/Http/Controllers/User/BidController.php',
];

echo "\nController migration\n";
foreach ($controllers as $file) {
    $content = file_get_contents(__DIR__ . '/../' . $file);
    $usesGateway = str_contains($content, 'GatewayCheckout') || str_contains($content, 'PaymentResource::gatewayCheckout');
    $noBridge = !preg_match('/InertiaBridge::(buyer|master)\("Template::.*payment/i', $content);
    echo ($usesGateway && $noBridge) ? "OK  {$file}\n" : "CHECK {$file}\n";
}

Cache::flush();

echo "\nModule 21 deliverables:\n";
echo "  React gateway checkout: Shared/GatewayCheckout.jsx\n";
echo "  Buyer deposits + provider monetisation payments use native Inertia shell\n";
echo "  BidController assignProject redirects to project upload form\n";
echo "\nModule 21 applied.\n";
