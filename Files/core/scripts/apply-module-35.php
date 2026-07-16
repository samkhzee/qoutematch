<?php
/** Module 35 — Admin React: Monetisation */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 35 — Admin React monetisation\n";
$c = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/MonetisationController.php');
echo str_contains($c, "Inertia::render('Admin/Monetisation/") ? "OK  MonetisationController\n" : "CHECK\n";
echo "Module 35 applied.\n";
