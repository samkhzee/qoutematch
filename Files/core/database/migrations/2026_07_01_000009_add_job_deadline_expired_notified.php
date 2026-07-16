<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jobs') && !Schema::hasColumn('jobs', 'deadline_expired_notified_at')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->timestamp('deadline_expired_notified_at')->nullable()->after('deadline');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('jobs') && Schema::hasColumn('jobs', 'deadline_expired_notified_at')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->dropColumn('deadline_expired_notified_at');
            });
        }
    }
};
