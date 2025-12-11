<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Attendance</h2>
                    <p class="text-muted">Track participant attendance</p>
                </div>
                <form method="GET" class="d-flex gap-2">
                    <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
                </form>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total Enrolled</p>
                                <h3 class="mb-0">{{ $participants->count() }}</h3>
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
                                <p class="text-muted mb-1 small">Present</p>
                                <h3 class="mb-0 text-success">{{ $presentCount }}</h3>
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
                                <p class="text-muted mb-1 small">Absent</p>
                                <h3 class="mb-0 text-danger">{{ $absentCount }}</h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-x-circle text-danger fs-4"></i>
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
                                <p class="text-muted mb-1 small">Attendance Rate</p>
                                <h3 class="mb-0">{{ $participants->count() > 0 ? number_format(($presentCount / $participants->count()) * 100, 0) : 0 }}%</h3>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-bar-chart text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance List -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @forelse($participants as $participant)
                    <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-person text-primary fs-5"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $participant->first_name }} {{ $participant->last_name }}</h6>
                                <small class="text-muted">Age {{ $participant->date_of_birth ? \Carbon\Carbon::parse($participant->date_of_birth)->age : 'N/A' }}</small>
                            </div>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-success" disabled>
                                <i class="bi bi-check-circle me-1"></i> Present
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" disabled>
                                <i class="bi bi-x-circle me-1"></i> Absent
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-people text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 mb-2">No Participants</h5>
                        <p class="text-muted mb-0">No participants enrolled in your program yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Coming Soon:</strong> Interactive attendance tracking with daily reports, absence notifications, and attendance history.
        </div>
    </div>
</x-app-layout>
