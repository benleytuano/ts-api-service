<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    //

    public function register(RegisterRequest $request, UserService $service)
    {
        
        $validated =  $request->validated();

        $user = $service->create($validated);

        return response()->json($user);

    }

}
