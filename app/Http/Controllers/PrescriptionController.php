<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Attachment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        // Get the DOCTOR PATIENTS appointments on the date and checked-in
        $bookings = Booking::where('date', date('m-d-Y'))
            // ->where('status', 1)
            ->where('doctor_id', Auth::id())
            ->get();
        if ($request->date) {
            $formatDate = date('m-d-Y', strtotime($request->date));
            $bookings = Booking::where('date',  $formatDate)
                // ->where('status', 1)
                ->where('doctor_id', Auth::id())
                ->get();
        }
        return view('prescription.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        // Validate uploaded files
        $request->validate([
            'attachements.*' => ['nullable', 'file', 'max:2048'] // Validation rules for any file type
        ], [
            'attachements.*.max' => 'Maximum file size to upload is 2MB',
        ]);

        try {
            $data = $request->except('attachements');
            // Create a new prescription record
            $pres = Prescription::create($data);
            // Handle uploaded files
            if ($request->file('attachements')) {
                // dd($request->file('attachements'));
                foreach ($request->file('attachements') as $file) {
                    $file_name = uploadImage($file, '', 'report');
                    // Create a new attachment record
                    $attachment = new Attachment();
                    $attachment->name = $file_name;
                    $attachment->prescription_id = $pres->id;
                    $attachment->doctor_id = Auth::id();
                    $attachment->booking_id = $request->booking_id;
                    $attachment->upload_by = 'doctor';
                    $attachment->attachment_url = asset('uploads/' . $file_name); // Assuming you are using Laravel's storage system
                    $attachment->save();
                }
            } else {
                return redirect()->back()->with('errMessage', 'image uploading');
            }


            return redirect()->back()->with('message', 'A prescription was created successfully!');
        } catch (\Exception $e) {
            // Handle any exceptions that may occur during the process
            return redirect()->back()->with('errMessage', 'An error occurred while creating the prescription: ' . $e->getMessage());
        }
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

    public function toggleStatus($id)
    {
        $booking = Booking::find($id);
        $booking->status = !$booking->status;
        $booking->save();
        return redirect()->back();
    }
}
