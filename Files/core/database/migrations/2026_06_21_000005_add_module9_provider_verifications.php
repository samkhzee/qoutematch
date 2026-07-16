<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('provider_verifications')) {
            Schema::create('provider_verifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('type', 32);
                $table->string('document')->nullable();
                $table->string('reference_number')->nullable();
                $table->date('expires_at')->nullable();
                $table->unsignedTinyInteger('status')->default(0);
                $table->text('admin_note')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'type']);
                $table->index(['status', 'type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_verifications');
    }
};
