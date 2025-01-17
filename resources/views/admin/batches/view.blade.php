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
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">Course</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0">{{$batche->title ?? ''}}</p>
                                        </div>
                                    </div>
                                    <div class="separator separator-solid my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">Start Date</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0"> {{date('d F, Y H:i', strtotime($batche->start_date)); }}</p>
                                        </div>
                                    </div>
                                    <div class="separator separator-solid my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">End date</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0">{{date('d F, Y H:i', strtotime($batche->end_date)); }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="separator separator-solid my-3"></div>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="text-dark-75 font-weight-bolder font-size-lg mb-0">Description</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted font-weight-bold text-hover-warning mb-0">{!!$batche->description!!}</p>
                                        </div>
                                    </div>
                                 
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    
                </div>
            </div>
            <!--end::Card-->
            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h1 class="ml-2 mb-3 text-dark text-capitalize">Batch content </h1>
                    </div>
                    <div class="card-header">
                        <div class="card-title">
                        </div>
                        <div class="card-toolbar">
                            <!--begin::Button-->
                            <a href="{{ route('batche-content-add-form', $batche->id) }}" class="btn btn-warning font-weight-bolder">
                            <i class="la la-plus"></i>New Record</a>
                            <!--end::Button-->
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!--begin: Datatable-->
                  
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Content type</th>
                                <th>Video Link</th>
                                <th>Description</th>
                                <th>Status</th>

                                <th width="105" class="no-sort text-center">Actions</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr class="filter">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="title"></td>
                                <td>
                                    <select class="form-control form-control-sm form-filter kt-input" title="Select" name="content_type">
                                        <option value="">Select</option>
                                        <option value="media" >Media</option>
                                        <option value="desc" >Description</option>
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    <select class="form-control form-control-sm form-filter kt-input" title="Select" name="status">
                                        <option value="">Select</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </td>
                               
                                <td>
                                    <button class="btn btn-light-warning font-weight-bolder btn-sm filter-submit"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
                                    <button class="btn btn-secondary btn-sm  mt-0 filter-cancel reset-btn search-btn"><span><i class="la la-close"></i><span>Reset</span></span></button>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <!--end: Datatable-->
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

        var url =  '{{config('constants.ADMIN_URL')}}batches-content/list-ajax';
        DataTables.init('#datatable_ajax', url);
    });


  </script>
@stop
@stop

