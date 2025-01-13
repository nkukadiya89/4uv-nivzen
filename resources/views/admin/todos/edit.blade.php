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
                        <form class="form-horizontal" id="frmEdit"  action="{{ route('todo-edit',$todo->id) }}" >
                            <div class="card-body">
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="name">Name<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="name" type="text" class="form-control required" name="name" placeholder="Name" value="{{$todo->name}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="date">Date<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="date" name="date" id="date" class="form-control" value="{{ $todo->date }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="time">Time<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="time" name="time" id="time" class="form-control" value="{{ $todo->time }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="customer_list">Customer List<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="customer_list" id="customer_list" class="form-control" value="{{ $todo->customer_list }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="note">Note<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3" >{{ old('note', $todo->note) }}</textarea>
                                    </div>
                                    @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end">

                                <a href="{{ route('distributors-manage') }}" class="btn btn-secondary mr-2">Cancel</a>
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
    $('#note').summernote({
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

