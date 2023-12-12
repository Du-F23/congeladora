@extends('layouts.app')
@section('title')
    {{ __('Profile') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            @if( session('status') )
                <div class="alert alert-success alert-dismissible text-dark" role="alert">
                <span class="text-sm"> <a href="javascript:" class="alert-link text-dark">Excelente</a>.
                    {{ session('status') }}.</span>
                    <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ __('Profile') }}</h4>
                            <p class="card-description">{{ __("Update your account's profile information and email address.") }}</p>
                            <span class="text-danger">* Campos requeridos</span>
                            <div class="dropdown-divider"></div>
                            <form method="post" action="{{ route('profile.update') }}" class="forms-sample">
                                @csrf
                                @method('patch')
                                <div class="form-group">
                                    <input type="text" name="id" value="{{ old('id', $user->id) }}"
                                           class="form-control" id="id" hidden>
                                </div>
                                <div class="form-group">
                                    <label for="name">{{ __('Full Name') }}</label>
                                    <input type="text" class="form-control form-control-lg" id="name"
                                           placeholder="{{ __('Full Name') }}" name="name" value="{{ old('name', $user->name) }}">
                                    @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">{{ __('Email') }}</label>
                                    <input type="email" class="form-control form-control-lg" id="email"
                                           placeholder="{{ __('Email') }}" name="email" value="{{ old('email', $user->email) }}">
                                    @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if(Auth::user()->rol_id != 1)
                                    <div class="form-group">
                                        <label for="number">{{ __('Number') }}</label>
                                        <input type="number" class="form-control form-control-lg" id="number"
                                               placeholder="{{ __('Number') }}" name="number" value="{{ old('number', $user->number) }}">
                                        @error('number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif
                                <button type="submit"
                                        class="btn btn-outline-facebook mr-2 align-items-center justify-content-center">
                                    <i class="mdi mdi-content-save mdi-16px align-middle"></i>
                                    {{ __('Update your Profile') }}
                                </button>
                                <a href="{{ route('dashboard') }}"
                                   class="btn btn-outline-danger  align-items-center justify-content-center">
                                    <i class="mdi mdi-close-circle-outline mdi-16px align-middle"></i>
                                    {{ __('Cancel') }}</a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ __('Update Password') }}</h4>
                            <p class="card-description"> {{ __('Update Password') }} </p>
                            <div class="dropdown-divider"></div>
                            <form method="post" action="{{ route('password.update') }}" class="forms-sample">
                                @csrf
                                @method('put')
                                <div class="form-group">
                                    <label for="current_password">{{ __('Current Password') }}</label>
                                    <input type="password" name="current_password" id="current_password"
                                           class="form-control" placeholder="{{ __('Current Password') }}">
                                    @error('current_password')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group w-100">
                                    <label for="password">{{ __('New Password') }}</label>
                                    <input type="password" name="password" id="password"
                                           class="form-control" placeholder="{{ __('New Password') }}">
                                    @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control" placeholder="{{ __('Confirm Password') }}">
                                    @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit"
                                        class="btn btn-outline-facebook mr-2 align-items-center justify-content-center">
                                    {{ __('Update Password') }}
                                    <i class="mdi mdi-lock-outline mdi-16px align-middle"></i>
                                </button>
                                <a href="{{ route('dashboard') }}"
                                   class="btn btn-outline-danger  align-items-center justify-content-center">
                                    <i class="mdi mdi-close-circle-outline mdi-16px align-middle"></i>
                                    {{ __('Cancel') }}</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
