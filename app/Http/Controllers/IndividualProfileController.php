<?php

namespace App\Http\Controllers;

use App\Models\IndividualProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndividualProfileController extends Controller
{
    /**
     * Display a listing of the individual profiles.
     */
    public function index()
    {
        $user = Auth::user();
        $familyUsers = collect();

        // Family admins and members see their family's individuals
        if ($user->hasRole('family_admin|family_member|dsp|agency_admin|program_staff')) {
            $individuals = IndividualProfile::accessibleBy($user->id)
                ->with('careNotes', 'moodChecks')
                ->get();

            // If family admin, also get their linked family members (users)
            if ($user->hasRole('family_admin')) {
                $familyUsers = $user->familyMembers;
            } elseif ($user->hasRole('family_member')) {
                // If family member, get the admin and other members
                $familyUsers = User::where('id', $user->family_admin_id)
                    ->orWhere('family_admin_id', $user->family_admin_id)
                    ->get()
                    ->reject(fn($u) => $u->id === $user->id);
            }
        } else {
            $individuals = collect();
        }

        return view('individuals.index', compact('individuals', 'familyUsers'));
    }

    /**
     * Show the form for creating a new individual profile.
     */
    public function create()
    {
        return view('individuals.create');
    }

    /**
     * Store a newly created individual profile.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'pronouns' => 'nullable|string|max:50',
            'strengths_abilities' => 'nullable|string',
            'preferences_interests' => 'nullable|string',
            'communication_style' => 'nullable|string',
            'sensory_profile' => 'nullable|string',
            'triggers' => 'nullable|string',
            'calming_strategies' => 'nullable|string',
            'safety_notes' => 'nullable|string',
        ]);

        $validated['family_user_id'] = Auth::id();
        $validated['status'] = 'active';

        $individual = IndividualProfile::create($validated);

        return redirect()->route('individuals.show', $individual)
            ->with('success', 'Individual profile created successfully!');
    }

    /**
     * Display the specified individual profile.
     */
    public function show(IndividualProfile $individual)
    {
        // Check authorization
        $this->authorize('view', $individual);

        $individual->load(['careNotes', 'moodChecks', 'team.users']);

        return view('individuals.show', compact('individual'));
    }

    /**
     * Show the form for editing the specified individual profile.
     */
    public function edit(IndividualProfile $individual)
    {
        // Check authorization
        $this->authorize('update', $individual);

        return view('individuals.edit', compact('individual'));
    }

    /**
     * Update the specified individual profile.
     */
    public function update(Request $request, IndividualProfile $individual)
    {
        // Check authorization
        $this->authorize('update', $individual);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'pronouns' => 'nullable|string|max:50',
            'strengths_abilities' => 'nullable|string',
            'preferences_interests' => 'nullable|string',
            'communication_style' => 'nullable|string',
            'sensory_profile' => 'nullable|string',
            'triggers' => 'nullable|string',
            'calming_strategies' => 'nullable|string',
            'safety_notes' => 'nullable|string',
        ]);

        $individual->update($validated);

        return redirect()->route('individuals.show', $individual)
            ->with('success', 'Individual profile updated successfully!');
    }

    /**
     * Remove the specified individual profile.
     */
    public function destroy(IndividualProfile $individual)
    {
        // Check authorization
        $this->authorize('delete', $individual);

        $individual->delete();

        return redirect()->route('individuals.index')
            ->with('success', 'Individual profile deleted successfully.');
    }
}
