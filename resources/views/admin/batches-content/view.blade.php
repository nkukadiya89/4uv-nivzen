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
                        <input type="file" id="fileUpload" />
                        <button id="uploadButton">Upload</button>
                        <div id="results"></div>
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
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.930.0.min.js"></script>

<script>
    $(document).ready(function() {
      // Configure AWS
     
      $('#uploadButton').click(function() {
        const file = $('#fileUpload')[0].files[0];

        if (!file) {
          alert('Please select a file first');
          return;
        }

        const params = {
          Bucket: 'viva-uat',
          Key: file.name,
          Body: file,
          ContentType: file.type,
          ACL: 'public-read'
        };

        s3.upload(params, function(err, data) {
          if (err) {
            console.error('Error uploading file:', err);
            alert('Error uploading file');
          } else {
            console.log('File uploaded successfully:', data.Location);
            alert('File uploaded successfully!');
          }
        });
      });
    });
  </script>
@stop
@stop