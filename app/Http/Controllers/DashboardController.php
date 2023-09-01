<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    private function admin_dashboard(){
        // Admin Dashboard
        $users_total = User::count();
        $users_admin = User::where('role', 'admin')->count();
        $users_consultant = User::where('role', 'consultant')->count();
        $users_client = User::where('role', 'client')->count();

        $appointments_count = Appointment::count();
        $appointments_today = Appointment::whereDate('time', Carbon::today()->toDateString())->count();
        // Inheritance ... we are calling the 'where' function on the Appointment model
        // Which is inherited from its parent class 'Model'
        $appointments_approved = Appointment::where('status', 'approved')->count();
        $appointments_pending = Appointment::where('status', 'pending')->count();

        return compact(
            'users_total',
            'users_admin',
            'users_consultant',
            'users_client',
            'appointments_count',
            'appointments_today',
            'appointments_approved',
            'appointments_pending',
        );
    }

    private function consultant_dashboard(){
        $user = Auth::user();
        $appointments_count = Appointment::where('consultant_id', $user->id)->count();
        $appointments_today = Appointment::where('consultant_id', $user->id)->whereDate('time', Carbon::today()->toDateString())->count();
        // Inheritance ... we are calling the 'where' function on the Appointment model
        // Which is inherited from its parent class 'Model'
        $appointments_approved = Appointment::where('consultant_id', $user->id)->where('status', 'approved')->count();
        $appointments_pending = Appointment::where('consultant_id', $user->id)->where('status', 'pending')->count();

        return compact(
            'appointments_count',
            'appointments_today',
            'appointments_approved',
            'appointments_pending',
        );
    }


    public function index (Request $request){
        // total users, admins, clients, consultants

        //Admin Dashboard
        //Consultants , Job Seekrs, Appointments, Today, Approved  Pending
        $dashboard = match(Auth::user()->role){
            'admin' => $this->admin_dashboard(),
            'consultant' => $this->consultant_dashboard(),
            default => 'You cannot access the dashboard'
        };

        return response()->json([
            'success' => true,
            'dashboard' => $dashboard
        ]);

    }
}
