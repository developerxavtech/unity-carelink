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
        // 1. Create the wrapper 'teams' table
        if (!Schema::hasTable('teams')) {
            Schema::create('teams', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->nullableMorphs('teamable'); // Links to IndividualProfile or Organization
                $table->timestamps();
            });
        }

        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamKey = $columnNames['team_foreign_key'] ?? 'team_id';

        // 2. Add team_id to roles
        if (!Schema::hasColumn($tableNames['roles'], $teamKey)) {
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
                $table->unsignedBigInteger($teamKey)->nullable()->after('id');
                $table->index($teamKey);

                // Re-create unique constraint to include team_id
                $table->dropUnique(['name', 'guard_name']);
                $table->unique([$teamKey, 'name', 'guard_name']);
            });
        }

        // 3. Add team_id to model_has_roles
        if (!Schema::hasColumn($tableNames['model_has_roles'], $teamKey)) {
            Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $teamKey) {
                $table->unsignedBigInteger($teamKey)->after('role_id');
                $table->index($teamKey);

                // MySQL tricky part: drop foreign keys before dropping primary key if they interfere
                try {
                    $table->dropForeign(['role_id']);
                } catch (\Exception $e) {
                }

                // Re-create primary key
                $table->dropPrimary('model_has_roles_role_model_type_primary');
                $table->primary([$teamKey, 'role_id', 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');

                // Re-add foreign key
                $table->foreign('role_id')
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->onDelete('cascade');
            });
        }

        // 4. Add team_id to model_has_permissions
        if (!Schema::hasColumn($tableNames['model_has_permissions'], $teamKey)) {
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $teamKey) {
                $table->unsignedBigInteger($teamKey)->after('permission_id');
                $table->index($teamKey);

                try {
                    $table->dropForeign(['permission_id']);
                } catch (\Exception $e) {
                }

                // Re-create primary key
                $table->dropPrimary('model_has_permissions_permission_model_type_primary');
                $table->primary([$teamKey, 'permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');

                $table->foreign('permission_id')
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamKey = $columnNames['team_foreign_key'] ?? 'team_id';

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey) {
            $table->dropPrimary('model_has_permissions_permission_model_type_primary');
            $table->primary(['permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');
            $table->dropColumn($teamKey);
        });

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey) {
            $table->dropPrimary('model_has_roles_role_model_type_primary');
            $table->primary(['role_id', 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');
            $table->dropColumn($teamKey);
        });

        Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
            $table->dropUnique([$teamKey, 'name', 'guard_name']);
            $table->unique(['name', 'guard_name']);
            $table->dropColumn($teamKey);
        });

        Schema::dropIfExists('teams');
    }
};
