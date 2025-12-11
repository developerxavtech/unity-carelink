<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Agency Network</h2>
                <p class="text-muted">Overview of your agency operations and programs</p>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Programs</p>
                                <h3 class="mb-0">{{ $totalPrograms }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-building text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Staff</p>
                                <h3 class="mb-0">{{ $totalStaff }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-people text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Individuals Served</p>
                                <h3 class="mb-0">{{ $totalIndividuals }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-person-heart text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Compliance Alerts</p>
                                <h3 class="mb-0 {{ $complianceAlerts > 0 ? 'text-danger' : '' }}">{{ $complianceAlerts }}</h3>
                            </div>
                            <div class="bg-{{ $complianceAlerts > 0 ? 'danger' : 'warning' }} bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-exclamation-triangle text-{{ $complianceAlerts > 0 ? 'danger' : 'warning' }} fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Programs Overview -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="bi bi-building text-primary"></i> Programs
                            </h5>
                            <a href="{{ route('agency.program-utilization') }}" class="btn btn-sm btn-outline-primary">
                                View Analytics
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($programs as $program)
                            <div class="border-start border-3 border-primary ps-3 mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $program->name }}</h6>
                                        <p class="text-muted small mb-2">{{ $program->address ?? 'No address listed' }}</p>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-geo-alt"></i> {{ $program->city ?? 'N/A' }}
                                            </span>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-telephone"></i> {{ $program->phone ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-gear"></i> Manage
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-building text-muted fs-1 mb-3"></i>
                                <p class="text-muted">No programs found.</p>
                                <p class="small text-muted">Programs will appear here once configured.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Quick Stats & Actions -->
            <div class="col-lg-4">
                <!-- Active Staff -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="bi bi-person-check text-success"></i> Active Staff
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $activeStaff }}</h2>
                        <p class="text-muted small mb-0">Active in last 30 days</p>
                        <a href="{{ route('agency.staffing') }}" class="btn btn-outline-primary btn-sm mt-3 w-100">
                            Manage Staff
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="bi bi-lightning-charge text-warning"></i> Quick Actions
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('agency.staffing') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-people"></i> View All Staff
                            </a>
                            <a href="{{ route('agency.compliance-alerts') }}" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-exclamation-triangle"></i> Compliance Alerts
                            </a>
                            <a href="{{ route('agency.incident-reports') }}" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-file-text"></i> Incident Reports
                            </a>
                            <a href="{{ route('agency.billing-payroll') }}" class="btn btn-outline-success btn-sm">
                                <i class="bi bi-currency-dollar"></i> Billing & Payroll
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Alerts Summary -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0">
                            <i class="bi bi-bell text-danger"></i> Alerts
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small">Compliance Issues</span>
                            <span class="badge bg-danger">{{ $complianceAlerts }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small">Pending Incidents</span>
                            <span class="badge bg-warning">{{ $pendingIncidents }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
