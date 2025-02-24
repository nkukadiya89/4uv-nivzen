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
            <form class="form-horizontal" id="frmEdit" action="{{ route('distributor-edit',$distributor->id) }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="enagic_id">Enagic Id<span class="required">*</span></label>
                                <div>
                                    <input id="enagic_id" type="text" class="form-control required" name="enagic_id"
                                        placeholder="Enagic Id" value="{{$distributor->enagic_id}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="firstname">First Name<span class="required">*</span></label>
                                <div>
                                    <input id="firstname" type="text" class="form-control required" name="firstname"
                                        placeholder="First Name" value="{{$distributor->firstname}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="lastname">Last Name<span class="required">*</span></label>
                                <div>
                                    <input id="lastname" type="text" class="form-control required" name="lastname"
                                        placeholder="Last Name" value="{{$distributor->lastname}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="phone">Mobile No<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="phone" id="phone" class="form-control required"
                                        value="{{ $distributor->phone }}" placeholder="phone" >
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="dob">Date of Birth<span class="required">*</span></label>
                                <div>
                                    <input class="form-control" type="date" placeholder="Birth date" name="dob"
                                        value="{{ $distributor->dob }}" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="email">Email ID<span class="required">*</span></label>
                                <div>
                                    <input type="email" name="email" id="email" class="form-control required"
                                        value="{{ $distributor->email }}" placeholder="Email">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="address1">Address<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="address1" id="address1" class="form-control required"
                                        value="{{ $distributor->address1 }}" placeholder="Address">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="address2">Area<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="address2" id="address2" class="form-control required"
                                        value="{{ $distributor->address2 }}" placeholder="Area">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="city">City<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="city" id="city" class="form-control required"
                                        value="{{ $distributor->city }}" placeholder="City">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="state">State<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="state" id="state" class="form-control required"
                                        value="{{ $distributor->state }}" placeholder="State">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="country">Country<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="country" id="country" class="form-control required"
                                        value="{{ $distributor->country }}" placeholder="Country">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="type">Type<span class="required">*</span></label>
                                <div>
                                    <select name="type" id="type" class="form-control city custom-select required" placeholder="User Type">
                                        <option value="">Select User Type</option>
                                        <option value="User"
                                            {{ old('type', $distributor->type ?? '') == 'User' ? 'selected' : '' }}>User
                                        </option>
                                        <option value="Distributor"
                                            {{ old('type', $distributor->type ?? '') == 'Distributor' ? 'selected' : '' }}>
                                            Distributor</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="status">Distributor Status</label>
                                <div>
                                    <select name="distributor_status" id="distributor_status" class="form-control"
                                        required>
                                        <option value="Active"
                                            {{ old('distributor_status', $distributor->distributor_status ?? '') == 'Active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="Inactive"
                                            {{ old('distributor_status', $distributor->distributor_status ?? '') == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="goal_for">Goal For<span class="required">*</span></label>
                                <div>
                                    <select name="goal_for" id="goal_for" class="form-control city custom-select required" placeholder="Goal For">
                                        <option value="">Select Goal For</option>
                                        <option value="User" {{ $distributor->goal_for == 'User' ? 'selected' : '' }}>
                                            User</option>
                                        <option value="3A" {{ $distributor->goal_for == '3A' ? 'selected' : '' }}>3A
                                        </option>
                                        <option value="6A" {{ $distributor->goal_for == '6A' ? 'selected' : '' }}>6A
                                        </option>
                                        <option value="6A2" {{ $distributor->goal_for == '6A2' ? 'selected' : '' }}>6A2
                                        </option>
                                        <option value="6A2-3" {{ $distributor->goal_for == '6A2-3' ? 'selected' : '' }}>
                                            6A2-3</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="upline_name">Upline Name<span class="required">*</span></label>
                                <div>
                                    <select class="form-control select2 required" name="upline_id"
                                        id="upline_id" placeholder="Upline Name" data-selected="{{ $distributor->upline_id }}">

                                        <option value="">Select Upline Name</option>
                                        @foreach ($users as $res)
                                        <option value="{{ $res->id }}" {{ $distributor->upline_id == $res->id ? 'selected' : '' }}>
                                            {{ $res->firstname }} {{$res->lastname}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="leader_name">Leader Name<span class="required">*</span></label>
                                <div>
                                    <select class="form-control select2 required" name="leader_id"
                                        id="leader_id" placeholder="Leader Name">

                                        <option value="">Select Leader Name</option>
                                        @foreach ($users as $res)
                                        <option value="{{ $res->id }}" {{ $distributor->leader_id == $res->id ? 'selected' : '' }}>
                                            {{ $res->firstname }} {{$res->lastname}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="status">Account Status</label>
                                <div>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="Active"
                                            {{ old('status', $distributor->status ?? '') == 'Active' ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="Inactive"
                                            {{ old('status', $distributor->status ?? '') == 'Inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
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

    $('#upline_id').select2({
        placeholder: "Select Upline Name",
        allowClear: true,
        width: '100%' // Ensures proper width inside Bootstrap forms
    });
    let selectedValue = $('#upline_id').attr('data-selected');
    console.log(selectedValue);
    if (selectedValue) {
        $('#upline_id').val(selectedValue).trigger('change');
    }
    $('#leader_id').select2({
        placeholder: "Select Leader Name",
        allowClear: true,
        width: '100%' // Ensures proper width inside Bootstrap forms
    });
});
</script>
@stop

@stop
