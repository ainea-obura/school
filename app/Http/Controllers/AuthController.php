<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Utilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use Utilities;

    public function register(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create a new user
        $user = User::create([
            'uuid' => $this->generateUuid(),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $token = $user->createToken('appToken')->accessToken;

        // Return a success response with the newly created user data
        // return response()->json(['user' => $user], 201);
        return response()->json(['user' => $user, 'access_token' => $token], 200);
    }

    public function login(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Attempt to log in the user
        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            $user = Auth::user();
            $token = $user->createToken('AppName')->accessToken;

            // Return a success response with the user data and access token
            return response()->json(['user' => $user, 'access_token' => $token], 200);
        }

        // If login fails, return an error response
        return response()->json(['error' => 'Invalid Email or Password'], 400);
    }

    public function logout(Request $request)
    {
        // Get the authenticated user
        $user = $request->user();

        // Revoke the user's access token
        $user->token()->revoke();

        // Return a success response upon logout
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    public function changePassword(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Check if the current password matches the user's password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }

        // Update the user's password with the new password
        $user->password = bcrypt($request->input('new_password'));
        $user->save();

        // Return a success response upon password change
        return response()->json(['message' => 'Password changed successfully'], 200);
    }
}
