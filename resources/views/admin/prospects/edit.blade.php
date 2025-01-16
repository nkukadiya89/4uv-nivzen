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
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <!--begin::Form-->
                        <form class="form-horizontal" id="frmEdit"  action="{{ route('prospect-edit',$prospect->id) }}" >
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label  for="name">Name<span class="required">*</span></label>
                                            <div>
                                                <input id="name" type="text" class="form-control required" name="name" placeholder="Name" value="{{$prospect->name}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="email">Email ID<span class="required">*</span></label>
                                            <div>
                                                <input type="email" name="email" id="email" class="form-control" value="{{ $prospect->email }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label  for="mobile_no">Mobile No<span class="required">*</span></label>
                                            <div>
                                                <input type="text" name="mobile_no" id="mobile_no" class="form-control" value="{{ $prospect->mobile_no }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label  for="address">Address<span class="required">*</span></label>
                                            <div>
                                                <input type="text" name="address" id="address" class="form-control" value="{{ $prospect->address }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="area">Area<span class="required">*</span></label>
                                            <div>
                                                <input type="text" name="area" id="area" class="form-control" value="{{ $prospect->area }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="city">City<span class="required">*</span></label>
                                            <div>
                                                <input type="text" name="city" id="city" class="form-control" value="{{ $prospect->city }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label  for="state">State<span class="required">*</span></label>
                                            <div>
                                                <input type="text" name="state" id="state" class="form-control" value="{{ $prospect->state }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-4 col-lg-3">
                                        <div class="form-group">
                                            <label for="country">Country<span class="required">*</span></label>
                                            <div>
                                                <input type="text" name="country" id="country" class="form-control" value="{{ $prospect->country }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end">

                                <a href="{{ route('prospects-manage') }}" class="btn btn-secondary mr-2">Cancel</a>
                                <button type="submit" class="btn btn-primary ">Submit</button>

                            </div>
                            <!-- /.card-footer -->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Card-->
                </div>
            </div>
        </div>

        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

@section('custom_js')
<script>
    $('#description').summernote({
            height: 200,
    });
    $(document).ready(function() {

        @if(Session::has('success-message'))
            toastr.info("{{ session('success-message') }}");
        @endif
    });
  </script>
@stop

@stop

