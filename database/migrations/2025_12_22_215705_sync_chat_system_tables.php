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
        Schema::table('conversations', function (Blueprint $table) {
            $table->string('scope_type')->nullable()->after('type'); // individual, organization, etc.
            $table->unsignedBigInteger('scope_id')->nullable()->after('scope_type');
            $table->string('name')->nullable()->after('subject');
            $table->json('participants')->nullable()->after('name');
            $table->json('settings')->nullable()->after('participants');
        });

        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'body')) {
                $table->renameColumn('body', 'content');
            }
            $table->string('message_type')->default('text')->after('user_id');
            $table->json('attachments')->nullable()->after('content');
            $table->json('mentioned_users')->nullable()->after('attachments');
            $table->json('tags')->nullable()->after('mentioned_users');
            $table->json('read_by')->nullable()->after('tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            $table->dropColumn(['scope_type', 'scope_id', 'name', 'participants', 'settings']);
        });

        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'content')) {
                $table->renameColumn('content', 'body');
            }
            $table->dropColumn(['message_type', 'attachments', 'mentioned_users', 'tags', 'read_by']);
        });
    }
};
