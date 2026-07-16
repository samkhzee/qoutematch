<?php

/**
 * Add balance (credits) to a provider account.
 * Usage:
 *   php scripts/add-user-balance.php --list
 *   php scripts/add-user-balance.php 5000
 *   php scripts/add-user-balance.php 5000 1
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transaction;
use App\Models\User;

$amount = (float) ($argv[1] ?? 5000);
$userId = isset($argv[2]) ? (int) $argv[2] : null;
$listOnly = in_array('--list', $argv, true);

$users = User::select('id', 'username', 'email', 'balance')->orderBy('id')->get();
echo "Providers:\n";
foreach ($users as $u) {
    echo "{$u->id} | {$u->username} | {$u->email} | balance: {$u->balance}\n";
}

if ($listOnly) {
    exit(0);
}

$user = $userId ? User::findOrFail($userId) : $users->sortByDesc('id')->first();
if (!$user) {
    echo "No provider found.\n";
    exit(1);
}

$trx = getTrx();
$user->balance += $amount;
$user->save();

$transaction = new Transaction();
$transaction->user_id = $user->id;
$transaction->amount = $amount;
$transaction->post_balance = $user->balance;
$transaction->charge = 0;
$transaction->trx_type = '+';
$transaction->remark = 'balance_add';
$transaction->trx = $trx;
$transaction->details = 'Manual credit top-up for testing';
$transaction->save();

echo "Done. Provider #{$user->id} ({$user->email}) new balance: {$user->balance}\n";
