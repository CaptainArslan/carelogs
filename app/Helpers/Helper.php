<?php


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
    $filename = time()  . '_' . $defaultName . '.' . $extension;

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

function createZoomMeeting($accessToken, $meetingSettings)
{
    // Define the API endpoint for creating a meeting
    $apiEndpoint = 'https://api.zoom.us/v2/users/me/meetings';

    // Define the headers for the request
    $headers = [
        'Authorization' => 'Bearer ' . $accessToken,
        'Content-Type' => 'application/json',
    ];

    // Prepare the request payload as JSON
    $jsonData = json_encode($meetingSettings);

    // Make the API request to create the meeting
    $response = Http::withHeaders($headers)->withBody(
        $jsonData,
        'application/json'
    )->post($apiEndpoint);

    // Check for a successful response (status code 201)
    if ($response->successful()) {
        return $response->json();
    } else {
        // Handle error cases
        return ['error' => $response->json()];
    }
}
