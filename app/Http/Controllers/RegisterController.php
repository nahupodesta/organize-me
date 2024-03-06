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
            $validatedData = $this->validate($request, [
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

            $user = User::create([
                'name' => $validatedData['name'],
                'last_name' => $validatedData['last_name'],
                'username' => Str::slug($validatedData['username']),
                'age' => $validatedData['age'],
                'birth_date' => $validatedData['birth_date'],
                'telephone' => $validatedData['telephone'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password'])
            ]);
            return response()->json(['message' => 'Usuario creado con éxito'], 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
}
