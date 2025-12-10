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
        Schema::create('mood_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('individual_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('submitted_by_user_id')->constrained('users')->onDelete('cascade');
            $table->date('check_date')->default(DB::raw('CURRENT_DATE'));
            $table->string('mood'); // great, good, okay, concern
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['individual_profile_id', 'check_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_checks');
    }
};
