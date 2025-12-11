<x-app-layout>
    <x-slot name="header">
        Program Updates
    </x-slot>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="bi bi-megaphone text-primary"></i>
                        Announcements & Updates
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($updates as $update)
                        <div class="mb-4 pb-4 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1">
                                        {{ $update->title }}
                                        @if($update->pinned)
                                            <i class="bi bi-pin-angle-fill text-danger"></i>
                                        @endif
                                    </h5>
                                    <small class="text-muted">
                                        {{ $update->author->first_name }} {{ $update->author->last_name }} •
                                        {{ $update->published_at->format('M d, Y') }}
                                        @if($update->organization)
                                            • {{ $update->organization->name }}
                                        @endif
                                    </small>
                                </div>
                                <span class="badge bg-{{ $update->priority == 'urgent' ? 'danger' : 'secondary' }}">
                                    {{ ucfirst($update->priority) }}
                                </span>
                            </div>
                            <p class="mt-3 mb-0">{{ $update->content }}</p>

                            @if($update->tags)
                                <div class="mt-2">
                                    @foreach($update->tags as $tag)
                                        <span class="badge bg-light text-dark">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">No program updates available</p>
                            <small class="text-muted">Check back later for announcements from your care providers</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
