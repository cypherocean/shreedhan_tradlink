@extends('layouts.app')

@section('title')
Users
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
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
                        <h2 class="content-header-title float-start mb-0">Users</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a> </li>
                                <li class="breadcrumb-item active"> Create </li>
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
                            <h4 class="card-title">Create User</h4>
                        </div>
                        <div class="card-body py-2 my-25">
                            <!-- form -->
                            <form class="validate-form mt-2 pt-50" id="form" action="{{ route('users.insert') }}" method="post">
                                @csrf
                                <div class="row">

                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" data-msg="Please enter name" />
                                        <span class="kt-form__help help-block name"></span>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="number">Number</label>
                                        <input type="number" class="form-control" id="number" name="number" placeholder="9099089105" />
                                        <span class="kt-form__help help-block number"></span>
                                    </div>

                                    <div class="col-12 mb-1">
                                        <label class="form-label" for="carrier">Carrier</label>
                                        <select id="carrier" class="select2 form-select">
                                            <option value="">Select Carrier</option>
                                            <option value="jio">Jio</option>
                                            <option value="vi">VI</option>
                                            <option value="airtel">Airtel</option>
                                            <option value="bsnl">BSNL</option>
                                        </select>
                                        <span class="kt-form__help help-block carrier"></span>
                                    </div>
                                    <div class="col-12">
                                        <button id="submit-button" type="submit" class="btn btn-primary round waves-effect">Save changes</button>
                                        <a href="{{ route('users') }}">
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
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('project/users/create.js') }}"></script>
@endsection