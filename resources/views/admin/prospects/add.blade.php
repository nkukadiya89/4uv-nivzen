@extends('admin.layouts.master')
@section('content')
<div class=" d-flex flex-column flex-column-fluid" id="kt_content">
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
        <div class="card card-custom w-full">
            <!--begin::Form-->
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <form class="form-horizontal" id="frmAdd" action="{{ route('prospect-add') }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="name">Name<span class="required">*</span></label>
                                <div>
                                    <input id="name" type="text" class="form-control required" name="name"
                                        value="{{ old('name') }}" placeholder="Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="email">Email<span class="required">*</span></label>
                                <div>
                                    <input type="email" name="email" id="email" class="form-control required"
                                        value="{{ old('email') }}" placeholder="Email" >
                                </div>
                                @error('email')
                                  <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="mobile_no">Mobile No<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="mobile_no" id="mobile_no" class="form-control required"
                                        value="{{ old('mobile_no') }}" placeholder="Mobile No" >
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="address">Address<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="address" id="address" class="form-control required"
                                        value="{{ old('address') }}" placeholder="Address" >
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="area">Area<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="area" id="area" class="form-control required"
                                        value="{{ old('area') }}" placeholder="Area" >
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="city">City<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="city" id="city" class="form-control required"
                                        value="{{ old('city') }}" placeholder="City" >
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="state">State<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="state" id="state" class="form-control required"
                                        value="{{ old('state') }}" placeholder="State" >
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="country">Country<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="country" id="country" class="form-control required"
                                        value="{{ old('country') }}" placeholder="Country" >
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 col-lg-12">
                            <h4>Statuses</h4>
                            <table class="table" id="statuses_table">
                                <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Remarks</th>
                                    <th><button type="button" id="add_row" class="btn btn-sm btn-primary">+</button></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select name="statuses[0][status]" class="form-control">
                                            <option value="Invitation">Invitation</option>
                                            <option value="Demo">Demo</option>
                                            <option value="Followup">Followup</option>
                                            <option value="Machine Purchased">Machine Purchased</option>
                                        </select>
                                    </td>
                                    <td><input type="date" name="statuses[0][date]" class="form-control"></td>
                                    <td><input type="text" name="statuses[0][remarks]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-sm btn-danger remove_row">-</button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer d-flex justify-content-end">

                    <a href="{{ route('prospects-manage') }}" class="btn btn-secondary mr-2">Cancel</a>
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
document.getElementById('add_row').addEventListener('click', function() {
    let table = document.querySelector("#statuses_table tbody");
    let rowCount = table.rows.length;
    let row = table.insertRow();
    row.innerHTML = `
        <td>
            <select name="statuses[${rowCount}][status]" class="form-control">
                <option value="Invitation">Invitation</option>
                <option value="Demo">Demo</option>
                <option value="Followup">Followup</option>
                <option value="Machine Purchased">Machine Purchased</option>
            </select>
        </td>
        <td><input type="date" name="statuses[${rowCount}][date]" class="form-control"></td>
        <td><input type="text" name="statuses[${rowCount}][remarks]" class="form-control"></td>
        <td><button type="button" class="btn btn-sm btn-danger remove_row">-</button></td>
    `;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove_row')) {
        e.target.closest('tr').remove();
    }
});
</script>
@stop

@stop
