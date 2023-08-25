<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'jobs' => Job::all()
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
            'name' => 'required|unique:jobs'
        ]);

        // Validation passed, create the appointment
        $job = Job::create([
            'name' => $request->name,
        ]);

        // Optionally, you can return a response indicating success or the created appointment
        return response()->json([
            'success' => true,
            'job' => $job
        ], 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     */
    public function show(Job $job)
    {
        return response()->json([
            'success' => true,
            'job' => $job
        ]);                         
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Job $job)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'name' => 'required|unique:jobs',
        ]);

        // If the country doesn't exist, return a not found response
        if (!$job) {
            return response()->json([
                'message' => 'Job not found'
            ], 404); // 404 Not Found status code
        }

        // Update the country attributes
        $job->update([
            'job' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'job' => $job
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        if (!$job) {
            return response()->json([
                'message' => 'User not found'
            ], 404); // 404 Not Found status code
        }

        // Delete the appointment
        $job->delete();

        // Optionally, you can return a response indicating success
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
    }

