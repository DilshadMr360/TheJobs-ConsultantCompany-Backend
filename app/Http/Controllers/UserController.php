<?php

namespace App\Http\Controllers;

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

        if ($request->role && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        if($request->search){
            $search = $request->search;
            $query->where(function ($query) use($search) {
                $query->where('name', 'like', '%' . $search . '%')
                   ->orWhere('email', 'like', '%' . $search . '%')
                   ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        return response()->json([
            'success' => true,
            'users' => $query->get()
        ]);
    }

    public function find(Request $request)
    {
        $job_id = $request->job_id ?? 0;
        $country_id = $request->country_id ?? 0;

        $users = User::where('role', 'consultant')
            ->whereHas('jobs', function ($query) use ($job_id) {
                $query->where('job_id', $job_id);
            })
            ->whereHas('countries', function ($query) use ($country_id) {
                $query->where('country_id', $country_id);
            })
            ->get();

        return response()->json([
            'success' => true,
            'users' => $users
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
            'phone' => 'required|string|max:16|regex:/^[0-9]*$/|unique:users',
            'role' => 'required|in:client,consultant,admin',
            'password' => 'required|string|min:8|confirmed'
        ]);

        // Validation passed, create the appointment
        $created_user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role' => $request->input('role'),
            'password' => Hash::make($request->input('password'))
        ]);

        $user = User::find($created_user->id);

        if ($request->jobs) {
            $user->jobs()->attach($request->jobs);
        }

        if ($request->countries) {
            $user->countries()->attach($request->countries);
        }

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
        return response()->json([
            'success' => true,
            'user' => $user,
            'jobs' => $user->jobs->pluck('id'),
            'countries'=> $user->countries->pluck('id'),
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
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'. $user->id, // Assuming the table name for users is 'users'
            'phone' => 'required|string|max:16|regex:/^[0-9]*$/|unique:users,phone,' . $user->id,
            'role' => 'required|in:client,consultant,admin',
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'required|min:8|confirmed'
            ]);

            $user->update([
                'password' => hash::make($request->password)
            ]);
        }

        // If the user doesn't exist, return a not found response
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404); // 404 Not Found status code
        }

        // Update the user attributes
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role' => $request->input('role'),
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

        // Delete the user
        $user->delete();

        // Optionally, you can return a response indicating success
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
