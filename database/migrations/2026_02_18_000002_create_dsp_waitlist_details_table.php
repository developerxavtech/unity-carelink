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
        Schema::create('dsp_waitlist_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('city_service_area')->nullable();
            $table->boolean('has_drivers_license')->default(false);
            $table->boolean('has_auto_insurance')->default(false);
            $table->boolean('has_reliable_vehicle')->default(false);
            $table->string('comfort_transporting_disabilities')->nullable();
            $table->json('availability')->nullable();
            $table->string('max_distance')->nullable();
            $table->json('comfort_levels')->nullable();
            $table->string('experience')->nullable();
            $table->text('certifications')->nullable();
            $table->text('message_to_families')->nullable();
            $table->boolean('usage_intent_driver_pilot')->default(true);
            $table->boolean('usage_intent_peer_network_only')->default(true);
            $table->json('pilot_questions')->nullable();
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
        Schema::dropIfExists('dsp_waitlist_details');
    }
};
