<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Activity Boards</h2>
                <p class="text-muted">{{ now()->format('l, F j, Y') }}</p>
            </div>
        </div>

        <!-- Program Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Participants</p>
                                <h3 class="mb-0">{{ $totalParticipants }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-people text-primary fs-4"></i>
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
                                <p class="text-muted mb-1 small">Present Today</p>
                                <h3 class="mb-0">{{ $presentToday }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-check-circle text-success fs-4"></i>
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
                                <p class="text-muted mb-1 small">Activities Today</p>
                                <h3 class="mb-0">{{ $activitiesScheduled }}</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-calendar-event text-info fs-4"></i>
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
                                <p class="text-muted mb-1 small">Programs</p>
                                <h3 class="mb-0">{{ $programs->count() }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-building text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Today's Activities -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Today's Schedule</h5>
                    </div>
                    <div class="card-body">
                        @forelse($todaysActivities as $activity)
                            <div class="border-start border-3 border-primary ps-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0">{{ $activity->name }}</h6>
                                    <small class="text-muted">{{ $activity->time }}</small>
                                </div>
                                <p class="text-muted small mb-0">{{ $activity->description }}</p>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x text-muted fs-1 mb-3"></i>
                                <p class="text-muted">No activities scheduled for today.</p>
                                <p class="small text-muted">Activity scheduling functionality coming soon!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Program Participants -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Participants</h5>
                    </div>
                    <div class="card-body">
                        @forelse($participants->take(5) as $participant)
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-person text-primary fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $participant->first_name }} {{ $participant->last_name }}</h6>
                                    <small class="text-muted">Age {{ $participant->date_of_birth ? \Carbon\Carbon::parse($participant->date_of_birth)->age : 'N/A' }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-people text-muted fs-1 mb-3"></i>
                                <p class="text-muted small">No participants enrolled yet.</p>
                            </div>
                        @endforelse

                        @if($participants->count() > 5)
                            <p class="text-center text-muted small mt-3">
                                and {{ $participants->count() - 5 }} more...
                            </p>
                        @endif
                    </div>
                </div>

                @if($programs->count() > 0)
                    <div class="card border-0 shadow-sm mt-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0">My Programs</h5>
                        </div>
                        <div class="card-body">
                            @foreach($programs as $program)
                                <div class="mb-2">
                                    <h6 class="mb-0">{{ $program->name }}</h6>
                                    <small class="text-muted">{{ $program->type }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
