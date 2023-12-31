@extends('layouts.app')
@section('title')
    {{ __('Team') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                </div>
                <div class="card-body mt-auto">
                    <div class="row">
                    <div class="col-md-5 m-2">
                        <h1 class="font-weight-medium">{{ __('Team') }}: {{$team->name}}</h1>
                        <h2 class="font-weight-medium text-gray m-3">{{ $team->acronym }}</h2>
                        <h3 class="font-weight-light ">Capitan: {{ $team->capitan->name }}</h3>

                        <div class="card mt-5">
                            <div class="p-0 position-relative mt-n4 mx-3">
                                <div class="rounded pt-4 pb-3">
                                    <h3 class="text-capitalize ps-3 font-weight-medium text-center">{{ __('Players') }}</h3>
                                </div>
                            </div>
                            <div class="card-body mt-auto">
                                <div class="row">
                                @foreach($team->players as $player)
                                    <div class="card">
                                        <div class="card-body">
                                            <img src="{{ asset('storage/'.$player->photo)}}" alt="{{$player->name}}" width="100" height="100" class="rounded-full"/>
                                            <p class="text-2xl font-bold text-capitalize">{{ $player->name }}</p>
                                            <p class="text-gray-500 text-xl font-weight-medium">{{ __('Number') }}: {{ $player->number }}</p>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset('storage/'.$team->team)}}" alt="{{$team->name}}" width="400" height="400"/>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
