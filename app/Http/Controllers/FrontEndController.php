<?php

namespace App\Http\Controllers;

use App\Models\Time;
use App\Models\User;
use App\Models\Booking;
use App\Models\Attachment;
use App\Models\Appointment;
use Illuminate\Support\Str;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Jubaer\Zoom\Facades\Zoom;
use App\Traits\ZoomMeetingTrait;
use Illuminate\Support\Facades\Log;
use App\Mail\Booking as MailBooking;
use App\Models\Disease;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\BookingMadeNotification;

class FrontEndController extends Controller
{

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = User::activeDoctors()->latest()->take(3)->get();
        return view('frontend.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Auth::user()->name;
        // Set timezone
        $request->validate(['time' => 'required']);
        $check = $this->checkBookingTimeInterval();

        if ($check) {
            return redirect()->back()->with('errMessage', 'You already made an appointment. Please check your email for the appointment!');
        }

        $doctorId = $request->doctorId;
        $time = $request->time;
        $appointmentId = $request->appointmentId;
        $date = $request->date;

        $startTime = date("Y-m-d H:i:s", strtotime($request->date . ' ' . $request->time));
        $meeting = $this->createZoom($startTime);
        Log::info($meeting);

        $booking = new Booking();
        // $booking->id = Str::uuid()->toString();
        $booking->user_id = auth()->user()->id;
        $booking->doctor_id = $doctorId;
        $booking->time = $time;
        $booking->date = $date;
        $booking->status = 1;
        $booking->meeting_details = json_encode($meeting);
        $booking->save();

        $doctor = User::where('id', $doctorId)->first();
        Time::where('appointment_id', $appointmentId)->where('time', $time)->update(['status' => 1]);

        // $booking->update(['meeting_details' => json_encode($meeting)]);
        $bookingData = [
            'name' => auth()->user()->name,
            'time' => $time,
            'date' => $date,
            'doctorName' => $doctor->name,
            'doctorEmail' => $doctor->email,
            'doctorPhone' => $doctor->phone,
            'meetingId' => $meeting['id'],
            'zoomLink' => $meeting['join_url'],
            'zoomPassword' => $meeting['password'],
        ];
        Log::info($bookingData);

        try {
            Mail::to(Auth::user()->email)->send(new MailBooking($bookingData));
        } catch (\Exception $e) {
            return redirect()->back()->with('errMessage', 'Your Booking has been confimed but email not sent! <br>' . $e->getMessage());
        }

        return redirect()->back()->with('message', 'Your appointment was booked for ' . $date . ' at ' . $time . ' with ' . $doctor->name . '.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($doctorId, $date)
    {
        $appointment = Appointment::where('user_id', $doctorId)->where('date', $date)->first();
        $times = Time::where('appointment_id', $appointment->id)->where('status', 0)->get();
        $user = User::where('id', $doctorId)->first();
        $doctor_id = $doctorId;
        return view('appointment', compact('times', 'date', 'user', 'doctor_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // check if user already make a booking.
    public function checkBookingTimeInterval()
    {
        return Booking::orderby('id', 'desc')
            ->where('user_id', auth()->user()->id)
            ->whereDate('created_at', date('m-d-Y'))
            ->exists();
    }

    public function myBookings()
    {
        $appointments = Booking::latest()->where('user_id', auth()->user()->id)->get();
        return view('booking.index', compact('appointments'));
    }

    public function myPrescription()
    {
        $prescriptions = Prescription::where('user_id', auth()->user()->id)->get();
        return view('my-prescription', compact('prescriptions'));
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
        $diseases = Disease::get();
        return view('frontend.doctor', get_defined_vars());
    }

    function createZoom($startTime)
    {
        try {
            $meetingSettings = [
                "agenda" => "Doctor Appointment",
                "topic" => "Doctor Appointment",
                "type" => 2, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
                "duration" => 20, // in minutes
                "timezone" => "Asia/Karachi",
                "password" => Str::random(8),
                "start_time" => $startTime, // set your start time
                "settings" => [
                    "join_before_host" => false, // if you want to join before host set true otherwise set false
                    "host_video" => false, // if you want to start video when host join set true otherwise set false
                    "participant_video" => false, // if you want to start video when participants join set true otherwise set false
                    "mute_upon_entry" => false, // if you want to mute participants when they join the meeting set true otherwise set false
                    "waiting_room" => false, // if you want to use waiting room for participants set true otherwise set false
                    "audio" => "both", // values are 'both', 'telephony', 'voip'. default is both.
                    "auto_recording" => "none", // values are 'none', 'local', 'cloud'. default is none.
                    "approval_type" => 2 // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
                ]
            ];
            $accessTokenResponse = getZoomAccessToken(env('ZOOM_ACCOUNT_ID'), env('ZOOM_CLIENT_ID'), env('ZOOM_CLIENT_SECRET'));
            $responseData = json_decode($accessTokenResponse, true);
            $result = createZoomMeeting($responseData['access_token'], $meetingSettings);
            return $result;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function reportUpload(Request $request)
    {
        // Validate uploaded files
        $request->validate([
            'attachements.*' => ['nullable', 'file', 'max:2048'], // Validation rules for any file type
            'prescription_id' => ['required', 'numeric', 'exists:prescriptions,id'] // Validation rules for any file type
        ], [
            'attachements.*.max' => 'Maximum file size to upload is 2MB',
            'prescription_id.required' => 'Prescription ID is required',
            'prescription_id.numeric' => 'Prescription ID must be a number',
            'prescription_id.exists' => 'Prescription ID does not exist',
        ]);

        try {
            if ($request->file('attachements')) {
                // dd($request->file('attachements'));
                foreach ($request->file('attachements') as $file) {
                    $file_name = uploadImage($file, '', 'report');
                    // Create a new attachment record
                    $attachment = new Attachment();
                    $attachment->name = $file_name;
                    $attachment->prescription_id = $request->prescription_id;
                    $attachment->doctor_id = Auth::id();
                    $attachment->booking_id = $request->booking_id;
                    $attachment->upload_by = 'patient';
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
}
