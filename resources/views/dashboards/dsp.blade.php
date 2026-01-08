<x-app-layout>
    <x-slot name="header">
        Welcome Back, {{ Auth::user()->first_name ?? 'User' }}!
    </x-slot>

    <div class="row">
        <!-- Quick Stats for DSP -->
        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Today's Shifts</h6>
                            <h2 class="mb-0">0</h2>
                        </div>
                        <i class="bi bi-clock fs-1 text-primary opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Upcoming Shifts</h6>
                            <h2 class="mb-0">0</h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Hours This Week</h6>
                            <h2 class="mb-0">0</h2>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Pending Notes</h6>
                            <h2 class="mb-0">0</h2>
                        </div>
                        <i class="bi bi-journal-text fs-1 text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today's Shifts -->
        <div class="col-lg-8 mb-4">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-day text-primary"></i>
                            Today's Shifts
                        </h5>
                        <span class="badge bg-primary">{{ now()->format('l, M d, Y') }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        No shifts scheduled for today. Enjoy your day off!
                    </div>

                    <!-- Shift Card Example (Hidden for now) -->
                    <div class="d-none">
                        <div class="card border mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">John Doe</h6>
                                        <p class="text-muted mb-2">
                                            <i class="bi bi-clock"></i> 9:00 AM - 5:00 PM
                                        </p>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="bi bi-journal-plus"></i> Add Note
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Care Notes -->
                    <h6 class="text-muted mb-3 mt-4">Recent Care Notes</h6>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-journal fs-1 mb-2"></i>
                        <p class="mb-0">No care notes yet</p>
                        <small>Your shift notes will appear here</small>
                    </div>
                </div>
            </div>

            <!-- CarePulse Check-Ins -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-pulse text-danger"></i>
                        CarePulse Check-Ins
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Quick mood check-ins for individuals you support</p>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-emoji-smile fs-1 mb-2"></i>
                        <p class="mb-0">No check-ins available</p>
                        <small>Complete check-ins during your shifts</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Actions -->
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge text-warning"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg" disabled>
                            <i class="bi bi-journal-plus"></i>
                            Add Care Note
                        </button>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="bi bi-heart-pulse"></i>
                            CarePulse Check
                        </button>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="bi bi-calendar"></i>
                            View Schedule
                        </button>
                        <button class="btn btn-outline-success" disabled>
                            <i class="bi bi-arrow-left-right"></i>
                            Request Shift Swap
                        </button>
                    </div>

                    <hr class="my-4">

                    <!-- User Status Widget -->
                    <div class="mb-2">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-heart-pulse"></i>
                            CarePulse Check-In
                        </h6>
                        <div
                            class="alert {{ Auth::user()->isBusy() ? 'alert-warning' : 'alert-light' }} border d-flex justify-content-between align-items-center mb-0">
                            <div>
                                <span class="fs-4">{{ Auth::user()->status_emoji ?? '📍' }}</span>
                                <span class="ms-2 fw-bold">{{ Auth::user()->activity_status ?? 'Available' }}</span>
                                @if (Auth::user()->status_busy_until && Auth::user()->isBusy())
                                    <div class="text-muted small ms-5">
                                        Until {{ Auth::user()->status_busy_until->format('g:i A') }}
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('dsp.status.edit') }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Shifts -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-briefcase text-success"></i>
                        Available Shifts
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Pick up extra shifts</p>
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-calendar-x"></i>
                        <p class="mb-0 small">No shifts available</p>
                    </div>
                </div>
            </div>

            <!-- My Individuals -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-people text-primary"></i>
                        My Individuals
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-person"></i>
                        <p class="mb-0 small">No assignments yet</p>
                    </div>
                </div>
            </div>

            <!-- Support & Community -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-chat-heart text-danger"></i>
                        DSP Corner
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Anonymous peer support</p>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-primary" disabled>
                            <i class="bi bi-megaphone"></i> Share Victory
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" disabled>
                            <i class="bi bi-chat-quote"></i> Vent Corner
                        </button>
                        <button class="btn btn-sm btn-outline-info" disabled>
                            <i class="bi bi-question-circle"></i> Ask Advice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Overview -->
    <div class="row">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-week text-primary"></i>
                            This Week's Schedule
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary" disabled>
                            <i class="bi bi-calendar3"></i> View Full Calendar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Date</th>
                                    <th>Shift Time</th>
                                    <th>Individual</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-calendar-x fs-3"></i>
                                        <p class="mb-0">No shifts scheduled this week</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
