<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Program Utilization</h2>
                <p class="text-muted">Analytics and performance metrics across your programs</p>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $totalCapacity }}</h2>
                        <p class="text-muted small mb-0">Total Capacity</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $totalEnrolled }}</h2>
                        <p class="text-muted small mb-0">Total Enrolled</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $averageUtilization }}%</h2>
                        <p class="text-muted small mb-0">Avg Utilization</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h2 class="mb-1">{{ $programs->count() }}</h2>
                        <p class="text-muted small mb-0">Active Programs</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Program Cards -->
        <div class="row">
            @forelse($utilizationData as $data)
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">{{ $data['program']->name }}</h5>
                            <small class="text-muted">{{ ucfirst($data['program']->type) }} Program</small>
                        </div>
                        <div class="card-body">
                            <!-- Capacity Metrics -->
                            <div class="row g-3 mb-3">
                                <div class="col-4 text-center">
                                    <div class="p-2 bg-primary bg-opacity-10 rounded">
                                        <h4 class="mb-0">{{ $data['capacity'] }}</h4>
                                        <small class="text-muted">Capacity</small>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="p-2 bg-success bg-opacity-10 rounded">
                                        <h4 class="mb-0">{{ $data['enrolled'] }}</h4>
                                        <small class="text-muted">Enrolled</small>
                                    </div>
                                </div>
                                <div class="col-4 text-center">
                                    <div class="p-2 bg-info bg-opacity-10 rounded">
                                        <h4 class="mb-0">{{ $data['utilization'] }}%</h4>
                                        <small class="text-muted">Utilized</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Utilization Progress Bar -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Capacity Utilization</small>
                                    <small class="text-muted">{{ $data['utilization'] }}%</small>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar
                                        @if($data['utilization'] >= 90) bg-danger
                                        @elseif($data['utilization'] >= 70) bg-success
                                        @else bg-warning
                                        @endif"
                                         role="progressbar"
                                         style="width: {{ $data['utilization'] }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- Program Details -->
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Location:</span>
                                    <span class="small">{{ $data['program']->city ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Available Spots:</span>
                                    <span class="small">{{ $data['capacity'] - $data['enrolled'] }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Est. Monthly Revenue:</span>
                                    <span class="small">${{ number_format($data['revenue'], 2) }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2 mt-3 pt-3 border-top">
                                <button class="btn btn-sm btn-outline-primary flex-fill" disabled>
                                    <i class="bi bi-graph-up"></i> Analytics
                                </button>
                                <button class="btn btn-sm btn-outline-secondary flex-fill" disabled>
                                    <i class="bi bi-gear"></i> Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-building text-muted fs-1 mb-3"></i>
                            <p class="text-muted">No programs found.</p>
                            <p class="small text-muted">Program utilization tracking coming soon!</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Analytics Summary -->
        @if($utilizationData->isNotEmpty())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-body">
                            <h6 class="mb-3">
                                <i class="bi bi-graph-up text-primary"></i> Performance Insights
                            </h6>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <i class="bi bi-trophy text-warning fs-3"></i>
                                    <p class="mb-0 mt-2"><strong>Top Program</strong></p>
                                    <small class="text-muted">
                                        @if($utilizationData->isNotEmpty())
                                            {{ $utilizationData->sortByDesc('utilization')->first()['program']->name ?? 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <i class="bi bi-people text-success fs-3"></i>
                                    <p class="mb-0 mt-2"><strong>Total Participants</strong></p>
                                    <small class="text-muted">{{ $totalEnrolled }} individuals</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <i class="bi bi-door-open text-info fs-3"></i>
                                    <p class="mb-0 mt-2"><strong>Available Capacity</strong></p>
                                    <small class="text-muted">{{ $totalCapacity - $totalEnrolled }} spots</small>
                                </div>
                                <div class="col-md-3 text-center">
                                    <i class="bi bi-bar-chart text-primary fs-3"></i>
                                    <p class="mb-0 mt-2"><strong>Network Health</strong></p>
                                    <small class="text-muted">
                                        @if($averageUtilization >= 70)
                                            <span class="text-success">Excellent</span>
                                        @elseif($averageUtilization >= 50)
                                            <span class="text-info">Good</span>
                                        @else
                                            <span class="text-warning">Needs Attention</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
