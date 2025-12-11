<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Skill Tracking</h2>
                        <p class="text-muted">Monitor participant skill development and goal progress</p>
                    </div>
                    <button class="btn btn-primary" disabled>
                        <i class="bi bi-plus-circle me-1"></i> Add Goal
                    </button>
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
                                <label class="form-label small text-muted">Select Participant</label>
                                <select name="participant_id" class="form-select">
                                    <option value="">All Participants</option>
                                    @foreach($participants as $participant)
                                        <option value="{{ $participant->id }}">
                                            {{ $participant->first_name }} {{ $participant->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Skill Category</label>
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    <option value="daily_living">Daily Living</option>
                                    <option value="social">Social Skills</option>
                                    <option value="communication">Communication</option>
                                    <option value="motor">Motor Skills</option>
                                    <option value="cognitive">Cognitive</option>
                                    <option value="vocational">Vocational</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small text-muted">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                    <option value="on_hold">On Hold</option>
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

        @if(isset($message))
            <!-- Coming Soon Message -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-graph-up-arrow text-primary fs-1 mb-3"></i>
                            <h4>{{ $message }}</h4>
                            <p class="text-muted">Track participant goals, monitor progress, and celebrate achievements.</p>

                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <i class="bi bi-target text-info fs-2"></i>
                                        <h6 class="mt-2">Set Goals</h6>
                                        <p class="small text-muted mb-0">Create individualized skill development goals</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <i class="bi bi-graph-up text-success fs-2"></i>
                                        <h6 class="mt-2">Track Progress</h6>
                                        <p class="small text-muted mb-0">Monitor daily progress and milestones</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded">
                                        <i class="bi bi-trophy text-warning fs-2"></i>
                                        <h6 class="mt-2">Celebrate Wins</h6>
                                        <p class="small text-muted mb-0">Acknowledge achievements and successes</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Skills Grid -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">Active Goals</h5>
                        </div>
                        <div class="card-body">
                            @forelse($skills as $skill)
                                <div class="border-start border-3 border-primary ps-3 mb-4 pb-4 border-bottom">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $skill->goal_name }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-person"></i> {{ $skill->participant_name }}
                                            </small>
                                        </div>
                                        <span class="badge bg-info">{{ $skill->category }}</span>
                                    </div>

                                    <p class="text-muted small mb-3">{{ $skill->description }}</p>

                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-muted">Progress</small>
                                            <small class="text-muted">{{ $skill->progress }}%</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                 style="width: {{ $skill->progress }}%"
                                                 aria-valuenow="{{ $skill->progress }}"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 mt-3">
                                        <button class="btn btn-sm btn-primary" disabled>
                                            <i class="bi bi-plus-circle"></i> Log Progress
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>
                                            <i class="bi bi-eye"></i> View Details
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="bi bi-clipboard-check text-muted fs-1 mb-3"></i>
                                    <p class="text-muted">No active goals found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
