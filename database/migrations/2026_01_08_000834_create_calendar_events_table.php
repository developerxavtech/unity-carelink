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
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('location')->nullable();
            $table->enum('event_type', ['meeting', 'appointment', 'reminder', 'other'])->default('other');
            $table->string('color', 7)->nullable(); // Hex color code
            $table->boolean('all_day')->default(false);
            $table->string('recurrence_rule')->nullable(); // For future recurring events
            $table->integer('reminder_minutes')->nullable(); // Minutes before event
            $table->timestamps();

            // Indexes for performance
            $table->index('user_id');
            $table->index('start_datetime');
            $table->index(['user_id', 'start_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
