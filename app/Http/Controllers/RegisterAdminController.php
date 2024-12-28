<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAdminRequest;
use App\Models\User;

class RegisterAdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterAdminRequest $request)
    {
        User::create($request->getData());
    }
}