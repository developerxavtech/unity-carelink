<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-1">Peer Support</h2>
                <p class="text-muted">Connect with fellow DSPs and share resources</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-4 mb-3">
                    <i class="bi bi-people-fill text-primary" style="font-size: 3rem;"></i>
                </div>
                <h5 class="mb-2">Peer Support Community Coming Soon!</h5>
                <p class="text-muted mb-4">{{ $message }}</p>

                <div class="row g-3 mt-4 text-start">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-chat-square-text text-primary fs-4 mb-2"></i>
                            <h6>Discussion Forums</h6>
                            <p class="small text-muted mb-0">Share experiences and ask questions in a supportive community.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-lightbulb text-warning fs-4 mb-2"></i>
                            <h6>Best Practices</h6>
                            <p class="small text-muted mb-0">Access tips, strategies, and resources from experienced DSPs.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <i class="bi bi-calendar-event text-success fs-4 mb-2"></i>
                            <h6>Training & Events</h6>
                            <p class="small text-muted mb-0">Join workshops, webinars, and community events.</p>
                        </div>
                    </div>
                </div>

                <div class="alert alert-light mt-4 d-inline-block text-start">
                    <h6 class="mb-2">
                        <i class="bi bi-stars me-2"></i>What You'll Be Able to Do:
                    </h6>
                    <ul class="mb-0 ps-3 small">
                        <li>Connect with other DSPs in your organization and region</li>
                        <li>Share success stories and learn from challenges</li>
                        <li>Access a library of best practices and training materials</li>
                        <li>Request and offer peer mentorship</li>
                        <li>Participate in recognition programs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
