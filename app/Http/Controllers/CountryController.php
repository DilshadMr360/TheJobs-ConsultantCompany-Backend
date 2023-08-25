<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'countries' => Country::all()
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
            'name' => 'required|unique:countries'
        ]);

        // Validation passed, create the appointment
        $country = Country::create([
            'name' => $request->name,
        ]);

        // Optionally, you can return a response indicating success or the created appointment
        return response()->json([
            'success' => true,
            'country' => $country
        ], 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        return response()->json([
            'success' => true,
            'country' => $country
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => 'required|unique:countries',
        ]);

        // If the country doesn't exist, return a not found response
        if (!$country) {
            return response()->json([
                'message' => 'Country not found'
            ], 404); // 404 Not Found status code
        }

        // Update the country attributes
        $country->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'country' => $country
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        if (!$country) {
            return response()->json([
                'message' => 'Country not found'
            ], 404); // 404 Not Found status code
        }

        // Delete the appointment
        $country->delete();

        // Optionally, you can return a response indicating success
        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }
}
