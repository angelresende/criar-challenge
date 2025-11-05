<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{

    /**
     * Authenticate user and return token data.
     */
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Return the authenticated user.
     */
    public function me(): ?User
    {
        return Auth::user();
    }

    /**
     * Revoke current token.
     */
    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            $user->currentAccessToken()->delete();
        }
    }
}
