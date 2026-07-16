<?php

/**
 * Module 12 — Monetisation: lead credits & subscriptions (Blueprint §17, §38 Milestone 6)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\GeneralSetting;
use App\Models\LeadCreditPackage;
use App\Models\NotificationTemplate;
use App\Models\SubscriptionPlan;

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

echo "Module 12 — Monetisation\n";
echo str_repeat('-', 40) . "\n";

$general = GeneralSetting::first();
if ($general) {
    if (!isset($general->monetisation_enabled)) {
        echo "Run: php artisan migrate (module 12 migration required)\n";
    } else {
        echo "Monetisation enabled: " . ((int) $general->monetisation_enabled) . " (0 = free MVP default)\n";
        echo "Mode: " . ($general->monetisation_mode ?? 'credits') . "\n";
        echo "Quote credit cost: " . ($general->quote_credit_cost ?? 1) . "\n";
    }
}

$packages = [
    ['Starter Pack', 10, 0, 29.00, 1],
    ['Growth Pack', 25, 5, 59.00, 2],
    ['Pro Pack', 60, 15, 119.00, 3],
];

foreach ($packages as [$name, $credits, $bonus, $price, $sort]) {
    LeadCreditPackage::updateOrCreate(
        ['name' => $name],
        [
            'credits' => $credits,
            'bonus_credits' => $bonus,
            'price' => $price,
            'sort_order' => $sort,
            'status' => Status::ENABLE,
        ]
    );
    echo "Credit package: {$name}\n";
}

$plans = [
    ['Provider Pro', 'provider-pro', 49.00, 30, 0, 1, 'Unlimited quote submissions for 30 days.', 1],
    ['Provider Plus', 'provider-plus', 29.00, 30, 20, 0, '20 bonus lead credits each month.', 2],
];

foreach ($plans as [$name, $slug, $price, $days, $monthlyCredits, $unlimited, $desc, $sort]) {
    SubscriptionPlan::updateOrCreate(
        ['slug' => $slug],
        [
            'name' => $name,
            'price' => $price,
            'duration_days' => $days,
            'monthly_credits' => $monthlyCredits,
            'unlimited_quotes' => $unlimited,
            'description' => $desc,
            'sort_order' => $sort,
            'status' => Status::ENABLE,
        ]
    );
    echo "Subscription plan: {$name}\n";
}

upsertNotificationTemplate(
    'LEAD_CREDITS_PURCHASED',
    'Lead Credits Purchased',
    '{{site_name}} - Lead credits added',
    '{{site_name}} - Credits purchased',
    '<div>Your purchase of <b>{{package}}</b> was successful.</div><div><br></div><div><b>Credits added:</b> {{credits}}</div><div><b>New balance:</b> {{balance}}</div>',
    'You received {{credits}} lead credits. Balance: {{balance}}.',
    'Lead credits added to your account.',
    [
        'package' => 'Package name',
        'credits' => 'Credits added',
        'balance' => 'New credit balance',
        'amount' => 'Amount paid',
        'trx' => 'Transaction reference',
    ]
);

upsertNotificationTemplate(
    'SUBSCRIPTION_ACTIVATED',
    'Subscription Activated',
    '{{site_name}} - Subscription active',
    '{{site_name}} - Subscription active',
    '<div>Your subscription is now active on {{site_name}}.</div><div><br></div><div><b>Plan:</b> {{plan}}</div><div><b>Valid until:</b> {{expires}}</div>',
    'Your {{plan}} subscription is active until {{expires}}.',
    'Your provider subscription is now active.',
    [
        'plan' => 'Plan name',
        'expires' => 'Expiry date',
        'amount' => 'Amount paid',
        'trx' => 'Transaction reference',
    ]
);

Illuminate\Support\Facades\Cache::flush();

echo "\nAdmin UI:\n";
echo "  Settings:  /admin/monetisation/settings\n";
echo "  Packages:  /admin/monetisation/packages\n";
echo "  Plans:     /admin/monetisation/plans\n";
echo "\nProvider UI: /freelancer/lead-credits (when monetisation enabled)\n";
echo "\nModule 12 applied. Monetisation is OFF by default — enable in admin when ready.\n";
