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
        return view('admin.login');
    }

    public function register(RegisterRequest $request)
    {
        return view('admin.register');
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

        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }
}
