<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatedRequest;
use App\Models\User;

class UpdatedController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdatedRequest $request, $userid)
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
}
