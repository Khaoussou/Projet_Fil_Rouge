<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("username", "password"))) {
            return response([
                "message" => "Invalid credentials"
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        return $user->createToken("token")->plainTextToken;
    }
}
