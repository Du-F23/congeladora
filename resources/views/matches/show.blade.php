@php use Carbon\Carbon; @endphp
@extends('layouts.app')
@section('title')
    {{ __('Soccer Match') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                </div>
                <div class="card-body mt-auto">
                    <div class="row">
                        <div class="col-md-12 m-2">
                            <h1 class="font-weight-medium">{{ __('Day Of Match') }}
                                : {{Carbon::parse($match->dayOfMatch)->locale(app()->getLocale())->translatedFormat('D, d M Y H:i A')}}</h1>
                            <div class="card mt-5">
                                <div class="p-0 position-relative mt-n4 mx-3">
                                    <div class="rounded pt-4 pb-3">
                                        <h3 class="text-capitalize ps-3 font-weight-medium text-center">{{ __('Teams') }}</h3>
                                    </div>
                                </div>
                                <div class="card-body mt-auto">
                                    <div class="row">
                                        <div class="d-flex justify-content-between align-items-center col-lg-12">
                                            <div class="col-lg-5">
                                                <div class="text-center ml-lg-2">
                                                    <img src="{{ asset('storage/'.$match->team_local->team)}}"
                                                         alt="{{$match->team_local->name}}" width="300" height="300"
                                                         class="rounded-full"/>
                                                    <h2 class="text-2xl font-weight-medium text-capitalize">{{ $match->team_local->name }}</h2>
                                                    <h3>{{ $match->team_local_goals }}</h3>

                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label" for="player_id">{{ __('Add Goals') }}</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" name="player_id" id="player_id">
                                                                @foreach($team_local_users as $player_local)
                                                                    <option value="{{ $player_local->id }}">{{ $player_local->name }}</option>
                                                                @endforeach
                                                                @error('player_id')
                                                                <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="text-center">
                                                    <h2 class="font-weight-medium">VS</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="text-center mr-lg-2">
                                                    <img src="{{ asset('storage/'.$match->team_visit->team)}}"
                                                         alt="{{$match->team_visit->name}}" width="300" height="300"
                                                         class="rounded-full"/>
                                                    <h2 class="text-2xl font-weight-medium text-capitalize">{{ $match->team_visit->name }}</h2>
                                                    <h3>{{ $match->team_visit_goals }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
