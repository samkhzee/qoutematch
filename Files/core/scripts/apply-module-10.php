<?php

/**
 * Module 10 — Structured reviews (Blueprint §14, §42.14)
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\GeneralSetting;
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
    'REVIEW_APPROVED',
    'Review Published',
    '{{site_name}} - Your review is now live',
    '{{site_name}} - Review published',
    '<div>Good news! A customer review for <b>{{job}}</b> has been approved and is now visible on your profile.</div><div><br></div><div><b>Overall rating:</b> {{rating}}/5</div>',
    'Your review for {{job}} was approved ({{rating}}/5).',
    'A customer review was approved ({{rating}}/5).',
    [
        'buyer' => 'Customer name',
        'rating' => 'Overall rating',
        'job' => 'Project / request title',
    ]
);

upsertNotificationTemplate(
    'REVIEW_PENDING_ADMIN',
    'Review Pending Moderation',
    '{{site_name}} - New review awaiting moderation',
    '{{site_name}} - Review pending',
    '<div>A new structured review was submitted for provider <b>{{provider}}</b>.</div><div><br></div><div><b>Project:</b> {{job}}</div><div><b>Overall rating:</b> {{rating}}/5</div>',
    'New review pending moderation for {{provider}}.',
    'New review pending moderation.',
    [
        'provider' => 'Provider name',
        'buyer' => 'Customer name',
        'rating' => 'Overall rating',
        'job' => 'Project / request title',
    ]
);

$general = GeneralSetting::first();
if ($general && property_exists($general, 'review_moderation')) {
    echo "Review moderation setting available (review_moderation = " . (int) $general->review_moderation . ").\n";
    echo "Set review_moderation = 1 in general_settings to require admin approval before reviews go live.\n";
}

Illuminate\Support\Facades\Cache::flush();
echo "Module 10 structured review templates applied.\n";

upsertNotificationTemplate(
    'FREELANCER_INVITATION',
    'Freelancer Invitation',
    '{{site_name}} - {{buyer}} invited you to quote',
    '{{site_name}} - New quote invitation',
    '<div><b>{{buyer}}</b> invited you to submit a quote on {{active_post}} active request(s) on {{site_name}}.</div><div><br></div><div><a href="{{post_page}}">View all requests</a></div><div><a href="{{job_link}}">Open latest request</a></div>',
    '{{buyer}} invited you to quote. View requests: {{post_page}}',
    '{{buyer}} invited you to submit a quote.',
    [
        'buyer' => 'Customer name',
        'active_post' => 'Number of active requests',
        'post_page' => 'Filtered requests page URL',
        'job_title' => 'Latest request title',
        'job_link' => 'Latest request detail URL',
    ]
);

echo "Freelancer invitation template updated.\n";
