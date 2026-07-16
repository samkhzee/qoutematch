<?php
/** Module 39 — Postcode outcode matching v3 */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 39 — Postcode outcode matching v3\n" . str_repeat('-', 40) . "\n";
echo method_exists(\App\Lib\JobMatchingService::class, 'hasPostcodeOutcodeMatch') ? "OK  hasPostcodeOutcodeMatch()\n" : "MISSING\n";
$res = file_get_contents(__DIR__ . '/../app/Lib/InertiaResource.php');
echo str_contains($res, 'postcodeMatch') ? "OK  postcodeMatch in InertiaResource\n" : "MISSING\n";
echo "Module 39 applied.\n";
