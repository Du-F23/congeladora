@extends('layouts.app')

@section('title')
    {{ __('Dashboard') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="card-body mt-auto">
                    <div class="row">
                        @foreach($soccer as $match)
                            <a href="{{ route('matches.show', Vinkla\Hashids\Facades\Hashids::encode($match->id) ) }}" style="text-decoration: none; color: #0e1014">
                            <div class="card d-flex align-items-center" style="background-color: #d5d0d0; margin: 4px">
                                <div class="card-body" style="width: 300px">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-center ml-lg-2">
                                            <img src="{{ asset('storage/'.$match->team_local->team)}}" alt="{{$match->team_local->name}}" width="100" height="100" class="rounded-full"/>
                                            <p class="text-2xl font-bold text-capitalize">{{ $match->team_local->name }}</p>
                                            <h3>{{ $match->team_local_goals }}</h3>
                                        </div>
                                        <div class="text-center">
                                            <p class="font-weight-medium">VS</p>
                                        </div>
                                        <div class="text-center mr-lg-2">
                                            <img src="{{ asset('storage/'.$match->team_visit->team)}}" alt="{{$match->team_visit->name}}" width="100" height="100" class="rounded-full"/>
                                            <p class="text-2xl font-bold text-capitalize">{{ $match->team_visit->name }}</p>
                                            <h3>{{ $match->team_visit_goals }}</h3>
                                        </div>
                                    </div>
                                    <label class="badge badge-info">Faltan {{ \Carbon\Carbon::now()->diff($match->dayOfMatch)->days }} días para el partido.</label>
                                </div>
                            </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
