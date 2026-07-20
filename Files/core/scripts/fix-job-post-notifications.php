<?php

/**
 * Ensure job approval / submission email templates are complete and enabled.
 * Usage: php scripts/fix-job-post-notifications.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\NotificationTemplate;

$approvedShortcodes = [
    'job' => 'Approved job title',
    'title' => 'Approved job title',
    'link' => 'Buyer dashboard link to view the job',
    'job_link' => 'Public Find Jobs link for the request',
    'browse_link' => 'Browse all freelance jobs',
    'deadline' => 'Quote deadline',
    'budget' => 'Job budget',
];

$rejectedShortcodes = array_merge($approvedShortcodes, [
    'reason' => 'Reject reason of this job',
]);

$submittedShortcodes = $approvedShortcodes;

NotificationTemplate::unguard();

$approved = NotificationTemplate::firstOrNew(['act' => 'JOB_APPROVED']);
$approved->name = 'Job-Post-Approved';
$approved->subject = 'Your job request is now live on {{site_name}}';
$approved->email_status = Status::ENABLE;
$approved->sms_status = $approved->exists ? $approved->sms_status : Status::DISABLE;
$approved->shortcodes = $approvedShortcodes;
$approved->email_body = 'Hi {{fullname}},<br><br>'
    . 'Good news — your job request <strong>{{job}}</strong> has been approved and is now live on Find Jobs.<br><br>'
    . 'Budget: {{budget}}<br>'
    . 'Deadline: {{deadline}}<br><br>'
    . '<a href="{{job_link}}">View your live request</a><br>'
    . '<a href="{{link}}">Manage this request in your account</a><br><br>'
    . 'Providers can now send you quotes.<br><br>'
    . 'Thanks,<br>{{site_name}}';
$approved->sms_body = $approved->sms_body ?: '[{{job}}] has been approved and is live.';
$approved->push_status = $approved->exists ? $approved->push_status : Status::DISABLE;
$approved->save();
echo "Updated JOB_APPROVED\n";

$rejected = NotificationTemplate::firstOrNew(['act' => 'JOB_REJECTED']);
$rejected->name = 'Job-Post-Rejected';
$rejected->subject = 'Update on your job request on {{site_name}}';
$rejected->email_status = Status::ENABLE;
$rejected->sms_status = $rejected->exists ? $rejected->sms_status : Status::DISABLE;
$rejected->shortcodes = $rejectedShortcodes;
$rejected->email_body = 'Hi {{fullname}},<br><br>'
    . 'Your job request <strong>{{job}}</strong> was not approved.<br><br>'
    . 'Reason: {{reason}}<br><br>'
    . '<a href="{{link}}">Open your request</a> to review or edit and resubmit.<br><br>'
    . 'Thanks,<br>{{site_name}}';
$rejected->sms_body = $rejected->sms_body ?: '[{{job}}] was rejected. Reason: {{reason}}';
$rejected->push_status = $rejected->exists ? $rejected->push_status : Status::DISABLE;
$rejected->save();
echo "Updated JOB_REJECTED\n";

$submitted = NotificationTemplate::firstOrNew(['act' => 'JOB_SUBMITTED']);
$submitted->name = 'Job-Post-Submitted';
$submitted->subject = 'We received your job request on {{site_name}}';
$submitted->email_status = Status::ENABLE;
$submitted->sms_status = Status::DISABLE;
$submitted->push_status = Status::DISABLE;
$submitted->shortcodes = $submittedShortcodes;
$submitted->email_body = 'Hi {{fullname}},<br><br>'
    . 'Thanks for posting <strong>{{job}}</strong>. Our team is reviewing it now.<br><br>'
    . 'Budget: {{budget}}<br>'
    . 'Deadline: {{deadline}}<br><br>'
    . 'You will get another email as soon as it is approved and live on Find Jobs.<br><br>'
    . '<a href="{{link}}">Track your request</a><br><br>'
    . 'Thanks,<br>{{site_name}}';
$submitted->sms_body = 'We received your job request [{{job}}]. We will email you when it is approved.';
$submitted->save();
echo "Updated JOB_SUBMITTED\n";

NotificationTemplate::reguard();

echo "Done.\n";
