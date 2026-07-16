<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('care_notes', function (Blueprint $table) {
            $table->foreignId('family_user_id')
                ->nullable()
                ->after('individual_profile_id')
                ->constrained('users')
                ->onDelete('cascade');
        });

        // Daily logs created via the mobile API attach directly to the family
        // admin (family_user_id) rather than an individual profile, so this
        // column can no longer be required. Raw SQL avoids needing
        // doctrine/dbal, which this project doesn't have installed, just to
        // call Blueprint::change().
        DB::statement('ALTER TABLE care_notes MODIFY individual_profile_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('care_notes', function (Blueprint $table) {
            $table->dropForeign(['family_user_id']);
            $table->dropColumn('family_user_id');
        });

        DB::statement('ALTER TABLE care_notes MODIFY individual_profile_id BIGINT UNSIGNED NOT NULL');
    }
};
