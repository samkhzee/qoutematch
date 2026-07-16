<?php

use App\Constants\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('disputes')) {
            Schema::create('disputes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('job_id')->default(0);
                $table->unsignedBigInteger('bid_id')->default(0);
                $table->unsignedBigInteger('project_id')->default(0);
                $table->unsignedBigInteger('buyer_id')->default(0);
                $table->unsignedBigInteger('user_id')->default(0);
                $table->string('raised_by', 20);
                $table->string('type', 50)->default('other');
                $table->string('subject');
                $table->text('description');
                $table->unsignedTinyInteger('status')->default(Status::DISPUTE_OPEN);
                $table->text('admin_note')->nullable();
                $table->unsignedBigInteger('resolved_by')->default(0);
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();

                $table->index(['status', 'created_at']);
                $table->index('project_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};
