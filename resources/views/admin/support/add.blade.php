@extends('admin.layouts.master')
@section('content')
<div class=" d-flex flex-column flex-column-fluid" id="kt_content">
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
    <div class="p-6">
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
            <form class="form-horizontal" id="frmAdd" action="{{ route('support-add') }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="from_user_id">From User:<span class="required">*</span></label>
                                <div>
                                    <select class="form-control city custom-select required" name="from_user_id"
                                        id="from_user_id" required>
                                        <option value="">Select From User Name</option>
                                        @foreach ($users as $res)
                                        <option value="{{$res->id}}">{{$res->firstname}} {{$res->lastname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('from_user_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="to_user_id">To User:<span class="required">*</span></label>
                                <div>
                                    <select class="form-control city custom-select required" name="to_user_id"
                                        id="to_user_id" required>
                                        <option value="">Select To User Name</option>
                                        @foreach ($users as $res)
                                        <option value="{{$res->id}}">{{$res->firstname}} {{$res->lastname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('from_user_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="support_name">Subject<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="support_name" id="support_name" class="form-control"
                                        value="{{ old('support_name') }}" placeholder="Support Name" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description"> Description <span class="required">*</span></label>
                                <div>
                                    <textarea type="text" class="form-control required ckeditor" name="description"
                                        placeholder="Description" rows="20" id="kt-ckeditor-5"></textarea>

                                </div>
                            </div>
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