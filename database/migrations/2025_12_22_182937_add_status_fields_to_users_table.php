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
        Schema::table('users', function (Blueprint $table) {
            $table->string('activity_status')->nullable()->after('status');
            $table->string('status_emoji')->nullable()->after('activity_status');
            $table->text('status_message')->nullable()->after('status_emoji');
            $table->timestamp('status_busy_until')->nullable()->after('status_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['activity_status', 'status_emoji', 'status_message', 'status_busy_until']);
        });
    }
};
