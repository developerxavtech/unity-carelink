<x-app-layout>
    <x-slot name="header">
        UCL Rides
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-car-front fs-1 text-muted mb-3"></i>
                    <h4>Transportation Coordination</h4>
                    <p class="text-muted mb-4">{{ $message }}</p>
                    <p class="small text-muted">
                        This feature will allow you to coordinate transportation for your loved ones,<br>
                        schedule rides, and communicate with drivers.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
