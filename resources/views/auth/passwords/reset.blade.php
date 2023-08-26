@extends('frontend.layouts.app')

@section('content')
<!-- HOME -->
<section id="appointment" data-stellar-background-ratio="3">
    <div class="container">
        <div class="row">

            <div class="col-md-6 col-sm-6">
                <img src="{{ asset('/images/appointment-image.jpg')}}" class="img-responsive" alt="">
            </div>

            <div class="col-md-6 col-sm-6">
                <!-- CONTACT FORM HERE -->
                <form id="appointment-form" role="form" method="post" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <!-- SECTION TITLE -->
                    <div class="section-title wow fadeInUp" data-wow-delay="0.4s">
                        <h2>{{ __('Reset Password') }}</h2>
                    </div>

                    <div class="wow fadeInUp" data-wow-delay="0.8s">

                        <div class="col-md-12 col-sm-12">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Your Email">
                            @error('email')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <label for="password">Create Password</label>
                            <input type="password" name="password" class="form-control">
                            @error('password')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <label for="password">Confirm Password</label>
                            <input type="password" id="password-confirm" name="password_confirmation" class="form-control">
                            @error('password_confirmation')
                            <span class="invalid-feedback text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <!-- <label for="telephone">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone">
                            <label for="Message">Additional Message</label>
                            <textarea class="form-control" rows="5" id="message" name="message" placeholder="Message"></textarea> -->
                            <button type="submit" class="form-control" id="cf-submit" name="submit">Reset password</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection