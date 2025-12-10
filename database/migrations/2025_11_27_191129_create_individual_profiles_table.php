<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('individual_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_user_id')->constrained('users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('pronouns')->nullable();
            $table->string('profile_photo')->nullable();
            $table->text('strengths_abilities')->nullable();
            $table->text('preferences_interests')->nullable();
            $table->text('communication_style')->nullable();
            $table->text('sensory_profile')->nullable();
            $table->text('triggers')->nullable();
            $table->text('calming_strategies')->nullable();
            $table->text('safety_notes')->nullable();
            $table->json('emergency_contacts')->nullable();
            $table->text('medical_info')->nullable();
            $table->json('goals')->nullable();
            $table->json('profile_visibility_settings')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_profiles');
    }
};
