@extends('layouts.app')
@section('title')
    {{ __('Teams') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin">
            @if( session('status') )
                <div class="alert alert-success alert-dismissible text-dark mb-4" role="alert">
                <span class="text-sm"> <a href="javascript:" class="alert-link text-dark">Excelente</a>.
                    {{ session('status') }}.</span>
                    <button type="button" class="btn btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
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
            <div class="card">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary rounded pt-4 pb-3">
                        <h3 class="text-white text-capitalize ps-3 font-weight-medium ml-lg-4">{{ __('List of Teams') }}</h3>
                        @if(Auth::user()->rol_id === 1)
                            <div class="float-right justify-content-end align-items-end mr-lg-5">
                                <a href="{{route('teams.create')}}" class="btn btn-primary"
                                   title="Agregar una nuevo Equipo">
                                    <i class="ti ti-plus btn-icon-prepend"></i>
                                </a>
                            </div>
                        @endif
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
                                <th>{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($teams as $team)
                                <tr>
                                    <td><p class="font-weight-medium text-xl">{{ $loop->index + 1 }}</p></td>
                                    <td>
                                        <img src="{{ asset('storage/'.$team->team)}}" alt="{{$team->name}}"/>
                                        <h2 class="font-weight-medium">{{ $team->name }}</h2>
                                    </td>
                                    <td><h3 class="font-weight-medium text-body">{{ $team->acronym }}</h3></td>
                                    <td>
                                        <a href="{{ route('teams.show', Vinkla\Hashids\Facades\Hashids::encode($team->id)) }}"
                                           class="btn btn-facebook">
                                            <i class="ti ti-eye btn-icon-prepend"></i>
                                        </a>
                                        @if(Auth::user()->rol_id === 1)
                                            <a href="{{ route('teams.edit', Vinkla\Hashids\Facades\Hashids::encode($team->id)) }}"
                                               class="btn btn-twitter">
                                                <i class="ti ti-edit btn-icon-prepend"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#deleteTeam" onclick="deleteTeam({{$team->id}})">
                                                <i class="ti ti-trash-x btn-icon-prepend"></i>
                                            </button>
                                        @endif
                                    </td>
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
                                                    <button class="btn btn-danger"
                                                            type="submit">{{ __('Delete Team') }}</button>
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
