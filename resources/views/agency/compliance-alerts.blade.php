<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Compliance Alerts</h2>
                <p class="text-muted">Monitor and manage compliance issues across your agency</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Alerts</p>
                                <h3 class="mb-0">{{ $totalAlerts }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
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
                                <p class="text-muted mb-1 small">Critical</p>
                                <h3 class="mb-0 text-danger">{{ $criticalAlerts }}</h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-exclamation-octagon text-danger fs-4"></i>
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
                                <p class="text-muted mb-1 small">Overdue</p>
                                <h3 class="mb-0 text-danger">{{ $overdueAlerts }}</h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-clock-history text-danger fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Priority</label>
                                <select name="priority" class="form-select">
                                    <option value="all" {{ $priority === 'all' ? 'selected' : '' }}>All Priorities</option>
                                    <option value="critical" {{ $priority === 'critical' ? 'selected' : '' }}>Critical</option>
                                    <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>High</option>
                                    <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>Low</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Status</label>
                                <select name="status" class="form-select">
                                    <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small text-muted">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">All</option>
                                    <option value="documentation">Documentation</option>
                                    <option value="training">Training</option>
                                    <option value="licensing">Licensing</option>
                                    <option value="safety">Safety</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check text-warning"></i> Compliance Issues
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($alerts as $alert)
                            <div class="border-start border-3 border-{{ $alert->priority === 'critical' ? 'danger' : ($alert->priority === 'high' ? 'warning' : 'secondary') }} ps-3 mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $alert->title }}</h6>
                                        <p class="text-muted small mb-2">{{ $alert->description }}</p>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-{{ $alert->priority === 'critical' ? 'danger' : ($alert->priority === 'high' ? 'warning' : 'secondary') }}">
                                                {{ ucfirst($alert->priority) }} Priority
                                            </span>
                                            <span class="badge bg-light text-dark">
                                                {{ $alert->category }}
                                            </span>
                                            <span class="badge bg-{{ $alert->status === 'resolved' ? 'success' : 'warning' }}">
                                                {{ ucfirst(str_replace('_', ' ', $alert->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $alert->due_date }}</small>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <button class="btn btn-sm btn-primary" disabled>
                                        <i class="bi bi-check-circle"></i> Resolve
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-eye"></i> View Details
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-check-circle text-success fs-1 mb-3"></i>
                                <h5 class="text-success">All Clear!</h5>
                                <p class="text-muted">No compliance alerts at this time.</p>
                                <p class="small text-muted">Compliance monitoring functionality coming soon!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
