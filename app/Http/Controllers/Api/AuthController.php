<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\LoginResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $token = $user->createToken('AuthToken')->plainTextToken;

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
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed'],
            'role' => ['required', 'string', 'in:family_admin,family_member,dsp,agency_admin,program_staff,super_admin'],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => '',
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);
        $user->assignRole($request->role);
        Auth::login($user);
        $token = $user->createToken('AuthToken')->plainTextToken;

        return $this->sendResponse(
            new LoginResource($user, $token),
            'User registered successfully.'
        );
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
