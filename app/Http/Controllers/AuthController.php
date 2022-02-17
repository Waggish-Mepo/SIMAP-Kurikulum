<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'string|required',
            'password' => 'required'
        ]);

        $credentials = request(['username', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => [
                        'Invalid credentials'
                    ],
                ]
            ], 422);
        }

        $user = User::where('username', $request->username)->first();
        $authToken = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'access_token' => $authToken,
            'user_data' => $user,
        ], 200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response(['message' => 'destroyed']);
    }

    public function me()
    {
        $user = auth()->user();
        return response()->json([
            'data' => $user,
        ], 200);
    }
}
