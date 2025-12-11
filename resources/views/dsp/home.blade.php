<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Today's Plan</h2>
                <p class="text-muted">{{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>

        <!-- Today's Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Today's Shifts</p>
                                <h3 class="mb-0">{{ $todayShifts }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-calendar-check text-primary fs-4"></i>
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
                                <p class="text-muted mb-1 small">Participants</p>
                                <h3 class="mb-0">{{ $individuals->count() }}</h3>
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
                                <p class="text-muted mb-1 small">Hours This Week</p>
                                <h3 class="mb-0">{{ $hoursThisWeek }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-clock-history text-info fs-4"></i>
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
                                <p class="text-muted mb-1 small">Pending Notes</p>
                                <h3 class="mb-0">{{ $pendingNotes }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-journal-x text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Today's Notes -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Today's Care Notes</h5>
                    </div>
                    <div class="card-body">
                        @forelse($todaysNotes as $note)
                            <div class="border-start border-3 border-primary ps-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0">{{ $note->individualProfile->first_name }} {{ $note->individualProfile->last_name }}</h6>
                                    <small class="text-muted">{{ $note->created_at->format('g:i A') }}</small>
                                </div>
                                @if($note->mood)
                                    <span class="badge bg-{{ $note->mood === 'great' ? 'success' : ($note->mood === 'good' ? 'info' : ($note->mood === 'okay' ? 'warning' : 'danger')) }} mb-2">
                                        {{ ucfirst($note->mood) }}
                                    </span>
                                @endif
                                <p class="text-muted small mb-0">{{ Str::limit($note->notes, 150) }}</p>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-journal-plus text-muted fs-1 mb-3"></i>
                                <p class="text-muted">No care notes created today yet.</p>
                                <a href="{{ route('dsp.daily-logs') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-journal-text me-1"></i> View Daily Logs
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- My Participants -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">My Participants</h5>
                    </div>
                    <div class="card-body">
                        @forelse($individuals as $individual)
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-person text-primary fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $individual->first_name }} {{ $individual->last_name }}</h6>
                                    <small class="text-muted">Age {{ $individual->date_of_birth ? \Carbon\Carbon::parse($individual->date_of_birth)->age : 'N/A' }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-people text-muted fs-1 mb-3"></i>
                                <p class="text-muted small">No participants assigned yet.</p>
                            </div>
                        @endforelse

                        @if($individuals->count() > 0)
                            <a href="{{ route('dsp.participants') }}" class="btn btn-outline-primary btn-sm w-100">
                                View All Participants
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
