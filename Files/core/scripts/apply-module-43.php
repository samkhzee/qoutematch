<?php
/** Module 43 — Admin React: Transactions + withdraw methods */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 43 — Admin React reports & withdraw methods\n";
$r = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ReportController.php');
$w = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/WithdrawMethodController.php');
echo str_contains($r, "Inertia::render('Admin/Reports/Transactions'") ? "OK  ReportController transactions\n" : "CHECK reports\n";
echo str_contains($w, "Inertia::render('Admin/WithdrawMethods/Index'") ? "OK  WithdrawMethodController\n" : "CHECK withdraw methods\n";
echo "Module 43 applied.\n";
