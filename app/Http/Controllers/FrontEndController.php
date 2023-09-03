<?php

namespace App\Http\Controllers;


use App\Models\Time;
use App\Models\User;
use App\Models\Booking;
use App\Models\Appointment;
use Illuminate\Support\Str;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Jubaer\Zoom\Facades\Zoom;
use App\Traits\ZoomMeetingTrait;
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
        // Set timezone
        // date_default_timezone_set('America/New_York');
        // // If there is set date, find the doctors
        // if (request('date')) {
        //     $formatDate = date('m-d-yy', strtotime(request('date')));
        //     $doctors = Appointment::where('date', $formatDate)->get();
        //     return view('welcome', compact('doctors', 'formatDate'));
        // };
        // // Return all doctors avalable for today to the welcome page
        // $doctors = Appointment::where('date', date('m-d-yy'))->get();
        // return view('welcome', compact('doctors'));
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
        Booking::create([
            'id' => Str::uuid()->toString(),
            'user_id' => auth()->user()->id,
            'doctor_id' => $doctorId,
            'time' => $time,
            'date' => $date,
            'status' => 1
        ]);
        $doctor = User::where('id', $doctorId)->first();
        Time::where('appointment_id', $appointmentId)->where('time', $time)->update(['status' => 1]);

        // Send email notification
        $mailData = [
            'name' => auth()->user()->name,
            'time' => $time,
            'date' => $date,
            'doctorName' => $doctor->name
        ];

        // $meetings = Zoom::createMeeting([
        //     "agenda" => 'Doctor Appointment',
        //     "topic" => 'Dcoitor Appointment',
        //     "type" => self::MEETING_TYPE_SCHEDULE, // 1 => instant, 2 => scheduled, 3 => recurring with no fixed time, 8 => recurring with fixed time
        //     "duration" => 30, // in minutes
        //     "timezone" => 'Asia/Karachi', // set your timezone
        //     "password" => '12345678',
        //     "start_time" => 'set your start time', // set your start time
        //     "template_id" => 'set your template id', // set your template id  Ex: "Dv4YdINdTk+Z5RToadh5ug==" from https://marketplace.zoom.us/docs/api-reference/zoom-api/meetings/meetingtemplates
        //     "pre_schedule" => false,  // set true if you want to create a pre-scheduled meeting
        //     "schedule_for" => 'set your schedule for profile email ', // set your schedule for
        //     "settings" => [
        //         'join_before_host' => false, // if you want to join before host set true otherwise set false
        //         'host_video' => false, // if you want to start video when host join set true otherwise set false
        //         'participant_video' => false, // if you want to start video when participants join set true otherwise set false
        //         'mute_upon_entry' => false, // if you want to mute participants when they join the meeting set true otherwise set false
        //         'waiting_room' => false, // if you want to use waiting room for participants set true otherwise set false
        //         'audio' => 'both', // values are 'both', 'telephony', 'voip'. default is both.
        //         'auto_recording' => 'none', // values are 'none', 'local', 'cloud'. default is none.
        //         'approval_type' => 2, // 0 => Automatically Approve, 1 => Manually Approve, 2 => No Registration Required
        //     ],
        // ]);

        // dd($meetings);


        // $type = self::MEETING_TYPE_SCHEDULE;
        // $time = now()->addMinutes(5)->format('Y-m-d\TH:i:s');
        // $meeting = createScheduledMeeting($time, $type);
        // dd($meeting);

        // $user = Auth::user();

        // $user->notify(new BookingMadeNotification());

        try {
            // Mail::to(auth()->user()->email)->send(new AppointmentMail($mailData));
        } catch (\Exception $e) {
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
        return view('frontend.doctor', get_defined_vars());
    }
}
