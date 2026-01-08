<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            'family_admin',
            'family_member',
            'dsp',
            'agency_admin',
            'program_staff',
            'super_admin',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        /*
        // Assign roles to users (Moved to MigrateRolesToSpatieTeamsSeeder for context support)
        $assignments = [
            'family@unitycarelink.com' => 'family_admin',
            'dsp@unitycarelink.com' => 'dsp',
            'admin@unitycarelink.com' => 'agency_admin',
            'program@unitycarelink.com' => 'program_staff',
            'pete@unitycarelink.com' => 'super_admin', // From DatabaseSeeder
        ];

        foreach ($assignments as $email => $roleName) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->assignRole($roleName);
            }
        }
        */
    }
}
