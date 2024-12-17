<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdatedRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class Authentication extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!auth()->attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $user->createToken('ecommerce')->plainTextToken
        ]);
    }

    public function register(RegisterRequest $request)
    {
        User::create($request->getData());
    }

    public function update(UpdatedRequest $request, $userid)
    {
        $user = User::find($userid);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully'
        ], 200);
    }

    public function logout(Request $request)
    {
        auth()->guard('web')->logout();

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }
}
