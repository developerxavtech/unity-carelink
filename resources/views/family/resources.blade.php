<x-app-layout>
    <x-slot name="header">
        Resources
    </x-slot>

    <div class="row">
        @foreach($categories as $categoryName => $resources)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">{{ $categoryName }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($resources as $resource)
                                <a href="{{ $resource['url'] }}" class="list-group-item list-group-item-action">
                                    <i class="bi bi-{{ $resource['type'] == 'pdf' ? 'file-pdf' : ($resource['type'] == 'video' ? 'play-circle' : 'link-45deg') }} text-primary"></i>
                                    {{ $resource['title'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-4">
                    <h6 class="text-muted">Need Additional Support?</h6>
                    <p class="text-muted mb-3">Our team is here to help you navigate your care journey</p>
                    <button class="btn btn-primary" disabled>
                        <i class="bi bi-headset"></i> Contact Support
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
