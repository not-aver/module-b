<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request){
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => ['required', 'string', 'min:8', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        User::create([
            'name' => explode('@', $validated['email'])[0],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
        ]);

        return response()->json([
            'success' => true,
        ], 201);
    }

    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email',  $validated['email'])->first(); //находим запись с email пользователя в БД

        if(!$user || !Hash::check($validated['password'], $user->password)){
            return response()->json([[
                'message' => 'Invalid data',
                'errors' => [
                    'email' => 'Invalid data'
                ]
            ]], 422);
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'token' => $token
        ], 200);
    }
}
