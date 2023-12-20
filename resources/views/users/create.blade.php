@extends('layouts.app')
@section('title')
    {{ __('Create User') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title">
                        {{ __('Create User') }}
                    </h3>
                    <form class="form-sample" method="post" action="{{ route('users.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('Full Name') }}</label>
                            <input type="text" class="form-control form-control-lg" id="name"
                                   placeholder="{{ __('Full Name') }}" name="name" value="{{ old('name') }}">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" class="form-control form-control-lg" id="email"
                                   placeholder="{{ __('Email') }}" name="email" value="{{ old('email') }}">
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="rol_id">Rol</label>
                                <select class="form-control" name="rol_id" id="rol_id">
                                    <option value="1">{{ __('Administrator') }}</option>
                                    <option value="2">{{ __('Referee') }}</option>
                                    <option value="3">{{ __('Captains') }}</option>
                                    @error('rol_id')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </select>
                        </div>
                        <div class="form-group" hidden id="number_player">
                            <label for="number">{{ __('Number') }}</label>
                            <input type="number" class="form-control form-control-lg" id="number"
                                   placeholder="{{ __('Number') }}" name="number" value="{{ old('number') }}">
                            @error('number')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input type="password" class="form-control form-control-lg"
                                   id="password" placeholder="{{ __('Password') }}" name="password" value="{{ old('password') }}">
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control form-control-lg"
                                   id="password_confirmation" placeholder=""
                                   name="password_confirmation" value="{{ old('password_confirmation') }}">
                            @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-2 ml-4">
                                <a class="btn btn-danger" href="{{ route('users.index') }}">{{ __('Cancel') }}</a>
                            </div>
                            <div class="col-md-9 justify-end flex justify-items-end mr-10">
                                <button class="btn btn-success" type="submit">{{ __('Create User') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let rol = document.getElementById('rol_id')
        let div = document.getElementById('number_player')

        rol.onchange = function () {
            if (rol.value === "3") {
                div.hidden = false
            }
            else {
                div.hidden = true
            }
        }
    </script>
@endsection
