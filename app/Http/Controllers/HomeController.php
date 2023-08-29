<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use PhpParser\Comment\Doc;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // redirect to dashboard if user is admin or doctor
        if (Auth::user()->role->name == Role::ADMINROLE || Auth::user()->role->name == Role::DOCTORROLE) {
            return redirect()->to('/dashboard');
        };
        $doctors = User::activeDoctors()->latest()->take(3)->get();
        return view('frontend.index', get_defined_vars());
    }

    /**
     * Show the doctor page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function doctor(Request $request)
    {
        $appointments = Appointment::where('date', date('m-d-Y'))->get();
        if ($request->date) {
            $formatDate = date('m-d-Y', strtotime($request->date));
            $appointments = Appointment::where('date', $formatDate)->get();
        };
        return view('frontend.doctor', get_defined_vars());
    }
}
