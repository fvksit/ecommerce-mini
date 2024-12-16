<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function index()
    {
        $user = User::all();
        return response()->json($user);
    }
}
