<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('conversations', 'buyer_hidden_at')) {
                $table->timestamp('buyer_hidden_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('conversations', 'user_hidden_at')) {
                $table->timestamp('user_hidden_at')->nullable()->after('buyer_hidden_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            if (Schema::hasColumn('conversations', 'user_hidden_at')) {
                $table->dropColumn('user_hidden_at');
            }
            if (Schema::hasColumn('conversations', 'buyer_hidden_at')) {
                $table->dropColumn('buyer_hidden_at');
            }
        });
    }
};
