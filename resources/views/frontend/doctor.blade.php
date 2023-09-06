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
            <h4 class="text-danger text-center"> Please Sign Up / Login for to make Appointment or Booking </h4>
            @endif

            <div class="col-md-12 col-sm-6">
                <div class="about-info">
                    <div class="col-md-12 col-sm-6">
                        <h4 class="wow fadeInUp text-danger" data-wow-delay="0.1s">Seach Your Doctor</h4>
                    </div>
                    <!-- <div class="col-md-12 col-sm-6">
                        <form action="{{ route('frontend.doctor') }}" method="GET" id="form">
                            <input type="date" name="date" value="{{ request()->date }}" class="form-control" onchange="formSubmit()">
                        </form>
                    </div> -->
                </div>

                <form action="{{ route('frontend.doctor') }}" method="GET" id="form">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="text-danger" for="disease">Disease</label>
                            <select id="disease" class="form-control" name="disease">
                                <option>Choose...</option>
                                @forelse ($diseases as $disease)
                                <option value="{{ $disease->id }}" @if (request()->disease == $disease->id) selected @endif >{{ $disease->name }}</option>
                                @empty
                                <option value="">No Disease Found</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="text-danger" for="availablity">Availablity</label>
                            <input type="date" id="availablity" name="date" value="{{ request()->date }}" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary" name="filter" style="margin-top: 25px;">search</button>
                    </div>

                </form>
            </div>


            <div class="about-info">
                <div class="col-md-12 col-sm-6">
                    <h2 class="wow fadeInUp" data-wow-delay="0.1s">Available Doctor</h2>
                </div>
            </div>
            <div class="clearfix"></div>
            @forelse ($appointments as $key => $appointment)
            <div class="col-md-4 col-sm-6">
                <div class="team-thumb wow fadeInUp" data-wow-delay="0.2s">
                    <img src=" @if ($appointment->doctor->image)  {{ asset('uploads/') . $appointment->doctor->image }} @else {{  getPlaceholderImage() }}  @endif " class="img-responsive" alt="">
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
                </div><br>
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