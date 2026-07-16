<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Buyer;
use App\Models\Transaction;

$amount = (float) ($argv[1] ?? 5000);
$buyerId = isset($argv[2]) ? (int) $argv[2] : null;
$listOnly = in_array('--list', $argv, true);

$buyers = Buyer::select('id', 'username', 'email', 'balance')->orderBy('id')->get();
echo "Buyers:\n";
foreach ($buyers as $b) {
    echo "{$b->id} | {$b->username} | {$b->email} | balance: {$b->balance}\n";
}

if ($listOnly) {
    exit(0);
}

if ($buyerId) {
    $buyer = Buyer::findOrFail($buyerId);
} else {
    $buyer = $buyers->sortByDesc('id')->first();
    if (!$buyer) {
        echo "No buyer found.\n";
        exit(1);
    }
    echo "\nAdding {$amount} to latest buyer #{$buyer->id} ({$buyer->email})\n";
}

$trx = getTrx();
$buyer->balance += $amount;
$buyer->save();

$transaction = new Transaction();
$transaction->buyer_id = $buyer->id;
$transaction->amount = $amount;
$transaction->post_balance = $buyer->balance;
$transaction->charge = 0;
$transaction->trx_type = '+';
$transaction->remark = 'balance_add';
$transaction->trx = $trx;
$transaction->details = 'Manual credit top-up for testing';
$transaction->save();

echo "Done. Buyer #{$buyer->id} new balance: {$buyer->balance}\n";
