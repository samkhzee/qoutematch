<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'request_form_id')) {
                $table->unsignedBigInteger('request_form_id')->nullable()->after('image');
            }
        });

        Schema::table('jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('jobs', 'request_data')) {
                $table->json('request_data')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'request_form_id')) {
                $table->dropColumn('request_form_id');
            }
        });

        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasColumn('jobs', 'request_data')) {
                $table->dropColumn('request_data');
            }
        });
    }
};
