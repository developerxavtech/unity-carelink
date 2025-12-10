<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Unity CareLink') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="min-vh-100 d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <h2 class="fw-bold" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            Unity CareLink
                        </h2>
                        <p class="text-muted">A Network Designed for Care, Not Chaos</p>
                    </div>

                    <!-- Card -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            {{ $slot }}
                        </div>
                    </div>

                    <!-- Footer Links -->
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            &copy; {{ date('Y') }} Unity CareLink. All rights reserved.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
