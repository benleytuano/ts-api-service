<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{

    public function register(RegisterRequest $request, AuthService $service)
    {
        $payload = $request->validated();

        $result = $service->register($payload);
        
        return response()->json($result, 201);

    }


    public function login(LoginRequest $request, AuthService $service)
    {
        $payload = $request->validated();

        $data =  $service->login($payload);

        if(!$data){
            return response()->json([
                "message" => "Invalid Password"
            ]);
        }

        return response()->json($data);

    }

    public function logout(Request $request, AuthService $service)
    {
        $result = $service->logout($request->user()); 

        return response()->json([
            'success' => $result,
            'message' => 'Logged out successfully',
        ]);

    }

    public function me(AuthService $service)
    {
        $result = $service->getCurrentUser();
        
        return response()->json($result);
    }

}
