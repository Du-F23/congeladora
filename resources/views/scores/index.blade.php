@extends('layouts.app')
@section('title')
    {{ __('Scores') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary rounded pt-4 pb-3">
                        <h3 class="text-white text-capitalize ps-3 font-weight-medium ml-lg-4">{{ __('Scores') }}</h3>
                    </div>
                </div>
                <div class="card-body mt-auto">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('Team') }}</th>
                                <th>{{ __('Acronym') }}</th>
                                <th>{{ __('Games Played') }}</th>
                                <th>{{ __('Wins') }}</th>
                                <th>{{ __('Draw') }}</th>
                                <th>{{ __('Loses') }}</th>
                                <th>{{ __('Goals Matched') }}</th>
                                <th>{{ __('Goals Conceded') }}</th>
                                <th>{{ __('Difference Goals') }}</th>
                                <th>{{ __('Points') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($scores as $team)
                                <tr>
                                    <td><p class="font-weight-medium text-xl">{{ $loop->index + 1 }}</p></td>
                                    <td>
                                        <img src="{{ asset('storage/'.$team->team->team)}}" alt="{{$team->team->name}}"/>
                                        <h2 class="font-weight-medium">{{ $team->team->name }}</h2>
                                    </td>
                                    <td><h3 class="font-weight-medium text-body">{{ $team->team->   acronym }}</h3></td>
                                    <td><h3 class="text-center">{{ $team->wins + $team->loses + $team->draw }}</h3></td>
                                    <td><h3 class="text-center">{{ $team->wins }}</h3></td>
                                    <td><h3 class="text-center">{{ $team->draw }}</h3></td>
                                    <td><h3 class="text-center">{{ $team->loses }}</h3></td>
                                    <td><h3 class="text-center">{{ $team->goals_team }}</h3></td>
                                    <td><h3 class="text-center">{{ $team->goals_conceded }}</h3></td>
                                    <td><h3 class="text-center">{{ $team->goals_team - $team->goals_conceded}}</h3></td>
                                    <td><h3 class="text-center">{{ $team->points }}</h3></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal fade" id="deleteTeam" tabindex="-1"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Delete Team') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <form action="" id="deleteForm" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <p id="banner">{{ __('Are you sure you want to delete this record?') }}</p>
                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary" type="button"
                                                            data-bs-dismiss="modal">{{ __('Cancel')}}
                                                    </button>
                                                    <button class="btn btn-danger" type="submit">{{ __('Delete Team') }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <script type="application/javascript">
                // hace una peticion ajax para obtener la informacion de la moto
                function deleteTeam(id) {
                    let form = document.getElementById('deleteForm')
                    form.action = route('teams.delete', id)
                    $.ajax({
                        url: route('teams.json', id),
                        type: 'GET',
                        success: function (response) {
                            // console.log(response.name)
                            $('#banner').html(`{{__('Are you sure you want to delete this record?')}}` + ' ' + response.name);
                        }
                    })
                }
            </script>
    </div>
@endsection
