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
                        <form class="form-horizontal" name="frmAdd" id="frmAdd" action="{{ config('constants.ADMIN_URL')}}site-settings" >

                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="ran_exchange_rate">Ran Exchange Rate <span class="required">*</span></label>
                                    <div class="col-lg-6">
                                    <input id="" type="text"
                                        class="form-control required"
                                        name="ran_exchange_rate" value="{{ old('ran_exchange_rate') ? old('ran_exchange_rate') : $site->ran_exchange_rate }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="admin_fees">Admin Fees <span class="required">*</span></label>
                                    <div class="col-lg-6">
                                    <input id="" type="text"
                                        class="form-control rqquired"
                                        name="admin_fees" value="{{ old('admin_fees') ? old('admin_fees') : $site->admin_fees }}">
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="vat">Vat <span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="" type="text"
                                            class="form-control required"
                                            name="vat" value="{{ old('vat') ? old('vat') : $site->vat }}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="vat">Mobile Number <span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="" type="text"
                                        class="form-control required"
                                        name="mobile_number" value="{{ old('mobile_number') ? old('mobile_number') : $site->mobile_number }}">
                                    </div>

                                </div>
                             
                            </div>
                        </div>
                            <div class="card card-custom gutter-b example example-compact">
                                <div class="card-header">
                                    <h3 class="font-size-lg col-form-label card-title">Competition Terms & Conditions</h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="competition_title">Title</label>
                                        <div class="col-lg-6">
                                            <input id="competition_title" type="text" class="form-control required" name="competition_title" value="{{$site->competition_title}}" placeholder="Title">
                                        </div>
                                    </div>
                                  
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label"  for="competition_description">Description</label>
                                        <div class="col-lg-6">
                                            <textarea id="competition_description" name="competition_description" row="10" class="form-control ">{{$site->competition_description}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-6">
                                            <button type="submit" class="btn btn-warning mr-2">Submit</button>
    
                                            <a href="{{ route('event-category-list') }}" class="btn btn-secondary"> Cancel </a>
                                        </div>
                                    </div>
                                </div>
                           
                            <!-- /.card-body -->
                         
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
    $('#competition_description').summernote({
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

