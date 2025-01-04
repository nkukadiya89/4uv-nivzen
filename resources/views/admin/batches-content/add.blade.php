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
                        <form class="form-horizontal" id="frmAdd"  action="{{ route('batche-content-add', $id) }}" enctype="multipart/form-data">
                            <div class="card-body">
                                
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="title">Title<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="title" type="text" class="form-control required" name="title" value="{{ old('title') }}" placeholder="Title">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="title">Upload<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                
                                        <div id="upload-container">
                                            <input type="file" id="browseFile" class="form-control ">
                                        </div>
                                        <div  style="display: none" class="progress mt-3" style="height: 25px">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%; height: 100%">75%</div>
                                        </div>
                        
                                        <div class="card-footer p-4" style="display: none">
                                            <video id="videoPreview" src="" controls style="width: 100%; height: auto"></video>
                                        </div>
                                    
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="content_type">Content Type<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        
                                        <select class="form-control city custom-select required" name="content_type" id="content_type" placeholder="content type">
                                    
                                            <option value="">-- Select Content Type --</option>
                                            <option value="media" >Media</option>
                                            <option value="desc" >Description</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row d-none" id="show_media">
                                    <label  class="col-lg-3 col-form-label" for="video_link">Video link<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="video_link" type="text" class="form-control " name="video_link"  placeholder="Video link">
                                    </div>
                                </div>
                                <div class="form-group row d-none" id="show_description">
                                    <label  class="col-lg-3 col-form-label" for="description"> Description <span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <textarea type="text" class="form-control  ckeditor" name="description" placeholder="Description" rows="20" id="kt-ckeditor-5"></textarea>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="status">Status &nbsp;</label>
                                    <div class="col-lg-9 col-md-9 col-sm-12">
                                    
                                        <input type="checkbox"   name="status"   value="1"  data-toggle="toggle" data-on="Yes" data-off="No"  > 
                                    </div>

                                </div>
                                
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                        <a href="{{ route('batche-view', $id) }}" class="btn btn-secondary">Cancel</a>
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
<script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
<script>
    $('#description').summernote({
            height: 200,
    });
    $(document).ready(function() {

        @if(Session::has('success-message'))
            toastr.info("{{ session('success-message') }}");
        @endif
        
        $('#content_type').on('change', function() {
            if ($(this).val() === 'media') {
                $('#show_media').removeClass('d-none'); 
                $('#show_description').addClass('d-none'); 
                $('#video_link').addClass('required'); 
                $('#kt-ckeditor-5').removeClass('required'); 
            } else {
                $('#show_description').removeClass('d-none');
                $('#show_media').addClass('d-none');
                $('#kt-ckeditor-5').addClass('required'); 
                $('#video_link').removeClass('required'); 
            }
        });

        // FIle Upload

        let browseFile = $('#browseFile');
        let resumable = new Resumable({
            target: '{{ route('files.upload.large') }}',
            query:{_token:'{{ csrf_token() }}'} ,// CSRF token
            fileType: ['mp4'],
            chunkSize: 10*1024*1024, // default is 1*1024*1024, this should be less than your maximum limit in php.ini
            headers: {
                'Accept' : 'application/json'
            },
            testChunks: false,
            throttleProgressCallbacks: 1,
        });

        resumable.assignBrowse(browseFile[0]);

        resumable.on('fileAdded', function (file) { // trigger when file picked
            showProgress();
            resumable.upload() // to actually start uploading.
        });

        resumable.on('fileProgress', function (file) { // trigger when file progress update
            updateProgress(Math.floor(file.progress() * 100));
        });

        resumable.on('fileSuccess', function (file, response) { // trigger when file upload complete
            response = JSON.parse(response)
            $('#videoPreview').attr('src', response.path);
            $('.card-footer').show();
        });

        resumable.on('fileError', function (file, response) { // trigger when there is any error
            alert('file uploading error.')
        });


        let progress = $('.progress');
        function showProgress() {
            progress.find('.progress-bar').css('width', '0%');
            progress.find('.progress-bar').html('0%');
            progress.find('.progress-bar').removeClass('bg-success');
            progress.show();
        }

        function updateProgress(value) {
            progress.find('.progress-bar').css('width', `${value}%`)
            progress.find('.progress-bar').html(`${value}%`)
        }

        function hideProgress() {
            progress.hide();
        }
    });
  </script>
@stop

@stop

