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
            ->where('status', 1)
            ->where('doctor_id', Auth::id())
            ->get();
        if ($request->date) {
            $formatDate = date('m-d-Y', strtotime($request->date));
            $bookings = Booking::where('date',  $formatDate)
                ->where('status', 1)
                ->where('doctor_id', Auth::id())
                ->get();
        }
        return view('prescription.index', compact('bookings'));
    }


    public function store(Request $request)
    {
        // Validate uploaded files
        $request->validate([
            'prescription.*' => 'file|max:2048' // Validation rules for any file type
        ]);

        try {
            $data = $request->except('report');
            // Create a new prescription record
            $pres = Prescription::create($data);
            // Handle uploaded files
            if($request->file('report')){
                foreach ($request->file('report') as $file) {
                    $image = uploadImage($file, '/uploads/', 'profile');
                    // $fileName = time() . '_' . $file->getClientOriginalName();
                    // $file->storeAs('uploads', $fileName); // Store the file in the "uploads" directory
    
                    // Create a new attachment record
                    $attachment = new Attachment();
                    $attachment->prescription_id = $pres->id;
                    $attachment->doctor_id = Auth::id();
                    $attachment->url = asset('uploads/' . $image); // Assuming you are using Laravel's storage system
                    $attachment->save();
                }
            }else{
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
}
