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
    <div class="p-6 flex-fill">
        <div class="card card-custom gutter-b">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 ">
                        <div class="form-group">
                            <label class="customlbl">Name</label>
                            <div>

                                {{$training->name ?? ''}}
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label class="customlbl">Videos</label>
                            <div class="videoswrp">
                                @if(!empty($videoPaths))
                                @foreach($videoPaths as $video)
                                <div class="video-item">
                                    <!-- Create a video thumbnail preview -->
                                    <video width="300" height="200" controls>
                                        <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>

                                </div>
                                @endforeach
                                @else
                                <p>No videos uploaded for this training program.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
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