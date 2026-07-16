<?php
/** Module 37 — Admin React: Projects */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 37 — Admin React projects\n";
$c = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ProjectManagerController.php');
echo str_contains($c, "Inertia::render('Admin/Projects/") ? "OK  ProjectManagerController\n" : "CHECK\n";
echo "Module 37 applied.\n";
