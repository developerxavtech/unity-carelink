<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Incident Reports</h2>
                        <p class="text-muted">Track and manage incident reports across your agency</p>
                    </div>
                    <button class="btn btn-primary" disabled>
                        <i class="bi bi-plus-circle me-1"></i> New Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Incidents</p>
                                <h3 class="mb-0">{{ $totalIncidents }}</h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-file-text text-danger fs-4"></i>
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
                                <p class="text-muted mb-1 small">Pending Review</p>
                                <h3 class="mb-0 text-warning">{{ $pendingReview }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-hourglass-split text-warning fs-4"></i>
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
                                <p class="text-muted mb-1 small">Resolved</p>
                                <h3 class="mb-0 text-success">{{ $resolved }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-check-circle text-success fs-4"></i>
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
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Incident Type</label>
                                <select name="type" class="form-select">
                                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
                                    <option value="injury" {{ $type === 'injury' ? 'selected' : '' }}>Injury</option>
                                    <option value="behavior" {{ $type === 'behavior' ? 'selected' : '' }}>Behavior</option>
                                    <option value="safety" {{ $type === 'safety' ? 'selected' : '' }}>Safety</option>
                                    <option value="medical" {{ $type === 'medical' ? 'selected' : '' }}>Medical</option>
                                    <option value="other" {{ $type === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Status</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="under_review" {{ $status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Date Range</label>
                                <select name="date_range" class="form-select">
                                    <option value="week" {{ $dateRange === 'week' ? 'selected' : '' }}>This Week</option>
                                    <option value="month" {{ $dateRange === 'month' ? 'selected' : '' }}>This Month</option>
                                    <option value="quarter" {{ $dateRange === 'quarter' ? 'selected' : '' }}>This Quarter</option>
                                    <option value="year" {{ $dateRange === 'year' ? 'selected' : '' }}>This Year</option>
                                    <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>All Time</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-funnel"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Incident Reports List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-folder2-open text-danger"></i> Incident Reports
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($incidents as $incident)
                            <div class="border-start border-3 border-{{ $incident->severity === 'critical' ? 'danger' : ($incident->severity === 'moderate' ? 'warning' : 'secondary') }} ps-3 mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0">{{ $incident->title }}</h6>
                                            <small class="text-muted">{{ $incident->incident_date }}</small>
                                        </div>
                                        <p class="text-muted small mb-2">{{ $incident->description }}</p>
                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="badge bg-{{ $incident->type === 'injury' ? 'danger' : ($incident->type === 'behavior' ? 'warning' : 'info') }}">
                                                {{ ucfirst($incident->type) }}
                                            </span>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-person"></i> {{ $incident->individual_name }}
                                            </span>
                                            <span class="badge bg-light text-dark">
                                                <i class="bi bi-building"></i> {{ $incident->program_name }}
                                            </span>
                                            <span class="badge bg-{{ $incident->status === 'resolved' ? 'success' : ($incident->status === 'under_review' ? 'info' : 'warning') }}">
                                                {{ ucfirst(str_replace('_', ' ', $incident->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-3">
                                    <button class="btn btn-sm btn-primary" disabled>
                                        <i class="bi bi-eye"></i> Review
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-download"></i> Export
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-shield-check text-success fs-1 mb-3"></i>
                                <h5>No Incidents Reported</h5>
                                <p class="text-muted">No incident reports match your filters.</p>
                                <p class="small text-muted">Incident reporting functionality coming soon!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
