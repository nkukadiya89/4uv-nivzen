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
                        <form class="form-horizontal" id="frmEdit"  action="{{ route('module-edit',$module->id) }}" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="module_id">Module Id<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="module_id" type="text" class="form-control required" name="module_id" value="{{$module->module_id}}" placeholder="Module Id" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="title">Title<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="title" type="text" class="form-control required" name="title" value="{{$module->title}}" placeholder="Title">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="description"> Description <span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <textarea type="text" class="form-control required ckeditor" name="description" placeholder="Description" rows="20" id="kt-ckeditor-5">{{$module->description}}</textarea>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="status">Status &nbsp;</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                    
                                        <input type="checkbox"   name="status"   value="1"  data-toggle="toggle" data-on="Yes" data-off="No" @if($module->status == 1)  checked @endif > 

                                    </div>

                                </div>
                                
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                        <a href="{{ route('modules-manage') }}" class="btn btn-secondary">Cancel</a>
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

