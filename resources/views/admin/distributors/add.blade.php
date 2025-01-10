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
                <form class="form-horizontal" id="frmAdd" action="{{ route('distributor-add') }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="batch_id">Enagic Id<span class="required">*</span></label>
                                    <div>
                                        <input id="enagic_id" type="text" class="form-control required" name="enagic_id"
                                               value="{{ old('enagic_id') }}" placeholder="Enagic Id">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group ">
                                    <label for="title">Name<span class="required">*</span></label>
                                    <div>
                                        <input id="name" type="text" class="form-control required" name="name"
                                               value="{{ old('name') }}" placeholder="Name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="mobile_no">Mobile No<span class="required">*</span></label>
                                    <div>
                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control"
                                               value="{{ old('mobile_no') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="email">Email ID<span class="required">*</span></label>
                                    <div>
                                        <input type="email" name="email" id="email" class="form-control"
                                               value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label for="address">Address<span class="required">*</span></label>
                                    <div>
                                        <input type="text" name="address" id="address" class="form-control"
                                               value="{{ old('address') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="area">Area<span class="required">*</span></label>
                                    <div>
                                        <input type="text" name="area" id="area" class="form-control"
                                               value="{{ old('area') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label for="city">City<span class="required">*</span></label>
                                    <div>
                                        <input type="text" name="city" id="city" class="form-control"
                                               value="{{ old('city') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="state">State<span class="required">*</span></label>
                                    <div>
                                        <input type="text" name="state" id="state" class="form-control"
                                               value="{{ old('state') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="country">Country<span class="required">*</span></label>
                                    <div>
                                        <input type="text" name="country" id="country" class="form-control"
                                               value="{{ old('country') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label for="type">Type<span class="required">*</span></label>
                                    <div>
                                        <select name="type" id="type" class="form-control" required>
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
                                                    {{ old('distributor_status') == 'Active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="Inactive"
                                                    {{ old('distributor_status') == 'Inactive' ? 'selected' : '' }}>Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label for="goal_for">Goal For<span class="required">*</span></label>
                                    <div>
                                        <select name="goal_for" id="goal_for" class="form-control" required>
                                            <option value="User" {{ old('goal_for') == 'User' ? 'selected' : '' }}>User
                                            </option>
                                            <option value="3A" {{ old('goal_for') == '3A' ? 'selected' : '' }}>3A</option>
                                            <option value="6A" {{ old('goal_for') == '6A' ? 'selected' : '' }}>6A</option>
                                            <option value="6A2" {{ old('goal_for') == '6A2' ? 'selected' : '' }}>6A2
                                            </option>
                                            <option value="6A2-3" {{ old('goal_for') == '6A2-3' ? 'selected' : '' }}>6A2-3
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">

                                <div class="form-group">
                                    <label for="upline_name">Upline Name<span class="required">*</span></label>
                                    <div>
                                        <input type="text" name="upline_name" id="upline_name" class="form-control"
                                               value="{{ old('upline_name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="leader_name">Leader Name<span class="required">*</span></label>
                                    <div>
                                        <input type="text" name="leader_name" id="leader_name" class="form-control"
                                               value="{{ old('leader_name') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="status">Account Status</label>
                                    <div>
                                        <select name="account_status" id="account_status" class="form-control" required>
                                            <option value="Active"
                                                    {{ old('account_status') == 'Active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="Inactive"
                                                    {{ old('account_status') == 'Inactive' ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer d-flex justify-content-end">



                        <a href="{{ route('batches-manage') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary ml-2">Submit</button>

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