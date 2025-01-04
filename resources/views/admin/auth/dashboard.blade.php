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
            <div class="container">
                <!--begin::Dashboard-->
                <!--begin::Row-->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="row">
                           
                        </div>
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

