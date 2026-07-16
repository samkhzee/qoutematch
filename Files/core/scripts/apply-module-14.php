<?php

/**
 * Module 14 — Notifications hub (Blueprint §16, §42 #17)
 *
 * Consolidates email / SMS / push templates for Modules 7–13, enables in-app
 * notification history for providers and buyers, and verifies deadline cron.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

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

echo "Module 14 — Notifications hub\n";
echo str_repeat('-', 40) . "\n";

// Module 7–8: quote comparison & messaging
upsertNotificationTemplate(
    'QUOTE_SHORTLISTED',
    'Quote Shortlisted',
    '{{site_name}} - Your quote was shortlisted',
    '{{site_name}} - Quote shortlisted',
    '<div>Good news! {{customer}} has shortlisted your quote for <b>{{title}}</b>.</div><div><br></div><div>Quote amount: {{amount}}</div><div><br></div><div>Log in to review the request and respond if the customer messages you.</div>',
    '{{customer}} shortlisted your quote for {{title}} ({{amount}}).',
    'Your quote for {{title}} was shortlisted.',
    ['title' => 'Request title', 'customer' => 'Buyer name', 'amount' => 'Quoted amount']
);

upsertNotificationTemplate(
    'QUOTE_REVISION_REQUESTED',
    'Quote Revision Requested',
    '{{site_name}} - Revision requested on your quote',
    '{{site_name}} - Quote revision requested',
    '<div>{{customer}} has requested a revision to your quote for <b>{{title}}</b>.</div><div><br></div><div>Current quote: {{amount}}</div><div><br></div><div><b>Revision notes:</b></div><div>{{note}}</div><div><br></div><div>Please update your quote from your provider dashboard.</div>',
    '{{customer}} requested a revision on your quote for {{title}}.',
    'Revision requested on your quote for {{title}}.',
    ['title' => 'Request title', 'customer' => 'Buyer name', 'amount' => 'Quoted amount', 'note' => 'Buyer revision notes']
);

upsertNotificationTemplate(
    'NEW_CHAT_MESSAGE',
    'New Chat Message',
    '{{site_name}} - New message about {{title}}',
    '{{site_name}} - New message',
    '<div>You have a new message from <b>{{sender}}</b> regarding <b>{{title}}</b>.</div><div><br></div><div><i>{{preview}}</i></div><div><br></div><div>Log in to reply in your dashboard.</div>',
    'New message from {{sender}} about {{title}}: {{preview}}',
    'New message from {{sender}} about {{title}}.',
    ['title' => 'Request title', 'sender' => 'Message sender name', 'preview' => 'Message preview text']
);

// Module 9: provider approval & verification
upsertNotificationTemplate(
    'PROVIDER_APPROVED',
    'Provider Account Approved',
    '{{site_name}} - Your provider account is approved',
    '{{site_name}} - Provider approved',
    '<div>Congratulations {{provider}}!</div><div><br></div><div>Your provider account on {{site_name}} has been approved. You can now receive matching requests and submit quotes.</div>',
    'Your provider account on {{site_name}} has been approved.',
    'Your provider account has been approved.',
    ['provider' => 'Provider full name']
);

upsertNotificationTemplate(
    'PROVIDER_VERIFICATION_APPROVED',
    'Provider Verification Approved',
    '{{site_name}} - {{type}} approved',
    '{{site_name}} - Verification approved',
    '<div>Good news! Your <b>{{type}}</b> verification has been approved on {{site_name}}.</div>',
    'Your {{type}} verification was approved.',
    'Your {{type}} verification was approved.',
    ['type' => 'Verification type label', 'provider' => 'Provider name']
);

upsertNotificationTemplate(
    'PROVIDER_VERIFICATION_REJECTED',
    'Provider Verification Rejected',
    '{{site_name}} - {{type}} needs resubmission',
    '{{site_name}} - Verification rejected',
    '<div>Your <b>{{type}}</b> verification could not be approved.</div><div><br></div><div><b>Reason:</b></div><div>{{note}}</div>',
    'Your {{type}} verification was rejected. Reason: {{note}}',
    'Your {{type}} verification was rejected.',
    ['type' => 'Verification type label', 'provider' => 'Provider name', 'note' => 'Admin rejection note']
);

// Module 10: reviews & invitations
upsertNotificationTemplate(
    'REVIEW_APPROVED',
    'Review Published',
    '{{site_name}} - Your review is now live',
    '{{site_name}} - Review published',
    '<div>Good news! A customer review for <b>{{job}}</b> has been approved and is now visible on your profile.</div><div><br></div><div><b>Overall rating:</b> {{rating}}/5</div>',
    'Your review for {{job}} was approved ({{rating}}/5).',
    'A customer review was approved ({{rating}}/5).',
    ['buyer' => 'Customer name', 'rating' => 'Overall rating', 'job' => 'Project / request title']
);

upsertNotificationTemplate(
    'FREELANCER_INVITATION',
    'Freelancer Invitation',
    '{{site_name}} - {{buyer}} invited you to quote',
    '{{site_name}} - New quote invitation',
    '<div><b>{{buyer}}</b> invited you to submit a quote on {{active_post}} active request(s) on {{site_name}}.</div><div><br></div><div><a href="{{post_page}}">View all requests</a></div>',
    '{{buyer}} invited you to quote. View requests: {{post_page}}',
    '{{buyer}} invited you to submit a quote.',
    ['buyer' => 'Customer name', 'active_post' => 'Number of active requests', 'post_page' => 'Filtered requests page URL', 'job_title' => 'Latest request title', 'job_link' => 'Latest request detail URL']
);

// Module 12: monetisation
upsertNotificationTemplate(
    'LEAD_CREDITS_PURCHASED',
    'Lead Credits Purchased',
    '{{site_name}} - Lead credits added',
    '{{site_name}} - Credits purchased',
    '<div>Your purchase of <b>{{package}}</b> was successful.</div><div><br></div><div><b>Credits added:</b> {{credits}}</div><div><b>New balance:</b> {{balance}}</div>',
    'You received {{credits}} lead credits. Balance: {{balance}}.',
    'Lead credits added to your account.',
    ['package' => 'Package name', 'credits' => 'Credits added', 'balance' => 'New credit balance', 'amount' => 'Amount paid', 'trx' => 'Transaction reference']
);

upsertNotificationTemplate(
    'SUBSCRIPTION_ACTIVATED',
    'Subscription Activated',
    '{{site_name}} - Subscription active',
    '{{site_name}} - Subscription active',
    '<div>Your subscription is now active on {{site_name}}.</div><div><br></div><div><b>Plan:</b> {{plan}}</div><div><b>Valid until:</b> {{expires}}</div>',
    'Your {{plan}} subscription is active until {{expires}}.',
    'Your provider subscription is now active.',
    ['plan' => 'Plan name', 'expires' => 'Expiry date', 'amount' => 'Amount paid', 'trx' => 'Transaction reference']
);

// Module 13: disputes & deadlines
upsertNotificationTemplate(
    'DISPUTE_OPENED',
    'Dispute Opened',
    'A dispute was opened — {{subject}}',
    'Dispute opened',
    '<p>A dispute has been opened regarding <strong>{{request}}</strong>.</p><p><strong>Subject:</strong> {{subject}}</p><p><strong>Raised by:</strong> {{raised_by}}</p><p>{{description}}</p>',
    'Dispute opened: {{subject}}',
    'Dispute opened on {{request}}',
    ['subject' => 'Dispute subject', 'request' => 'Request title', 'raised_by' => 'Buyer or provider', 'description' => 'Summary']
);

upsertNotificationTemplate(
    'DISPUTE_RESOLVED',
    'Dispute Closed',
    'Dispute update — {{subject}}',
    'Dispute closed',
    '<p>Your dispute regarding <strong>{{request}}</strong> has been closed by an administrator.</p><p><strong>Admin note:</strong> {{admin_note}}</p>',
    'Dispute closed: {{subject}}',
    'Dispute on {{request}} was closed',
    ['subject' => 'Dispute subject', 'request' => 'Request title', 'admin_note' => 'Administrator note']
);

upsertNotificationTemplate(
    'QUOTE_DEADLINE_EXPIRED',
    'Quote Deadline Expired',
    'Quote deadline expired — {{request}}',
    'Quote deadline expired',
    '<p>The quote deadline for <strong>{{request}}</strong> has passed (deadline: {{deadline}}).</p><p>This request stays on Find Jobs for {{grace_days}} days so providers can still submit quotes.</p><p><a href="{{link}}">View request</a></p>',
    'Quote deadline expired for {{request}}. Still open for {{grace_days}} days.',
    'Quote deadline expired — {{request}}',
    ['request' => 'Request title', 'deadline' => 'Original deadline', 'grace_days' => 'Grace days on Find Jobs', 'link' => 'Request URL']
);

upsertNotificationTemplate(
    'PROJECT_REPORTED',
    'Project Reported',
    '{{site_name}} - A project was reported',
    '{{site_name}} - Project reported',
    '<div>A project regarding <b>{{title}}</b> has been reported.</div><div><br></div><div><b>Reason:</b></div><div>{{reason}}</div>',
    'Project reported: {{title}}',
    'A project was reported: {{title}}',
    ['title' => 'Project title', 'reason' => 'Report reason', 'reporter' => 'Reporter name']
);

$templateCount = NotificationTemplate::count();
echo "\nTotal notification templates: {$templateCount}\n";

if (Schema::hasColumn('jobs', 'deadline_expired_notified_at')) {
    $notified = \App\Lib\QuoteDeadlineService::processExpiryNotifications();
    echo "Expired deadline notifications processed: {$notified}\n";
    echo "Cron: quotes:notify-expired-deadlines (daily 00:30 via routes/console.php)\n";
    echo "Manual test: php artisan quotes:notify-expired-deadlines\n";
} else {
    echo "Run migration 2026_07_01_000009 for expired deadline notifications\n";
}

Cache::flush();

// Fix broken default email logo (dead imgur link) — use site logo shortcode
$general = \App\Models\GeneralSetting::first();
if ($general && $general->email_template) {
    $template = $general->email_template;
    $template = preg_replace(
        '#https?://i\.imgur\.com/[^"\']+#',
        '{{site_logo}}',
        $template
    );
    $template = str_replace('href="#"', 'href="{{site_url}}"', $template);
    if ($template !== $general->email_template) {
        $general->email_template = $template;
        $general->save();
        echo "Email template logo updated to {{site_logo}}\n";
    } else {
        echo "Email template logo already configured\n";
    }
}

echo "\nUser-facing notification history:\n";
echo "  Provider: /freelancer/notifications\n";
echo "  Buyer:    /buyer/notifications\n";
echo "  Admin log: /admin/report/notification/history\n";
echo "\nModule 14 applied.\n";
