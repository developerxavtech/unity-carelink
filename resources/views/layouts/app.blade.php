<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Unity CareLink') }}</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @auth
            <nav class="sidebar">
                <div class="sidebar-sticky">
                    <!-- Logo -->
                    <div class="text-center py-4">
                        <h4 class="text-black fw-bold">Unity CareLink</h4>
                        <small class="text-black-50">Care, Not Chaos</small>
                    </div>

                    <hr class="text-black-50 mx-3">

                    <!-- Navigation Links -->
                    <ul class="nav flex-column">
                        @if (auth()->user()->hasRole('family_admin'))
                            <li class="nav-item">
                                <hr class="text-black-50 mx-3">
                                <small class="text-black-50 px-3">FAMILY DASHBOARD</small>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('family.home') ? 'active' : '' }}"
                                    href="{{ route('family.home') }}">
                                    <i class="bi bi-house-door"></i>
                                    Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('family.daily-updates') ? 'active' : '' }}"
                                    href="{{ route('family.daily-updates') }}">
                                    <i class="bi bi-newspaper"></i>
                                    Daily Updates
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('family.calendar') ? 'active' : '' }}"
                                    href="{{ route('family.calendar') }}">
                                    <i class="bi bi-calendar3"></i>
                                    Calendar
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('family.dsp-notes') ? 'active' : '' }}"
                                    href="{{ route('family.dsp-notes') }}">
                                    <i class="bi bi-journal-text"></i>
                                    DSP Notes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('family.program-updates') ? 'active' : '' }}"
                                    href="{{ route('family.program-updates') }}">
                                    <i class="bi bi-megaphone"></i>
                                    Program Updates
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('family.rides') ? 'active' : '' }}"
                                    href="{{ route('family.rides') }}">
                                    <i class="bi bi-car-front"></i>
                                    UCL Rides
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('family.messages*') ? 'active' : '' }}"
                                    href="{{ route('family.messages') }}">
                                    <i class="bi bi-chat-dots"></i>
                                    Messages
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('family.resources') ? 'active' : '' }}"
                                    href="{{ route('family.resources') }}">
                                    <i class="bi bi-book"></i>
                                    Resources
                                </a>
                            </li>

                            <li class="nav-item">
                                <hr class="text-black-50 mx-3">
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('individuals.*') ? 'active' : '' }}"
                                    href="{{ route('individuals.index') }}">
                                    <i class="bi bi-people"></i>
                                    My Loved Ones
                                </a>
                            </li>
                        @elseif(auth()->user()->hasRole('dsp') && auth()->user()->isDspVerified())
                            @php
                                $isVerified = auth()->user()->isDspVerified();
                            @endphp
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                    href="{{ route('dashboard') }}">
                                    <i class="bi bi-house-door"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <hr class="text-black-50 mx-3">
                                <small class="text-black-50 px-3">DSP DASHBOARD</small>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ !$isVerified ? 'locked' : (request()->routeIs('dsp.home') ? 'active' : '') }}"
                                    href="{{ $isVerified ? route('dsp.home') : 'javascript:void(0)' }}"
                                    {!! !$isVerified ? 'title="Verification Required"' : '' !!}>
                                    <i class="bi bi-calendar-day"></i>
                                    Today's Plan
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link {{ !$isVerified ? 'locked' : (request()->routeIs('dsp.participants') ? 'active' : '') }}"
                                    href="{{ $isVerified ? route('dsp.participants') : 'javascript:void(0)' }}"
                                    {!! !$isVerified ? 'title="Verification Required"' : '' !!}>
                                    <i class="bi bi-people"></i>
                                    Participants
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link {{ !$isVerified ? 'locked' : (request()->routeIs('dsp.daily-logs') ? 'active' : '') }}"
                                    href="{{ $isVerified ? route('dsp.daily-logs') : 'javascript:void(0)' }}"
                                    {!! !$isVerified ? 'title="Verification Required"' : '' !!}>
                                    <i class="bi bi-journal-text"></i>
                                    Daily Logs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ !$isVerified ? 'locked' : (request()->routeIs('dsp.skill-tracking') ? 'active' : '') }}"
                                    href="{{ $isVerified ? route('dsp.skill-tracking') : 'javascript:void(0)' }}"
                                    {!! !$isVerified ? 'title="Verification Required"' : '' !!}>
                                    <i class="bi bi-graph-up"></i>
                                    Skill Tracking
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ !$isVerified ? 'locked' : (request()->routeIs('dsp.rides') ? 'active' : '') }}"
                                    href="{{ $isVerified ? route('dsp.rides') : 'javascript:void(0)' }}"
                                    {!! !$isVerified ? 'title="Verification Required"' : '' !!}>
                                    <i class="bi bi-car-front"></i>
                                    Ride Assigned
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ !$isVerified ? 'locked' : (request()->routeIs('dsp.peer-support') ? 'active' : '') }}"
                                    href="{{ $isVerified ? route('dsp.peer-support') : 'javascript:void(0)' }}"
                                    {!! !$isVerified ? 'title="Verification Required"' : '' !!}>
                                    <i class="bi bi-people-fill"></i>
                                    Peer Support
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ !$isVerified ? 'locked' : (request()->routeIs('chat.*') ? 'active' : '') }}"
                                    href="{{ $isVerified ? route('chat.index') : 'javascript:void(0)' }}"
                                    {!! !$isVerified ? 'title="Verification Required"' : '' !!}>
                                    <i class="bi bi-chat-dots"></i>
                                    Messages
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ !$isVerified ? 'locked' : (request()->routeIs('dsp.time-tracking') ? 'active' : '') }}"
                                    href="{{ $isVerified ? route('dsp.time-tracking') : 'javascript:void(0)' }}"
                                    {!! !$isVerified ? 'title="Verification Required"' : '' !!}>
                                    <i class="bi bi-clock-history"></i>
                                    Time Tracking
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ !$isVerified ? 'locked' : (request()->routeIs('dsp.calendar') ? 'active' : '') }}"
                                    href="{{ $isVerified ? route('dsp.calendar') : 'javascript:void(0)' }}"
                                    {!! !$isVerified ? 'title="Verification Required"' : '' !!}>
                                    <i class="bi bi-calendar3"></i>
                                    My Calendar
                                </a>
                            </li>
                        @elseif(auth()->user()->hasRole('program_staff'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                    href="{{ route('dashboard') }}">
                                    <i class="bi bi-house-door"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <hr class="text-black-50 mx-3">
                                <small class="text-black-50 px-3">PROGRAM DASHBOARD</small>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('program.home') ? 'active' : '' }}"
                                    href="{{ route('program.home') }}">
                                    <i class="bi bi-grid-3x3"></i>
                                    Activity Boards
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('program.attendance') ? 'active' : '' }}"
                                    href="{{ route('program.attendance') }}">
                                    <i class="bi bi-calendar-check"></i>
                                    Attendance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('program.family-updates') ? 'active' : '' }}"
                                    href="{{ route('program.family-updates') }}">
                                    <i class="bi bi-megaphone"></i>
                                    Family Updates
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('program.skill-tracking') ? 'active' : '' }}"
                                    href="{{ route('program.skill-tracking') }}">
                                    <i class="bi bi-graph-up"></i>
                                    Skill Tracking
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('program.spot-availability') ? 'active' : '' }}"
                                    href="{{ route('program.spot-availability') }}">
                                    <i class="bi bi-door-open"></i>
                                    Spot Availability
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('program.messages*') ? 'active' : '' }}"
                                    href="{{ route('program.messages') }}">
                                    <i class="bi bi-chat-dots"></i>
                                    Messages
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                    href="{{ route('dashboard') }}">
                                    <i class="bi bi-house-door"></i>
                                    Dashboard
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->hasRole('agency_admin'))
                            <li class="nav-item">
                                <hr class="text-black-50 mx-3">
                                <small class="text-black-50 px-3">AGENCY DASHBOARD</small>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agency.home') ? 'active' : '' }}"
                                    href="{{ route('agency.home') }}">
                                    <i class="bi bi-diagram-3"></i>
                                    Agency Network
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agency.staffing') ? 'active' : '' }}"
                                    href="{{ route('agency.staffing') }}">
                                    <i class="bi bi-people"></i>
                                    Staffing
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agency.compliance-alerts') ? 'active' : '' }}"
                                    href="{{ route('agency.compliance-alerts') }}">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    Compliance Alerts
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agency.incident-reports') ? 'active' : '' }}"
                                    href="{{ route('agency.incident-reports') }}">
                                    <i class="bi bi-file-text"></i>
                                    Incident Reports
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agency.program-utilization') ? 'active' : '' }}"
                                    href="{{ route('agency.program-utilization') }}">
                                    <i class="bi bi-graph-up"></i>
                                    Program Utilization
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agency.team-communication*') ? 'active' : '' }}"
                                    href="{{ route('agency.team-communication') }}">
                                    <i class="bi bi-chat-dots"></i>
                                    Team Communication
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agency.billing-payroll') ? 'active' : '' }}"
                                    href="{{ route('agency.billing-payroll') }}">
                                    <i class="bi bi-currency-dollar"></i>
                                    Billing/Payroll
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('agency.calendar') ? 'active' : '' }}"
                                    href="{{ route('agency.calendar') }}">
                                    <i class="bi bi-calendar3"></i>
                                    My Calendar
                                </a>
                            </li>
                        @endif
                    </ul>

                    <div class="sidebar-footer">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle text-black d-flex align-items-center" href="#"
                                role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-5 me-2"></i>
                                <div>
                                    <p class="text-truncate mb-0">{{ Auth::user()->full_name }}
                                    </p>

                                    @role('dsp')
                                        <p
                                            class="text-truncate mb-0 verification-status {{ Auth::user()->isDspVerified() ? 'verified' : '' }}">
                                            <i class="bi bi-patch-check-fill"></i>
                                            {{ Auth::user()->isDspVerified() ? 'Verified' : 'Not Verified' }}
                                        </p>
                                    @endrole
                                </div>

                            </a>
                            <ul class="dropdown-menu shadow">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-gear me-2"></i> Profile
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth

        <!-- Main Content -->
        <div class="{{ auth()->check() ? 'main-content' : '' }} flex-grow-1">
            @guest
                <!-- Guest Navigation (Login/Register) -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
                    <div class="container">
                        <a class="navbar-brand fw-bold" href="/">
                            Unity CareLink
                        </a>
                        <div class="ms-auto">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                        </div>
                    </div>
                </nav>
            @endguest

            @auth
                <!-- Page Header -->
                @isset($header)
                    <div class="navbar-custom mb-4 p-3">
                        <div class="container-fluid">
                            <h3 class="mb-0">{{ $header }}</h3>
                        </div>
                    </div>
                @endisset
            @endauth

            <!-- Page Content -->
            <main class="container-fluid">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')

    <!-- Verification Reminder Modal -->
    @if (Auth::user()->hasRole('dsp') && !Auth::user()->isDspVerified())
        <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center pt-0 pb-4">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 rounded-circle"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-shield-lock text-primary fs-1"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold mb-3">Verification Required</h3>
                        <p class="text-muted mb-4 px-3">
                            To access all the powerful features of Unity CareLink, please complete your profile
                            information and verify your phone number.
                        </p>
                        <div class="d-grid gap-2 px-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-lg fw-bold"
                                style="border-radius: 1rem;">
                                Complete Profile
                            </a>
                            <button type="button" class="btn btn-link text-muted text-decoration-none"
                                data-bs-dismiss="modal">
                                I'll do it later
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const modalElement = document.getElementById('verificationModal');
                if (!modalElement) return;

                // Create unique key per page
                const pageKey = 'verificationModalClosed_' + window.location.pathname;

                // If not closed before on this page
                if (!sessionStorage.getItem(pageKey)) {

                    const myModal = new bootstrap.Modal(modalElement);
                    myModal.show();

                    // When modal is closed → store state
                    modalElement.addEventListener('hidden.bs.modal', function() {
                        sessionStorage.setItem(pageKey, 'true');
                    });
                }

            });
        </script>
    @endif
</body>

</html>
