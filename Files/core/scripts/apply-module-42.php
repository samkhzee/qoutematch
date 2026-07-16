<?php
/** Module 42 — Admin React: Trial tasks */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 42 — Admin React trial tasks\n";
$c = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ManageTaskController.php');
echo str_contains($c, "Inertia::render('Admin/Tasks/") ? "OK  ManageTaskController\n" : "CHECK\n";
echo "Module 42 applied.\n";
