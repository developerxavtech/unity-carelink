<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Ride Assigned</h2>
                <p class="text-muted">Transportation assignments and schedules</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                    <i class="bi bi-car-front text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-2">Ride Coordination Coming Soon!</h5>
                <p class="text-muted mb-4">{{ $message }}</p>

                <div class="alert alert-info d-inline-block text-start">
                    <h6 class="alert-heading">
                        <i class="bi bi-info-circle me-2"></i>Upcoming Features
                    </h6>
                    <ul class="mb-0 ps-3">
                        <li>View assigned transportation routes</li>
                        <li>Track pickup and drop-off times</li>
                        <li>Log mileage and trip details</li>
                        <li>Communicate with families about schedules</li>
                        <li>Report transportation incidents</li>
                    </ul>
                </div>
            </div>
        </div>

        @if($individuals->count() > 0)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Participants I Transport</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($individuals as $individual)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center border rounded p-3">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-person text-primary fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $individual->first_name }} {{ $individual->last_name }}</h6>
                                        <small class="text-muted">Transportation schedules will appear here</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
