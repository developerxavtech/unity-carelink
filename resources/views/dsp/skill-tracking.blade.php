<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Skill Tracking</h2>
                <p class="text-muted">Track participant progress and goals</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                    <i class="bi bi-graph-up text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-2">Skill Tracking Coming Soon!</h5>
                <p class="text-muted mb-4">{{ $message }}</p>

                <div class="row g-3 mt-4 text-start">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-check-circle text-success fs-4 mb-2"></i>
                            <h6>Track Skills</h6>
                            <p class="small text-muted mb-0">Monitor and document skill development for each participant.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-trophy text-warning fs-4 mb-2"></i>
                            <h6>Set Goals</h6>
                            <p class="small text-muted mb-0">Create and track progress towards individual goals.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-bar-chart-line text-info fs-4 mb-2"></i>
                            <h6>View Progress</h6>
                            <p class="small text-muted mb-0">Visual charts showing skill development over time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($individuals->count() > 0)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">My Participants</h5>
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
                                        <small class="text-muted">Skills and goals will be tracked here</small>
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
