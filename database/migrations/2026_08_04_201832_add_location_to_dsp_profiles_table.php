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
        Schema::table('dsp_profiles', function (Blueprint $table) {
            // Live location, updated periodically by the DSP's mobile app
            // while they're online, used to find/sort nearby DSPs for ride
            // requests.
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('location_updated_at')->nullable();
            $table->boolean('is_available')->default(false);

            $table->index(['is_available', 'latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dsp_profiles', function (Blueprint $table) {
            $table->dropIndex(['is_available', 'latitude', 'longitude']);
            $table->dropColumn(['latitude', 'longitude', 'location_updated_at', 'is_available']);
        });
    }
};
