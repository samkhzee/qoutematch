<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->string('seo_title')->nullable()->after('description');
            $table->string('seo_description', 500)->nullable()->after('seo_title');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->string('seo_title')->nullable()->after('description');
            $table->string('seo_description', 500)->nullable()->after('seo_title');
            $table->unique(['category_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropUnique(['category_id', 'slug']);
            $table->dropColumn(['slug', 'description', 'seo_title', 'seo_description']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'seo_title', 'seo_description']);
        });
    }
};
