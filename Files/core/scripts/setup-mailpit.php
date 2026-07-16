<?php

/**
 * Configure local email via Laragon Mailpit (SMTP 127.0.0.1:1025).
 * Inbox UI: http://127.0.0.1:8025
 *
 * Usage: php scripts/setup-mailpit.php
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$general = \App\Models\GeneralSetting::first();

if (!$general) {
    fwrite(STDERR, "general_settings row not found.\n");
    exit(1);
}

$general->mail_config = (object) [
    'name' => 'smtp',
    'host' => '127.0.0.1',
    'port' => '1025',
    'enc' => 'none',
    'username' => '',
    'password' => '',
];
$general->email_from = 'noreply@quotematch.test';
$general->email_from_name = $general->site_name ?: 'QuoteMatch';
$general->en = 1;
$general->save();

\Illuminate\Support\Facades\Cache::forget('GeneralSetting');

echo "Mail configured for Laragon Mailpit.\n";
echo "SMTP: 127.0.0.1:1025\n";
echo "Inbox: http://127.0.0.1:8025\n";
echo "From: {$general->email_from}\n";
