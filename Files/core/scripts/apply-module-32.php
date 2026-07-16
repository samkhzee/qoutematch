<?php
/** Module 32 — Admin React: Provider lists */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 32 — Admin React provider lists\n";
$c = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ManageUsersController.php');
echo str_contains($c, "Inertia::render('Admin/Users/Index'") ? "OK  Users Index Inertia\n" : "CHECK\n";
echo file_exists(__DIR__ . '/../resources/js/Pages/Admin/Users/Index.jsx') ? "OK  Users/Index.jsx\n" : "MISSING\n";
echo "Module 32 applied.\n";
