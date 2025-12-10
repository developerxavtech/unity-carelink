<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Models\IndividualProfile;
use App\Models\RoleAssignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users for each role type

        // 1. Family Admin
        $familyUser = User::create([
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'email' => 'family@unitycarelink.com',
            'password' => Hash::make('password'),
            'phone' => '555-0101',
            'status' => 'active',
        ]);

        RoleAssignment::create([
            'user_id' => $familyUser->id,
            'role_type' => 'family_admin',
        ]);

        // Create an individual profile managed by family admin
        $individual = IndividualProfile::create([
            'family_user_id' => $familyUser->id,
            'first_name' => 'Michael',
            'last_name' => 'Johnson',
            'date_of_birth' => '1995-06-15',
            'pronouns' => 'He/Him',
            'strengths_abilities' => 'Great at art, loves music, excellent memory',
            'preferences_interests' => 'Painting, listening to jazz, playing video games',
            'communication_style' => 'Verbal, prefers visual cues',
            'sensory_profile' => 'Sensitive to loud noises, enjoys soft textures',
            'triggers' => 'Sudden changes in routine, crowded spaces',
            'calming_strategies' => 'Listening to music, deep breathing, quiet space',
            'safety_notes' => 'No known allergies, takes daily medication',
            'status' => 'active',
        ]);

        // 2. DSP User
        $dspUser = User::create([
            'first_name' => 'James',
            'last_name' => 'Martinez',
            'email' => 'dsp@unitycarelink.com',
            'password' => Hash::make('password'),
            'phone' => '555-0102',
            'status' => 'active',
        ]);

        RoleAssignment::create([
            'user_id' => $dspUser->id,
            'role_type' => 'dsp',
            'individual_profile_id' => $individual->id,
        ]);

        // 3. Create test organization (Agency)
        $agency = Organization::create([
            'name' => 'Hope Care Services',
            'type' => 'agency',
            'address' => '123 Main Street',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'zip' => '90001',
            'phone' => '555-1000',
            'email' => 'info@hopecare.com',
            'description' => 'Providing quality care services since 2010',
            'status' => 'active',
        ]);

        // 4. Agency Admin
        $agencyAdmin = User::create([
            'first_name' => 'Linda',
            'last_name' => 'Williams',
            'email' => 'admin@unitycarelink.com',
            'password' => Hash::make('password'),
            'phone' => '555-0103',
            'status' => 'active',
        ]);

        RoleAssignment::create([
            'user_id' => $agencyAdmin->id,
            'role_type' => 'agency_admin',
            'organization_id' => $agency->id,
        ]);

        // Also assign DSP to agency
        RoleAssignment::create([
            'user_id' => $dspUser->id,
            'role_type' => 'dsp',
            'organization_id' => $agency->id,
            'individual_profile_id' => $individual->id,
        ]);

        // 5. Program Staff
        $programStaff = User::create([
            'first_name' => 'Robert',
            'last_name' => 'Davis',
            'email' => 'program@unitycarelink.com',
            'password' => Hash::make('password'),
            'phone' => '555-0104',
            'status' => 'active',
        ]);

        // Create a day program organization
        $dayProgram = Organization::create([
            'name' => 'Sunshine Day Program',
            'type' => 'program',
            'address' => '456 Oak Avenue',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'zip' => '90002',
            'phone' => '555-2000',
            'email' => 'info@sunshineprogram.com',
            'description' => 'Day program for adults with developmental disabilities',
            'hours_of_operation' => 'Mon-Fri 8am-4pm',
            'status' => 'active',
        ]);

        RoleAssignment::create([
            'user_id' => $programStaff->id,
            'role_type' => 'program_staff',
            'organization_id' => $dayProgram->id,
            'individual_profile_id' => $individual->id,
        ]);

        // 6. Super Admin
        $superAdmin = User::create([
            'first_name' => 'Pete',
            'last_name' => 'Anderson',
            'email' => 'pete@unitycarelink.com',
            'password' => Hash::make('password'),
            'phone' => '555-0105',
            'status' => 'active',
        ]);

        RoleAssignment::create([
            'user_id' => $superAdmin->id,
            'role_type' => 'super_admin',
        ]);

        $this->command->info('Test users created successfully!');
        $this->command->info('');
        $this->command->info('Login credentials (password for all: password):');
        $this->command->info('Family Admin: family@unitycarelink.com');
        $this->command->info('DSP: dsp@unitycarelink.com');
        $this->command->info('Agency Admin: admin@unitycarelink.com');
        $this->command->info('Program Staff: program@unitycarelink.com');
        $this->command->info('Super Admin: pete@unitycarelink.com');
    }
}
