<x-app-layout>
    <x-slot name="header">
        Agency Command Center
    </x-slot>

    <!-- Premium Badge -->
    <div class="alert alert-gradient bg-gradient mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">
                    <i class="bi bi-star-fill"></i> Premium Agency Account
                </h5>
                <p class="mb-0 small opacity-75">Full access to Agency Command Center features</p>
            </div>
            <span class="badge bg-white text-primary px-3 py-2">Pro Plan</span>
        </div>
    </div>

    <div class="row">
        <!-- Key Metrics -->
        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Active Staff</h6>
                            <h2 class="mb-0">0</h2>
                            <small class="text-success"><i class="bi bi-arrow-up"></i> 0% this month</small>
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
                            <h6 class="text-muted mb-2">Active Clients</h6>
                            <h2 class="mb-0">0</h2>
                            <small class="text-muted">Total individuals</small>
                        </div>
                        <i class="bi bi-person-check fs-1 text-success opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Open Shifts</h6>
                            <h2 class="mb-0">0</h2>
                            <small class="text-warning"><i class="bi bi-exclamation-triangle"></i> Need coverage</small>
                        </div>
                        <i class="bi bi-calendar-x fs-1 text-warning opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Expiring Certs</h6>
                            <h2 class="mb-0">0</h2>
                            <small class="text-muted">Next 30 days</small>
                        </div>
                        <i class="bi bi-patch-check fs-1 text-danger opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Real-Time Operations -->
        <div class="col-lg-8 mb-4">
            <!-- Today's Operations -->
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-speedometer2 text-primary"></i>
                            Real-Time Operations
                        </h5>
                        <div>
                            <span class="badge bg-success me-2">
                                <i class="bi bi-circle-fill" style="font-size: 0.5rem;"></i> Live
                            </span>
                            <span class="badge bg-primary">{{ now()->format('l, M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Active Shifts Today -->
                    <h6 class="text-muted mb-3">Active Shifts Today</h6>
                    <div class="alert alert-light border">
                        <i class="bi bi-info-circle"></i>
                        No active shifts running currently
                    </div>

                    <!-- Shift Coverage Status -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <h3 class="mb-1 text-success">0</h3>
                                <small class="text-muted">Covered</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <h3 class="mb-1 text-warning">0</h3>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <h3 class="mb-1 text-danger">0</h3>
                                <small class="text-muted">Unfilled</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compliance Dashboard -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-check text-success"></i>
                            Compliance Overview
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary" disabled>
                            <i class="bi bi-file-earmark-text"></i> Full Report
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Overall Compliance Score</span>
                            <span class="fw-bold text-success">100%</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Staff Certifications</small>
                                        <h5 class="mb-0">0/0</h5>
                                    </div>
                                    <i class="bi bi-patch-check fs-2 text-success opacity-25"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Documentation</small>
                                        <h5 class="mb-0">0/0</h5>
                                    </div>
                                    <i class="bi bi-file-earmark-check fs-2 text-success opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info border-info mb-0">
                        <i class="bi bi-lightbulb"></i>
                        <strong>Pro Tip:</strong> Set up automated compliance alerts to stay ahead of deadlines
                    </div>
                </div>
            </div>

            <!-- Recent Incidents -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle text-warning"></i>
                            Incident Reports
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-primary" disabled>View All</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                        <p class="mb-0">No incidents reported</p>
                        <small>All clear - great job team!</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Quick Actions & Alerts -->
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
                        <button class="btn btn-primary" disabled>
                            <i class="bi bi-person-plus"></i> Add Staff Member
                        </button>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="bi bi-calendar-plus"></i> Create Shift
                        </button>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="bi bi-file-earmark-plus"></i> Log Incident
                        </button>
                        <button class="btn btn-outline-primary" disabled>
                            <i class="bi bi-clipboard-data"></i> Run Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- Critical Alerts -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-bell text-danger"></i>
                        Critical Alerts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-check-circle fs-2 text-success"></i>
                        <p class="mb-0 small">No critical alerts</p>
                    </div>
                </div>
            </div>

            <!-- Shift Coverage Engine -->
            <div class="card dashboard-card mt-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-robot"></i>
                        Smart Shift Matching
                    </h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">AI-powered DSP matching for open shifts</p>
                    <button class="btn btn-sm btn-primary w-100" disabled>
                        <i class="bi bi-magic"></i> Find Matches
                    </button>
                </div>
            </div>

            <!-- Billing Prep -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-currency-dollar text-success"></i>
                        Billing Prep
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Current Period</small>
                        <h6>{{ now()->format('F Y') }}</h6>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-success" disabled>
                            <i class="bi bi-file-earmark-spreadsheet"></i> Export Hours
                        </button>
                        <button class="btn btn-sm btn-outline-primary" disabled>
                            <i class="bi bi-file-pdf"></i> Generate Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- Staff Performance -->
            <div class="card dashboard-card mt-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-graph-up text-info"></i>
                        Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Staff Retention</small>
                            <small class="fw-bold">--%</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Shift Fill Rate</small>
                            <small class="fw-bold">--%</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: 0%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <small>On-time Documentation</small>
                            <small class="fw-bold">--%</small>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-info" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Overview Table -->
    <div class="row">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-people text-primary"></i>
                            Staff Overview
                        </h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2" disabled>
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <button class="btn btn-sm btn-primary" disabled>
                                <i class="bi bi-person-plus"></i> Add Staff
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Certifications</th>
                                    <th>Hours (MTD)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <i class="bi bi-people fs-1 mb-2"></i>
                                        <p class="mb-0">No staff members added yet</p>
                                        <small>Add your first staff member to get started</small>
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
