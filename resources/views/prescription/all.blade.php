@extends('admin.layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (Session::has('message'))
            <div class="alert bg-success alert-success text-white text-center" role="alert">
                {{ Session::get('message') }}
            </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                Total Prescription : {{ $bookings->count() }}
                            </div>
                            <div class="col-6">
                                <form action="{{ route('booking') }}" method="GET" id="form">
                                    <input type="date" class="form-control" name="date" value="{{ request()->date }}" onchange="formSubmit()" autocomplete="off">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Photo</th>
                                <th scope="col">Date</th>
                                <th scope="col">User</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Gender</th>
                                <th scope="col">Doctor</th>
                                <th scope="col">Prescription</th>
                                <th scope="col">Prescription</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $key=>$booking)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <img src="@if ($booking->user->image)
                                    {{ $booking->user->image }}
                                @else
                                    {{ getPlaceholderImage() }}
                                @endif" width="80">
                                </td>
                                </td>
                                <td>{{ $booking->date }}</td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->user->email }}</td>
                                <td>{{ $booking->user->phone_number }}</td>
                                <td>{{ $booking->user->gender }}</td>
                                <td>{{ $booking->doctor->name }}</td>
                                <td>
                                    <ol style="list-style-type: disc">
                                        @forelse ($booking->attachments as $attachment)
                                        <li>
                                            <div class="d-flex align-items-center">
                                                <span class="text-secondary">
                                                    @if ($attachment->upload_by == 'doctor')
                                                    {{ $attachment->doctor->name }}:
                                                    @else
                                                    {{ Auth::user()->name }}:
                                                    @endif
                                                </span>
                                                <span class="ml-2">
                                                    <a href="{{ $attachment->attachment_url }}" target="_blank" class="text-primary" download="{{ $attachment->name }}">{{ $attachment->name }}</a>
                                                </span>
                                            </div>
                                        </li>
                                        @empty
                                        <li class="text-danger">No reports uploaded</li>
                                        @endforelse
                                    </ol>
                                </td>
                                <td>
                                    @php
                                    $hasPrescription = hasPrescription(date('m-d-Y'), $booking->user->id, $booking->doctor->id);
                                    @endphp
                                    @if (!$hasPrescription)
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{ $booking->id }}">
                                        Prescribe
                                    </button>
                                    @include('prescription.form')

                                    @else
                                    <a href="{{ route('prescription.show', [$booking->user_id, $booking->date]) }}" class="btn btn-info">View</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <td>There is no patient!</td>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- MODAL FORM --}}
@include('prescription.form')

@endsection