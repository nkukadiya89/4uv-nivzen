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
                        <form class="form-horizontal" id="frmEdit"  action="{{ route('training-edit',$training->id) }}" >
                            <div class="card-body">
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="name">Name<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="name" type="text" class="form-control required" name="name"  placeholder="Name" value="{{$training->name}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="firstname">First Name<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <label for="title">Upload Videos<span class="required">*</span></label>
                                        <div>
                                            <input type="file" name="videos[]" id="videos" class="form-control" multiple accept="video/*">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-9">
                                    <div class="videos">
                                        @if (!empty($videoPaths))
                                            <div class="form-group">
                                                <label>Current Videos</label>
                                                <ul id="current-videos" class="list-inline">
                                                    @foreach ($videoPaths as $index => $video)
                                                        <li class="list-inline-item" id="video-{{ $index }}">
                                                            <video width="300" height="200" controls>
                                                                <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                            {{--<a href="{{ asset('storage/' . $video) }}" target="_blank">{{ basename($video) }}</a>--}}
                                                            <button type="button" class="btn btn-danger btn-sm remove-video" data-index="{{ $index }}" data-video="{{ $video }}">Remove</button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                        <a href="{{ route('trainings-manage') }}" class="btn btn-secondary">Cancel</a>
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
    $(document).on('click', '.remove-video', function() {
        var videoIndex = $(this).data('index');
        var videoPath = $(this).data('video');

        // Confirm before deletion
        if (confirm("Are you sure you want to delete this video?")) {
            $.ajax({
                url: '{{ route('trainings.remove_video', $training->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    video_path: videoPath
                },
                success: function(response) {
                    if (response.success) {
                        // Remove the video from the UI
                        $('#video-' + videoIndex).remove();
                        alert("Video removed successfully!");
                    } else {
                        alert("Error removing video.");
                    }
                },
                error: function() {
                    alert("An error occurred.");
                }
            });
        }
    });
  </script>
@stop

@stop

