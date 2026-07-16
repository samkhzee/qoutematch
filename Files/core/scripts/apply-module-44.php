<?php
/** Module 44 — Admin bridge audit (marketplace vs settings) */
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Module 44 — Admin bridge audit\n";
echo str_repeat('-', 40) . "\n";

$migrated = [
    'ConfigCategoryController.php',
    'ManageUsersController.php',
    'ManageBuyersController.php',
    'MonetisationController.php',
    'DepositController.php',
    'WithdrawalController.php',
    'ProjectManagerController.php',
    'MarketplaceFormController.php',
    'SupportTicketController.php',
    'ManageTaskController.php',
    'ReportController.php',
    'ManageJobController.php',
    'ManageBidController.php',
    'DisputeController.php',
    'MarketplaceDashboardController.php',
    'ProviderVerificationController.php',
    'ReviewModerationController.php',
];

$intentionalBlade = [
    'GeneralSettingController.php',
    'FrontendController.php',
    'NotificationController.php',
    'PageBuilderController.php',
    'LanguageController.php',
    'AutomaticGatewayController.php',
    'ManualGatewayController.php',
];

$dir = __DIR__ . '/../app/Http/Controllers/Admin';
foreach ($migrated as $file) {
    $path = $dir . '/' . $file;
    if (!file_exists($path)) {
        echo "MISSING $file\n";
        continue;
    }
    $content = file_get_contents($path);
    $usesInertia = str_contains($content, "Inertia::render('Admin/");
    $bridgeCount = substr_count($content, 'InertiaBridge::admin');
    echo ($usesInertia && $bridgeCount === 0) ? "OK  $file (fully migrated)\n" : "PARTIAL $file (Inertia + {$bridgeCount} bridge)\n";
}

echo "\nIntentionally remaining Blade (settings/CMS):\n";
foreach ($intentionalBlade as $file) {
    echo "  INFO $file\n";
}

echo "\nModule 44 applied.\n";
