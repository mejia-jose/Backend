<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    //Función que permite registrar usuarios
    public function registerUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuario registrado exitosamente.', 'user' => $user], 201);
    }

    public function loginUsers(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Las credenciales ingresadas son incorrectas.'], 401);
        }
    
        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json(['message' => 'Usuario logueado', 'access_token' => $token, 'token_type' => 'Bearer']);
    }
    
}
