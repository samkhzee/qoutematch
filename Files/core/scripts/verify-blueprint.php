<?php

/**
 * Blueprint health check — verifies Modules 1–20 against PDF §39 / §42 requirements.
 *
 * Usage: php scripts/verify-blueprint.php
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\Category;
use App\Models\Dispute;
use App\Models\Form;
use App\Models\LeadCreditPackage;
use App\Models\NotificationTemplate;
use App\Models\Page;
use App\Models\ProviderVerification;
use App\Models\SeoLocation;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

$pass = 0;
$fail = 0;
$warn = 0;

function check(bool $ok, string $label, string $failHint = ''): void
{
    global $pass, $fail;
    if ($ok) {
        echo "  OK   {$label}\n";
        $pass++;
    } else {
        echo "  FAIL {$label}" . ($failHint ? " — {$failHint}" : '') . "\n";
        $fail++;
    }
}

function warn(string $label): void
{
    global $warn;
    echo "  WARN {$label}\n";
    $warn++;
}

echo "QuoteMatch Blueprint Verification\n";
echo str_repeat('=', 50) . "\n\n";

echo "§42 First version build\n";
check(Schema::hasTable('categories') && Category::active()->count() >= 2, 'Categories seeded (Builders + Freight)');
check(Form::where('act', 'request_builders')->exists() && Form::where('act', 'quote_builders')->exists(), 'Dynamic request + quote forms');
check(Schema::hasColumn('jobs', 'request_data'), 'Structured request data (jobs.request_data)');
check(Schema::hasColumn('bids', 'quote_data'), 'Structured quote data (bids.quote_data)');
check(Schema::hasColumn('bids', 'revision_requested_at'), 'Quote revision fields');
check(Schema::hasTable('provider_verifications'), 'Provider verification badges table');
check(Schema::hasColumn('reviews', 'scores'), 'Structured review scores');
check(Schema::hasTable('disputes'), 'Disputes MVP table');
check(SeoLocation::active()->count() >= 5, 'SEO location pages seeded');
check(file_exists(__DIR__ . '/../sitemap.xml'), 'sitemap.xml generated');
check(Route::has('categories') && Route::has('locations') && Route::has('seo.category.location'), 'Public SEO routes');

$templates = [
    'QUOTE_SHORTLISTED', 'QUOTE_REVISION_REQUESTED', 'NEW_CHAT_MESSAGE',
    'PROVIDER_APPROVED', 'REVIEW_APPROVED', 'DISPUTE_OPENED',
    'LEAD_CREDITS_PURCHASED', 'LEAD_CREDITS_LOW', 'SUBSCRIPTION_EXPIRED',
];
foreach ($templates as $act) {
    check(NotificationTemplate::where('act', $act)->exists(), "Notification template: {$act}");
}

echo "\n§39 MVP acceptance criteria\n";
check(Page::where('slug', '/')->exists(), 'Public homepage');
check(Route::has('buyer.register') && Route::has('user.register'), 'Customer + provider registration routes');
check(Route::has('buyer.job.post.details'), 'Customer request posting flow');
check(Route::has('buyer.job.post.bids'), 'Quote comparison route');
check(Route::has('buyer.job.post.bids.shortlist'), 'Shortlist quote route');
check(Route::has('buyer.job.post.bids.reject'), 'Reject quote route');
check(Route::has('buyer.job.post.bids.revision'), 'Revision request route');
check(Route::has('buyer.conversation.bid'), 'Quote-gated messaging route');
check(Route::has('user.bid.store'), 'Provider quote submission route');
check(Route::has('user.verification.index'), 'Provider verification uploads');
check(Route::has('admin.marketplace.dashboard'), 'Admin marketplace hub');
check(Route::has('admin.disputes.index'), 'Admin dispute moderation');
check(Route::has('admin.marketplace.forms.index'), 'Admin form builder');
check(Route::has('user.lead.credits.index'), 'Provider lead credits page');
check(Route::has('user.notifications.index') && Route::has('buyer.notifications.index'), 'In-app notification inboxes');
check(Route::has('policy.pages'), 'Legal policy pages');
check(Route::has('cookie.policy'), 'Cookie / GDPR policy');

echo "\nModule scripts\n";
for ($i = 1; $i <= 25; $i++) {
    if ($i === 6) {
        check(file_exists(__DIR__ . '/apply-module-6.php'), 'apply-module-6.php');
        continue;
    }
    if (in_array($i, [7, 8], true)) {
        if ($i === 7) {
            check(file_exists(__DIR__ . '/apply-module-7-8.php'), 'apply-module-7-8.php (modules 7+8)');
        }
        continue;
    }
    check(file_exists(__DIR__ . "/apply-module-{$i}.php"), "apply-module-{$i}.php");
}

echo "\nMonetisation (Module 12/16)\n";
if (Schema::hasColumn('general_settings', 'monetisation_enabled')) {
    $enabled = (bool) gs('monetisation_enabled');
    echo '  INFO Monetisation enabled: ' . ($enabled ? 'yes' : 'no (use apply-module-16.php)') . "\n";
    check(LeadCreditPackage::active()->count() >= 1, 'Credit packages configured');
    check(SubscriptionPlan::active()->count() >= 1, 'Subscription plans configured');
} else {
    check(false, 'Monetisation schema', 'run Module 12 migration');
}

echo "\nReact vs Blade (Module 17–19 progress)\n";
$reactPages = [
    'resources/js/Pages/Public/Home.jsx',
    'resources/js/Pages/Buyer/Job/CompareQuotes.jsx',
    'resources/js/Pages/User/LeadCredits.jsx',
    'resources/js/Pages/User/Notifications.jsx',
    'resources/js/Pages/Buyer/Notifications.jsx',
    'resources/js/Pages/Buyer/Conversation.jsx',
    'resources/js/Pages/User/Conversation.jsx',
    'resources/js/Pages/Buyer/Projects/Index.jsx',
    'resources/js/Pages/User/Projects/Index.jsx',
    'resources/js/Pages/User/Bids/Index.jsx',
    'resources/js/Pages/Buyer/Disputes/Index.jsx',
    'resources/js/Pages/User/Disputes/Index.jsx',
    'resources/js/Pages/Buyer/Support/Index.jsx',
    'resources/js/Pages/User/Support/Index.jsx',
    'resources/js/Pages/Buyer/Profile/Settings.jsx',
    'resources/js/Pages/Buyer/Withdraw/Methods.jsx',
    'resources/js/Pages/User/Withdraw/Methods.jsx',
    'resources/js/Pages/Buyer/Account/Transactions.jsx',
    'resources/js/Pages/Buyer/Account/TwoFactor.jsx',
    'resources/js/Pages/Buyer/Kyc/Form.jsx',
    'app/Lib/AccountResource.php',
];
foreach ($reactPages as $page) {
    check(file_exists(__DIR__ . '/../' . $page), "React page: {$page}");
}

echo "\nModule 14 — Notifications hub\n";
$module14Templates = [
    'QUOTE_SHORTLISTED', 'QUOTE_REVISION_REQUESTED', 'NEW_CHAT_MESSAGE', 'PROVIDER_APPROVED',
    'PROVIDER_VERIFICATION_APPROVED', 'REVIEW_APPROVED', 'DISPUTE_OPENED', 'DISPUTE_RESOLVED',
    'QUOTE_DEADLINE_EXPIRED', 'LEAD_CREDITS_PURCHASED', 'SUBSCRIPTION_ACTIVATED',
];
foreach ($module14Templates as $act) {
    check(NotificationTemplate::where('act', $act)->exists(), "Module 14 template: {$act}");
}
check(Route::has('user.notifications.index') && Route::has('buyer.notifications.index'), 'Module 14 in-app notification routes');

echo "\nModule 15 — Public SEO & legal\n";
check(SeoLocation::active()->count() >= 10, 'Module 15 UK location pages (10+)');
check(Route::has('locations') && Route::has('locations.show') && Route::has('seo.category.location'), 'Module 15 SEO routes');
check(Route::has('policy.pages') && Route::has('cookie.policy'), 'Module 15 legal routes');
check(file_exists(__DIR__ . '/../robots.txt'), 'Module 15 robots.txt');

echo "\nModule 16 — Monetisation go-live\n";
check(Route::has('user.lead.credits.index'), 'Module 16 provider lead credits page');
check(Route::has('admin.monetisation.settings'), 'Module 16 admin monetisation settings');
check(NotificationTemplate::where('act', 'LEAD_CREDITS_LOW')->exists(), 'Module 16 low-credit template');
check(NotificationTemplate::where('act', 'SUBSCRIPTION_EXPIRED')->exists(), 'Module 16 subscription expired template');

echo "\nModule 17 — MVP polish & mobile\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/User/Notifications.jsx'), 'Module 17 provider notifications React page');
check(file_exists(__DIR__ . '/../resources/js/Pages/Buyer/Notifications.jsx'), 'Module 17 buyer notifications React page');

echo "\nModule 18 — React dashboard\n";
check(file_exists(__DIR__ . '/../app/Lib/DashboardResource.php'), 'Module 18 DashboardResource');
check(file_exists(__DIR__ . '/../resources/js/Pages/Buyer/Conversation.jsx'), 'Module 18 buyer conversation');
check(file_exists(__DIR__ . '/../resources/js/Pages/User/Disputes/Index.jsx'), 'Module 18 provider disputes');

echo "\nModule 19 — React account pages\n";
check(file_exists(__DIR__ . '/../app/Lib/AccountResource.php'), 'Module 19 AccountResource');
check(file_exists(__DIR__ . '/../resources/js/Pages/Buyer/Support/Index.jsx'), 'Module 19 buyer support');
check(file_exists(__DIR__ . '/../resources/js/Pages/User/Withdraw/Methods.jsx'), 'Module 19 provider withdraw');

echo "\nModule 20 — Final React migration\n";
$module20Pages = [
    'resources/js/Pages/Buyer/Auth/ForgotPassword.jsx',
    'resources/js/Pages/User/Auth/Authorization.jsx',
    'resources/js/Pages/Buyer/Payment/Deposit.jsx',
    'resources/js/Pages/Buyer/Payment/Manual.jsx',
    'resources/js/Pages/Buyer/Task/Index.jsx',
    'app/Lib/AuthResource.php',
    'app/Lib/PaymentResource.php',
];
foreach ($module20Pages as $page) {
    check(file_exists(__DIR__ . '/../' . $page), "Module 20: {$page}");
}

$userFacingBridge = 0;
$scanDirs = [
    __DIR__ . '/../app/Http/Controllers/Buyer',
    __DIR__ . '/../app/Http/Controllers/User',
];
foreach ($scanDirs as $dir) {
    foreach (glob($dir . '/**/*.php') ?: [] as $file) {
        $content = file_get_contents($file);
        if (preg_match('/InertiaBridge::(auth|buyer|master)\([\'"]Template::(buyer|user)\./', $content)) {
            $userFacingBridge++;
        }
    }
    foreach (glob($dir . '/*.php') ?: [] as $file) {
        $content = file_get_contents($file);
        if (preg_match('/InertiaBridge::(auth|buyer|master)\([\'"]Template::(buyer|user)\./', $content)) {
            $userFacingBridge++;
        }
    }
}
if ($userFacingBridge > 0) {
    warn("{$userFacingBridge} user-facing controller(s) still use InertiaBridge (legacy routes only)");
} else {
    check(true, 'Module 20+ user-facing Blade bridge cleared');
}

