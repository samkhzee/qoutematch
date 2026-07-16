<?php

/**
 * Module 9 — Verification badges (Blueprint §13, §42.15)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\NotificationTemplate;

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

upsertNotificationTemplate(
    'PROVIDER_VERIFICATION_APPROVED',
    'Provider Verification Approved',
    '{{site_name}} - {{type}} approved',
    '{{site_name}} - Verification approved',
    '<div>Good news! Your <b>{{type}}</b> verification has been approved on {{site_name}}.</div><div><br></div><div>Customers can now see this badge on your profile and quotes.</div>',
    'Your {{type}} verification was approved.',
    'Your {{type}} verification was approved.',
    [
        'type' => 'Verification type label',
        'provider' => 'Provider name',
    ]
);

upsertNotificationTemplate(
    'PROVIDER_VERIFICATION_REJECTED',
    'Provider Verification Rejected',
    '{{site_name}} - {{type}} needs resubmission',
    '{{site_name}} - Verification rejected',
    '<div>Your <b>{{type}}</b> verification could not be approved.</div><div><br></div><div><b>Reason:</b></div><div>{{note}}</div><div><br></div><div>Please upload a corrected document from your provider dashboard.</div>',
    'Your {{type}} verification was rejected. Reason: {{note}}',
    'Your {{type}} verification was rejected.',
    [
        'type' => 'Verification type label',
        'provider' => 'Provider name',
        'note' => 'Admin rejection note',
    ]
);

upsertNotificationTemplate(
    'PROVIDER_APPROVED',
    'Provider Account Approved',
    '{{site_name}} - Your provider account is approved',
    '{{site_name}} - Provider approved',
    '<div>Congratulations {{provider}}!</div><div><br></div><div>Your provider account on {{site_name}} has been approved. You can now receive matching requests and submit quotes.</div><div><br></div><div>Complete your verification badges to stand out to customers.</div>',
    'Your provider account on {{site_name}} has been approved.',
    'Your provider account has been approved.',
    [
        'provider' => 'Provider full name',
    ]
);

define('KYC_FORMS_ALREADY_BOOTSTRAPPED', true);
require __DIR__ . '/seed-kyc-forms.php';

Illuminate\Support\Facades\Cache::flush();
echo "Module 9 verification badge templates applied.\n";
