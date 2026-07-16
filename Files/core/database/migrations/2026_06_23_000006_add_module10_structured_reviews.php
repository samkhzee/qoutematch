<?php

use App\Constants\ReviewDimension;
use App\Constants\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasColumn('reviews', 'scores')) {
                    $table->json('scores')->nullable()->after('rating');
                }
                if (!Schema::hasColumn('reviews', 'admin_note')) {
                    $table->text('admin_note')->nullable()->after('status');
                }
                if (!Schema::hasColumn('reviews', 'moderated_at')) {
                    $table->timestamp('moderated_at')->nullable()->after('admin_note');
                }
                if (!Schema::hasColumn('reviews', 'moderated_by')) {
                    $table->unsignedBigInteger('moderated_by')->nullable()->after('moderated_at');
                }
            });

            $reviews = DB::table('reviews')->select('id', 'rating', 'scores', 'status')->get();
            foreach ($reviews as $review) {
                $scores = $review->scores ? json_decode($review->scores, true) : null;
                if (!is_array($scores) || empty($scores)) {
                    $rating = max(1, min(5, (int) $review->rating));
                    $scores = array_fill_keys(ReviewDimension::keys(), $rating);
                }

                DB::table('reviews')->where('id', $review->id)->update([
                    'scores' => json_encode($scores),
                    'status' => Status::REVIEW_APPROVED,
                ]);
            }
        }

        if (Schema::hasTable('general_settings') && !Schema::hasColumn('general_settings', 'review_moderation')) {
            Schema::table('general_settings', function (Blueprint $table) {
                $table->unsignedTinyInteger('review_moderation')->default(0)->after('system_customized');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                foreach (['scores', 'admin_note', 'moderated_at', 'moderated_by'] as $column) {
                    if (Schema::hasColumn('reviews', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('general_settings') && Schema::hasColumn('general_settings', 'review_moderation')) {
            Schema::table('general_settings', function (Blueprint $table) {
                $table->dropColumn('review_moderation');
            });
        }
    }
};