echo "\nModule 21 — Gateway checkout React\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Shared/GatewayCheckout.jsx'), 'Module 21 GatewayCheckout.jsx');
$paymentCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Gateway/PaymentController.php');
check(str_contains($paymentCtrl, 'PaymentResource::gatewayCheckout'), 'Module 21 buyer gateway uses PaymentResource');

echo "\nModule 22 — Quote intelligence\n";
check(file_exists(__DIR__ . '/../app/Lib/QuoteAmountService.php'), 'Module 22 QuoteAmountService');
check(class_exists(\App\Lib\QuoteAmountService::class) && method_exists(\App\Lib\QuoteAmountService::class, 'breakdown'), 'Module 22 quote breakdown');

echo "\nModule 23 — Location matching v2\n";
check(method_exists(\App\Lib\JobMatchingService::class, 'matchScore'), 'Module 23 JobMatchingService::matchScore');
check(method_exists(\App\Lib\JobMatchingService::class, 'jobLocationTerms'), 'Module 23 jobLocationTerms');

echo "\nModule 24 — Admin React marketplace hub\n";
check(file_exists(__DIR__ . '/../app/Lib/AdminResource.php'), 'Module 24 AdminResource');
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Marketplace/Dashboard.jsx'), 'Module 24 admin dashboard React');
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Disputes/Index.jsx'), 'Module 24 admin disputes React');
$marketCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/MarketplaceDashboardController.php');
check(str_contains($marketCtrl, "Inertia::render('Admin/Marketplace/Dashboard'"), 'Module 24 marketplace controller');

