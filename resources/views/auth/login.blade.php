@extends('auth.layouts.app')
@section('title')
Login
@endsection
@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="auth-wrapper auth-basic px-2">
                <div class="auth-inner my-2">
                    <!-- Login basic -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <a href="javascript:void(0)" class="brand-logo">
                                <img src="{{ asset('app-assets/images/logo/logo.png') }}" alt="logo" width="150px">
                            </a>

                            <h4 class="card-title mb-1">Welcome to Shreedhan Tradelinks! 👋</h4>
                            <p class="card-text mb-2">Please sign-in to your account and start the day</p>

                            <form class="auth-login-form mt-2" action="{{ route('signIn') }}" method="POST">
                                @csrf
                                @method('post')
                                <div class="mb-1">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="john@example.com" aria-describedby="email" tabindex="1" autofocus />
                                </div>

                                <div class="mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Password</label>
                                        <a href="auth-forgot-password-basic.html">
                                            <small>Forgot Password?</small>
                                        </a>
                                    </div>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge" id="password" name="password" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="login-password" />
                                        <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                    </div>
                                </div>
                                <div class="mb-1">
                                </div>
                                <button class="btn btn-primary w-100" tabindex="4">Sign in</button>
                            </form>
                        </div>
                    </div>
                    <!-- /Login basic -->
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->
@endsection