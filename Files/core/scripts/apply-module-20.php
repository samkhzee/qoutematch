<?php

/**
 * Module 20 — Final React migration + blueprint closure (auth, payments, trial tasks)
 *
 * Completes remaining user-facing Blade bridge pages and runs full blueprint audit.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Cache;

echo "Module 20 — Final React migration + blueprint closure\n";
echo str_repeat('-', 40) . "\n";

$reactPages = [
    'app/Lib/AuthResource.php',
    'app/Lib/PaymentResource.php',
    'app/Lib/TaskResource.php',
    'resources/js/Pages/Shared/AuthPages.jsx',
    'resources/js/Pages/Buyer/Auth/ForgotPassword.jsx',
    'resources/js/Pages/Buyer/Auth/VerifyCode.jsx',
    'resources/js/Pages/Buyer/Auth/ResetPassword.jsx',
    'resources/js/Pages/Buyer/Auth/Authorization.jsx',
    'resources/js/Pages/User/Auth/ForgotPassword.jsx',
    'resources/js/Pages/User/Auth/VerifyCode.jsx',
    'resources/js/Pages/User/Auth/ResetPassword.jsx',
    'resources/js/Pages/User/Auth/Authorization.jsx',
    'resources/js/Pages/Buyer/Payment/Deposit.jsx',
    'resources/js/Pages/Buyer/Payment/Manual.jsx',
    'resources/js/Pages/User/Payment/Manual.jsx',
    'resources/js/Pages/Buyer/Task/Index.jsx',
    'resources/js/Pages/User/Task/Index.jsx',
    'resources/js/Components/Auth/VerificationCodeInput.jsx',
    'resources/js/Components/Shared/DepositMethods.jsx',
];

foreach ($reactPages as $file) {
    $path = __DIR__ . '/../' . $file;
    echo file_exists($path) ? "OK  {$file}\n" : "MISSING {$file}\n";
}

$controllers = [
    'app/Http/Controllers/Buyer/Auth/ForgotPasswordController.php',
    'app/Http/Controllers/Buyer/Auth/ResetPasswordController.php',
    'app/Http/Controllers/User/Auth/ForgotPasswordController.php',
    'app/Http/Controllers/User/Auth/ResetPasswordController.php',
    'app/Http/Controllers/Buyer/AuthorizationController.php',
    'app/Http/Controllers/User/AuthorizationController.php',
    'app/Http/Controllers/Gateway/PaymentController.php',
    'app/Http/Controllers/User/MonetisationPaymentController.php',
    'app/Http/Controllers/Buyer/ManageTaskController.php',
    'app/Http/Controllers/User/TaskController.php',
];

echo "\nController Inertia migration\n";
foreach ($controllers as $file) {
    $content = file_get_contents(__DIR__ . '/../' . $file);
    $usesInertia = str_contains($content, 'Inertia::render');
    $stillBridge = (bool) preg_match('/InertiaBridge::(auth|buyer|master)\([\'"]Template::(buyer|user)\.(auth|payment|task)/', $content);
    echo ($usesInertia && !$stillBridge) ? "OK  {$file}\n" : "CHECK {$file}\n";
}

echo "\nRunning blueprint verification...\n";
passthru(PHP_BINARY . ' ' . escapeshellarg(__DIR__ . '/verify-blueprint.php'), $exitCode);

if ($exitCode !== 0) {
    echo "\nBlueprint verification reported failures.\n";
    exit(1);
}

Cache::flush();

echo "\nModule 20 deliverables:\n";
echo "  Auth: password reset + email/SMS/2FA verify (buyer + provider)\n";
echo "  Payments: deposit methods + manual confirm (buyer + provider monetisation)\n";
echo "  Trial tasks: buyer + provider lists/forms (when trial_task enabled)\n";
echo "\nIntentionally still Blade/InertiaBridge:\n";
echo "  - Automatic gateway checkout views (Stripe/PayPal/etc.)\n";
echo "  - Admin panel\n";
echo "\nModule 20 applied.\n";
