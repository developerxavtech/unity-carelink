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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dsp_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('individual_profile_id')->nullable()->constrained('individual_profiles')->nullOnDelete();

            $table->string('vehicle_type'); // key into config('rides.vehicle_types')
            $table->string('status')->default('pending'); // pending, accepted, rejected, cancelled, in_progress, completed

            $table->string('pickup_address');
            $table->decimal('pickup_latitude', 10, 7);
            $table->decimal('pickup_longitude', 10, 7);

            $table->string('destination_address');
            $table->decimal('destination_latitude', 10, 7);
            $table->decimal('destination_longitude', 10, 7);

            $table->decimal('distance_miles', 8, 2); // pickup -> destination, server-computed
            $table->decimal('fare', 8, 2); // server-computed from distance + vehicle_type rate

            $table->string('rejection_reason')->nullable();

            $table->timestamp('responded_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->timestamps();

            $table->index(['dsp_user_id', 'status']);
            $table->index(['family_user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
};
