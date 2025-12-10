<?php

namespace App\Http\Controllers;

use App\Models\IndividualProfile;
use App\Models\CareNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CareNoteController extends Controller
{
    /**
     * Store a new care note.
     */
    public function store(Request $request, IndividualProfile $individual)
    {
        $validated = $request->validate([
            'notes' => 'required|string',
            'mood' => 'nullable|string|in:great,good,okay,concern',
            'activities' => 'nullable|string',
            'meals' => 'nullable|string',
            'medications' => 'nullable|string',
        ]);

        $validated['individual_profile_id'] = $individual->id;
        $validated['dsp_user_id'] = Auth::id();
        $validated['shift_date'] = now()->toDateString();

        CareNote::create($validated);

        return redirect()->route('individuals.show', $individual)
            ->with('success', 'Care note added successfully!');
    }
}
