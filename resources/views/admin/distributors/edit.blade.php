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
                        <form class="form-horizontal" id="frmEdit"  action="{{ route('distributor-edit',$distributor->id) }}" >
                            <div class="card-body">
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="enagic_id">Enagic Id<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="enagic_id" type="text" class="form-control required" name="enagic_id"  placeholder="Enagic Id" value={{$distributor->enagic_id}}>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-form-label" for="name">Name<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input id="name" type="text" class="form-control required" name="name" placeholder="Name" value={{$distributor->name}}>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="mobile_no">Mobile No<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control" value={{ $distributor->mobile_no }} required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="email">Email ID<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="email" name="email" id="email" class="form-control" value={{ $distributor->email }} required>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="address">Address<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="address" id="address" class="form-control" value={{ $distributor->address }} required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="area">Area<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="area" id="area" class="form-control" value={{ $distributor->area }} required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="city">City<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="city" id="city" class="form-control" value={{ $distributor->city }} required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="state">State<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="state" id="state" class="form-control" value={{ $distributor->state }} required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="country">Country<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="country" id="country" class="form-control" value={{ $distributor->country }}" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="type">Type<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="User" {{ old('type', $distributor->type ?? '') == 'User' ? 'selected' : '' }}>User</option>
                                            <option value="Distributor" {{ old('type', $distributor->type ?? '') == 'Distributor' ? 'selected' : '' }}>Distributor</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="status">Distributor Status</label>
                                    <div class="col-lg-6">
                                        <select name="distributor_status" id="distributor_status" class="form-control" required>
                                            <option value="Active" {{ old('distributor_status', $distributor->distributor_status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ old('distributor_status', $distributor->distributor_status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="goal_for">Goal For<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <select name="goal_for" id="goal_for" class="form-control" required>
                                            <option value="User" {{ $distributor->goal_for == 'User' ? 'selected' : '' }}>User</option>
                                            <option value="3A" {{ $distributor->goal_for == '3A' ? 'selected' : '' }}>3A</option>
                                            <option value="6A" {{ $distributor->goal_for == '6A' ? 'selected' : '' }}>6A</option>
                                            <option value="6A2" {{ $distributor->goal_for == '6A2' ? 'selected' : '' }}>6A2</option>
                                            <option value="6A2-3" {{ $distributor->goal_for == '6A2-3' ? 'selected' : '' }}>6A2-3</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="upline_name">Upline Name<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="upline_name" id="upline_name" class="form-control" value={{ $distributor->upline_name }} required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="leader_name">Leader Name<span class="required">*</span></label>
                                    <div class="col-lg-6">
                                        <input type="text" name="leader_name" id="leader_name" class="form-control" value={{ $distributor->leader_name }} required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label" for="status">Account Status</label>
                                    <div class="col-lg-6">
                                        <select name="account_status" id="account_status" class="form-control" required>
                                            <option value="Active" {{ old('account_status', $distributor->account_status ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ old('account_status', $distributor->account_status ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-warning mr-2">Submit</button>
                                        <a href="{{ route('batches-manage') }}" class="btn btn-secondary">Cancel</a>
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
  </script>
@stop

@stop

