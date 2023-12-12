@extends('layouts.guest')

@section('title')
    {{__('Login')}}
@endsection
@section('content')
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
                            </div>
                            <h4 class="font-weight-light">{{ __('Sign in to continue.') }}</h4>

                            <form method="POST" action="{{ route('login') }}" class="pt-3">
                                @csrf
                                <div class="form-group">
                                    <label for="email"></label>
                                    <input type="email" class="form-control form-control-lg" id="email"
                                           placeholder="{{ __('Email') }}" name="email">
                                    @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password"></label>
                                    <input type="password" class="form-control form-control-lg"
                                           id="password" placeholder="{{ __('Password') }}" name="password">
                                    @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"
                                            type="submit">{{ __('Login') }}</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input">
                                            {{ __('Remember me')}}
                                        </label>
                                    </div>
                                    <a href="#" class="auth-link text-black">{{ __('Forgot your password?') }}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
@endsection
