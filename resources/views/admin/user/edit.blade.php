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
                        <!--begin::Form-->
                        <form class="form" id="frmEdit" action="{{ route('user-edit', $user->id) }}" enctype="multipart/form-data" method="POST">
                            <div class="card card-custom gutter-b example example-compact">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="firstname">First name<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="firstname" type="text" class="form-control " id="firstname" name="firstname" value="{{ $user->firstname}}" placeholder="first name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="lastname">Last Name<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="lastname" type="text" class="form-control required"  name="lastname" value="{{ $user->lastname }}" placeholder="last name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="email">Email<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="email" type="text" class="form-control required" name="email" value="{{ $user->email }}" placeholder="email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="user_batch">User Batch<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <select class="form-control required" name="user_batch[]" id="user_batch" multiple="multiple">
                                                <option value="">Select Batch</option>

                                                @foreach($batches as $key => $option)
                                                    <option value="{{$key}}" <?php echo in_array($key, $selected_batches) ? 'selected' : ''; ?>>{{$option}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                       
                                        <label class="col-lg-3 col-form-label" for="roles" >Roles</label>
                                        <div class="col-lg-6">

                                            <select name="roles[]" class="form-control required"  id="user_roles" multiple="multiple">
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
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="dob">Birth date<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="dob" type="date" class="form-control required" name="dob" value="{{ $user->dob }}" placeholder="birth date">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="phone">Phone<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="phone" type="text" class="form-control required" name="phone" value="{{ $user->phone }}" placeholder="phone">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="city">City<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="city" type="text" class="form-control required" name="city" value="{{ $user->city }}" placeholder="city">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="state">State<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="state" type="text" class="form-control required" name="state" value="{{ $user->state }}" placeholder="state">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="address1">Address 1<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="address1" type="text" class="form-control required" name="address1" value="{{ $user->state }}" placeholder="address 1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="address2">Address 2</label>
                                        <div class="col-lg-6">
                                            <input id="address2" type="text" class="form-control " name="address2" value="{{ $user->state }}" >
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="country">Country<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="country" type="text" class="form-control required" name="country" value="{{ $user->state }}" placeholder="country">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label  class="col-lg-3 col-form-label" for="pincode">Pincode<span class="required">*</span></label>
                                        <div class="col-lg-6">
                                            <input id="pincode" type="text" class="form-control required" name="pincode" value="{{ $user->state }}" placeholder="pincode">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label" for="status">Status &nbsp;</label>
                                        <div class="col-lg-9 col-md-9 col-sm-12">
                                         
                                            <input type="checkbox"   name="status"   value="1"  data-toggle="toggle" data-on="Yes" data-off="No" @if($user->status == 1)  checked @endif > 
                                        </div>

                                    </div>
                                </div>
                            
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-6">
                                            <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                            <a href="{{ route('users-manage') }}" class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </div>
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

