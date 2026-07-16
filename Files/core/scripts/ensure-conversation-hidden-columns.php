<?php

/**
 * Ensures conversation hide columns exist (safe to run multiple times).
 * Usage: php scripts/ensure-conversation-hidden-columns.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

if (!Schema::hasTable('conversations')) {
    echo "conversations table not found.\n";
    exit(1);
}

Schema::table('conversations', function (Blueprint $table) {
    if (!Schema::hasColumn('conversations', 'buyer_hidden_at')) {
        $table->timestamp('buyer_hidden_at')->nullable()->after('status');
        echo "Added buyer_hidden_at\n";
    }
    if (!Schema::hasColumn('conversations', 'user_hidden_at')) {
        $table->timestamp('user_hidden_at')->nullable()->after(
            Schema::hasColumn('conversations', 'buyer_hidden_at') ? 'buyer_hidden_at' : 'status'
        );
        echo "Added user_hidden_at\n";
    }
});

if (!Schema::hasColumn('conversations', 'buyer_hidden_at') || !Schema::hasColumn('conversations', 'user_hidden_at')) {
    echo "Failed to add required columns.\n";
    exit(1);
}

$migrationName = '2026_06_23_000001_add_conversation_hidden_columns';
if (!DB::table('migrations')->where('migration', $migrationName)->exists()) {
    DB::table('migrations')->insert([
        'migration' => $migrationName,
        'batch' => (int) DB::table('migrations')->max('batch') + 1,
    ]);
    echo "Recorded migration in migrations table.\n";
}

echo "Done. conversations.buyer_hidden_at and user_hidden_at are ready.\n";
