<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Unity CareLink') }}</title>

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
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door"></i>
                                Dashboard
                            </a>
                        </li>

                        @if(auth()->user()->hasRole('family_admin'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('individuals.*') ? 'active' : '' }}"
                                    href="{{ route('individuals.index') }}">
                                    <i class="bi bi-people"></i>
                                    My Loved Ones
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->hasAnyRole(['dsp', 'agency_admin']))
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-calendar-check"></i>
                                    My Shifts
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-chat-dots"></i>
                                Messages
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="bi bi-calendar3"></i>
                                Calendar
                            </a>
                        </li>

                        @if(auth()->user()->hasRole('agency_admin'))
                            <li class="nav-item">
                                <hr class="text-black-50 mx-3">
                                <small class="text-black-50 px-3">AGENCY ADMIN</small>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-speedometer2"></i>
                                    Operations
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-people"></i>
                                    Staff
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-clipboard-check"></i>
                                    Compliance
                                </a>
                            </li>
                        @endif
                    </ul>

                    <!-- User Menu (Bottom) -->
                    <div class="position-absolute bottom-0 w-100 p-3">
                        <hr class="text-black-50">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle text-black" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::user()->full_name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-gear"></i> Profile
                                    </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i> Logout
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
</body>

</html>