echo "\nModule 25 — Phase 2 closure\n";
for ($i = 21; $i <= 25; $i++) {
    check(file_exists(__DIR__ . "/apply-module-{$i}.php"), "Module 25 apply-module-{$i}.php");
}

echo "\nModule 26 — Admin React jobs & quotes\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Jobs/Index.jsx'), 'Module 26 Admin Jobs Index');
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Bids/Detail.jsx'), 'Module 26 Admin Bids Detail');
$jobCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ManageJobController.php');
check(str_contains($jobCtrl, "Inertia::render('Admin/Jobs/"), 'Module 26 ManageJobController Inertia');
$bidCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ManageBidController.php');
check(str_contains($bidCtrl, "Inertia::render('Admin/Bids/"), 'Module 26 ManageBidController Inertia');
check(method_exists(\App\Lib\AdminResource::class, 'jobDetail'), 'Module 26 AdminResource job serializers');

echo "\nModule 27 — Admin verifications & reviews\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Verifications/Index.jsx'), 'Module 27 Verifications Index');
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Reviews/Detail.jsx'), 'Module 27 Reviews Detail');
$verifyCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ProviderVerificationController.php');
check(str_contains($verifyCtrl, "Inertia::render('Admin/Verifications/"), 'Module 27 ProviderVerificationController Inertia');
$reviewCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ReviewModerationController.php');
check(str_contains($reviewCtrl, "Inertia::render('Admin/Reviews/"), 'Module 27 ReviewModerationController Inertia');

