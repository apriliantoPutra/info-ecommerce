<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Register successful',
            'token'   => $token,
            'data'    => $user
        ], 201);
    }
    public function login(Request $request)
    {
        try {

            $validated = $request->validate([
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah.'
                ], 422);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token'   => $token,
                'data'    => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'SERVER ERROR',
                'error'   => $e->getMessage(),

            ], 500);
        }
    }


    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            $user = $request->user();
            $personalToken = $user->tokens()->where('token', hash('sha256', explode('|', $token, 2)[1]))->first();
            if ($personalToken) {
                $personalToken->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Log out successful'
        ]);
    }
}
