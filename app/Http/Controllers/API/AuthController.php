<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\TemplateResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:55'],
            'email' => ['email', 'required', 'unique:users'],
            'password' => ['confirmed', Rules\Password::defaults()],
        ]);

        $validatedData['password'] = bcrypt($request->password);


        $user = User::create($validatedData);

        $token = $user->createToken('authToken');

        return response(
            [
                'user' => $user,
                'access_token' => $token->plainTextToken,
                'message' => 'Account created successfully',
            ],
            200
        );
    }

    // Login
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['email', 'required'],
            'password' => ['required', 'min:8'],
        ]);

        if (!Auth::attempt($validatedData)) {
            return response(['message' => 'Invalid credentials'], 401);
        }
        $user = User::find(Auth::id());
        return response([
            'user' => $user,
            'access_token' => $user->createToken('authToken')->plainTextToken,
            'message' => 'Logged in successfully!',
        ], 200);
    }

    // User
    public function user(Request $request)
    {
        return response(['user' => $request->user()], 200);
    }
    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'Logged out successfully!'], 200);
    }

    //change password
    public function changePassword(ChangePasswordRequest $request)
    {
        $attributes = $request->validated();

        $user = $request->user();
        $user->password = Hash::make($attributes['new_password']);
        $user->save();

        return new TemplateResource([
            'message' => 'Password changed',
            'code' => 200
        ]);
    }
}
