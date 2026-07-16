<?php

/**
 * Module 13 — Admin dashboard extensions (Blueprint §6.4, §31–32)
 *
 * Marketplace analytics hub, structured quote admin detail, disputes MVP.
 */
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\Dispute;
use App\Models\NotificationTemplate;
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

echo "Module 13 — Admin dashboard extensions\n";
echo str_repeat('-', 40) . "\n";

if (!Schema::hasTable('disputes')) {
    echo "MISSING disputes table — run:\n";
    echo "  php artisan migrate --path=database/migrations/2026_06_25_000008_add_module13_marketplace_admin.php --force\n";
} else {
    echo "OK  disputes table\n";
    echo "Open disputes: " . Dispute::active()->count() . "\n";
}

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

if (Schema::hasColumn('jobs', 'deadline_expired_notified_at')) {
    $notified = \App\Lib\QuoteDeadlineService::processExpiryNotifications();
    echo "Expired deadline notifications sent: {$notified}\n";
} else {
    echo "Run migration 2026_07_01_000009 for expired deadline notifications\n";
}

echo "\nAdmin UI:\n";
echo "  /admin/marketplace/dashboard\n";
echo "  /admin/disputes\n";
echo "  /admin/bids/detail/{id}\n";
echo "\nDone.\n";
