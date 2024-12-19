<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }

    public function show()
    {
        return response()->json(auth()->user());
    }
}
