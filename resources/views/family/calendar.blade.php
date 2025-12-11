<x-app-layout>
    <x-slot name="header">
        Calendar
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            {{ $currentMonth->format('F Y') }}
                        </h5>
                        <div>
                            <button class="btn btn-sm btn-outline-primary" disabled>
                                <i class="bi bi-plus"></i> Add Event
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Calendar functionality coming soon! You'll be able to track appointments, activities, and important dates.
                    </div>

                    <!-- Upcoming Events List (Placeholder) -->
                    <h6 class="mt-4">Upcoming Events</h6>
                    <div class="list-group">
                        @forelse($events as $event)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $event->title }}</strong>
                                    <span class="badge bg-primary">{{ $event->start_date->format('M d') }}</span>
                                </div>
                                @if($event->individualProfile)
                                    <small class="text-muted">{{ $event->individualProfile->full_name }}</small>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted">No upcoming events scheduled.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
