<x-app-layout>
    <x-slot name="header">
        Welcome Back, {{ Auth::user()->first_name ?? 'User' }}!
    </x-slot>

    <div class="row">
        <!-- Quick Stats -->
        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Active Individuals</h6>
                            <h2 class="mb-0">{{ $individualProfiles->count() }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Unread Messages</h6>
                            <h2 class="mb-0">{{ $unreadMessages }}</h2>
                        </div>
                        <i class="bi bi-chat-dots fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Upcoming Events</h6>
                            <h2 class="mb-0">{{ $upcomingEvents }}</h2>
                        </div>
                        <i class="bi bi-calendar-event fs-1 text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pending Items</h6>
                            <h2 class="mb-0">{{ $pendingItems }}</h2>
                        </div>
                        <i class="bi bi-exclamation-circle fs-1 text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today's Overview -->
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-day text-primary"></i>
                            Today's Overview
                        </h5>
                        <span class="badge bg-primary">{{ now()->format('l, M d, Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Today's Schedule -->
                    <h6 class="text-muted mb-3">Scheduled Events</h6>
                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle"></i>
                        No scheduled events for today. Your calendar is clear!
                    </div>

                    <!-- Recent Activity Feed -->
                    <h6 class="text-muted mb-3 mt-4">Recent Activity</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 mb-2"></i>
                                <p class="mb-0">No recent activity to display</p>
                                <small>Activity from your care team will appear here</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge text-warning"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('individuals.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-person-plus"></i>
                            Add Individual Profile
                        </a>
                        <a href="{{ route('individuals.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-people"></i>
                            View All Individuals
                        </a>
                        <a href="{{ route('chat.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-chat-dots"></i>
                            Messages
                        </a>
                        <a href="{{ route('family.calendar') }}" class="btn btn-outline-primary">
                            <i class="bi bi-calendar-plus"></i>
                            Calendar
                        </a>
                    </div>

                    <hr class="my-4">

                    <!-- User Status Widget -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-heart-pulse"></i>
                            CarePulse Check-In
                        </h6>
                        <div
                            class="alert {{ Auth::user()->isBusy() ? 'alert-warning' : 'alert-light' }} border d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fs-4">{{ Auth::user()->status_emoji ?? '📍' }}</span>
                                <span class="ms-2 fw-bold">{{ Auth::user()->activity_status ?? 'Available' }}</span>
                                @if(Auth::user()->status_busy_until && Auth::user()->isBusy())
                                    <div class="text-muted small ms-5">
                                        Until {{ Auth::user()->status_busy_until->format('g:i A') }}
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('family.status.edit') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- CarePulse Widget -->
                    <!-- <div class="mb-3">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-heart-pulse"></i>
                            CarePulse Check-In
                        </h6>
                        <div class="alert alert-light border text-center">
                            <p class="mb-2">How is everyone doing today?</p>
                            <small class="text-muted">Check-in feature coming soon</small>
                        </div>
                    </div> -->
                </div>
            </div>

            <!-- Support Resources -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-question-circle text-info"></i>
                        Getting Started
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('family.resources') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-book"></i> Quick Start Guide
                        </a>
                        <a href="{{ route('family.resources') }}" class="list-group-item list-group-item-action">
                            <i class="bi bi-play-circle"></i> Video Tutorials
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-headset"></i> Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Individuals Overview (For Family Admin) -->
    <div class="row">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-people text-primary"></i>
                            My Loved Ones
                        </h5>
                        <a href="{{ route('individuals.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Individual
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($individualProfiles->count() > 0)
                        <div class="row">
                            @foreach($individualProfiles as $individual)
                                <div class="col-md-6 mb-3">
                                    <div class="card border">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $individual->first_name }} {{ $individual->last_name }}
                                                    </h6>
                                                    <small class="text-muted">{{ $individual->age }} years old</small>
                                                </div>
                                                <span class="badge bg-success">Active</span>
                                            </div>
                                            <p class="mt-2 mb-3 small text-muted">
                                                {{ Str::limit($individual->strengths_abilities ?? 'Profile information available', 80) }}
                                            </p>
                                            <a href="{{ route('individuals.show', $individual) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> View Profile
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-person-plus fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">No Individual Profiles Yet</h5>
                            <p class="text-muted mb-4">
                                Get started by creating a profile for your loved one.<br>
                                This will be the central hub for all their care coordination.
                            </p>
                            <a href="{{ route('individuals.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create First Profile
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>