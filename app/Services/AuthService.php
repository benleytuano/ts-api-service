<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService{

    public function register(array $payload)
    {
        // Use UserService for user creation (following your existing pattern)
        $userService = app()->make(UserService::class);
        $user = $userService->create($payload);
        
        return [
            'user' => $user,
        ];
    }

    public function login(array $credentials)
    {
        // eager-load related role and department
        $user = User::with(['role', 'department'])
            ->where('email', $credentials['email'])
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,           // now includes: role {id,name}, department {id,name}
            'token' => $token,
            'message' => 'Login successful',
        ];
    }

    public function logout(User $user)
    {
        // Revoke all tokens (if using Sanctum)
        $user->tokens()->delete();

        return true;
    }

    public function getCurrentUser(): array
    {
        $user = auth()->user(); // or just auth()->user()

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // Pull in related role, department & locations (minimal columns)
        $user->loadMissing([
            'role:id,name',
            'department:id,name',
            'department.locations:id,department_id,name',
        ]);

        // (Optional) include current token abilities for frontend gating
        $abilities = $user->currentAccessToken()?->abilities ?? [];

        return [
            'user' => $user,
            'abilities' => $abilities, // remove if you don't need it
        ];
    }

    /**
     * Update user profile (email and/or password)
     * At least one field must be provided
     */
    public function updateProfile(array $payload): array
    {
        $user = auth()->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        // Update email if provided
        if (isset($payload['email']) && !empty($payload['email'])) {
            $user->email = $payload['email'];
        }

        // Update password if provided
        if (isset($payload['password']) && !empty($payload['password'])) {
            $user->password = Hash::make($payload['password']);
        }

        $user->save();

        // Reload with relationships
        $user->loadMissing([
            'role:id,name',
            'department:id,name',
        ]);

        return [
            'success' => true,
            'user' => $user,
            'message' => 'Profile updated successfully',
        ];
    }
    // public function sendPasswordResetLink(array $data)
    // {
    //     $status = Password::sendResetLink(['email' => $data['email']]);

    //     if ($status === Password::RESET_LINK_SENT) {
    //         return [
    //             'message' => 'Password reset link sent successfully'
    //         ];
    //     }

    //     return [
    //         'message' => 'Unable to send password reset link'
    //     ];
    // }

    // public function resetPassword(array $data)
    // {
    //     $status = Password::reset(
    //         [
    //             'email' => $data['email'],
    //             'password' => $data['password'],
    //             'password_confirmation' => $data['password_confirmation'],
    //             'token' => $data['token']
    //         ],
    //         function (User $user, string $password) {
    //             $user->forceFill([
    //                 'password' => Hash::make($password)
    //             ])->setRememberToken(Str::random(60));

    //             $user->save();

    //             event(new PasswordReset($user));
    //         }
    //     );

    //     return $status === Password::PASSWORD_RESET;
    // }

    // public function refreshToken(User $user)
    // {
    //     // Revoke current token
    //     $user->currentAccessToken()->delete();
        
    //     // Create new token
    //     $token = $user->createToken('auth-token')->plainTextToken;

    //     return [
    //         'user' => $user,
    //         'token' => $token,
    //         'message' => 'Token refreshed successfully'
    //     ];
    // }

}