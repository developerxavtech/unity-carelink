<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Time Tracking</h2>
                    <p class="text-muted">Log hours and manage timesheets</p>
                </div>
                <button class="btn btn-primary" disabled>
                    <i class="bi bi-plus-circle me-1"></i> Clock In/Out
                </button>
            </div>
        </div>

        <!-- Weekly Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">This Week</p>
                                <h3 class="mb-0">{{ $totalHours }} hrs</h3>
                                <small class="text-muted">{{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j') }}</small>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-clock text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">This Month</p>
                                <h3 class="mb-0">0 hrs</h3>
                                <small class="text-muted">{{ now()->format('F Y') }}</small>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-calendar-month text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Pending Approval</p>
                                <h3 class="mb-0">0 hrs</h3>
                                <small class="text-muted">Awaiting review</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-hourglass-split text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                    <i class="bi bi-clock-history text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-2">Time Tracking Coming Soon!</h5>
                <p class="text-muted mb-4">{{ $message }}</p>

                <div class="row g-3 mt-4 text-start">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-clock-fill text-primary fs-4 mb-2"></i>
                            <h6>Clock In/Out</h6>
                            <p class="small text-muted mb-0">Quick and easy time logging with automatic calculations.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-calendar-week text-success fs-4 mb-2"></i>
                            <h6>View Timesheets</h6>
                            <p class="small text-muted mb-0">Review and edit your time entries for accurate payroll.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-file-earmark-text text-info fs-4 mb-2"></i>
                            <h6>Export Reports</h6>
                            <p class="small text-muted mb-0">Download timesheets for your records and compliance.</p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-light mt-4 d-inline-block text-start">
                    <h6 class="mb-2">
                        <i class="bi bi-info-circle me-2"></i>Upcoming Features:
                    </h6>
                    <ul class="mb-0 ps-3 small">
                        <li>Mobile clock in/out with GPS verification</li>
                        <li>Automatic break time calculations</li>
                        <li>Timesheet approval workflows</li>
                        <li>Integration with payroll systems</li>
                        <li>Overtime tracking and alerts</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
