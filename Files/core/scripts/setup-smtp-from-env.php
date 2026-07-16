<?php

/**
 * Apply Gmail/real SMTP settings from .env to general_settings.
 *
 * Required in .env:
 *   MAIL_HOST=smtp.gmail.com
 *   MAIL_PORT=587
 *   MAIL_ENCRYPTION=tls
 *   MAIL_USERNAME=your@gmail.com
 *   MAIL_PASSWORD=your-gmail-app-password
 *   MAIL_FROM_ADDRESS=your@gmail.com
 *   MAIL_FROM_NAME=QuoteMatch
 *
 * Usage: php scripts/setup-smtp-from-env.php
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$username = trim((string) env('MAIL_USERNAME', ''));
$password = trim((string) env('MAIL_PASSWORD', ''));

if ($username === '' || $password === '') {
    fwrite(STDERR, "Missing MAIL_USERNAME or MAIL_PASSWORD in .env\n");
    fwrite(STDERR, "For Gmail: use an App Password from https://myaccount.google.com/apppasswords\n");
    exit(1);
}

\App\Lib\MailConfigurator::syncFromEnv();

echo "SMTP configured from .env\n";
echo "Host: " . env('MAIL_HOST', 'smtp.gmail.com') . ':' . env('MAIL_PORT', '587') . "\n";
echo "From: " . env('MAIL_FROM_ADDRESS', $username) . "\n";
