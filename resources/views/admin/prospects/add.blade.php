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
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form class="form-horizontal" id="frmAdd"  action="{{ route('prospect-add') }}" >
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="name">Name<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="name" type="text" class="form-control required" name="name" value="{{ old('name') }}" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="email">Email<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                     <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Email" required>
                                    </div>
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="mobile_no">Mobile No<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control" value="{{ old('mobile_no') }}" placeholder="Mobile No" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="address">Address<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address') }}" placeholder="Address" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="area">Area<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                      <input type="text" name="area" id="area" class="form-control" value="{{ old('area') }}" placeholder="Area" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="city">City<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                    <input type="text" name="city" id="city" class="form-control" value="{{ old('city') }}" placeholder="City" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="state">State<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                     <input type="text" name="state" id="state" class="form-control" value="{{ old('state') }}" placeholder="State" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="country">Country<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                      <input type="text" name="country" id="country" class="form-control" value="{{ old('country') }}" placeholder="Country" required>
                                    </div>
                                </div>



                                
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                        <a href="{{ route('prospects-manage') }}" class="btn btn-secondary">Cancel</a>
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

