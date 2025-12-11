<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Daily Logs</h2>
                    <p class="text-muted">View and manage care notes</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('dsp.daily-logs') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="individual_id" class="form-label small">Participant</label>
                        <select name="individual_id" id="individual_id" class="form-select">
                            <option value="">All Participants</option>
                            @foreach($individuals as $individual)
                                <option value="{{ $individual->id }}" {{ request('individual_id') == $individual->id ? 'selected' : '' }}>
                                    {{ $individual->first_name }} {{ $individual->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="start_date" class="form-label small">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $filters['start_date'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label small">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $filters['end_date'] ?? '' }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Care Notes List -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @forelse($logs as $log)
                    <div class="border-start border-3 border-primary ps-3 mb-4 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="mb-1">{{ $log->individualProfile->first_name }} {{ $log->individualProfile->last_name }}</h5>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($log->shift_date)->format('M j, Y') }}
                                    <span class="mx-2">|</span>
                                    <i class="bi bi-clock me-1"></i> {{ $log->created_at->format('g:i A') }}
                                </small>
                            </div>
                            @if($log->mood)
                                <span class="badge bg-{{ $log->mood === 'great' ? 'success' : ($log->mood === 'good' ? 'info' : ($log->mood === 'okay' ? 'warning' : 'danger')) }}">
                                    {{ ucfirst($log->mood) }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-2">
                            <h6 class="small text-muted mb-1">Notes</h6>
                            <p class="mb-0">{{ $log->notes }}</p>
                        </div>

                        @if($log->activities)
                            <div class="mb-2">
                                <h6 class="small text-muted mb-1">Activities</h6>
                                <p class="mb-0">{{ $log->activities }}</p>
                            </div>
                        @endif

                        @if($log->meals)
                            <div class="mb-2">
                                <h6 class="small text-muted mb-1">Meals</h6>
                                <p class="mb-0">{{ $log->meals }}</p>
                            </div>
                        @endif

                        @if($log->medications_administered)
                            <div class="mb-2">
                                <span class="badge bg-info">
                                    <i class="bi bi-capsule me-1"></i> Medications Administered
                                </span>
                            </div>
                        @endif

                        @if($log->incidents_noted)
                            <div class="mb-2">
                                <span class="badge bg-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Incident Noted
                                </span>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 mb-2">No Care Notes Found</h5>
                        <p class="text-muted mb-3">Care notes are created from participant profile pages.</p>
                        <a href="{{ route('dsp.participants') }}" class="btn btn-primary">
                            <i class="bi bi-people me-1"></i> View Participants
                        </a>
                    </div>
                @endforelse

                @if($logs->hasPages())
                    <div class="mt-4">
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
