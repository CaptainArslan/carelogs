@component('mail::message')
# Appointment Confirmation

Hello {{ $booking['name'] ?? '' }},

You have successfully booked an appointment with Dr. {{ $booking['doctorName'] }}.

**Appointment Details:**
- Date: {{ $booking['date'] ?? '' }}
- Time: {{ $booking['time'] ?? '' }}
- Doctor: Dr. {{ $booking['doctorName'] ?? '' }}
- Doctor's Email: {{ $booking['doctorEmail'] ?? '' }}
- Doctor's Phone: {{ $booking['doctorPhone'] ?? '' }}

**Zoom Meeting Information:**
- Meeting ID: {{ $booking['meetingId'] ?? '' }}
- Zoom Link: [Join Meeting]({{ $booking['zoomLink'] ?? '' }})
- Zoom Meeting Password: {{ $booking['zoomPassword'] ?? '' }}

Please make sure to join the Zoom meeting on time.

Thank you for choosing our services.

Best regards,
{{ config('app.name') }}
@endcomponent