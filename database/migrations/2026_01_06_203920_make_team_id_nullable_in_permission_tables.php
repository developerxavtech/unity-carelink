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
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $teamKey = $columnNames['team_foreign_key'] ?? 'team_id';
        $pivotRole = $columnNames['role_pivot_key'] ?? 'role_id';
        $pivotPermission = $columnNames['permission_pivot_key'] ?? 'permission_id';
        $modelKey = $columnNames['model_morph_key'] ?? 'model_id';

        $cleanupIndices = function ($tableName, $column) {
            if (!Schema::hasColumn($tableName, $column))
                return;

            $indexes = Schema::getIndexes($tableName);
            Schema::table($tableName, function (Blueprint $table) use ($indexes, $column) {
                foreach ($indexes as $index) {
                    // Check if column is part of this index
                    if (in_array($column, $index['columns'])) {
                        if ($index['primary']) {
                            try {
                                $table->dropPrimary();
                            } catch (\Exception $e) {
                            }
                        } elseif ($index['unique']) {
                            try {
                                $table->dropUnique($index['name']);
                            } catch (\Exception $e) {
                            }
                        } else {
                            try {
                                $table->dropIndex($index['name']);
                            } catch (\Exception $e) {
                            }
                        }
                    }
                }
            });
        };

        // 1. Update roles table
        if (Schema::hasColumn($tableNames['roles'], $teamKey)) {
            $cleanupIndices($tableNames['roles'], $teamKey);
            Schema::table($tableNames['roles'], function (Blueprint $table) use ($teamKey) {
                $table->dropColumn($teamKey);
                $table->unique(['name', 'guard_name']);
            });
        }

        // 2. Update model_has_permissions table
        if (Schema::hasColumn($tableNames['model_has_permissions'], $teamKey)) {
            $cleanupIndices($tableNames['model_has_permissions'], $teamKey);
            Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) use ($teamKey, $pivotPermission, $modelKey) {
                $table->dropColumn($teamKey);
                $table->primary([$pivotPermission, $modelKey, 'model_type'], 'model_has_permissions_permission_model_type_primary');
            });
        }

        // 3. Update model_has_roles table
        if (Schema::hasColumn($tableNames['model_has_roles'], $teamKey)) {
            $cleanupIndices($tableNames['model_has_roles'], $teamKey);
            Schema::table($tableNames['model_has_roles'], function (Blueprint $table) use ($teamKey, $pivotRole, $modelKey) {
                $table->dropColumn($teamKey);
                $table->primary([$pivotRole, $modelKey, 'model_type'], 'model_has_roles_role_model_type_primary');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reversing this would be complex and likely not needed as this is a "stripping out" operation.
        // We'll leave it empty or implement if requested.
    }
};
