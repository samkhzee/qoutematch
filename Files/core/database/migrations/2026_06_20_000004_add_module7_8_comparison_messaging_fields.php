<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            if (!Schema::hasColumn('bids', 'revision_requested_at')) {
                $table->timestamp('revision_requested_at')->nullable()->after('is_shortlist');
            }
            if (!Schema::hasColumn('bids', 'revision_note')) {
                $table->text('revision_note')->nullable()->after('revision_requested_at');
            }
        });

        Schema::table('conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('conversations', 'job_id')) {
                $table->unsignedBigInteger('job_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('conversations', 'bid_id')) {
                $table->unsignedBigInteger('bid_id')->nullable()->after('job_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bids', function (Blueprint $table) {
            if (Schema::hasColumn('bids', 'revision_note')) {
                $table->dropColumn('revision_note');
            }
            if (Schema::hasColumn('bids', 'revision_requested_at')) {
                $table->dropColumn('revision_requested_at');
            }
        });

        Schema::table('conversations', function (Blueprint $table) {
            if (Schema::hasColumn('conversations', 'bid_id')) {
                $table->dropColumn('bid_id');
            }
            if (Schema::hasColumn('conversations', 'job_id')) {
                $table->dropColumn('job_id');
            }
        });
    }
};
