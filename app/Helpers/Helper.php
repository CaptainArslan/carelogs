<?php

use App\Models\Prescription;

function getRandomTime(){
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