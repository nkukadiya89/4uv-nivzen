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
        <div class="container">

            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-body">
                   {{-- User Details --}}
                    <div class="d-flex  justify-content-between">
                        <div class="w-100">
                            {{-- <h1 class="ml-2 mb-3 text-dark text-capitalize">{{$batche->title}}</h1> --}}
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">Name</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0"> {{$prospect->name ?? '' }}</p>
                                        </div>
                                    </div>
                                    <div class="separator separator-solid my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">Email</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0">{!!$prospect->email!!}</p>
                                        </div>
                                    </div>
                                    <div class="separator separator-solid my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">Mobile</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0">{{$prospect->mobile_no ?? '' }}</p>
                                        </div>
                                    </div>

                                    <div class="separator separator-solid my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">Address</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0">{!!$prospect->address!!}</p>
                                        </div>
                                    </div>

                                    <div class="separator separator-solid my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">Area</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0">{!!$prospect->area!!}</p>
                                        </div>
                                    </div>

                                    <div class="separator separator-solid my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">City</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0">{!!$prospect->city!!}</p>
                                        </div>
                                    </div>

                                    <div class="separator separator-solid my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">State</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0">{!!$prospect->state!!}</p>
                                        </div>
                                    </div>


                                 
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


    });


  </script>
@stop
@stop

