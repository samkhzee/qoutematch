<?php
/** Module 40 — Gateway checkout metadata polish */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 40 — Gateway checkout polish\n" . str_repeat('-', 40) . "\n";
$pr = file_get_contents(__DIR__ . '/../app/Lib/PaymentResource.php');
echo str_contains($pr, "'gateway'") ? "OK  PaymentResource gateway metadata\n" : "MISSING\n";
$jsx = file_get_contents(__DIR__ . '/../resources/js/Pages/Shared/GatewayCheckout.jsx');
echo str_contains($jsx, 'gateway?.name') ? "OK  GatewayCheckout gateway label\n" : "MISSING\n";
echo "Module 40 applied.\n";
