<?php

/**
 * Module 7 & 8 — Quote comparison notifications + messaging templates (Blueprint §9, §15–16, §42.11–12)
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
    'QUOTE_SHORTLISTED',
    'Quote Shortlisted',
    '{{site_name}} - Your quote was shortlisted',
    '{{site_name}} - Quote shortlisted',
    '<div>Good news! {{customer}} has shortlisted your quote for <b>{{title}}</b>.</div><div><br></div><div>Quote amount: {{amount}}</div><div><br></div><div>Log in to review the request and respond if the customer messages you.</div>',
    '{{customer}} shortlisted your quote for {{title}} ({{amount}}).',
    'Your quote for {{title}} was shortlisted.',
    [
        'title' => 'Request title',
        'customer' => 'Buyer name',
        'amount' => 'Quoted amount',
    ]
);

upsertNotificationTemplate(
    'QUOTE_REVISION_REQUESTED',
    'Quote Revision Requested',
    '{{site_name}} - Revision requested on your quote',
    '{{site_name}} - Quote revision requested',
    '<div>{{customer}} has requested a revision to your quote for <b>{{title}}</b>.</div><div><br></div><div>Current quote: {{amount}}</div><div><br></div><div><b>Revision notes:</b></div><div>{{note}}</div><div><br></div><div>Please update your quote from your provider dashboard.</div>',
    '{{customer}} requested a revision on your quote for {{title}}.',
    'Revision requested on your quote for {{title}}.',
    [
        'title' => 'Request title',
        'customer' => 'Buyer name',
        'amount' => 'Quoted amount',
        'note' => 'Buyer revision notes',
    ]
);

upsertNotificationTemplate(
    'NEW_CHAT_MESSAGE',
    'New Chat Message',
    '{{site_name}} - New message about {{title}}',
    '{{site_name}} - New message',
    '<div>You have a new message from <b>{{sender}}</b> regarding <b>{{title}}</b>.</div><div><br></div><div><i>{{preview}}</i></div><div><br></div><div>Log in to reply in your dashboard.</div>',
    'New message from {{sender}} about {{title}}: {{preview}}',
    'New message from {{sender}} about {{title}}.',
    [
        'title' => 'Request title',
        'sender' => 'Message sender name',
        'preview' => 'Message preview text',
    ]
);

Illuminate\Support\Facades\Cache::flush();
echo "Module 7 & 8 applied.\n";
