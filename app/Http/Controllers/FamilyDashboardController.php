<?php

namespace App\Http\Controllers;

use App\Models\IndividualProfile;
use App\Models\User;
use App\Models\CareNote;
use App\Models\MoodCheck;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamilyDashboardController extends Controller
{
    /**
     * Check if user has family_admin role
     */
    protected function checkFamilyAdminRole()
    {
        if (!Auth::user()->hasRole('family_admin')) {
            abort(403, 'Access denied. Family admin role required.');
        }
    }

    /**
     * Get user's individual profiles (used across multiple pages)
     */
    protected function getIndividualProfiles()
    {
        $this->checkFamilyAdminRole();
        return Auth::user()->individualProfiles()->with(['careNotes', 'moodChecks'])->get();
    }

    /**
     * Home page (existing dashboard content)
     */
    public function home()
    {
        $individualProfiles = $this->getIndividualProfiles();

        $data = [
            'individualProfiles' => $individualProfiles,
            'unreadMessages' => $this->getUnreadMessageCount(),
            'upcomingEvents' => Auth::user()->calendarEvents()->upcoming()->count(),
            'pendingItems' => 0,
        ];

        return view('family.home', $data);
    }

    /**
     * Daily updates - Recent activity across all individuals
     */
    public function dailyUpdates(Request $request)
    {
        $individualProfiles = $this->getIndividualProfiles();
        $profileIds = $individualProfiles->pluck('id');

        // Get recent care notes
        $query = CareNote::whereIn('individual_profile_id', $profileIds)
            ->with(['individualProfile', 'dsp'])
            ->orderBy('created_at', 'desc');

        // Filter by individual if requested
        $selectedIndividual = $request->input('individual_id');
        if ($selectedIndividual) {
            $query->where('individual_profile_id', $selectedIndividual);
        }

        $recentNotes = $query->paginate(20);

        // Get recent mood checks
        $recentMoodChecks = MoodCheck::whereIn('individual_profile_id', $profileIds)
            ->with(['individualProfile', 'submittedBy'])
            ->orderBy('check_date', 'desc')
            ->limit(10)
            ->get();

        return view('family.daily-updates', [
            'individualProfiles' => $individualProfiles,
            'recentNotes' => $recentNotes,
            'recentMoodChecks' => $recentMoodChecks,
            'selectedIndividual' => $selectedIndividual,
            'availableDsps' => $this->getAvailableDsps(),
        ]);
    }

    /**
     * Calendar - Consolidated event view
     */
    public function calendar(Request $request)
    {
        $individualProfiles = $this->getIndividualProfiles();

        // Get upcoming events for the authenticated user
        $events = Auth::user()->calendarEvents()
            ->upcoming()
            ->limit(10)
            ->get();

        return view('family.calendar', [
            'individualProfiles' => $individualProfiles,
            'events' => $events,
            'currentMonth' => now(),
        ]);
    }

    /**
     * DSP Notes - Filterable care notes view
     */
    public function dspNotes(Request $request)
    {
        $individualProfiles = $this->getIndividualProfiles();
        $profileIds = $individualProfiles->pluck('id');

        $query = CareNote::whereIn('individual_profile_id', $profileIds)
            ->with(['individualProfile', 'dsp']);

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

        // Filter by mood
        if ($request->filled('mood')) {
            $query->where('mood', $request->mood);
        }

        // Search in notes
        if ($request->filled('search')) {
            $query->where('notes', 'like', '%' . $request->search . '%');
        }

        $notes = $query->orderBy('shift_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);


        return view('family.dsp-notes', [
            'individualProfiles' => $individualProfiles,
            'notes' => $notes,
            'filters' => $request->only(['individual_id', 'start_date', 'end_date', 'mood', 'search']),
        ]);
    }

    /**
     * Program Updates - Announcements from agencies/staff
     */
    public function programUpdates()
    {
        $individualProfiles = $this->getIndividualProfiles();

        // TODO: Implement after ProgramUpdate model is created
        $updates = collect([]);

        return view('family.program-updates', [
            'updates' => $updates,
            'individualProfiles' => $individualProfiles,
        ]);
    }

    /**
     * UCL Rides - Transportation coordination (Scaffolded)
     */
    public function rides()
    {
        $individualProfiles = $this->getIndividualProfiles();

        return view('family.rides', [
            'individualProfiles' => $individualProfiles,
            'message' => 'Transportation coordination feature coming soon!',
        ]);
    }

    /**
     * Messages - Conversation list
     */
    public function messages()
    {
        return redirect()->route('chat.index');
    }

    /**
     * Single conversation view
     */
    public function conversation(Conversation $conversation)
    {
        return redirect()->route('chat.show', $conversation);
    }

    /**
     * Resources - Educational materials
     */
    public function resources()
    {
        // Static content for resources
        $categories = [
            'Getting Started' => [
                ['title' => 'Quick Start Guide', 'type' => 'pdf', 'url' => '#'],
                ['title' => 'Video Tutorials', 'type' => 'video', 'url' => '#'],
            ],
            'Support & Advocacy' => [
                ['title' => 'IDD Rights & Advocacy', 'type' => 'link', 'url' => '#'],
                ['title' => 'Financial Assistance Programs', 'type' => 'link', 'url' => '#'],
            ],
            'Health & Wellness' => [
                ['title' => 'Medication Management Tips', 'type' => 'article', 'url' => '#'],
                ['title' => 'Behavioral Support Strategies', 'type' => 'article', 'url' => '#'],
            ],
        ];

        return view('family.resources', [
            'categories' => $categories,
        ]);
    }


    /**
     * Get available DSPs for the user to chat with.
     */
    protected function getAvailableDsps()
    {
        // Without teams, we return all users with the DSP role.
        return User::role('dsp')->get();
    }

    // Helper Methods

    protected function getUnreadMessageCount(): int
    {
        // TODO: Implement proper unread message counting once Message model structure is confirmed
        return 0;
    }
}
