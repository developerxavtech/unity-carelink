<?php

namespace App\Http\Controllers;

use App\Models\IndividualProfile;
use App\Models\MoodCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoodCheckController extends Controller
{
    /**
     * Store a new mood check (CarePulse).
     */
    public function store(Request $request, IndividualProfile $individual)
    {
        $validated = $request->validate([
            'mood' => 'required|string|in:great,good,okay,concern',
            'notes' => 'nullable|string',
        ]);

        $validated['individual_profile_id'] = $individual->id;
        $validated['submitted_by_user_id'] = Auth::id();

        MoodCheck::create($validated);

        return redirect()->route('individuals.show', $individual)
            ->with('success', 'CarePulse check recorded!');
    }
}
