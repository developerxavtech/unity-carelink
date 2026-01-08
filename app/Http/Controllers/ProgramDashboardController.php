<?php

namespace App\Http\Controllers;

use App\Models\IndividualProfile;
use App\Models\Organization;
use App\Models\CareNote;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramDashboardController extends Controller
{
    /**
     * Check if user has program_staff role
     */
    protected function checkProgramStaffRole()
    {
        if (!Auth::user()->hasRole('program_staff')) {
            abort(403, 'Access denied. Program staff role required.');
        }
    }

    /**
     * Get program organization(s) for current user
     */
    protected function getUserPrograms()
    {
        $this->checkProgramStaffRole();

        return Auth::user()->organizations()
            ->where('type', 'program')
            ->get();
    }

    /**
     * Get participants in user's program(s)
     */
    protected function getProgramParticipants()
    {
        $programs = $this->getUserPrograms();

        return IndividualProfile::accessibleBy(Auth::id())
            ->with(['careNotes', 'moodChecks'])
            ->get();
    }

    /**
     * Home - Activity Boards
     */
    public function home()
    {
        $programs = $this->getUserPrograms();
        $participants = $this->getProgramParticipants();

        // Get today's activities
        $todaysActivities = collect([]);

        $data = [
            'programs' => $programs,
            'participants' => $participants,
            'todaysActivities' => $todaysActivities,
            'totalParticipants' => $participants->count(),
            'presentToday' => 0, // TODO: Implement attendance
            'activitiesScheduled' => 0,
        ];

        return view('program.home', $data);
    }

    /**
     * Attendance - Track participant attendance
     */
    public function attendance(Request $request)
    {
        $participants = $this->getProgramParticipants();

        // TODO: Implement Attendance model
        $attendanceRecords = collect([]);

        $date = $request->input('date', now()->toDateString());

        return view('program.attendance', [
            'participants' => $participants,
            'attendanceRecords' => $attendanceRecords,
            'date' => $date,
            'presentCount' => 0,
            'absentCount' => 0,
        ]);
    }

    /**
     * Family Updates - Share updates with families
     */
    public function familyUpdates(Request $request)
    {
        $participants = $this->getProgramParticipants();

        // Get recent care notes as updates
        $updates = CareNote::whereIn('individual_profile_id', $participants->pluck('id'))
            ->with(['individualProfile', 'dsp'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('program.family-updates', [
            'participants' => $participants,
            'updates' => $updates,
        ]);
    }

    /**
     * Skill Tracking - Monitor participant skills
     */
    public function skillTracking(Request $request)
    {
        $participants = $this->getProgramParticipants();

        // TODO: Implement Skill/Goal model
        $skills = collect([]);

        return view('program.skill-tracking', [
            'participants' => $participants,
            'skills' => $skills,
            'message' => 'Skill tracking functionality coming soon!',
        ]);
    }

    /**
     * Spot Availability - Available program spots
     */
    public function spotAvailability()
    {
        $programs = $this->getUserPrograms();

        // TODO: Implement capacity tracking
        $spotsData = $programs->map(function ($program) {
            return [
                'program' => $program,
                'capacity' => 0,
                'enrolled' => 0,
                'available' => 0,
                'waitlist' => 0,
            ];
        });

        return view('program.spot-availability', [
            'programs' => $programs,
            'spotsData' => $spotsData,
            'message' => 'Spot availability tracking coming soon!',
        ]);
    }

    /**
     * Messages - Communication
     */
    public function messages()
    {
        $this->checkProgramStaffRole();

        // Get conversations - basic implementation
        $conversations = collect([]);

        return view('program.messages', [
            'conversations' => $conversations,
            'unreadCount' => 0,
        ]);
    }

    /**
     * Single conversation view
     */
    public function conversation(Conversation $conversation)
    {
        // TODO: Verify user has access to this conversation

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('program.conversation', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }
}
