<?php
/** Module 41 — Admin React: Support tickets */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 41 — Admin React support\n";
$c = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/SupportTicketController.php');
echo str_contains($c, "Inertia::render('Admin/Support/") ? "OK  SupportTicketController\n" : "CHECK\n";
echo "Module 41 applied.\n";
