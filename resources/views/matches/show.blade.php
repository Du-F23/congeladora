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
                        <div class="col-md-12 m-2 table-responsive">
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
                                                    <h2 class="text-2xl font-weight-medium text-capitalize">{{ $match->team_local->name }}</h2>
                                                    <h1>{{ $match->team_local_goals }}</h1>
                                                    <img src="{{ asset('storage/'.$match->team_local->team)}}"
                                                         alt="{{$match->team_local->name}}"
                                                         class="rounded-full card-img"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="text-center">
                                                    <h2 class="font-weight-medium">VS</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="text-center mr-lg-2">
                                                    <h2 class="text-2xl font-weight-medium text-capitalize">{{ $match->team_visit->name }}</h2>
                                                    <h1>{{ $match->team_visit_goals }}</h1>
                                                    <img src="{{ asset('storage/'.$match->team_visit->team)}}"
                                                         alt="{{$match->team_visit->name}}"
                                                         class="rounded-full card-img"/>
                                                </div>
                                            </div>
                                        </div>
                                        @if(Auth::check() &&Auth::user()->rol_id != 3 && Auth::user()->rol_id != 4)
                                        <h3>{{ __('Add Goals') }}</h3>
                                        <form method="POST"
                                              action="{{ route('matches.team_goals', $match->id) }}"
                                              class="pt-3 d-flex justify-content-between align-items-center col-lg-12">
                                            @csrf
                                            <div class="col-lg-5">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label"
                                                           for="player_id_local">{{ __('Players') }}</label>
                                                    <div class="col-lg-9">
                                                        <select class="js-example-basic-multiple w-100"
                                                                multiple="multiple" name="player_id_local[]"
                                                                id="player_id_local">
                                                            @foreach($team_local_users as $player_local)
                                                                <option
                                                                    value="{{ $player_local->id }}">{{ $player_local->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('player_id_local')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label"
                                                           for="goals_local">{{ __('Goals') }}</label>
                                                    <div class="col-sm-9">
                                                        <input type="number"
                                                               class="form-control form-control-lg" id="goals_local"
                                                               name="goals_local">
                                                        @error('goals_local')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label"
                                                           for="player_id_visit">{{ __('Players') }}</label>
                                                    <div class="col-lg-9">
                                                        <select class="js-example-basic-multiple w-100"
                                                                multiple="multiple" name="player_id_visit[]"
                                                                id="player_id_visit">
                                                            @foreach($team_visit_users as $player_visit)
                                                                <option
                                                                    value="{{ $player_visit->id }}">{{ $player_visit->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('player_id_visit')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label"
                                                           for="goals_visit">{{ __('Goals') }}</label>
                                                    <div class="col-sm-9">
                                                        <input type="number"
                                                               class="form-control form-control-lg" id="goals_visit"
                                                               name="goals_visit">
                                                        @error('goals_visit')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-9 justify-end flex justify-items-end mr-10">
                                                    <button class="btn btn-success"
                                                            type="submit">{{ __('Add Goals') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                        @endif
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
