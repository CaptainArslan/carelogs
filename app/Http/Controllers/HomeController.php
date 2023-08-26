<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use PhpParser\Comment\Doc;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $doctors = User::activeDoctors()->latest()->take(3)->get();
        return view('frontend.index', get_defined_vars());
    }

    /**
     * Show the doctor page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function doctor()
    {
        $doctors = User::activeDoctors()->latest()->get();
        return view('frontend.doctor', get_defined_vars());
    }
}
