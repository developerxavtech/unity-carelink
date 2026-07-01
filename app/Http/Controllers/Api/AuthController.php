<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LoginResource;
use App\Mail\DspVerifiedMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Mail;

class AuthController extends BaseController
{
    /**
     * Login API
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
            }
            $user = User::where('email', $request->email)->first();
            if ($user && $user->hasRole('dsp')) {
                if (! $user->dspProfile || ! $user->dspProfile->is_verified) {
                    return $this->sendError('Your profile is not verified.', ['error' => 'Your profile is not verified.'], 401);
                }
            }

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $token = $user->createToken('AuthToken')->accessToken;

                return $this->sendResponse(new LoginResource($user, $token), 'User login successfully.');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 401);
            }
        } catch (Exception $e) {
            return $this->sendError('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Register API
     */
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed'],
                'role' => ['required', 'string', 'in:family_admin,family_member,dsp,agency_admin,program_staff,super_admin'],
            ]);

            $user = User::create([
                'first_name' => $request->name,
                'last_name' => '',
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password,
            ]);
            $user->assignRole($request->role);
            if ($user->hasRole('dsp')) {
                if ($request->has('communication_preferences')) {
                    $request->validate([
                        'communication_preferences' => 'array|in:In-app messaging,Phone,Email',
                    ]);
                    $user->dspProfile()->updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'communication_preferences' => $request->communication_preferences,
                        ]
                    );
                }

                try {
                    $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                    $user->dspProfile()->update([
                        'verification_code' => $code,
                        'verification_code_expires_at' => now()->addMinutes(10),
                    ]);

                    Mail::to($user->email)->send(new DspVerifiedMail($request->name, $code));
                    DB::commit();

                    return $this->sendResponse(
                        [],
                        'DSP verification code sent successfully. Please verify your email!'
                    );
                } catch (\Throwable $e) {
                    DB::rollBack();

                    return $this->sendError('Something went wrong.', ['error' => $e->getMessage()], 500);
                }

            }
            DB::commit();
            Auth::login($user);
            $token = $user->createToken('AuthToken')->accessToken;

            return $this->sendResponse(
                new LoginResource($user, $token),
                'User registered successfully.'
            );
        } catch (Exception $e) {
            DB::rollBack();

            return $this->sendError('Something went wrong.', ['error' => $e->getMessage()], 500);
        }
    }

    public function verifyCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'code' => 'required|string|size:6',
            ]);

            $user = User::where('email', $request->email)->first();
            if (! $user) {
                return $this->sendError('User not found.', [], 404);
            }
            $profile = $user->dspProfile;

            if (! $profile || ! $profile->verification_code) {
                return $this->sendError('Please complete your profile first.', [], 422);
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

                return $this->sendResponse(
                    [],
                    'Your DSP profile has been verified successfully!'
                );
            } else {
                return $this->sendError('Invalid or expired verification code.', [], 422);
            }

        } catch (Exception $e) {
            return $this->sendError('Invalid or expired verification code.', [], 422);
        }
    }

    public function resendVerificationCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();
            if (! $user) {
                return $this->sendError('User not found.', [], 404);
            }
            $profile = $user->dspProfile;

            if (! $profile) {
                return $this->sendError('Please complete your profile first.', [], 422);
            }
            if ($profile->is_verified) {
                return $this->sendError('Your DSP profile is already verified.', [], 422);
            }
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            Mail::to($user->email)->send(new DspVerifiedMail($user->name, $code));

            $profile->update([
                'is_verified' => false,
                'verification_code' => $code,
                'verification_code_expires_at' => now()->addMinutes(10),
            ]);

            return $this->sendResponse(
                [],
                'Your DSP profile verification code has been sent successfully!'
            );
        } catch (Exception $e) {

            return $this->sendError('Can\'t send verification code, please try again later.\n Reason: '.$e->getMessage(), [], 500);
        }
    }

    /**
     * Forgot Password API
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return $this->sendResponse([], __($status));
        }

        return $this->sendError(__($status), [], 400);
    }

    /**
     * Reset Password API
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray(), 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->sendResponse([], __($status));
        }

        return $this->sendError(__($status), [], 400);
    }

    /**
     * Get Profile API
     */
    public function getProfile(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            return $this->sendResponse(
                new LoginResource($user),
                'Profile retrieved successfully.'
            );
        }

        return $this->sendError('User not found.', [], 404);

    }
}
