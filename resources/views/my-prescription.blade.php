@extends('frontend.layouts.app')

@section('content')
<div class="container" style="margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (Session::has('message'))
            <div class="alert bg-success alert-success text-white text-center" role="alert">
                {{ Session::get('message') }}
            </div>
            @endif
            @if (Session::has('errMessage'))
            <div class="alert alert-danger">
                {{ Session::get('errMessage') }}
            </div>
            @endif

            <div class="card">
                <div class="card-header">My prescriptions: {{ $prescriptions->count() }}</div>
                <div class="card-body table-responsive-md">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Doctor</th>
                                <th scope="col">Disease</th>
                                <th scope="col">Symptoms</th>
                                <th scope="col">Medicines</th>
                                <th scope="col">Usage Instruction</th>
                                <th scope="col">Doctor's Feedback</th>
                                <th scope="col">Doctor's Feedback</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prescriptions as $prescription)
                            <tr>
                                <td>{{ $prescription->date }}</td>
                                <td>{{ $prescription->doctor->name }}</td>
                                <td>{{ $prescription->name_of_disease }}</td>
                                <td>{{ $prescription->symptoms }}</td>
                                <td>{{ $prescription->medicine }}</td>
                                <td>{{ $prescription->usage_instruction }}</td>
                                <td>{{ $prescription->feedback }}</td>
                                <td>
                                    <ol style="list-style-type: disc">
                                        @forelse ($prescription->attachments as $attachment)
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
                                <td><button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{ $prescription->booking_id }}">Upload file</button></td>
                                @include('frontend.report_form')
                            </tr>
                            @empty
                            <td>You have no prescriptions</td>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection