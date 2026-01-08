<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserStatusController extends Controller
{
    /**
     * Show the status update form.
     */
    public function edit(Request $request)
    {
        $view = $request->user()->hasAnyRole(['dsp', 'program_staff'])
            ? 'dsp.status.edit'
            : 'family.status.edit';

        return view($view, [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the authenticated user's status.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'activity_status' => 'nullable|string|max:255',
            'status_emoji' => 'nullable|string|max:255',
            'status_message' => 'nullable|string|max:1000',
            'status_busy_until' => 'nullable|date|after:now',
        ]);

        $user = $request->user();
        $user->update($validated);

        return back()->with('success', 'Status updated successfully!');
    }

    /**
     * Clear the authenticated user's status.
     */
    public function clear(Request $request)
    {
        $user = $request->user();
        $user->update([
            'activity_status' => null,
            'status_emoji' => null,
            'status_message' => null,
            'status_busy_until' => null,
        ]);

        return back()->with('success', 'Status cleared!');
    }
}
