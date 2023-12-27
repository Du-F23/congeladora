@php
    use Carbon\Carbon;
@endphp

@extends('layouts.guest')
@section('title')
    {{ __('Soccer Match Assigned') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">{{ __('Day Of Match') }}: {{  Carbon::parse($match->dayOfMatch)->locale(app()->getLocale())->translatedFormat('D, d M Y h:i A') }}</h1>
            <p>{{ __('Hi') }} {{ $user->name }}, we have assigned the proposal to your account.</p>
            <br>
            <div class="card d-flex align-items-center row" style="background-color: #d5d0d0; margin: 4px">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-center ml-lg-2">
                            <img src="{{ asset('storage/'.$match->team_local->team)}}" alt="{{$match->team_local->name}}" width="100" height="100" class="rounded-full"/>
                            <h3 class="font-weight-medium text-capitalize">{{ $match->team_local->name }}</h3>
                        </div>
                        <div class="text-center">
                            <h3 class="font-weight-medium">VS</h3>
                        </div>
                        <div class="text-center mr-lg-2">
                            <img src="{{ asset('storage/'.$match->team_visit->team)}}" alt="{{$match->team_visit->name}}" width="100" height="100" class="rounded-full"/>
                            <h3 class="font-weight-medium text-capitalize">{{ $match->team_visit->name }}</h3>
                        </div>
                    </div>
                    <label class="badge badge-info">Faltan {{ \Carbon\Carbon::now()->diff($match->dayOfMatch)->days }} días para el partido.</label>
                </div>
            </div>
            <br>
            <p>For more information, please log in to the system and check the assigned proposal.</p>
            <br>
            <p>Best regards, Canchas Purificadora</p>
        </div>
    </div>
    <div class="dropdown-divider"></div>
@endsection
