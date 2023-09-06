<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class PatientListController extends Controller
{
    public function index(Request $request)
    { // Set timezone
        if ($request->date) {
            $date = $request->date;
            $bookings = Booking::latest()->where('date', $date)->get();
            return view('admin.patientlist.index', compact('bookings', 'date'));
        };
        $bookings = Booking::latest()->where('date', date('m-d-yy'))->get();
        return view('admin.patientlist.index', compact('bookings'));
    }
    public function toggleStatus($id)
    {
        $booking = Booking::find($id);
        $booking->status = !$booking->status;
        $booking->save();
        return redirect()->back();
    }

    public function allTimeAppointment()
    {
        $bookings = Booking::latest()->paginate(20);
        return view('admin.patientlist.all', compact('bookings'));
    }
}
