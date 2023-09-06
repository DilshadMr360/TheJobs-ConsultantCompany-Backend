<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        switch ($user->role){
            case 'admin':
                $appointments = Appointment::with('client', 'consultant', 'job', 'country');
                break;
            case 'consultant':
                $appointments = Appointment::with('client', 'consultant', 'job', 'country')->where('consultant_id', $user->id)->where('status', 'approved');
                break;
            default:
                $appointments = Appointment::with('client', 'consultant', 'job', 'country')->where('client_id', $user->id);
        }

        if($request->status && $request->status != 'all'){
            $appointments = $appointments->where('status', $request->status);
        }

        return response()->json([
            'success' => true,
            'appointments' => $appointments->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if($user->role == 'admin') {
            $request->validate([
                'client_id' => 'required|exists:users,id'
            ]);
        }

        $request->validate([
            'consultant_id' => 'required|exists:users,id',
            'country_id' => 'required|exists:countries,id',
            'job_id' => 'required|exists:jobs,id',
            'time' => 'required'
        ]);

        $appointmentTime = Carbon::parse($request->input('time'));
        // Validation passed, create the appointment
        $appointment = Appointment::create([
            'client_id' => $user->role == 'admin' ? $request->client_id : $user->id,
            'consultant_id' => $request->input('consultant_id'),
            'country_id' => $request->input('country_id'),
            'job_id' => $request->input('job_id'),
            'time' => $appointmentTime
        ]);

        // Optionally, you can return a response indicating success or the created appointment
        return response()->json([
            'success' => true,
            'appointment' => $appointment
        ], 201); // 201 Created status code


    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return response()->json([
            'success' => true,
            'appointment' => $appointment
        ]);
    }



    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'consultant_id' => 'required|exists:users,id',
            'country_id' => 'required|exists:countries,id',
            'job_id' => 'required|exists:jobs,id',
            'time' => 'required'
        ]);

        // If the appointment doesn't exist, return a not found response
        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found'
            ], 404); // 404 Not Found status code
        }

        // Update the appointment attributes
        $appointment->update([
            'consultant_id' => $request->input('consultant_id'),
            'country_id' => $request->input('country_id'),
            'job_id' => $request->input('job_id'),
            'time' => $request->input('time')
        ]);

        // Optionally, you can return a response indicating success or the updated appointment
        return response()->json([
            'success' => true,
            'message' => 'Appointment updated successfully',
            'appointment' => $appointment
        ]);
    }

    public function review(Appointment $appointment, Request $request)
    {
        if($request->accept == 'true'){
            $appointment->update([
                'status' => 'approved'
            ]);
            Notification::create([
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->client->id,
                'message' => "Your appointment has been approved."
            ]);

            Notification::create([
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->consultant->id,
                'message' => "Your recived a new appointment."
            ]);
        }

        if($request->accept == 'false'){
            $appointment->update([
                'status' => 'rejected'
            ]);
            Notification::create([
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->client->id,
                'message' => "Your appointment has been rejected."
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Appointment has been ' . $appointment->status,
            'appointment' => $appointment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        // If the appointment doesn't exist, return a not found response
        if (!$appointment) {
            return response()->json([
                'message' => 'Appointment not found'
            ], 404); // 404 Not Found status code
        }

        // Delete the appointment
        $appointment->delete();

        // Optionally, you can return a response indicating success
        return response()->json([
            'success' => true,
            'message' => 'Appointment deleted successfully'
        ]);
    }
}
