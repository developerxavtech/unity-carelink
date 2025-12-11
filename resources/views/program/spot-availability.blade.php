<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Spot Availability</h2>
                        <p class="text-muted">Manage program capacity and enrollment</p>
                    </div>
                    <button class="btn btn-primary" disabled>
                        <i class="bi bi-gear me-1"></i> Update Capacity
                    </button>
                </div>
            </div>
        </div>

        @if(isset($message))
            <!-- Coming Soon Message -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-door-open text-primary fs-1 mb-3"></i>
                            <h4>{{ $message }}</h4>
                            <p class="text-muted">Manage program capacity, track enrollment, and maintain waitlists.</p>

                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="p-3 border rounded">
                                        <i class="bi bi-diagram-3 text-info fs-2"></i>
                                        <h6 class="mt-2">Capacity Planning</h6>
                                        <p class="small text-muted mb-0">Set and adjust program capacity</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 border rounded">
                                        <i class="bi bi-people text-success fs-2"></i>
                                        <h6 class="mt-2">Enrollment</h6>
                                        <p class="small text-muted mb-0">Track current enrollments</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 border rounded">
                                        <i class="bi bi-list-check text-warning fs-2"></i>
                                        <h6 class="mt-2">Waitlist</h6>
                                        <p class="small text-muted mb-0">Manage waiting list efficiently</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="p-3 border rounded">
                                        <i class="bi bi-graph-up text-danger fs-2"></i>
                                        <h6 class="mt-2">Analytics</h6>
                                        <p class="small text-muted mb-0">View utilization trends</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Program Capacity Cards -->
            <div class="row">
                @foreach($spotsData as $spot)
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0">{{ $spot['program']->name }}</h5>
                                <small class="text-muted">{{ ucfirst($spot['program']->type) }} Program</small>
                            </div>
                            <div class="card-body">
                                <!-- Stats Row -->
                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                            <h3 class="mb-0">{{ $spot['capacity'] }}</h3>
                                            <small class="text-muted">Total Capacity</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                            <h3 class="mb-0">{{ $spot['enrolled'] }}</h3>
                                            <small class="text-muted">Enrolled</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <small class="text-muted">Capacity Used</small>
                                        <small class="text-muted">
                                            @if($spot['capacity'] > 0)
                                                {{ round(($spot['enrolled'] / $spot['capacity']) * 100) }}%
                                            @else
                                                0%
                                            @endif
                                        </small>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar
                                            @if($spot['capacity'] > 0 && ($spot['enrolled'] / $spot['capacity']) >= 0.9) bg-danger
                                            @elseif($spot['capacity'] > 0 && ($spot['enrolled'] / $spot['capacity']) >= 0.7) bg-warning
                                            @else bg-success
                                            @endif"
                                             role="progressbar"
                                             style="width: {{ $spot['capacity'] > 0 ? (($spot['enrolled'] / $spot['capacity']) * 100) : 0 }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Available/Waitlist -->
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                                <i class="bi bi-door-open text-info"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">{{ $spot['available'] }}</h5>
                                                <small class="text-muted">Available</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-2">
                                                <i class="bi bi-hourglass-split text-warning"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">{{ $spot['waitlist'] }}</h5>
                                                <small class="text-muted">Waitlist</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="d-flex gap-2 mt-3 pt-3 border-top">
                                    <button class="btn btn-sm btn-outline-primary flex-fill" disabled>
                                        <i class="bi bi-person-plus"></i> Enroll
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary flex-fill" disabled>
                                        <i class="bi bi-list-ul"></i> Waitlist
                                    </button>
                                    <button class="btn btn-sm btn-outline-info flex-fill" disabled>
                                        <i class="bi bi-gear"></i> Settings
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if($spotsData->isEmpty())
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-building text-muted fs-1 mb-3"></i>
                                <p class="text-muted">No programs found.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Quick Stats Summary -->
        @if($programs->count() > 0 && !isset($message))
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-light">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <h4 class="mb-0">{{ $spotsData->sum('capacity') }}</h4>
                                    <small class="text-muted">Total Capacity</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="mb-0">{{ $spotsData->sum('enrolled') }}</h4>
                                    <small class="text-muted">Total Enrolled</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="mb-0">{{ $spotsData->sum('available') }}</h4>
                                    <small class="text-muted">Available Spots</small>
                                </div>
                                <div class="col-md-3">
                                    <h4 class="mb-0">{{ $spotsData->sum('waitlist') }}</h4>
                                    <small class="text-muted">On Waitlist</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
