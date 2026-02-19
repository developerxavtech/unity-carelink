<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dsp_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('preferred_name')->nullable();
            $table->string('pronouns')->nullable();
            $table->json('roles')->nullable(); // Store roles as JSON array
            $table->json('communication_preferences')->nullable();
            $table->text('experience_strengths')->nullable();
            $table->text('boundaries_expectations')->nullable();
            $table->text('final_notes')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('verification_code')->nullable();
            $table->timestamp('verification_code_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dsp_profiles');
    }
};
