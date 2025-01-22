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
            <form class="form-horizontal" id="frmAdd" action="{{ route('user-add') }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group ">
                                <label for="firstname">First Name<span class="required">*</span></label>
                                <div>
                                    <input id="firstname" type="text" class="form-control required" name="firstname"
                                        value="{{ old('firstname') }}" placeholder="First Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group ">
                                <label for="lastname">Last Name<span class="required">*</span></label>
                                <div>
                                    <input id="lastname" type="text" class="form-control required" name="lastname"
                                        value="{{ old('lastname') }}" placeholder="Last Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="phone">Mobile No<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="phone" id="phone" class="form-control required"
                                        value="{{ old('phone') }}" placeholder="Phone">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="dob">Date of Birth<span class="required">*</span></label>
                                <div>
                                    <input class="form-control required" type="date" placeholder="Birth date" name="dob"
                                        value="{{ old('dob') }}" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="email">Email ID<span class="required">*</span></label>
                                <div>
                                    <input type="email" name="email" id="email" class="form-control required"
                                        value="{{ old('email') }}" placeholder="Email">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label  for="roles" >Roles<span class="required">*</span></label>
                            <div>

                                <select name="roles" class="form-control required"  id="user_roles" placeholder="Role">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $k=>$res)
                                        <option value="{{ $k }}">
                                            {{ $res }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('roles') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">

                            <div class="form-group">
                                <label for="address1">Address<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="address1" id="address1" class="form-control required"
                                        value="{{ old('address1') }}" placeholder="Address">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="address2">Area<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="address2" id="address2" class="form-control required"
                                        value="{{ old('address2') }}" placeholder="Area">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">

                            <div class="form-group">
                                <label for="city">City<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="city" id="city" class="form-control required"
                                        value="{{ old('city') }}" placeholder="City">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="state">State<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="state" id="state" class="form-control required"
                                        value="{{ old('state') }}" placeholder="State">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="country">Country<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="country" id="country" class="form-control required"
                                        value="{{ old('country') }}" placeholder="Country">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="phone">Pincode</label>
                                <div>
                                    <input type="text" name="pincode" id="pincode" class="form-control"
                                           value="{{ old('pincode') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="status"> Status</label>
                                <div>
                                    <input type="checkbox"   name="status"   value="1"  data-toggle="toggle" data-on="Yes" data-off="No" checked >
                                </div>
                            </div>
                        </div>

                    </div>


                </div>
                <!-- /.card-body -->
                <div class="card-footer d-flex justify-content-end">



                    <a href="{{ route('users-manage') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary ml-2">Submit</button>
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
});
</script>
@stop

@stop