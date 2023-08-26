@extends('frontend.layouts.app')

@section('title', 'Home | Care-Logs')

@section('custom-css')

@endsection

@section('content')
<!-- HOME -->
<section id="home" class="slider" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row">

            <div class="owl-carousel owl-theme">
                <div class="item item-first">
                    <div class="caption">
                        <div class="col-md-offset-1 col-md-10">
                            <h3>Let's make your life happier</h3>
                            <h1>Healthy Living</h1>
                            <a href="#team" class="section-btn btn btn-default smoothScroll">Meet Our Doctors</a>
                        </div>
                    </div>
                </div>

                <div class="item item-second">
                    <div class="caption">
                        <div class="col-md-offset-1 col-md-10">
                            <h3>Aenean luctus lobortis tellus</h3>
                            <h1>New Lifestyle</h1>
                            <a href="#about" class="section-btn btn btn-default btn-gray smoothScroll">More About
                                Us</a>
                        </div>
                    </div>
                </div>

                <div class="item item-third">
                    <div class="caption">
                        <div class="col-md-offset-1 col-md-10">
                            <h3>Pellentesque nec libero nisi</h3>
                            <h1>Your Health Benefits</h1>
                            <a href="#news" class="section-btn btn btn-default btn-blue smoothScroll">Read
                                Stories</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ABOUT -->
<section id="about">
    <div class="container">
        <div class="row">

            <div class="col-md-6 col-sm-6">
                <div class="about-info">
                    <h2 class="wow fadeInUp" data-wow-delay="0.6s">Welcome to Your <i class="fa fa-h-square"></i>ealth
                        Center</h2>
                    <div class="wow fadeInUp" data-wow-delay="0.8s">
                        <p>Aenean luctus lobortis tellus, vel ornare enim molestie condimentum. Curabitur lacinia
                            nisi vitae velit volutpat venenatis.</p>
                        <p>Sed a dignissim lacus. Quisque fermentum est non orci commodo, a luctus urna mattis. Ut
                            placerat, diam a tempus vehicula.</p>
                    </div>
                    <figure class="profile wow fadeInUp" data-wow-delay="1s">
                        <img src="{{asset('images/author-image.jpg')}}" class="img-responsive" alt="">
                        <figcaption>
                            <h3>Dr. Neil Jackson</h3>
                            <p>General Principal</p>
                        </figcaption>
                    </figure>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- TEAM -->
<section id="team" data-stellar-background-ratio="1">
    <div class="container">
        <div class="row">

            <div class="col-md-6 col-sm-6">
                <div class="about-info">
                    <h2 class="wow fadeInUp" data-wow-delay="0.1s">Our Doctors</h2>
                </div>
            </div>
            <div class="clearfix"></div>
            @forelse ($doctors as $key => $doctor)

            <div class="col-md-4 col-sm-6">
                <div class="team-thumb wow fadeInUp" data-wow-delay="0.2s">
                    <img src="{{ asset('images/team-image' . ($key + 1) . '.jpg') }}" class="img-responsive" alt="">
                    <div class="team-info">
                        <h3>{{ $doctor->name }}</h3>
                        <p>{{ $doctor->department }}</p>
                        <div class="team-contact-info">
                            <p><i class="fa fa-phone"></i> {{ $doctor->phone_number }}</p>
                            <p><i class="fa fa-envelope-o"></i> <a href="#">{{ $doctor->email }}</a></p>
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