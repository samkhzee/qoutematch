<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'quote_form_id')) {
                $table->unsignedBigInteger('quote_form_id')->nullable()->after('request_form_id');
            }
        });

        Schema::table('bids', function (Blueprint $table) {
            if (!Schema::hasColumn('bids', 'quote_data')) {
                $table->json('quote_data')->nullable()->after('bid_quote');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'quote_form_id')) {
                $table->dropColumn('quote_form_id');
            }
        });

        Schema::table('bids', function (Blueprint $table) {
            if (Schema::hasColumn('bids', 'quote_data')) {
                $table->dropColumn('quote_data');
            }
        });
    }
};
