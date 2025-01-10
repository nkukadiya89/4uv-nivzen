@extends('admin.layouts.master')
@section('content')
    <div class="d-flex flex-column flex-column-fluid" id="kt_content">
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
        <div class="p-6 flex-fill">
            <!--begin::Container-->

            <div class="card card-custom gutter-b example example-compact">
                <!--begin::Form-->
                <form class="form-horizontal" id="frmAdd" method="POST" action="{{ route('training-add') }}" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="batch_id">Name<span class="required">*</span></label>
                                    <div>
                                        <input id="name" type="text" class="form-control required" name="name" value="{{ old('name') }}" placeholder="Name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group ">
                                    <label for="title">Upload Videos<span class="required">*</span></label>
                                    <div>
                                        <input type="file" name="videos[]" id="videos" class="form-control" multiple accept="video/*">
                                    </div>
                                </div>
                                @error('videos')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer d-flex justify-content-end">



                        <a href="{{ route('trainings-manage') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary ml-2">Submit</button>

                        <!-- /.card-footer -->
                </form>
                <!--end::Form-->
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
