@extends('frontend.layouts.app')

@section('title', 'Home | Care-Logs')

@section('custom-css')

@endsection

@section('content')

<!-- TEAM -->
<section id="team" data-stellar-background-ratio="1">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-6">
                <div class="about-info">
                    <h2 class="wow fadeInUp" data-wow-delay="0.1s">Available Doctors</h2>
                    <label for="date">Please Select date</label>
                    <input type="date">
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
                        <ul class="social-icon">
                            <li><a href="#" class="fa fa-linkedin-square"></a></li>
                            <li><a href="#" class="fa fa-envelope-o"></a></li>
                        </ul>
                    </div>

                </div>
            </div>
            @empty
            <p class="text-center">No Dcotor Found</p>
            @endforelse


        </div>
    </div>
</section>


@endsection