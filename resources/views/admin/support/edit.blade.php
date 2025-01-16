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
            <div class="row">
                <div class="col-lg-12">
                    <!--begin::Card-->
                    <div class="card card-custom gutter-b example example-compact">
                        <!--begin::Form-->
                        <form class="form-horizontal" id="frmEdit"  action="{{ route('support-requests.update',$supportRequest->id) }}" >
                            <div class="card-body">
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="support_name">Subject<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control @error('support_name') is-invalid @enderror" id="support_name" name="support_name" value="{{ old('support_name', $supportRequest->support_name) }}">
                                    </div>
                                </div>
                                @error('support_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="from_user_id">>From User<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <select class="form-control city custom-select @error('from_user_id') is-invalid @enderror" id="from_user_id" name="from_user_id">
                                            <option value="">Select From User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('from_user_id', $supportRequest->from_user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->firstname }} {{$user->lastname}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('from_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="to_user_id">To User<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <select class="form-control city custom-select @error('to_user_id') is-invalid @enderror" id="to_user_id" name="to_user_id">
                                            <option value="">Select To User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('to_user_id', $supportRequest->to_user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->firstname }} {{$user->lastname}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('to_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="description">Description<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $supportRequest->description) }}</textarea>
                                    </div>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>




                                
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer d-flex justify-content-end">

                                        <a href="{{ route('support-manage') }}" class="btn btn-secondary mr-2">Cancel</a>
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

