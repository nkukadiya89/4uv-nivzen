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
    <div class="p-6">
        <div class="card card-custom gutter-b example example-compact">
            <!--begin::Form-->
            <form class="form-horizontal" id="frmEdit"
                action="{{ route('support-edit',$supportRequest->id) }}">
                <div class="card-body">
                    @error('support_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="row">

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="from_user_id">From User<span class="required">*</span></label>
                                <div>
                                    <select
                                            class="form-control city custom-select required"
                                        id="from_user_id" name="from_user_id" placeholder="From User">
                                        <option value="">Select From User</option>
                                        @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('from_user_id', $supportRequest->from_user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->firstname }} {{$user->lastname}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">

                            <div class="form-group">
                                <label for="to_user_id">To User<span class="required">*</span></label>
                                <div>
                                    <select
                                            class="form-control city custom-select required"
                                        id="to_user_id" name="to_user_id" placeholder="To User">
                                        <option value="">Select To User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}"
                                                {{ old('to_user_id', $supportRequest->to_user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->firstname }} {{$user->lastname}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="support_name">Subject<span class="required">*</span></label>
                                <div>
                                    <input type="text" class="form-control required"
                                           id="support_name" name="support_name"
                                           value="{{ old('support_name', $supportRequest->support_name) }}" placeholder="Subject">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">

                            <div class="form-group">
                                <label for="description">Description<span class="required">*</span></label>
                                <div>
                                    <textarea class="form-control required"
                                        id="description" name="description"
                                        rows="3" placeholder="Description">{{ old('description', $supportRequest->description) }}</textarea>
                                </div>
                            </div>
                        </div>
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