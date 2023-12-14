@extends('layouts.app')
@section('title')
    {{ __('Create Team') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">{{ __('Create Soccer Match') }}</h3>
                    <form class="form-sample" method="post" action="{{ route('matches.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="team_local_id">{{ __('Local Team') }}</label>
                                    <div class="col-sm-9">
                                    <select class="form-control" name="team_local_id" id="team_local_id">
                                        <option>{{ __('Local Team') }}</option>
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                        @error('team_local_id')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="local_team">{{ __('Image') }}</label>
                                    <div class="col-sm-9">
                                        <div id="local_team" class="text-center ml-10 align-center self-center">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="team_visit_id">{{ __('Visit Team') }}</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="team_visit_id" id="team_visit_id">
                                            <option>{{ __('Visit Team') }}</option>
                                            @foreach($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                            @error('team_visit_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="team_visit">{{ __('Image') }}</label>
                                    <div class="col-sm-9">
                                        <div id="team_visit" class="text-center ml-10 align-center self-center">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="dayOfMatch">{{ __('Category') }}</label>
                                    <div class="col-sm-9">
                                        <input type="datetime-local" name="dayOfMatch" id="dayOfMatch" class="form-control form-control-lg">
                                        @error('dayOfMatch')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label" for="capitan_id">{{ __('Referee') }}</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="referee_id" id="capitan_id">
                                            @foreach($referees as $referee_id)
                                                <option value="{{ $referee_id->id }}">{{ $referee_id->name }}</option>
                                            @endforeach
                                            @error('referee_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 ml-4">
                                <a class="btn btn-danger" href="{{ route('teams.index') }}">{{ __('Cancel') }}</a>
                            </div>
                            <div class="col-md-9 justify-end flex justify-items-end mr-10">
                                <button class="btn btn-success" type="submit">{{ __('Create Soccer Match') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        let team_local_id = document.getElementById("team_local_id");
        team_local_id.onchange = function (e) {
            $.ajax({
                url: route('teams.json', team_local_id.value),
                type: 'GET',
                success: function (response) {
                    document.getElementById('local_team').innerHTML = '';
                    // console.log(response.name)
                    {{--$('#banner').html(`{{__('Are you sure you want to delete this record?')}}` + ' ' + response.name);--}}
                    let imageUrl = response.team // Assuming 'logo_url' is the key holding the image URL in the response
                    let imageElement = document.createElement('img');
                    imageUrl = `http://localhost:8000/storage/${imageUrl}`
                    imageElement.setAttribute('src', imageUrl);
                    imageElement.width = 150;
                    imageElement.height = 150;
                    // Append the image element to the #local_team container
                    document.getElementById('local_team').appendChild(imageElement);
                }
            })
        }

        let team_visit_id = document.getElementById("team_visit_id");
        team_visit_id.onchange = function (e) {
            $.ajax({
                url: route('teams.json', team_visit_id.value),
                type: 'GET',
                success: function (response) {
                    document.getElementById('team_visit').innerHTML = '';
                    // console.log(response.name)
                    {{--$('#banner').html(`{{__('Are you sure you want to delete this record?')}}` + ' ' + response.name);--}}
                    let imageUrl = response.team // Assuming 'logo_url' is the key holding the image URL in the response
                    let imageElement = document.createElement('img');
                    imageUrl = `http://localhost:8000/storage/${imageUrl}`
                    imageElement.setAttribute('src', imageUrl);
                    imageElement.width = 150;
                    imageElement.height = 150;
                    // Append the image element to the #local_team container
                    document.getElementById('team_visit').appendChild(imageElement);
                }
            })
        }
    </script>
@endsection
