<?php

namespace App\Http\Controllers;

use App\Traits\Format;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use Format;
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("username", "password"))) {
            return $this->response(Response::HTTP_UNAUTHORIZED, "Invalid credentials", []);
        }
        $user = Auth::user();
        $token = $user->createToken("token")->plainTextToken;
        $cookie = cookie("token", $token, 24 * 60);
        
        return response([
            "nom" => $user->name,
            "role" => $user->role,
            "token" => $token
        ])->withCookie($cookie);
    }
    public function user(Request $request)
    {
        return $request->user();
    }
    public function logout()
    {
        Auth::guard('sanctum')->user()->tokens()->delete();
    }
}
