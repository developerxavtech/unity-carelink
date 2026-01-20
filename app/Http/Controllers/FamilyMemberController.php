<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class FamilyMemberController extends Controller
{
    /**
     * Display a listing of family members.
     */
    public function index()
    {
        return redirect()->route('individuals.index');
    }

    /**
     * Show the form for creating a new family member.
     */
    public function create()
    {
        return view('family.members.create');
    }

    /**
     * Store a newly created family member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'family_admin_id' => Auth::id(),
            'status' => 'active',
        ]);

        $user->assignRole('family_member');

        return redirect()->route('individuals.index')
            ->with('success', 'Family member added & invited successfully!');
    }

    /**
     * Show the form for editing the specified family member.
     */
    public function edit(User $member)
    {
        // Check if member belongs to this admin
        if ($member->family_admin_id !== Auth::id()) {
            abort(403);
        }

        return view('family.members.edit', compact('member'));
    }

    /**
     * Update the specified family member.
     */
    public function update(Request $request, User $member)
    {
        // Check if member belongs to this admin
        if ($member->family_admin_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $member->update($validated);

        return redirect()->route('individuals.index')
            ->with('success', 'Family member updated successfully!');
    }

    /**
     * Remove the specified family member.
     */
    public function destroy(User $member)
    {
        // Check if member belongs to this admin
        if ($member->family_admin_id !== Auth::id()) {
            abort(403);
        }

        $member->delete();

        return redirect()->route('individuals.index')
            ->with('success', 'Family member removed successfully.');
    }
}
