<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if($request->role){
            $query->where('role', $request->role);
        }

        if($request->role){
            $query->where('role', $request->role);
        }

        return response()->json([
            'success' => true,
            'user' => User::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email', // Assuming the table name for users is 'users'
            'phone' => 'required|unique:users,phone', // Assuming the table name for users is 'users'
            'role' => 'required|in:client,consultant,admin',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Validation passed, create the appointment
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role' => $request->input('role'),
            'password' => Hash::make($request->input('password'))
        ]);

        // Optionally, you can return a response indicating success or the created appointment
        return response()->json([ 
            'success' => true,
            'message' => 'User Created Successfully',
            'user' => $user
        ], 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->jobs()->attach([1,4]);
        return response()->json([
            'success' => true,
            'user' => $user->with('countries', 'jobs')->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        $request->validate([
            'email' => 'required',
            'phone' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);

        // If the country doesn't exist, return a not found response
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404); // 404 Not Found status code
        }

        // Update the country attributes
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role' => $request->input('role'),
            'password' => Hash::make($request->input('password'))
        ]);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404); // 404 Not Found status code
        }

        // Delete the appointment
        $user->delete();

        // Optionally, you can return a response indicating success
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
