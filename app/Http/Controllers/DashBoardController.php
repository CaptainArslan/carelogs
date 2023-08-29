<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Booking;
use App\Models\Department;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashBoardController extends Controller
{
    // Prevent guest from going to dashboard
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role->name == Role::PATIENTROLE) {
            return view('home');
        };

        $Patients_count = User::where('role_id', Role::PATIENT)->count();
        $doctors_count = User::where('role_id', Role::DOCTOR)->count();
        $role_count = Role::count();
        $bookings_count = Booking::count();
        $prescription_count = Prescription::count();
        $departments_count = Department::count();

        return view('dashboard', get_defined_vars());
    }
}
