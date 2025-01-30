<?php

namespace App\Http\Controllers;

use App\Models\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserPostResource;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserPostController extends Controller
{
    public function register(Request $request){
        $validated = $request->validate([
            'username' => 'required|max:10|string',
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        $hashPw = Hash::make($validated['password']);

        $user = UserPost::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $hashPw
        ]);

        $token = JWTAuth::fromUser($user);

        return new UserPostResource(true, 'User created successfully', $token);
    }

    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        
        if(!$token = JWTAuth::attempt($validated)){
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => Auth::user()
        ], 200);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        
        return new UserPostResource(true, 'logout berhasil', null);
    }
}
