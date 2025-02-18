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

            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-header align-content-center">
                    <div class="card-title">
                    </div>
                    <div class="p-2">
                        <!--begin::Button-->
                        <a href="{{ route('prospects-manage') }}" class="btn btn-primary">
                            Back</a>
                        <!--end::Button-->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Name</label>
                                <div>
                                    {{$prospect->name ?? ''}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Email</label>
                                <div>
                                    {{$prospect->email ?? ''}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Mobile No</label>
                                <div>
                                    {{$prospect->mobile_no ?? ''}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Address</label>
                                <div>
                                    {{$prospect->address ?? ''}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">Area</label>
                                <div>
                                    {{$prospect->area ?? ''}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">City</label>
                                <div>
                                    {{$prospect->city ?? ''}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label class="customlbl">State</label>
                                <div>
                                    {{$prospect->state ?? ''}}
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <h4>Status History</h4>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($prospect->statuses as $status)
                                    <tr>
                                        <td>{{ $status->status }}</td>
                                        <td>{{ \Carbon\Carbon::parse($status->date)->format('d-m-Y') }}</td>
                                        <td>{{ $status->remarks }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
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


    });


  </script>
@stop
@stop

