<?php

namespace App\Http\Controllers;

use App\Models\IndividualProfile;
use App\Models\CareNote;
use App\Models\MoodCheck;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DspDashboardController extends Controller
{
    /**
     * Check if user has DSP or program_staff role
     */
    protected function checkDspRole()
    {
        if (!Auth::user()->hasAnyRole(['dsp', 'program_staff'])) {
            abort(403, 'Access denied. DSP role required.');
        }
    }

    /**
     * Get individuals assigned to this DSP
     */
    protected function getAssignedIndividuals()
    {
        $this->checkDspRole();

        $user = Auth::user();

        return IndividualProfile::whereHas('roleAssignments', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('role_type', ['dsp', 'program_staff']);
        })->with(['careNotes', 'moodChecks'])->get();
    }

    /**
     * Home - Today's Plan
     */
    public function home()
    {
        $individuals = $this->getAssignedIndividuals();

        // Get today's care notes
        $todaysNotes = CareNote::where('dsp_user_id', Auth::id())
            ->whereDate('shift_date', today())
            ->with('individualProfile')
            ->get();

        // Get pending notes (shifts without notes)
        $pendingNotesCount = 0; // TODO: Implement shift tracking

        $data = [
            'individuals' => $individuals,
            'todaysNotes' => $todaysNotes,
            'todayShifts' => 0, // TODO: Implement shift model
            'upcomingShifts' => 0,
            'hoursThisWeek' => 0,
            'pendingNotes' => $pendingNotesCount,
        ];

        return view('dsp.home', $data);
    }

    /**
     * Participants - Individuals I support
     */
    public function participants()
    {
        $individuals = $this->getAssignedIndividuals();

        return view('dsp.participants', [
            'individuals' => $individuals,
        ]);
    }

    /**
     * Daily Logs - Create and view care notes
     */
    public function dailyLogs(Request $request)
    {
        $individuals = $this->getAssignedIndividuals();
        $profileIds = $individuals->pluck('id');

        $query = CareNote::where('dsp_user_id', Auth::id())
            ->with(['individualProfile']);

        // Filter by individual
        if ($request->filled('individual_id')) {
            $query->where('individual_profile_id', $request->individual_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('shift_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('shift_date', '<=', $request->end_date);
        }

        $logs = $query->orderBy('shift_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dsp.daily-logs', [
            'individuals' => $individuals,
            'logs' => $logs,
            'filters' => $request->only(['individual_id', 'start_date', 'end_date']),
        ]);
    }

    /**
     * Skill Tracking - Track individual progress
     */
    public function skillTracking(Request $request)
    {
        $individuals = $this->getAssignedIndividuals();

        // TODO: Implement Skill/Goal model
        $skills = collect([]);

        return view('dsp.skill-tracking', [
            'individuals' => $individuals,
            'skills' => $skills,
            'message' => 'Skill tracking functionality coming soon!',
        ]);
    }

    /**
     * Ride Assigned - Transportation assignments
     */
    public function rides()
    {
        $individuals = $this->getAssignedIndividuals();

        // TODO: Implement Ride model
        $rides = collect([]);

        return view('dsp.rides', [
            'individuals' => $individuals,
            'rides' => $rides,
            'message' => 'Ride assignment functionality coming soon!',
        ]);
    }

    /**
     * Peer Support - DSP community
     */
    public function peerSupport()
    {
        $this->checkDspRole();

        // TODO: Implement peer support features
        return view('dsp.peer-support', [
            'message' => 'Peer support features coming soon!',
        ]);
    }

    /**
     * Messages - Communication
     */
    public function messages()
    {
        $user = Auth::user();

        // Get conversations - basic implementation
        $conversations = collect([]);

        return view('dsp.messages', [
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

        return view('dsp.conversation', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Time Tracking - Log hours
     */
    public function timeTracking(Request $request)
    {
        $this->checkDspRole();

        // Get time entries for current week/month
        // TODO: Implement TimeEntry model
        $entries = collect([]);

        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        return view('dsp.time-tracking', [
            'entries' => $entries,
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'totalHours' => 0,
            'message' => 'Time tracking functionality coming soon!',
        ]);
    }
}
