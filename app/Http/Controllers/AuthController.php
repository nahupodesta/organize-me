<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only('username', 'password');
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $token = $user->createToken('token-name');
            return response()->json([
                'name' => $user->name,
                'last_name' => $user->last_name,
                'username' => $user->username,
                'token' => $token->plainTextToken
            ]);
        }
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }


    public function logout(Request $request){
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Cerró la sesión exitosamente'], 200);
    }


}
