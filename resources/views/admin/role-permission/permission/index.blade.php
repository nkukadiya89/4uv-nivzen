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
            <div class="card card-custom">
                    
                <div class="card-header">
                    <div class="card-title">
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <a href="{{ url('backend/permissions/create') }}" class="btn btn-primary float-end ">
                            <i class="la la-plus"></i>Add Permission</a>
                        </a>

                        <!--end::Button-->
                    </div>
                </div>
                    
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <!--begin: Datatable-->
                  
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th width="105" class="no-sort text-center">Actions</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr class="filter">
                                <td></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input" name="permission_name"></td>                    
                                <td>
                                    <button class="btn btn-light-warning font-weight-bolder btn-sm filter-submit"><span><i class="la la-search"></i><span>Search</span></span></button> &nbsp;
                                    <button class="btn btn-secondary btn-sm  mb-2 filter-cancel reset-btn search-btn"><span><i class="la la-close"></i><span>Reset</span></span></button>
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

        var url =  '{{config('constants.ADMIN_URL')}}permissions/list-ajax';
        DataTables.init('#datatable_ajax', url);
    });


  </script>
@stop
@stop

