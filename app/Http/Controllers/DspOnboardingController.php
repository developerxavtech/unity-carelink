<?php

namespace App\Http\Controllers;

use App\Models\DspProfile;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DspOnboardingController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * Update the profile and send verification code.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'preferred_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'pronouns' => 'nullable|string|max:255',
            'roles' => 'nullable|array',
            'communication_preferences' => 'nullable|array',
            'experience_strengths' => 'nullable|string',
            'boundaries_expectations' => 'nullable|string',
            'final_notes' => 'nullable|string',
        ]);

        if ($request->filled('phone')) {
            $user->update(['phone' => $request->phone]);
        }

        $profile = $user->dspProfile()->updateOrCreate(
            ['user_id' => $user->id],
            collect($validated)->except('phone')->toArray()
        );

        // Check if all data are filled (basic check for required fields)
        $isFilled = $profile->preferred_name &&
            count($profile->roles ?? []) > 0 &&
            count($profile->communication_preferences ?? []) > 0;

        if ($isFilled) {
            // Generate verification code
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $profile->update([
                'verification_code' => $code,
                'verification_code_expires_at' => now()->addMinutes(10),
            ]);

            // Send via Twilio
            if ($user->phone) {
                $message = "Your Unity CareLink verification code is: $code. It expires in 10 minutes.";
                $this->twilio->sendSms($user->phone, $message);

                return redirect()->route('dsp.onboarding.verify')
                    ->with('success', 'Profile updated! A verification code has been sent to your phone.');
            } else {
                return redirect()->back()
                    ->with('error', 'Please add a phone number to your account to receive a verification code.');
            }
        }

        return redirect()->back()->with('success', 'Profile saved! Please complete all sections to verify.');
    }

    /**
     * Show the verification form.
     */
    public function verify()
    {
        return view('dsp.onboarding.verify');
    }

    /**
     * Verify the code entered by the user.
     */
    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = Auth::user();
        $profile = $user->dspProfile;

        if (!$profile || !$profile->verification_code) {
            return redirect()->route('dsp.onboarding.setup')
                ->with('error', 'Please complete your profile first.');
        }

        if (
            $profile->verification_code === $request->code &&
            $profile->verification_code_expires_at->isFuture()
        ) {

            $profile->update([
                'is_verified' => true,
                'verification_code' => null,
                'verification_code_expires_at' => null,
            ]);

            return redirect()->route('dsp.home')
                ->with('success', 'Your DSP profile has been verified successfully!');
        }

        return redirect()->back()->with('error', 'Invalid or expired verification code.');
    }
}
