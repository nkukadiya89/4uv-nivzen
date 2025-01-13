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
                            <form class="form-horizontal" id="frmAdd"  action="{{ route('support-store') }}" >
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label" for="from_user_id">From User:<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <select class="form-control city custom-select required" name="from_user_id" id="from_user_id" required>
                                                <option value="">-- Select From User Name --</option>
                                                @foreach ($users as $k=>$res)
                                                    <option value="{{$k}}" >{{$res}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('from_user_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label" for="to_user_id">To User:<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <select class="form-control city custom-select required" name="to_user_id" id="to_user_id" required>
                                                <option value="">-- Select To User Name --</option>
                                                @foreach ($users as $k=>$res)
                                                    <option value="{{$k}}" >{{$res}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('from_user_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label" for="support_name">Support Name<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input type="text" name="support_name" id="support_name" class="form-control" value="{{ old('support_name') }}" placeholder="Support Name" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="description"> Description <span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <textarea type="text" class="form-control required ckeditor" name="description" placeholder="Description" rows="20" id="kt-ckeditor-5"></textarea>

                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer d-flex justify-content-end">

                                            <a href="{{ route('support-manage') }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Submit</button>

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

