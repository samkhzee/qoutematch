<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Constants\Status;
use App\Models\User;

$email = 'john.doe@example.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "User not found: {$email}\n";
    exit(1);
}

echo "Before: id={$user->id} ts={$user->ts} tv={$user->tv} tsc=" . ($user->tsc ? 'set' : 'null') . "\n";

$user->ts = Status::DISABLE;
$user->tv = Status::VERIFIED;
$user->tsc = null;
$user->save();

echo "After:  id={$user->id} ts={$user->ts} tv={$user->tv} tsc=" . ($user->tsc ? 'set' : 'null') . "\n";
echo "2FA disabled for {$email}. Admin panel toggle at /admin/freelancers/detail/{id} still works.\n";
