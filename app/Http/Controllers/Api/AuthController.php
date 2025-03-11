<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register a new user and issue a token.
    public function register(Request $request)
    {
        // dd($request);
        // $data = $request->validate([
        //     'name'                  => 'required|string|max:255',
        //     'email'                 => 'required|string|email|max:255|unique:users',
        //     'password'              => 'required|string|min:6|confirmed',
        // ]);
        $data = $request->all();
        // dd($data);
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Issue an access token using Passport
        $token = $user->createToken('API Token')->accessToken;

        return response()->json([
            'user'         => $user,
            'access_token' => $token,
        ], 201);
    }

    // Log in a user and issue a token.
    public function login(Request $request)
    {
        // $data = $request->validate([
        //     'email'    => 'required|string|email',
        //     'password' => 'required|string',
        // ]);
        $data = $request->all();
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('API Token')->accessToken;

        return response()->json([
            'user'         => $user,
            'access_token' => $token,
        ]);
    }

    // Return the authenticated user.
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // Log out by revoking the token.
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
