@extends('frontend.layouts.app')

@section('title', 'Home | Care-Logs')

@section('custom-css')

@endsection

@section('content')

<!-- TEAM -->
<section id="team" data-stellar-background-ratio="1">
    <div class="container">
        <div class="row">

            @if (!Auth::user())
            <h5 class="text-danger text-center"> Please Login for to make booking </h5>
            @endif

            <div class="col-md-12 col-sm-6">
                <div class="about-info" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="col-md-12 col-sm-6">
                        <h2 class="wow fadeInUp" data-wow-delay="0.1s">Available Doctors</h2>
                    </div>
                    <div class="col-md-12 col-sm-6">
                        <form action="{{ route('frontend.doctor') }}" method="GET" id="form">
                            <input type="date" name="date" value="{{ old('date') }}" class="form-control" onchange="formSubmit()">
                        </form>
                    </div>

                </div>
            </div>
            <div class="clearfix"></div>
            @forelse ($appointments as $key => $appointment)
            <div class="col-md-4 col-sm-6">
                <div class="team-thumb wow fadeInUp" data-wow-delay="0.2s">
                    <img src="{{ asset('images/team-image' . ($key + 1) . '.jpg') }}" class="img-responsive" alt="">
                    <div class="team-info">
                        <h3>{{ $appointment->doctor->name }}</h3>
                        <p>{{ $appointment->doctor->department }}</p>
                        <div class="team-contact-info">
                            <p><i class="fa fa-phone"></i> {{ $appointment->doctor->phone_number }}</p>
                            <p><i class="fa fa-envelope-o"></i> <a href="#">{{ $appointment->doctor->email }}</a></p>
                        </div>
                        <!-- <ul class="social-icon">
                            <li><a href="#" class="fa fa-linkedin-square"></a></li>
                            <li><a href="#" class="fa fa-envelope-o"></a></li>
                        </ul> -->
                        @if (Auth::user() && Auth::user()->id)
                        <a href="{{ route('create.appointment', [$appointment->user_id, $appointment->date]) }}" class="section-btn btn btn-default smoothScroll">Make an Appointment</a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <h4 class="text-danger text-center">No Doctor Avaialable at ( {{ date('d, M y', strtotime(request()->date))}} ) </h4 class="text-danger text-center">
            @endforelse
        </div>
    </div>
</section>

<script>
    function formSubmit() {
        document.getElementById('form').submit();
    }
</script>

@endsection