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
        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        // Generate token (using Sanctum)
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'message' => 'Login successful'
        ];
    }

    public function logout(User $user)
    {
        // Revoke all tokens (if using Sanctum)
        $user->tokens()->delete();

        return true;
    }

    public function getCurrentUser()
    {
        return [
            'user' => auth()->user() // Already authenticated by middleware
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