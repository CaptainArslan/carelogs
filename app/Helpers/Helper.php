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


if (!function_exists('generate_jwt_token')) {
    /**
     * Generate a JSON Web Token (JWT).
     *
     * @param array $payload The data to include in the payload.
     * @param string $key The secret key used to sign the token.
     * @param int $expiration The token expiration time in seconds.
     * @return string The generated JWT token.
     */
    function generate_jwt_token($payload,  $key,  $expiration = 3600): string
    {
        // Set the token expiration time
        $payload['exp'] = time() + $expiration;

        // Generate the JWT token using the secret key
        return JWT::encode($payload, $key, 'HS256');
    }
}

function jetToken()
{
    $key = env('ZOOM_CLIENT_ID', '');
    $secret = env('ZOOM_CLIENT_SECRET', '');
    try {
        $client = new Client(['base_uri' => 'https://zoom.us']);

        $response = $client->request('POST', '/oauth/token', [
            "headers" => [
                "Authorization" => "Basic " . base64_encode($key . ':' . $secret)
            ],
            'form_params' => [
                "grant_type" => "authorization_code",
                "code" => $_GET['code'],
                "redirect_uri" => REDIRECT_URI
            ],
        ]);

        $token = json_decode($response->getBody()->getContents(), true);
        return $token;
        echo "Access token inserted successfully.";
    } catch (Exception $e) {
        return $e->getMessage();
        echo $e->getMessage();
    }
}

function generateZoomToken()
{
    $key = env('ZOOM_CLIENT_ID', '');
    $secret = env('ZOOM_CLIENT_SECRET', '');

    $tokenPayload = [
        'iss' => $key,
        'exp' => time() + 3600,
    ];

    return JWT::encode($tokenPayload, $secret, 'HS256');
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




    // Send a POST request to create the meeting
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . generateZoomToken(),
        'Content-Type' => 'application/json',
    ])->post("https://api.zoom.us/v2/users/me/meetings", $data);



    dd($response->json());

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
