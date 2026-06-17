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
        Schema::create('family_waitlist_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('city')->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('communication_method')->default('Email');
            $table->json('care_profiles')->nullable();
            $table->json('pilot_questions')->nullable();
            $table->boolean('usage_intent_rides_pilot')->default(true);
            $table->boolean('usage_intent_community_only')->default(true);
            $table->text('feedback_transportation')->nullable();
            $table->text('feedback_wants')->nullable();
            $table->boolean('pilot_acknowledged')->default(false);
            $table->unsignedTinyInteger('step_reached')->default(7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_waitlist_details');
    }
};
