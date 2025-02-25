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
            <form class="form-horizontal" id="frmEdit" action="{{ route('prospect-edit',$prospect->id) }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="name">Name<span class="required">*</span></label>
                                <div>
                                    <input id="name" type="text" class="form-control required" name="name"
                                        placeholder="Name" value="{{$prospect->name}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="email">Email ID<span class="required">*</span></label>
                                <div>
                                    <input type="email" name="email" id="email" class="form-control required"
                                        value="{{ $prospect->email }}" placeholder="Email">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="mobile_no">Mobile No<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="mobile_no" id="mobile_no" class="form-control required"
                                        value="{{ $prospect->mobile_no }}" placeholder="Mobile">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="address">Address<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="address" id="address" class="form-control required"
                                        value="{{ $prospect->address }}" placeholder="Address">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="area">Area<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="area" id="area" class="form-control required"
                                        value="{{ $prospect->area }}" placeholder="Area">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="city">City<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="city" id="city" class="form-control required"
                                        value="{{ $prospect->city }}" placeholder="City">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="state">State<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="state" id="state" class="form-control required"
                                        value="{{ $prospect->state }}" placeholder="State">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="country">Country<span class="required">*</span></label>
                                <div>
                                    <input type="text" name="country" id="country" class="form-control required"
                                        value="{{ $prospect->country }}" placeholder="Country">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
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
                                @if(isset($prospect) && $prospect->statuses->count() > 0)
                                    @foreach($prospect->statuses as $index => $status)
                                        <tr>
                                            <input type="hidden" name="statuses[{{$index}}][id]" value="{{ $status->id ?? '' }}">
                                            <td>
                                                <select name="statuses[{{ $index }}][status]" class="form-control">
                                                    <option value="Invitation" {{ $status->status == 'Invitation' ? 'selected' : '' }}>Invitation</option>
                                                    <option value="Demo" {{ $status->status == 'Demo' ? 'selected' : '' }}>Demo</option>
                                                    <option value="Followup" {{ $status->status == 'Followup' ? 'selected' : '' }}>Followup</option>
                                                    <option value="Machine Purchased" {{ $status->status == 'Machine Purchased' ? 'selected' : '' }}>Machine Purchased</option>
                                                </select>
                                            </td>
                                            <td><input type="date" name="statuses[{{ $index }}][date]" class="form-control" value="{{ $status->date }}" max="<?php echo date('Y-m-d'); ?>"></td>
                                            <td><input type="text" name="statuses[{{ $index }}][remarks]" class="form-control" value="{{ $status->remarks }}"></td>
                                            <td><button type="button" class="btn btn-sm btn-danger remove_row">-</button></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>
                                            <select name="statuses[0][status]" class="form-control">
                                                <option value="Invitation">Invitation</option>
                                                <option value="Demo">Demo</option>
                                                <option value="Followup">Followup</option>
                                                <option value="Machine Purchased">Machine Purchased</option>
                                            </select>
                                        </td>
                                        <td><input type="date" name="statuses[0][date]" class="form-control" max="<?php echo date('Y-m-d'); ?>"></td>
                                        <td><input type="text" name="statuses[0][remarks]" class="form-control"></td>
                                        <td><button type="button" class="btn btn-sm btn-danger remove_row">-</button></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <div id="error-message" style="color: red; display: none;;margin-left: 13px;"></div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer d-flex justify-content-end">

                    <a href="{{ route('prospects-manage') }}" class="btn btn-secondary mr-2">Cancel</a>
                    <button type="submit" class="btn btn-primary ">Submit</button>

                </div>
                <!-- /.card-footer -->
            </form>
            @include('admin.prospects.modal')
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
    let today = new Date().toISOString().split('T')[0];
    let table = document.querySelector("#statuses_table tbody");
    let rowCount = table.rows.length;
    let row = table.insertRow();
    row.innerHTML = `
        <td>
            <select id="statuses[${rowCount}][status]" name="statuses[${rowCount}][status]" class="form-control custom-select required" placeholder="status">
                <option value="Invitation">Invitation</option>
                <option value="Demo">Demo</option>
                <option value="Followup">Followup</option>
                <option value="Machine Purchased">Machine Purchased</option>
            </select>
        </td>
        <td><input type="date" id="statuses[${rowCount}][date]" name="statuses[${rowCount}][date]" class="form-control required" value="${today}" max="${today}"></td>
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
