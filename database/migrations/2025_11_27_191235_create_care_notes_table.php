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
        Schema::create('care_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('individual_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('dsp_user_id')->constrained('users')->onDelete('cascade');
            $table->date('shift_date');
            $table->text('notes');
            $table->string('mood')->nullable(); // great, good, okay, concern
            $table->text('activities')->nullable();
            $table->text('meals')->nullable();
            $table->text('medications')->nullable();
            $table->text('incidents')->nullable();
            $table->timestamps();

            $table->index(['individual_profile_id', 'shift_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('care_notes');
    }
};
