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
        Schema::dropIfExists('role_assignments');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('role_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role_type', [
                'family_admin',
                'individual',
                'dsp',
                'agency_admin',
                'program_staff',
                'case_manager',
                'super_admin'
            ]);
            $table->foreignId('organization_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('individual_profile_id')->nullable()->constrained()->onDelete('cascade');
            $table->json('permissions')->nullable();
            $table->foreignId('assigned_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['user_id', 'role_type']);
            $table->index('organization_id');
            $table->index('individual_profile_id');
        });
    }
};
