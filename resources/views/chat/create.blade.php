<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Start New Conversation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4">
                    @if ($relevantUsers->count() > 0)
                        <form action="{{ route('chat.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="user_id" class="form-label fw-bold">Select Recipient</label>
                                <select name="user_id" id="user_id" class="form-select" required>
                                    <option value="" selected disabled>Choose a person...</option>
                                    @foreach ($relevantUsers as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                            ({{ $user->getRoleNames()->first() }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="subject" class="form-label fw-bold">Subject (Optional)</label>
                                <input type="text" name="subject" id="subject" class="form-control"
                                    placeholder="e.g. Care Coordination, Weekend Schedule">
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label fw-bold">Initial Message</label>
                                <textarea name="message" id="message" class="form-control" rows="4" required
                                    placeholder="Type your first message here..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('chat.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">Create Conversation</button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">No relevant users found to start a chat with.</p>
                            <p class="small text-muted">If you are a family member, ensure your individuals have DSPs
                                assigned.</p>
                            <a href="{{ route('chat.index') }}" class="btn btn-link mt-2">Back to Conversations</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
