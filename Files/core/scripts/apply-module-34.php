<?php
/** Module 34 — Admin React: Buyers */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 34 — Admin React buyers\n";
$c = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ManageBuyersController.php');
echo str_contains($c, "Inertia::render('Admin/Buyers/") ? "OK  ManageBuyersController\n" : "CHECK\n";
echo "Module 34 applied.\n";
