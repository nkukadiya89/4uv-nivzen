@extends('admin.layouts.master')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-2">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{$title}}</h5>
                    <!--end::Page Title-->
                    <!--begin::Actions-->
                    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200">

                    </div>

                    <!--end::Actions-->
                </div>
                <!--end::Info-->

            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Dashboard-->
                <!--begin::Row-->
                <div class="row">
                    <div class="col-lg-12">
                        <!--begin::Card-->
                        <!--begin::Form-->
                        <form class="form" id="frmEdit" action="{{ route('auth.change_password') }}" enctype="multipart/form-data" method="POST">
                            <div class="card card-custom gutter-b example example-compact">
                                <div class="card-header align-content-center">
                                    <div class="card-title">
                                    </div>
                                    <div class="p-2">
                                        <!--begin::Button-->
                                        <a href="{{ route('backend.dashboard') }}" class="btn btn-primary">
                                            Back</a>
                                        <!--end::Button-->
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label for="current_password">Current Password</label>
                                                <input type="password" class="form-control" id="current_password"
                                                       placeholder="Current Password" name="current_password"
                                                       value="{{ old('current_password') }}">
                                                @if($errors->has('current_password'))
                                                    <div class="text-danger">{{ $errors->first('current_password') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label for="password">New Password</label>
                                                <input type="password" class="form-control" id="password"
                                                       placeholder="New Password" name="password" value="{{ old('password') }}">
                                                @if($errors->has('password'))
                                                    <div class="text-danger">{{ $errors->first('password') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="form-group">
                                                <label for="password_confirmation">Confirm Password</label>
                                                <input type="password" class="form-control" id="password_confirmation"
                                                       placeholder="Confirm Password" name="password_confirmation"
                                                       value="{{ old('password_confirmation') }}">
                                                @if($errors->has('password_confirmation'))
                                                    <div class="text-danger">{{ $errors->first('password_confirmation') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- /.card-body -->
                                <div class="card-footer d-flex justify-content-end">

                                    <a href="{{ route('backend.dashboard') }}" class="btn btn-secondary mr-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Update Profile</button>

                                </div>
                                <!-- /.card-footer -->
                            </div>
                        </form>

                        <!--end::Card-->
                    </div>
                </div>
                <!--end::Row-->
                <!--end::Dashboard-->
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

