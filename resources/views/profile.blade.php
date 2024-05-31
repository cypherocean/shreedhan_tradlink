@extends('layouts.app')

@section('title')
Profile
@endsection

@section('style')
@endsection

@section('content')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Profile</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a> </li>
                                <li class="breadcrumb-item active"> Edit </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    <!-- profile -->
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Edit Profile</h4>
                        </div>
                        <div class="card-body py-2 my-25">
                            <!-- form -->
                            <form class="validate-form mt-2 pt-50" id="form" action="{{ route('profile.update') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ auth()->user()->id }}">
                                <div class="row">
                                    <div class="d-flex align-items-center flex-row">
                                        <div class="col-2">
                                            @if($user->profile)
                                            <img class="img-fluid rounded mt-3 mb-2 previewImage" src="{{ url('/uploads/users/').'/'.$user->profile }}" height="110" width="110" alt="User avatar">
                                            @else
                                            <img class="img-fluid rounded mt-3 mb-2 previewImage" src="{{ asset('app-assets/images/portrait/small/avatar-s-11.jpg') }}" height="110" width="110" alt="User avatar">
                                            @endif
                                        </div>
                                        <div class="col-10">
                                            <div class="m-1">
                                                <label for="customFile" class="form-label">Profile pic</label>
                                                <input class="form-control" type="file" id="customFile" name="customFile" aria-invalid="false">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" data-msg="Please enter name" value="{{ $user->name ?? '' }}" />
                                        <span class="kt-form__help help-block name"></span>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="john@mail.com" value="{{ $user->email }}" />
                                        <span class="kt-form__help help-block email"></span>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                                        <p><small class="text-muted">Leave blank if you don't want to update password.</small></p>
                                        <span class="kt-form__help help-block password"></span>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="c_password">Confirm Password</label>
                                        <input type="password" class="form-control" id="c_password" name="c_password" placeholder="Conform Password" />
                                        <p><small class="text-muted">Confirm Password must be same as password.</small></p>
                                        <span class="kt-form__help help-block c_password"></span>
                                    </div>

                                    <div class="col-12">
                                        <button id="submit-button" type="submit" class="btn btn-primary round waves-effect">Save changes</button>
                                        <a href="{{ route('dashboard') }}">
                                            <button type="button" class="btn btn-outline-secondary round waves-effect">Discard</button>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <!--/ form -->
                        </div>
                    </div>
                    <!--/ profile -->
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@section('script')
<script>
    const defaultImage = "{{ asset('app-assets/images/portrait/small/avatar-s-11.jpg') }}";
</script>
<script type="text/javascript" src="{{ asset('project/profile/index.js') }}"></script>
@endsection