echo "\nModule 28 — Match score UI\n";
$inertiaRes = file_get_contents(__DIR__ . '/../app/Lib/InertiaResource.php');
check(str_contains($inertiaRes, 'matchScore'), 'Module 28 InertiaResource matchScore');
$jobCardJs = file_get_contents(__DIR__ . '/../resources/js/Components/Jobs/JobCard.jsx');
check(str_contains($jobCardJs, 'matchScore'), 'Module 28 JobCard matchScore UI');

echo "\nModule 29 — Provider approval queue\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Providers/PendingApproval.jsx'), 'Module 29 PendingApproval React');
check(method_exists(\App\Lib\AdminResource::class, 'pendingProviders'), 'Module 29 AdminResource pendingProviders');
$usersCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ManageUsersController.php');
check(str_contains($usersCtrl, "Inertia::render('Admin/Providers/PendingApproval'"), 'Module 29 pending approval Inertia');

echo "\nModule 30 — Phase 3 closure\n";
for ($i = 26; $i <= 30; $i++) {
    check(file_exists(__DIR__ . "/apply-module-{$i}.php"), "Module 30 apply-module-{$i}.php");
}

echo "\nModule 31 — Admin React categories\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Categories/Index.jsx'), 'Module 31 Categories Index');
$catCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ConfigCategoryController.php');
check(str_contains($catCtrl, "Inertia::render('Admin/Categories/"), 'Module 31 ConfigCategoryController');

