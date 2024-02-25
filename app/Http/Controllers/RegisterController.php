<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function registerUser(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string|max:30',
                'last_name' => 'required|string|max:30',
                'username' => 'required|string|unique:users|min:3|max:20',
                'age' => 'numeric|required|min:0',
                'birth_date' => 'date|required',
                'telephone' => 'required|numeric',
                'email' => 'required|email|unique:users|max:60',
                'password' => 'required|min:5',
                'password_confirmation' => 'required|same:password'
            ]);

            User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'username' => Str::slug($request->username),
                'age' => $request->age,
                'birth_date' => $request->birth_date,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            return response()->json(['message' => 'Usuario creado con éxito'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
}
