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
                        <a href="{{ route('support-manage') }}" class="btn btn-primary">
                            Back</a>
                        <!--end::Button-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Subject</label>
                                <div>
                                    {{$support->support_name}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Description</label>
                                <div>
                                    <p class="text-muted font-weight-bold text-hover-warning mb-0">{!!$support->description!!}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Request Number</label>
                                <div>
                                    {{$support->request_number}}
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

