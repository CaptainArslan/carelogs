<?php


use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use App\Models\Prescription;
use Illuminate\Support\Facades\Http;

function getRandomTime()
{
    $hour = rand(1, 12);
    $minute = rand(0, 59);
    $meridiem = rand(0, 1) ? 'am' : 'pm';
    $formattedTime = sprintf('%d.%02d%s', $hour, $minute, $meridiem);
    return $formattedTime;
}

function hasPrescription($doctorId, $userId, $date)
{
    return Prescription::where('date', $date)
        ->where('doctor_id', $doctorId)
        ->where('user_id', $userId)
        ->exists();
}

function getLogo()
{
    return asset('images/logo.png');
}

function getPlaceholderImage()
{
    return asset('images/placeholder.jpg');
}


function createScheduledMeeting($time, $type)
{
    // Prepare the request data
    $data = [
        'topic' => 'Booking Appointment',
        'type' => $type, // Scheduled meeting
        // 'start_time' => now()->addMinutes(5)->format('Y-m-d\TH:i:s'), // Example: Schedule for 5 minutes from now
        'start_time' => $time, // Example: Schedule for 5 minutes from now
        'duration' => 60, // Duration in minutes
        'timezone' => 'Asia/Karachi', // Set your desired timezone
    ];
    $token = getZoomAccessToken(env('ZOOM_ACCOUNT_ID'), env('ZOOM_CLIENT_ID'), env('ZOOM_CLIENT_SECRET'));

    // Send a POST request to create the meeting
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $token,
        'Content-Type' => 'application/json',
    ])->post("https://api.zoom.us/v2/users/me/meetings", $data);

    dd($response);

    // Check for a successful response
    if ($response->successful()) {
        $meetingData = $response->json();


        // Extract the meeting ID and join URL from $meetingData
        $meetingId = $meetingData['id'];
        $joinUrl = $meetingData['join_url'];

        // You can save the $meetingId and $joinUrl in your database or use them as needed.

        return $meetingData;
    } else {
        // Handle the case where the meeting creation was not successful
        return 'Failed to create the meeting: ' . $response->status();
    }
}

// function getZoomToken($accountID)
// {
//     // Replace these with your actual Zoom API credentials
//     $clientId = env('ZOOM_CLIENT_ID');
//     $clientSecret = env('ZOOM_CLIENT_SECRET');

//     // Base64 encode the client ID and client secret
//     $base64Credentials = base64_encode($clientId . ':' . $clientSecret);

//     // Define the request data
//     $requestData = [
//         'grant_type' => 'account_credentials',
//         'account_id' => $accountID,
//     ];

//     // Make the HTTP POST request to Zoom API
//     $response = Http::withHeaders([
//         'Host' => 'zoom.us',
//         'Authorization' => 'Basic ' . $base64Credentials,
//         'Content-Type' => ' application/json',
//     ])->post('https://zoom.us/oauth/token', $requestData);

//     dd($response->json());

//     // Check if the request was successful
//     if ($response->successful()) {
//         // Parse and return the response JSON
//         return $response->json();
//     } else {
//         // Handle the error response here
//         return $response->json();
//     }
// }


function getZoomAccessToken($accountID, $clientId, $clientSecret)
{
    $ch = curl_init();

    $url = 'https://zoom.us/oauth/token';
    $data = [
        'grant_type' => 'account_credentials',
        'account_id' => $accountID,
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $headers = array(
        'Host: zoom.us',
        'Authorization: Basic ' . base64_encode("$clientId:$clientSecret"),
        'Content-Type: application/x-www-form-urlencoded',
    );

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    }

    curl_close($ch);

    return $result;
}

function formatTime($time)
{
    return date('H:i:s', strtotime($time));
}

function uploadImage($image, $folderName, $defaultName = null)
{
    // Check if the image is valid
    if (!$image->isValid()) {
        throw new \Exception('Invalid image.');
    }

    $extension = $image->getClientOriginalExtension();

    // Generate a unique filename for the image
    $filename = uniqid() . '_' . time()  . '_' . $defaultName . '.' . $extension;

    if (!is_dir(public_path('uploads/' . $folderName))) {
        // create the directory if it does not exist
        mkdir(public_path('uploads/' . $folderName), 0777, true);
    }

    // Upload the image to the specified folder
    try {
        $image->move(public_path('uploads/' . $folderName), $filename);
    } catch (\Exception $e) {
        throw new \Exception('Error uploading image: ' . $e->getMessage());
    }

    // Return the filename so it can be saved to a database or used in a view
    return $filename;
}
