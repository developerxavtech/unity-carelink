<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Models\IndividualProfile;
use App\Models\CareNote;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgencyDashboardController extends Controller
{
    /**
     * Check if user has agency_admin role
     */
    protected function checkAgencyAdminRole()
    {
        if (!Auth::user()->hasRole('agency_admin')) {
            abort(403, 'Access denied. Agency admin role required.');
        }
    }

    /**
     * Get organization(s) for current agency admin
     */
    protected function getUserOrganizations()
    {
        $this->checkAgencyAdminRole();

        $user = Auth::user();

        return Organization::whereHas('roleAssignments', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('role_type', 'agency_admin');
        })->get();
    }

    /**
     * Get all staff across agency organizations
     */
    protected function getAgencyStaff()
    {
        $organizations = $this->getUserOrganizations();
        $organizationIds = $organizations->pluck('id');

        return User::whereHas('roleAssignments', function ($query) use ($organizationIds) {
            $query->whereIn('organization_id', $organizationIds);
        })->with(['roleAssignments'])->get();
    }

    /**
     * Home - Agency Network Overview
     */
    public function home()
    {
        $organizations = $this->getUserOrganizations();
        $staff = $this->getAgencyStaff();

        // Get programs under this agency
        $programs = Organization::whereHas('roleAssignments', function ($query) {
            $query->where('user_id', Auth::id())
                  ->where('role_type', 'agency_admin');
        })->where('type', 'program')->get();

        // Get all individuals across the agency
        $totalIndividuals = IndividualProfile::whereHas('organization', function ($query) use ($organizations) {
            $query->whereIn('id', $organizations->pluck('id'));
        })->count();

        // Calculate active staff (has logged activity in last 30 days)
        $activeStaff = CareNote::where('created_at', '>=', Carbon::now()->subDays(30))
            ->distinct('dsp_user_id')
            ->count('dsp_user_id');

        $data = [
            'organizations' => $organizations,
            'programs' => $programs,
            'totalStaff' => $staff->count(),
            'activeStaff' => $activeStaff,
            'totalIndividuals' => $totalIndividuals,
            'totalPrograms' => $programs->count(),
            'complianceAlerts' => 0, // TODO: Implement compliance tracking
            'pendingIncidents' => 0, // TODO: Implement incident reports
        ];

        return view('agency.home', $data);
    }

    /**
     * Staffing - Manage staff and assignments
     */
    public function staffing(Request $request)
    {
        $this->checkAgencyAdminRole();

        $query = User::whereHas('roleAssignments', function ($q) {
            $organizations = $this->getUserOrganizations();
            $q->whereIn('organization_id', $organizations->pluck('id'));
        })->with(['roleAssignments.organization']);

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roleAssignments', function ($q) use ($request) {
                $q->where('role_type', $request->role);
            });
        }

        // Filter by organization
        if ($request->filled('organization_id')) {
            $query->whereHas('roleAssignments', function ($q) use ($request) {
                $q->where('organization_id', $request->organization_id);
            });
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $staff = $query->paginate(20);
        $organizations = $this->getUserOrganizations();

        return view('agency.staffing', [
            'staff' => $staff,
            'organizations' => $organizations,
            'filters' => $request->only(['role', 'organization_id', 'search']),
        ]);
    }

    /**
     * Compliance Alerts - Monitor compliance issues
     */
    public function complianceAlerts(Request $request)
    {
        $this->checkAgencyAdminRole();

        // TODO: Implement Compliance model
        $alerts = collect([]);

        $priority = $request->input('priority', 'all');
        $status = $request->input('status', 'open');

        return view('agency.compliance-alerts', [
            'alerts' => $alerts,
            'priority' => $priority,
            'status' => $status,
            'totalAlerts' => 0,
            'criticalAlerts' => 0,
            'overdueAlerts' => 0,
        ]);
    }

    /**
     * Incident Reports - Track and manage incidents
     */
    public function incidentReports(Request $request)
    {
        $this->checkAgencyAdminRole();

        // TODO: Implement IncidentReport model
        $incidents = collect([]);

        $type = $request->input('type', 'all');
        $status = $request->input('status', 'pending');
        $dateRange = $request->input('date_range', 'month');

        return view('agency.incident-reports', [
            'incidents' => $incidents,
            'type' => $type,
            'status' => $status,
            'dateRange' => $dateRange,
            'totalIncidents' => 0,
            'pendingReview' => 0,
            'resolved' => 0,
        ]);
    }

    /**
     * Program Utilization - Analytics and reporting
     */
    public function programUtilization(Request $request)
    {
        $this->checkAgencyAdminRole();

        $organizations = $this->getUserOrganizations();
        $programs = Organization::whereIn('id', $organizations->pluck('id'))
            ->where('type', 'program')
            ->get();

        // Get utilization data for each program
        $utilizationData = $programs->map(function ($program) {
            $participants = IndividualProfile::where('organization_id', $program->id)->count();

            return [
                'program' => $program,
                'capacity' => 0, // TODO: Implement capacity tracking
                'enrolled' => $participants,
                'utilization' => 0,
                'revenue' => 0, // TODO: Implement billing tracking
            ];
        });

        return view('agency.program-utilization', [
            'programs' => $programs,
            'utilizationData' => $utilizationData,
            'totalCapacity' => $utilizationData->sum('capacity'),
            'totalEnrolled' => $utilizationData->sum('enrolled'),
            'averageUtilization' => $programs->count() > 0 ? round($utilizationData->avg('utilization'), 1) : 0,
        ]);
    }

    /**
     * Team Communication - Internal messaging
     */
    public function teamCommunication()
    {
        $this->checkAgencyAdminRole();

        // Get conversations for agency admin
        $conversations = collect([]);

        return view('agency.team-communication', [
            'conversations' => $conversations,
            'unreadCount' => 0,
        ]);
    }

    /**
     * Single conversation view
     */
    public function conversation(Conversation $conversation)
    {
        $this->checkAgencyAdminRole();

        // TODO: Verify user has access to this conversation

        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('agency.conversation', [
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Billing/Payroll - Financial management
     */
    public function billingPayroll(Request $request)
    {
        $this->checkAgencyAdminRole();

        // TODO: Implement Billing and Payroll models
        $billingRecords = collect([]);
        $payrollRecords = collect([]);

        $period = $request->input('period', 'current_month');

        return view('agency.billing-payroll', [
            'billingRecords' => $billingRecords,
            'payrollRecords' => $payrollRecords,
            'period' => $period,
            'totalRevenue' => 0,
            'totalPayroll' => 0,
            'netIncome' => 0,
        ]);
    }
}
