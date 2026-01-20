<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Models\IndividualProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Initialize Roles and Permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Create test users for each role type

        // 1. Family Admin
        $familyUser = User::updateOrCreate(
            ['email' => 'family@unitycarelink.com'],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'password' => Hash::make('password'),
                'phone' => '555-0101',
                'status' => 'active',
            ]
        );

        // Create an individual profile managed by family admin
        $individual = IndividualProfile::updateOrCreate(
            [
                'family_user_id' => $familyUser->id,
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
            ],
            [
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
            ]
        );

        $familyUser->assignRole('family_admin');

        // 2. DSP User
        $dspUser = User::updateOrCreate(
            ['email' => 'dsp@unitycarelink.com'],
            [
                'first_name' => 'James',
                'last_name' => 'Martinez',
                'password' => Hash::make('password'),
                'phone' => '555-0102',
                'status' => 'active',
            ]
        );

        $dspUser->assignRole('dsp');

        $dspUser2 = User::updateOrCreate(
            ['email' => 'dsp2@unitycarelink.com'],
            [
                'first_name' => 'DSP',
                'last_name' => 'Two',
                'password' => Hash::make('password'),
                'phone' => '555-0103',
                'status' => 'active',
            ]
        );

        $dspUser2->assignRole('dsp');

        // 3. Create test organization (Agency)
        $agency = Organization::updateOrCreate(
            ['email' => 'info@hopecare.com'],
            [
                'name' => 'Hope Care Services',
                'type' => 'agency',
                'address' => '123 Main Street',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'zip' => '90001',
                'phone' => '555-1000',
                'description' => 'Providing quality care services since 2010',
                'status' => 'active',
            ]
        );

        // 4. Agency Admin
        $agencyAdmin = User::updateOrCreate(
            ['email' => 'admin@unitycarelink.com'],
            [
                'first_name' => 'Linda',
                'last_name' => 'Williams',
                'password' => Hash::make('password'),
                'phone' => '555-0103',
                'status' => 'active',
            ]
        );

        $agencyAdmin->assignRole('agency_admin');

        // Also assign DSP to agency
        // DSP is already assigned 'dsp' role. Team linkage should be done via Team model if needed.

        // 5. Program Staff
        $programStaff = User::updateOrCreate(
            ['email' => 'program@unitycarelink.com'],
            [
                'first_name' => 'Robert',
                'last_name' => 'Davis',
                'password' => Hash::make('password'),
                'phone' => '555-0104',
                'status' => 'active',
            ]
        );

        // Create a day program organization
        $dayProgram = Organization::updateOrCreate(
            ['email' => 'info@sunshineprogram.com'],
            [
                'name' => 'Sunshine Day Program',
                'type' => 'program',
                'address' => '456 Oak Avenue',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'zip' => '90002',
                'phone' => '555-2000',
                'description' => 'Day program for adults with developmental disabilities',
                'hours_of_operation' => 'Mon-Fri 8am-4pm',
                'status' => 'active',
            ]
        );

        $programStaff->assignRole('program_staff');

        // Create a SECOND individual profile (Emma) also managed by Sarah
        $individual2 = IndividualProfile::updateOrCreate(
            [
                'family_user_id' => $familyUser->id,
                'first_name' => 'Emma',
                'last_name' => 'Johnson',
            ],
            [
                'date_of_birth' => '2015-03-20',
                'pronouns' => 'She/Her',
                'strengths_abilities' => 'Loves animals, very social, helpful',
                'preferences_interests' => 'Swimming, playing with dogs, puzzles',
                'communication_style' => 'Verbal, very expressive',
                'sensory_profile' => 'Enjoys music, tactile play',
                'triggers' => 'Loud bangs',
                'calming_strategies' => 'Holding a stuffed animal, quiet time',
                'safety_notes' => 'Peanut allergy',
                'status' => 'active',
            ]
        );

        // Role already assigned above

        // Create a Family Member User
        $memberUser = User::updateOrCreate(
            ['email' => 'member@unitycarelink.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Member',
                'password' => Hash::make('password'),
                'phone' => '555-0106',
                'status' => 'active',
                'family_admin_id' => $familyUser->id,
            ]
        );

        $memberUser->assignRole('family_member');

        // 6. Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'pete@unitycarelink.com'],
            [
                'first_name' => 'Pete',
                'last_name' => 'Anderson',
                'password' => Hash::make('password'),
                'phone' => '555-0105',
                'status' => 'active',
            ]
        );

        $superAdmin->assignRole('super_admin');

        // Seed calendar events for all users
        $this->call(CalendarEventSeeder::class);

        $this->command->info('Test users created successfully!');
    }
}
