<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'business_name')) {
                $table->string('business_name')->nullable()->after('lastname');
            }
            if (!Schema::hasColumn('users', 'company_number')) {
                $table->string('company_number')->nullable()->after('business_name');
            }
            if (!Schema::hasColumn('users', 'subcategory_ids')) {
                $table->json('subcategory_ids')->nullable()->after('company_number');
            }
            if (!Schema::hasColumn('users', 'service_areas')) {
                $table->text('service_areas')->nullable()->after('subcategory_ids');
            }
            if (!Schema::hasColumn('users', 'provider_approved')) {
                $table->boolean('provider_approved')->default(false)->after('work_profile_complete');
            }
        });

        Schema::table('buyers', function (Blueprint $table) {
            if (!Schema::hasColumn('buyers', 'customer_type')) {
                $table->string('customer_type', 20)->default('individual')->after('lastname');
            }
            if (!Schema::hasColumn('buyers', 'company_name')) {
                $table->string('company_name')->nullable()->after('customer_type');
            }
            if (!Schema::hasColumn('buyers', 'phone')) {
                $table->string('phone', 30)->nullable()->after('company_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['business_name', 'company_number', 'subcategory_ids', 'service_areas', 'provider_approved'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('buyers', function (Blueprint $table) {
            $columns = ['customer_type', 'company_name', 'phone'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('buyers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
