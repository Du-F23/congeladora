@extends('layouts.app')
@section('title')
    {{ __('Soccer Matches') }}
@endsection

@section('content')
    <div class="row">
        @if( session('status') )
            <div class="alert alert-success alert-dismissible text-dark mb-4" role="alert">
                <span class="text-sm"> <a href="javascript:" class="alert-link text-dark">Excelente</a>.
                    {{ session('status') }}.</span>
                <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                        aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
            @if( session('statusError') )
                <div class="alert alert-danger alert-dismissible text-dark mb-4" role="alert">
                <span class="text-sm"> <a href="javascript:" class="alert-link text-dark">Error</a>.
                    {{ session('statusError') }}.</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        <div class="col-md-12 grid-margin stretch-card mt-3">
            <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary rounded pt-4 pb-3">
                        <h3 class="text-white text-capitalize ps-3 font-weight-medium ml-lg-4">{{ __('List of Teams') }}</h3>
                        @if(Auth::user()->rol_id === 1)
                            <div class="float-end justify-content-end align-items-end mr-lg-5">
                                <a href="{{route('matches.create')}}" class="btn btn-primary"
                                   title="Agregar una nuevo Equipo">
                                    <i class="ti ti-plus btn-icon-prepend"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body mt-auto">
                    <div class="row">
                        @foreach($soccer as $match)
                            <div class="card d-flex align-items-center">
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
                                </div>
                            </div>
                        @endforeach
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
