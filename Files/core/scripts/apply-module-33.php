<?php
/** Module 33 — Admin React: Provider detail */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Module 33 — Admin React provider detail\n";
$c = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ManageUsersController.php');
echo str_contains($c, "Inertia::render('Admin/Users/Detail'") ? "OK  Users Detail Inertia\n" : "CHECK\n";
echo method_exists(\App\Lib\AdminResource::class, 'userDetail') ? "OK  AdminResource::userDetail\n" : "MISSING\n";
echo "Module 33 applied.\n";
