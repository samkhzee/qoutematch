<?php
/** Module 36 — Admin React: Deposits + withdrawals */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 36 — Admin React deposits & withdrawals\n";
$d = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/DepositController.php');
$w = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/WithdrawalController.php');
echo str_contains($d, "Inertia::render('Admin/Deposits/") ? "OK  DepositController\n" : "CHECK DepositController\n";
echo str_contains($w, "Inertia::render('Admin/Withdrawals/") ? "OK  WithdrawalController\n" : "CHECK WithdrawalController\n";
echo "Module 36 applied.\n";
