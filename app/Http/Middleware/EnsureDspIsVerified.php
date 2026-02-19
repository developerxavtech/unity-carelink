<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDspIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasRole('dsp') && !$user->isDspVerified()) {
            return redirect()->route('profile.edit')->with([
                'error' => 'Please complete your profile and verify your phone number to access all features.',
                'verification_required' => true
            ]);
        }

        return $next($request);
    }
}
