<?php

/**
 * Module 29 — Admin provider approval queue (React)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 29 — Admin provider approval queue\n";
echo str_repeat('-', 40) . "\n";

echo file_exists(__DIR__ . '/../resources/js/Pages/Admin/Providers/PendingApproval.jsx')
    ? "OK  PendingApproval.jsx\n"
    : "MISSING PendingApproval.jsx\n";

$adminResource = file_get_contents(__DIR__ . '/../app/Lib/AdminResource.php');
echo str_contains($adminResource, 'pendingProviders') ? "OK  AdminResource::pendingProviders\n" : "MISSING pendingProviders\n";

$ctrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ManageUsersController.php');
echo str_contains($ctrl, "Inertia::render('Admin/Providers/PendingApproval'")
    ? "OK  ManageUsersController pending approval\n"
    : "CHECK ManageUsersController\n";

Cache::flush();

echo "\nModule 29 deliverables:\n";
echo "  React admin: /admin/freelancers/pending-approval\n";
echo "\nModule 29 applied.\n";
