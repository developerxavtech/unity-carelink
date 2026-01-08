<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CalendarEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get test users
        $familyUser = User::where('email', 'family@unitycarelink.com')->first();
        $dspUser = User::where('email', 'dsp@unitycarelink.com')->first();
        $agencyAdmin = User::where('email', 'admin@unitycarelink.com')->first();
        $familyMember = User::where('email', 'member@unitycarelink.com')->first();

        // Family Admin Events
        if ($familyUser) {
            CalendarEvent::create([
                'user_id' => $familyUser->id,
                'title' => 'Doctor Appointment - Michael',
                'description' => 'Annual checkup with Dr. Smith at City Medical Center',
                'start_datetime' => Carbon::now()->addDays(3)->setTime(10, 0),
                'end_datetime' => Carbon::now()->addDays(3)->setTime(11, 0),
                'location' => 'City Medical Center, Room 205',
                'event_type' => 'appointment',
                'reminder_minutes' => 60,
            ]);

            CalendarEvent::create([
                'user_id' => $familyUser->id,
                'title' => 'Family Meeting',
                'description' => 'Quarterly family meeting to discuss care plan updates',
                'start_datetime' => Carbon::now()->addDays(7)->setTime(14, 0),
                'end_datetime' => Carbon::now()->addDays(7)->setTime(15, 30),
                'location' => 'Home',
                'event_type' => 'meeting',
                'reminder_minutes' => 120,
            ]);

            CalendarEvent::create([
                'user_id' => $familyUser->id,
                'title' => 'Medication Refill Reminder',
                'description' => 'Pick up Michael\'s monthly prescriptions',
                'start_datetime' => Carbon::now()->addDays(5)->setTime(9, 0),
                'end_datetime' => Carbon::now()->addDays(5)->setTime(9, 30),
                'location' => 'Pharmacy',
                'event_type' => 'reminder',
                'reminder_minutes' => 1440, // 24 hours
            ]);
        }

        // DSP Events
        if ($dspUser) {
            CalendarEvent::create([
                'user_id' => $dspUser->id,
                'title' => 'Team Meeting',
                'description' => 'Weekly DSP team meeting to discuss participant updates',
                'start_datetime' => Carbon::now()->addDays(2)->setTime(9, 0),
                'end_datetime' => Carbon::now()->addDays(2)->setTime(10, 0),
                'location' => 'Hope Care Services Office',
                'event_type' => 'meeting',
                'color' => '#3788d8',
                'reminder_minutes' => 30,
            ]);

            CalendarEvent::create([
                'user_id' => $dspUser->id,
                'title' => 'CPR Training Renewal',
                'description' => 'Annual CPR and First Aid certification renewal',
                'start_datetime' => Carbon::now()->addDays(10)->setTime(13, 0),
                'end_datetime' => Carbon::now()->addDays(10)->setTime(17, 0),
                'location' => 'Training Center',
                'event_type' => 'appointment',
                'color' => '#28a745',
                'reminder_minutes' => 1440,
            ]);

            CalendarEvent::create([
                'user_id' => $dspUser->id,
                'title' => 'Submit Weekly Reports',
                'description' => 'Complete and submit weekly care notes and documentation',
                'start_datetime' => Carbon::now()->addDays(4)->setTime(16, 0),
                'end_datetime' => Carbon::now()->addDays(4)->setTime(17, 0),
                'event_type' => 'reminder',
                'color' => '#ffc107',
                'reminder_minutes' => 120,
            ]);
        }

        // Agency Admin Events
        if ($agencyAdmin) {
            CalendarEvent::create([
                'user_id' => $agencyAdmin->id,
                'title' => 'Board Meeting',
                'description' => 'Monthly board meeting - Q1 review and planning',
                'start_datetime' => Carbon::now()->addDays(8)->setTime(10, 0),
                'end_datetime' => Carbon::now()->addDays(8)->setTime(12, 0),
                'location' => 'Conference Room A',
                'event_type' => 'meeting',
                'reminder_minutes' => 60,
            ]);

            CalendarEvent::create([
                'user_id' => $agencyAdmin->id,
                'title' => 'Compliance Audit',
                'description' => 'State compliance audit - prepare all documentation',
                'start_datetime' => Carbon::now()->addDays(15)->setTime(9, 0),
                'end_datetime' => Carbon::now()->addDays(15)->setTime(16, 0),
                'location' => 'Main Office',
                'event_type' => 'appointment',
                'all_day' => false,
                'reminder_minutes' => 2880, // 2 days
            ]);

            CalendarEvent::create([
                'user_id' => $agencyAdmin->id,
                'title' => 'Staff Performance Reviews',
                'description' => 'Quarterly performance review meetings with department heads',
                'start_datetime' => Carbon::now()->addDays(12)->setTime(13, 0),
                'end_datetime' => Carbon::now()->addDays(12)->setTime(17, 0),
                'location' => 'Office',
                'event_type' => 'meeting',
                'reminder_minutes' => 1440,
            ]);
        }

        // Family Member Events
        if ($familyMember) {
            CalendarEvent::create([
                'user_id' => $familyMember->id,
                'title' => 'Visit with Michael',
                'description' => 'Weekly visit at the day program',
                'start_datetime' => Carbon::now()->addDays(6)->setTime(15, 0),
                'end_datetime' => Carbon::now()->addDays(6)->setTime(16, 30),
                'location' => 'Sunshine Day Program',
                'event_type' => 'other',
                'reminder_minutes' => 60,
            ]);
        }

        $this->command->info('Calendar events created successfully!');
    }
}
