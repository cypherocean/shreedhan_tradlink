@extends('layouts.app')

@section('title')
Recharge
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/select/select2.min.css') }}">
<style>
    .recharge-edit{
        cursor: pointer;
    }

    .kt-form__help {
        color: red
    }
</style>
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
                        <h2 class="content-header-title float-start mb-0">Recharge</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('recharge') }}">Recharge</a> </li>
                                <li class="breadcrumb-item active"> List </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button id="add_recharge" class="btn btn-outline-primary round waves-effect">Add New</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Basic table -->
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-datatable">
                                <table id="data-table" class="datatables-ajax table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Carrier</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Basic table -->
        </div>
    </div>
</div>

<div class="modal fade" id="recharge" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1 modal-title">Add Recharge</h1>
                    <p class="modal-subtitle">Add Recharge Details.</p>
                </div>
                <form id="rechargeForm" class="row gy-1 pt-75" action="{{ route('recharge.insert') }}" method="post">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="user">Select User</label>
                        <select id="user" name="user" class="form-select" aria-label="Default select example">
                            <option selected>-- Select User --</option>
                            @foreach ($subscribers as $subscriber)
                            <option value="{{ $subscriber->id }}">{{ $subscriber->name }}</option>
                            @endforeach
                        </select>
                        <span class="user_id-error kt-form__help"></span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="carrier">Carrier</label>
                        <input type="text" id="carrier" name="carrier" class="form-control modal-edit-tax-id" placeholder="Jio" value="" />
                        <span class="carrier-error kt-form__help"></span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="number">Number</label>
                        <input type="text" id="number" name="number" class="form-control" placeholder="+91 xxx xxx xxxx" value="" />
                        <span class="number-error kt-form__help"></span>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="validity">Validity (in days)</label>
                        <input type="text" id="validity" name="validity" class="form-control" placeholder="84" value="" />
                        <span class="validity-error kt-form__help"></span>
                    </div>
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="amount">Amount</label>
                        <input type="text" id="amount" name="amount" class="form-control" placeholder="100" value="" />
                        <span class="amount-error kt-form__help"></span>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center mt-1">
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox" class="form-check-input" id="active-notification" name="active-notification" />
                                <label class="form-check-label" for="active-notification">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                                <span class="active-notification-error kt-form__help"></span>
                            </div>
                            <label class="form-check-label fw-bolder" for="active-notification">Start Subscription Notification</label>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Discard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection
@section('script')
<script src="{{ asset('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/responsive.bootstrap5.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('project/recharge/index.js') }}"></script>
@endsection