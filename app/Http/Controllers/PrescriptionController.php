<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index()
    {
        // Get the DOCTOR PATIENTS appointments on the date and checked-in
        $bookings = Booking::where('date', date('m-d-Y'))
            ->where('status', 1)
            ->where('doctor_id', Auth::id())
            ->get();

        $prescriptionsByBooking = [];

        foreach ($bookings as $booking) {
            $prescriptions = Prescription::where('date', date('m-d-yy'))
                ->where('doctor_id', Auth::id())
                ->where('user_id', $booking->user->id)
                ->get();

            $prescriptionsByBooking[$booking->id] = $prescriptions;
        }


        return view('prescription.index', compact('bookings', 'prescriptionsByBooking'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
        Prescription::create($data);
        return redirect()->back()->with('message', 'A prescription was created successfully!');
    }

    public function show($userId, $date)
    {
        $prescription = Prescription::where('user_id', $userId)->where('date', $date)->first();
        return view('prescription.show', compact('prescription'));
    }

    public function showAllPrescriptions()
    {
        $bookings = Prescription::get();
        return view('prescription.all', compact('bookings'));
    }
}
