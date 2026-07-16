<?php

use App\Constants\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'lead_credits')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedInteger('lead_credits')->default(0)->after('balance');
            });
        }

        if (Schema::hasTable('deposits') && !Schema::hasColumn('deposits', 'user_id')) {
            Schema::table('deposits', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->default(0)->after('buyer_id');
            });
        }

        if (Schema::hasTable('general_settings')) {
            Schema::table('general_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('general_settings', 'monetisation_enabled')) {
                    $table->unsignedTinyInteger('monetisation_enabled')->default(0)->after('review_moderation');
                }
                if (!Schema::hasColumn('general_settings', 'monetisation_mode')) {
                    $table->string('monetisation_mode', 20)->default('credits')->after('monetisation_enabled');
                }
                if (!Schema::hasColumn('general_settings', 'quote_credit_cost')) {
                    $table->unsignedInteger('quote_credit_cost')->default(1)->after('monetisation_mode');
                }
                if (!Schema::hasColumn('general_settings', 'provider_welcome_credits')) {
                    $table->unsignedInteger('provider_welcome_credits')->default(0)->after('quote_credit_cost');
                }
            });
        }

        if (!Schema::hasTable('lead_credit_packages')) {
            Schema::create('lead_credit_packages', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120);
                $table->unsignedInteger('credits');
                $table->unsignedInteger('bonus_credits')->default(0);
                $table->decimal('price', 28, 8);
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedTinyInteger('status')->default(Status::ENABLE);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('subscription_plans')) {
            Schema::create('subscription_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120);
                $table->string('slug', 60)->unique();
                $table->decimal('price', 28, 8);
                $table->unsignedInteger('duration_days')->default(30);
                $table->unsignedInteger('monthly_credits')->default(0);
                $table->unsignedTinyInteger('unlimited_quotes')->default(0);
                $table->text('description')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedTinyInteger('status')->default(Status::ENABLE);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('provider_subscriptions')) {
            Schema::create('provider_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('plan_id');
                $table->decimal('price_paid', 28, 8)->default(0);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->unsignedTinyInteger('status')->default(Status::SUBSCRIPTION_ACTIVE);
                $table->timestamps();

                $table->index(['user_id', 'status']);
            });
        }

        if (!Schema::hasTable('lead_credit_logs')) {
            Schema::create('lead_credit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->integer('credits');
                $table->unsignedInteger('balance_after')->default(0);
                $table->string('remark', 60);
                $table->string('trx', 40)->nullable();
                $table->unsignedBigInteger('bid_id')->nullable();
                $table->unsignedBigInteger('deposit_id')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_credit_logs');
        Schema::dropIfExists('provider_subscriptions');
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('lead_credit_packages');

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'lead_credits')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('lead_credits');
            });
        }

        if (Schema::hasTable('deposits') && Schema::hasColumn('deposits', 'user_id')) {
            Schema::table('deposits', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        if (Schema::hasTable('general_settings')) {
            Schema::table('general_settings', function (Blueprint $table) {
                foreach (['monetisation_enabled', 'monetisation_mode', 'quote_credit_cost', 'provider_welcome_credits'] as $column) {
                    if (Schema::hasColumn('general_settings', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
