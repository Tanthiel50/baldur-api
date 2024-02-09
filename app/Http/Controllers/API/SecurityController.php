<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SecurityController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validation = Validator::make(request()->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validation->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => "L'email ou le mot de passe ne sont pas correct"
                ], 401);
            }

            $user = User::where("email", $request->email)->first();

            return response()->json([
                "status" => true,
                "message" => "User connecté",
                "token" => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    public function register()
    {

        try {
            $validation = Validator::make(request()->all(), [
                'pseudo' => 'required|unique:users,pseudo',
                'firstName' => 'required',
                'lastName' => 'required',
                'zip' => 'required|integer',
                'email' => 'required|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'validation error',
                    'errors' => $validation->errors(),
                ], 401);
            }

            $user = User::create([
                'pseudo' => request('pseudo'),
                'firstName' => request('firstName'),
                'lastName' => request('lastName'),
                'zip' => request('zip'),
                'email' => request('email'),
                'role' => 'user',
                'password' => Hash::make(request('password')),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Merci pour votre inscription',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Deconnexion']);
    }
}