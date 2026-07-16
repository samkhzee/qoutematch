<?php

/**
 * Module 19 — React account pages (support, profile, withdraw, KYC, transactions, 2FA)
 *
 * Converts remaining buyer/provider Blade bridge pages to native React/Inertia.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 19 — React account pages migration\n";
echo str_repeat('-', 40) . "\n";

$reactPages = [
    'resources/js/Pages/Shared/AccountPages.jsx',
    'resources/js/Pages/Buyer/Support/Index.jsx',
    'resources/js/Pages/Buyer/Support/Create.jsx',
    'resources/js/Pages/Buyer/Support/View.jsx',
    'resources/js/Pages/User/Support/Index.jsx',
    'resources/js/Pages/User/Support/Create.jsx',
    'resources/js/Pages/User/Support/View.jsx',
    'resources/js/Pages/Buyer/Profile/Settings.jsx',
    'resources/js/Pages/Buyer/Profile/Password.jsx',
    'resources/js/Pages/User/Profile/Password.jsx',
    'resources/js/Pages/Buyer/Withdraw/Methods.jsx',
    'resources/js/Pages/Buyer/Withdraw/Preview.jsx',
    'resources/js/Pages/Buyer/Withdraw/History.jsx',
    'resources/js/Pages/User/Withdraw/Methods.jsx',
    'resources/js/Pages/User/Withdraw/Preview.jsx',
    'resources/js/Pages/User/Withdraw/History.jsx',
    'resources/js/Pages/Buyer/Account/Transactions.jsx',
    'resources/js/Pages/Buyer/Account/Deposits.jsx',
    'resources/js/Pages/Buyer/Account/TwoFactor.jsx',
    'resources/js/Pages/User/Account/Transactions.jsx',
    'resources/js/Pages/User/Account/Deposits.jsx',
    'resources/js/Pages/User/Account/TwoFactor.jsx',
    'resources/js/Pages/Buyer/Kyc/Form.jsx',
    'resources/js/Pages/Buyer/Kyc/Info.jsx',
    'resources/js/Pages/User/Kyc/Form.jsx',
    'resources/js/Pages/User/Kyc/Info.jsx',
    'resources/js/Components/Shared/SupportTicketList.jsx',
    'resources/js/Components/Shared/SupportTicketCreate.jsx',
    'resources/js/Components/Shared/SupportTicketView.jsx',
    'resources/js/Components/Shared/BuyerProfileForm.jsx',
    'resources/js/Components/Shared/ChangePasswordForm.jsx',
    'resources/js/Components/Shared/WithdrawMethods.jsx',
    'resources/js/Components/Shared/WithdrawPreview.jsx',
    'resources/js/Components/Shared/WithdrawHistory.jsx',
    'resources/js/Components/Shared/TransactionList.jsx',
    'resources/js/Components/Shared/DepositHistory.jsx',
    'resources/js/Components/Shared/TwoFactorPanel.jsx',
    'resources/js/Components/Shared/KycForm.jsx',
    'resources/js/Components/Shared/KycInfo.jsx',
    'app/Lib/AccountResource.php',
];

foreach ($reactPages as $file) {
    $path = __DIR__ . '/../' . $file;
    echo file_exists($path) ? "OK  {$file}\n" : "MISSING {$file}\n";
}

$controllers = [
    'app/Traits/SupportTicketManager.php',
    'app/Http/Controllers/Buyer/ProfileController.php',
    'app/Http/Controllers/User/ProfileController.php',
    'app/Http/Controllers/Buyer/WithdrawController.php',
    'app/Http/Controllers/User/WithdrawController.php',
    'app/Http/Controllers/Buyer/BuyerController.php',
    'app/Http/Controllers/User/UserController.php',
];

echo "\nController Inertia migration\n";
foreach ($controllers as $file) {
    $content = file_get_contents(__DIR__ . '/../' . $file);
    $usesInertia = str_contains($content, 'Inertia::render');
    $stillBridge = (bool) preg_match('/InertiaBridge::(buyer|master)\([\'"]Template::(buyer|user)\.(ticket|withdraw|kyc|twofactor|transactions|deposit|password|profile)/', $content);
    echo ($usesInertia && !$stillBridge) ? "OK  {$file}\n" : "CHECK {$file}\n";
}

echo "\nRunning blueprint verification...\n";
passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/verify-blueprint.php'), $exitCode);

if ($exitCode !== 0) {
    echo "\nBlueprint verification reported failures.\n";
    exit(1);
}

Cache::flush();

echo "\nModule 19 deliverables:\n";
echo "  Support tickets: /buyer/ticket, /ticket\n";
echo "  Profile/password: /buyer/profile/setting, /buyer/change-password, /freelancer/change-password\n";
echo "  Withdraw: /buyer/withdraw, /freelancer/withdraw\n";
echo "  Account: transactions, deposits, 2FA, KYC (buyer + provider)\n";
echo "\nRemaining Blade bridge: auth reset, payment confirm, trial tasks, admin panel\n";
echo "\nModule 19 applied.\n";
