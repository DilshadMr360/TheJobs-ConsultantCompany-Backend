<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:16|regex:/^[0-9]*$/|unique:users',
            'password' => 'required|confirmed|string|min:8',
        ]);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => User::find($user->id),
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email',  $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => ['Invalid credentials'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful'
        ]);
    }

    public function update_profile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:16|regex:/^[0-9]*$/|unique:users',
        ]);

        if ($request->password) {
            $request->validate([
                'current_password' => 'password',
                'password' => 'required|min:8|confirmed'
            ]);

            $user->update([
                'password' => hash::make($request->password)
            ]);
        }

        // Update the user attributes
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ]);

        if ($request->jobs) {
            $user->jobs()->sync($request->jobs);
        }

        if ($request->countries) {
            $user->countries()->sync($request->countries);
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
