<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the appropriate dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        // Route to appropriate dashboard based on primary role
        if ($user->hasRole('agency_admin')) {
            return $this->agencyAdminDashboard();
        }

        if ($user->hasRole('dsp')) {
            return $this->dspDashboard();
        }

        if ($user->hasRole('program_staff')) {
            return $this->programStaffDashboard();
        }

        if ($user->hasRole('case_manager')) {
            return $this->caseManagerDashboard();
        }

        if ($user->hasRole('super_admin')) {
            return $this->superAdminDashboard();
        }

        // Default to family admin dashboard
        return $this->familyAdminDashboard();
    }

    /**
     * Family Admin Dashboard
     */
    protected function familyAdminDashboard()
    {
        $user = Auth::user();

        $data = [
            'individualProfiles' => $user->individualProfiles()->with('careNotes', 'moodChecks')->get(),
            'unreadMessages' => 0, // TODO: Implement when messaging is ready
            'upcomingEvents' => 0, // TODO: Implement when calendar is ready
            'pendingItems' => 0,
        ];

        return view('dashboard', $data);
    }

    /**
     * DSP Dashboard
     */
    protected function dspDashboard()
    {
        $user = Auth::user();

        $data = [
            'todayShifts' => [], // TODO: Implement when shifts are ready
            'upcomingShifts' => 0,
            'hoursThisWeek' => 0,
            'pendingNotes' => 0,
        ];

        return view('dashboards.dsp', $data);
    }

    /**
     * Agency Admin Dashboard
     */
    protected function agencyAdminDashboard()
    {
        $user = Auth::user();

        // Get user's organization through role assignment
        $organization = $user->roleAssignments()
            ->where('role_type', 'agency_admin')
            ->with('organization')
            ->first()
            ?->organization;

        $data = [
            'organization' => $organization,
            'activeStaff' => 0, // TODO: Count staff in organization
            'activeClients' => 0, // TODO: Count individuals in organization
            'openShifts' => 0,
            'expiringCerts' => 0,
        ];

        return view('dashboards.agency-admin', $data);
    }

    /**
     * Program Staff Dashboard
     */
    protected function programStaffDashboard()
    {
        // For now, show DSP dashboard as they have similar needs
        return $this->dspDashboard();
    }

    /**
     * Case Manager Dashboard
     */
    protected function caseManagerDashboard()
    {
        // For now, show family admin dashboard with read-only access
        return $this->familyAdminDashboard();
    }

    /**
     * Super Admin Dashboard
     */
    protected function superAdminDashboard()
    {
        $data = [
            'totalUsers' => \App\Models\User::count(),
            'totalOrganizations' => 0, // TODO: Implement when organizations are used
            'totalIndividuals' => 0,
        ];

        // For now, show agency admin dashboard
        return $this->agencyAdminDashboard();
    }
}