echo "\nModule 32–33 — Admin React providers\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Users/Index.jsx'), 'Module 32 Users Index');
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Users/Detail.jsx'), 'Module 33 Users Detail');
check(method_exists(\App\Lib\AdminResource::class, 'userDetail'), 'Module 33 AdminResource userDetail');

echo "\nModule 34 — Admin React buyers\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Buyers/Index.jsx'), 'Module 34 Buyers Index');
$buyerCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ManageBuyersController.php');
check(str_contains($buyerCtrl, "Inertia::render('Admin/Buyers/"), 'Module 34 ManageBuyersController');

echo "\nModule 35 — Admin React monetisation\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Monetisation/Settings.jsx'), 'Module 35 Monetisation Settings');
$monCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/MonetisationController.php');
check(str_contains($monCtrl, "Inertia::render('Admin/Monetisation/"), 'Module 35 MonetisationController');

echo "\nModule 36 — Admin deposits & withdrawals\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Deposits/Index.jsx'), 'Module 36 Deposits Index');
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Withdrawals/Index.jsx'), 'Module 36 Withdrawals Index');

echo "\nModule 37 — Admin projects\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Projects/Index.jsx'), 'Module 37 Projects Index');
$projCtrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/Admin/ProjectManagerController.php');
check(str_contains($projCtrl, "Inertia::render('Admin/Projects/"), 'Module 37 ProjectManagerController');

echo "\nModule 38 — Admin marketplace forms\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/MarketplaceForms/Index.jsx'), 'Module 38 MarketplaceForms Index');

echo "\nModule 39 — Postcode outcode matching v3\n";
check(method_exists(\App\Lib\JobMatchingService::class, 'hasPostcodeOutcodeMatch'), 'Module 39 hasPostcodeOutcodeMatch');
$inertiaRes39 = file_get_contents(__DIR__ . '/../app/Lib/InertiaResource.php');
check(str_contains($inertiaRes39, 'postcodeMatch'), 'Module 39 postcodeMatch UI data');

echo "\nModule 40 — Gateway checkout polish\n";
$payRes = file_get_contents(__DIR__ . '/../app/Lib/PaymentResource.php');
check(str_contains($payRes, "'gateway'"), 'Module 40 PaymentResource gateway metadata');

echo "\nModule 41 — Admin support tickets\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Support/Index.jsx'), 'Module 41 Support Index');

echo "\nModule 42 — Admin trial tasks\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Tasks/Index.jsx'), 'Module 42 Tasks Index');

echo "\nModule 43 — Admin transactions & withdraw methods\n";
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/Reports/Transactions.jsx'), 'Module 43 Transactions');
check(file_exists(__DIR__ . '/../resources/js/Pages/Admin/WithdrawMethods/Index.jsx'), 'Module 43 WithdrawMethods');

echo "\nModule 44 — Admin bridge audit\n";
check(file_exists(__DIR__ . '/apply-module-44.php'), 'Module 44 audit script');

echo "\nModule 45 — Phase 4 closure\n";
for ($i = 31; $i <= 45; $i++) {
    check(file_exists(__DIR__ . "/apply-module-{$i}.php"), "Module 45 apply-module-{$i}.php");
}

echo "\n§42 deferred / out-of-scope (informational)\n";
echo "  INFO Settings/CMS admin (general settings, frontend builder, notifications) remain Blade\n";
echo "  INFO Full gateway SDK React rewrites deferred — GatewayCheckout shell + metadata used\n";
echo "  INFO Postcode-radius geo API deferred — outcode prefix matching used (Module 39)\n";

if (ProviderVerification::count() === 0) {
    warn('No provider verification uploads yet (expected until providers submit docs)');
}

echo "\n" . str_repeat('=', 50) . "\n";
echo "Passed: {$pass}  Failed: {$fail}  Warnings: {$warn}\n";

if ($fail > 0) {
    echo "\nRun missing migrations / apply scripts from docs/BLUEPRINT_IMPLEMENTATION.md\n";
    exit(1);
}

echo "\nBlueprint verification passed.\n";
exit(0);
