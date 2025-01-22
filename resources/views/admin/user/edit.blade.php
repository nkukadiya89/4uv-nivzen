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
                        <!--begin::Form-->
                        <form class="form" id="frmEdit" action="{{ route('user-edit', $user->id) }}" enctype="multipart/form-data" method="POST">
                            <div class="card card-custom gutter-b example example-compact">
                                <div class="card-header align-content-center">
                                    <div class="card-title">
                                    </div>
                                    <div class="p-2">
                                        <!--begin::Button-->
                                        <a href="{{ route('users-manage') }}" class="btn btn-primary">
                                            Back</a>
                                        <!--end::Button-->
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group ">
                                            <label for="firstname">First name<span class="required">*</span></label>
                                            <div>
                                                <input id="firstname" type="text" class="form-control required"  name="firstname" value="{{ $user->firstname}}" placeholder="first name">
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group ">
                                            <label  for="lastname">Last Name<span class="required">*</span></label>
                                            <div>
                                                <input id="lastname" type="text" class="form-control required"  name="lastname" value="{{ $user->lastname }}" placeholder="last name">
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group ">
                                            <label for="email">Email<span class="required">*</span></label>
                                            <div>
                                                <input id="email" type="text" class="form-control required" name="email" value="{{ $user->email }}" placeholder="email">
                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group ">
                                            <label  for="roles" >Roles<span class="required">*</span></label>
                                            <div>

                                                <select name="roles" class="form-control required"  id="user_roles"  placeholder="Role">
                                                    <option value="">Select Role</option>
                                                    @foreach ($roles as $role)
                                                    <option
                                                        value="{{ $role }}"
                                                        {{ in_array($role, $userRoles) ? 'selected':'' }}
                                                    >
                                                        {{ $role }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('roles') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group ">
                                            <label for="dob">Birth date<span class="required">*</span></label>
                                            <div>
                                                <input id="dob" type="date" class="form-control required" name="dob" value="{{ $user->dob }}" placeholder="birth date">
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group ">
                                            <label for="phone">Mobile No<span class="required">*</span></label>
                                            <div>
                                                <input id="phone" type="text" class="form-control required" name="phone" value="{{ $user->phone }}" placeholder="phone">
                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group">
                                            <label for="address1">Address<span class="required">*</span></label>
                                            <div>
                                                <input id="address1" type="text" class="form-control required" name="address1" value="{{ $user->address1 }}" placeholder="Address">
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group">
                                            <label for="address2">Area</label>
                                            <div>
                                                <input id="address2" type="text" class="form-control required" name="address2" value="{{ $user->address2 }}" placeholder="Area">
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group ">
                                                <label for="city">City<span class="required">*</span></label>
                                                <div>
                                                    <input id="city" type="text" class="form-control required" name="city" value="{{ $user->city }}" placeholder="city">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group ">
                                                <label  for="state">State<span class="required">*</span></label>
                                                <div>
                                                    <input id="state" type="text" class="form-control required" name="state" value="{{ $user->state }}" placeholder="state">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group">
                                            <label  for="country">Country<span class="required">*</span></label>
                                            <div >
                                                <input id="country" type="text" class="form-control required" name="country" value="{{ $user->country }}" placeholder="country">
                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group">
                                            <label  for="pincode">Pincode<span class="required">*</span></label>
                                            <div >
                                                <input id="pincode" type="text" class="form-control required" name="pincode" value="{{ $user->pincode }}" placeholder="pincode">
                                            </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4 col-lg-3">
                                            <div class="form-group">
                                            <label  for="status">Status &nbsp;</label>
                                            <div >

                                                <input type="checkbox"   name="status"   value="1"  data-toggle="toggle" data-on="Yes" data-off="No" @if($user->status == 1)  checked @endif >
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- /.card-body -->
                                <div class="card-footer d-flex justify-content-end">

                                    <a href="{{ route('users-manage') }}" class="btn btn-secondary mr-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>

                                </div>
                                <!-- /.card-footer -->
                            </div>
                        </form>
                   
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
    

    $(document).on('click','.delete_row',function(){
        $(this).closest('.row').remove();
    });

    $('#user_batch').selectize({
        plugins: ["remove_button","drag_drop"],
        delimiter: ",",
        persist: false,
    });
    $('#user_roles').selectize({
        plugins: ["remove_button","drag_drop"],
        delimiter: ",",
        persist: false,
    });
    

  </script>
@stop

@stop

