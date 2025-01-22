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
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <!--begin::Form-->
                        <form action="{{ url('backend/permissions') }}" method="POST"  id="frmAdd">

                            <div class="card-body">
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="Permission Name">Permission Name<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="permission_name" type="text" class="form-control required" name="permission_name" value="{{ old('permission_name') }}" placeholder="Permission Name" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="model_name">Model<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <select class="form-control city custom-select required" name="model_name"
                                                id="model_name" required>
                                            <option value="">Select Model</option>
                                            @foreach($models as $model)
                                                <option value="{{ $model }}">{{ $model }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('model_name')
                                     <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                        <a href="{{ url('backend/permissions') }}" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
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
   
    $(document).ready(function() {

        @if(Session::has('success-message'))
            toastr.info("{{ session('success-message') }}");
        @endif
    });
  </script>
@stop

@stop

