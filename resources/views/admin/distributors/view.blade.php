@extends('admin.layouts.master')
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{$title}}</h5>
                    <!--end::Page Title-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->

        </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container-fluid">

            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-header align-content-center">
                    <div class="card-title">
                    </div>
                    <div class="p-2">
                        <!--begin::Button-->
                        <a href="{{ route('distributors-manage') }}" class="btn btn-primary">
                            Back</a>
                        <!--end::Button-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Enagic Id</label>
                                <div>
                                    {{$distributor->enagic_id ?? ''}}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">First Name</label>
                                <div>
                                    {{$distributor->firstname ?? '' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Last Name</label>
                                <div>
                                    {{$distributor->lastname ?? '' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Mobile No.</label>
                                <div>
                                    {{ $distributor->phone }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Email</label>
                                <div>
                                    {{ $distributor->email }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Address</label>
                                <div>
                                    {{ $distributor->address1 }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Area</label>
                                <div>
                                    {{ $distributor->address2 }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">City</label>
                                <div>
                                    {{ $distributor->city }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">State</label>
                                <div>
                                    {{ $distributor->state }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Country</label>
                                <div>
                                    {{ $distributor->country }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Type</label>
                                <div>
                                    {{ $distributor->type }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Goal For</label>
                                <div>
                                    {{ $distributor->goal_for }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Status</label>
                                <div>
                                    {{ $distributor->distributor_status }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
@section('custom_js')
<script>
    $(document).ready(function() {

        @if(Session::has('success-message'))
            toastr.info("{{ session('success-message') }}");
        @endif

    });


  </script>
@stop
@stop
