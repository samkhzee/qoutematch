<?php

/**
 * Module 16 — Monetisation go-live (Blueprint §17, §38 Milestone 6)
 *
 * Builds on Module 12: enables provider monetisation, subscription expiry cron,
 * low-credit alerts, and live pricing page content.
 *
 * Usage:
 *   php scripts/apply-module-16.php           # enable monetisation (default)
 *   php scripts/apply-module-16.php --off     # disable monetisation again
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\GeneralSetting;
use App\Models\LeadCreditPackage;
use App\Models\NotificationTemplate;
use App\Models\SubscriptionPlan;
use App\Lib\SubscriptionExpiryService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

$enable = !in_array('--off', $argv ?? [], true);
$templateName = activeTemplateName();

function upsertNotificationTemplate(string $act, string $name, string $subject, string $pushTitle, string $emailBody, string $smsBody, string $pushBody, array $shortcodes): void
{
    $template = NotificationTemplate::where('act', $act)->first() ?? new NotificationTemplate();
    $template->act = $act;
    $template->name = $name;
    $template->subject = $subject;
    $template->push_title = $pushTitle;
    $template->email_body = $emailBody;
    $template->sms_body = $smsBody;
    $template->push_body = $pushBody;
    $template->shortcodes = $shortcodes;
    $template->email_status = Status::ENABLE;
    $template->sms_status = Status::ENABLE;
    $template->push_status = Status::ENABLE;
    $template->email_sent_from_name = '{{site_name}}';
    $template->save();

    echo "Notification template saved: {$act}\n";
}

function saveContent(string $key, array $values, string $templateName): void
{
    $row = App\Models\Frontend::where('data_keys', $key)->where('tempname', $templateName)->first()
        ?? App\Models\Frontend::where('data_keys', $key)->first()
        ?? new App\Models\Frontend();

    $row->data_keys = $key;
    $row->tempname = $templateName;
    $row->data_values = $values;
    $row->save();
    echo "Content updated: {$key}\n";
}

echo "Module 16 — Monetisation go-live\n";
echo str_repeat('-', 40) . "\n";

if (!Schema::hasColumn('general_settings', 'monetisation_enabled')) {
    echo "MISSING monetisation columns — run Module 12 migration first:\n";
    echo "  php artisan migrate --path=database/migrations/2026_06_25_000007_add_module12_monetisation.php --force\n";
    exit(1);
}

$general = GeneralSetting::first();
if ($general) {
    $general->monetisation_enabled = $enable ? 1 : 0;
    if ($enable) {
        $general->monetisation_mode = 'both';
        $general->quote_credit_cost = $general->quote_credit_cost ?: 1;
        $general->provider_welcome_credits = max(5, (int) ($general->provider_welcome_credits ?? 0));
    }
    $general->save();

    echo 'Monetisation enabled: ' . ((int) $general->monetisation_enabled) . "\n";
    echo 'Mode: ' . ($general->monetisation_mode ?? 'credits') . "\n";
    echo 'Quote credit cost: ' . ($general->quote_credit_cost ?? 1) . "\n";
    echo 'Welcome credits: ' . ($general->provider_welcome_credits ?? 0) . "\n";
}

$packageCount = LeadCreditPackage::active()->count();
$planCount = SubscriptionPlan::active()->count();
echo "Active credit packages: {$packageCount}\n";
echo "Active subscription plans: {$planCount}\n";

if ($packageCount === 0 || $planCount === 0) {
    echo "Tip: run php scripts/apply-module-12.php to seed default packages/plans.\n";
}

upsertNotificationTemplate(
    'LEAD_CREDITS_LOW',
    'Lead Credits Low',
    '{{site_name}} - Lead credits running low',
    '{{site_name}} - Low credits',
    '<div>Your lead credit balance is low on {{site_name}}.</div><div><br></div><div><b>Balance:</b> {{balance}}</div><div><b>Quote cost:</b> {{quote_cost}} credit(s)</div><div><br></div><div><a href="{{credits_url}}">Buy more credits</a></div>',
    'Low lead credits ({{balance}}). Buy more: {{credits_url}}',
    'Your lead credits are running low.',
    [
        'balance' => 'Current credit balance',
        'quote_cost' => 'Credits required per quote',
        'credits_url' => 'Lead credits page URL',
    ]
);

upsertNotificationTemplate(
    'SUBSCRIPTION_EXPIRING',
    'Subscription Expiring Soon',
    '{{site_name}} - Subscription expiring soon',
    '{{site_name}} - Subscription expiring',
    '<div>Your provider subscription on {{site_name}} is expiring soon.</div><div><br></div><div><b>Plan:</b> {{plan}}</div><div><b>Expires:</b> {{expires}}</div><div><b>Days left:</b> {{days}}</div><div><br></div><div><a href="{{credits_url}}">Renew or buy credits</a></div>',
    'Your {{plan}} subscription expires on {{expires}}.',
    'Your subscription expires in {{days}} day(s).',
    [
        'plan' => 'Plan name',
        'expires' => 'Expiry date',
        'days' => 'Days remaining',
        'credits_url' => 'Lead credits page URL',
    ]
);

upsertNotificationTemplate(
    'SUBSCRIPTION_EXPIRED',
    'Subscription Expired',
    '{{site_name}} - Subscription expired',
    '{{site_name}} - Subscription expired',
    '<div>Your provider subscription on {{site_name}} has expired.</div><div><br></div><div><b>Plan:</b> {{plan}}</div><div><b>Expired:</b> {{expired}}</div><div><br></div><div><a href="{{credits_url}}">Renew or buy credits</a></div>',
    'Your {{plan}} subscription expired on {{expired}}.',
    'Your provider subscription has expired.',
    [
        'plan' => 'Plan name',
        'expired' => 'Expiry date',
        'credits_url' => 'Lead credits page URL',
    ]
);

if ($enable) {
    saveContent('for_providers.content', [
        'heading' => 'For Service Providers',
        'subheading' => 'Receive matching customer requests and submit structured quotes.',
        'body' => '<h5>How it works</h5>
<ul>
<li>Register and complete your business profile</li>
<li>Get verified by our admin team</li>
<li>Receive matching leads in your categories and service areas</li>
<li>Submit structured quotes — each new quote uses lead credits unless you have an unlimited plan</li>
</ul>
<h5>Monetisation</h5>
<p>Customer posting stays <strong>free</strong>. Providers can buy lead credit packs or subscribe for unlimited quotes. All paid options are clearly labelled before purchase.</p>
<p>New approved providers receive welcome credits to get started.</p>',
        'button_text' => 'Join as Provider',
        'button_route' => 'provider',
    ], $templateName);

    saveContent('pricing.content', [
        'heading' => 'Pricing',
        'subheading' => 'Customers post free. Providers choose credits or subscriptions.',
        'body' => '<h5>For Customers</h5>
<p><strong>Always free</strong> — post requirements, compare quotes, and message providers at no cost.</p>
<h5>For Service Providers</h5>
<p>Buy lead credit packs or subscribe for unlimited quote submissions. Live package prices appear below when monetisation is enabled.</p>',
        'button_text' => 'Join as Provider',
        'button_route' => 'provider',
    ], $templateName);
}

$result = SubscriptionExpiryService::process();
echo "\nSubscription maintenance:\n";
echo "  Expired: {$result['expired']}\n";
echo "  Expiring soon reminders: {$result['expiring_soon']}\n";
echo "  Cron: subscriptions:process-expiry (daily 01:00 via routes/console.php)\n";
echo "  Manual test: php artisan subscriptions:process-expiry\n";

Cache::flush();

echo "\nProvider monetisation UI:\n";
echo "  /freelancer/lead-credits\n";
echo "  Admin: /admin/monetisation/settings\n";
echo "\nQuote gate: providers need credits (or unlimited plan) to submit new quotes.\n";
echo "\nModule 16 applied.\n";
