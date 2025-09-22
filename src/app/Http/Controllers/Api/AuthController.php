<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $cred = $request->validate([
            'email'       => ['required','email'],
            'password'    => ['required','string'],
            'device_name' => ['sometimes','string'], // für Token-Name
        ]);

        $user = User::where('email', $cred['email'])->first();

        if (! $user || ! Hash::check($cred['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Ungültige Anmeldedaten.',
            ]);
        }

        // ✅ Nur Nutzer mit Rolle != null dürfen sich einloggen
        if (is_null($user->role)) {
            throw ValidationException::withMessages([
                'email' => 'Dein Account ist noch nicht freigeschaltet.',
            ]);
        }

        // ✅ Abilities aus der Rolle ableiten
        $abilities = ['user']; // Basis-Rechte für alle aktiven
        if ($user->role === 'haendler') {
            $abilities[] = 'haendler';
        }
        if ($user->role === 'admin') {
            $abilities[] = 'admin';
        }

        $token = $user->createToken(
            $cred['device_name'] ?? 'api',
            $abilities
        )->plainTextToken;

        return response()->json([
            'token_type' => 'Bearer',
            'token'      => $token,
            'abilities'  => $abilities,
            'user'       => $user->only(['id','name','email','role']),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Abgemeldet']);
    }

    public function listUsers()
    {
        // Wenn du Middleware abilities:admin nutzt, ist dieser Check nicht nötig.
        abort_unless(auth()->user()?->role === 'admin', 403);

        return User::orderBy('created_at','desc')
            ->get(['id','name','email','role','created_at']);
    }
}
