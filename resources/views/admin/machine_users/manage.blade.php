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
    <div class="p-6 flex-fill">
        <div class="card card-custom w-full">
            <div class="card-header align-content-center">
                <div class="card-title">
                </div>
                <div class="p-2">

                </div>
            </div>

            <div class="card-body">
                <!--begin: Datatable-->
                <div class="table-bulk-action kt-hide">
                    <label class="kt-mr-20" style="display: inline;">Bulk Action</label>
                    <select class="form-control form-control-sm form-filter kt-input table-group-action-input"
                        title="Select Action" name="bulk_action" style="width: 150px;display: inline;">
                        <option value="">Select Action</option>
                        @if(auth()->user()->hasRole('Administrator') || auth()->user()->can('delete user'))
                            <option value="Delete">Delete</option>
                        @endif
                    </select>
                    <button href="javascript:;" type="button"
                        class="btn btn-primary font-weight-bolder btn-sm table-group-action-submit submit-btn"
                        id="bulk_action_submit"><i class="fa fa-check"></i> Submit</button>
                    <input type="hidden" class="table-group-action-url"
                        value="<?php echo 'users/bulk-action';?>" />
                </div>
                <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
                    <thead>
                        <tr>
                            <td><input type="checkbox" class="row-checkbox" id="select-all"></td>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Birth date</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Status</th>
                            <th width="105" class="no-sort text-center">Actions</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr class="filter">
                            <td></td>
                            <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                    name="firstname"></td>
                            <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                       name="lastname"></td>
                            <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                    name="email"></td>
                            <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                    name="phone"></td>
                            <td>

                                {{-- <input type="text" class="form-control form-control-sm form-filter kt-input" name="start_date" placeholder="Start Date"   onfocus="(this.type='date')" id="date"
                                    >
                                    <br>
                                    <input type="text" class="form-control form-control-sm form-filter kt-input" name="end_date" placeholder="End Date"   onfocus="(this.type='date')"
                                    > --}}
                                <input type="text" id="date_range" name="date_range"
                                    class="form-control form-control-sm form-filter kt-input" placeholder="Select date">
                            </td>
                            <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                       name="city"></td>
                            <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                       name="state"></td>
                            <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                       name="country"></td>
                            <td>
                                <select class="form-control form-control-sm form-filter kt-input" title="Select"
                                        name="account_status">
                                    <option value="">Select</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn btn-light-warning font-weight-bolder btn-sm filter-submit"><span><i
                                            class="la la-search"></i><span>Search</span></span></button> &nbsp;
                                <button class="btn btn-secondary btn-sm  mt-0 filter-cancel reset-btn search-btn"><span><i
                                            class="la la-close"></i><span>Reset</span></span></button>
                            </td>
                        </tr>
                    </thead>
                </table>
                <!--end: Datatable-->
            </div>
        </div>
    </div>
    <!--end::Entry-->
</div>
@section('custom_js')
<script>
$(document).ready(function() {

    @if(Session::has('success-message'))
    toastr.info("{{ session('success-message') }}");
    @endif

    var url = '{{config('constants.ADMIN_URL ')}}machine-users/list-ajax';
    DataTables.init('#datatable_ajax', url);

    $('#date_range').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        },
        placeholder: 'Date'
    });

    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' → ' + picker.endDate.format(
            'YYYY-MM-DD'));
    });

    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // When "Select All" checkbox is clicked
    $('#select-all').click(function() {
        var isChecked = $(this).prop('checked'); // Check if "Select All" is checked

        // Select or deselect all checkboxes based on the "Select All" checkbox state
        $('#datatable_ajax .row-checkbox').each(function() {
            $(this).prop('checked', isChecked); // Set checked state
        });
    });

    // Optionally, update the "Select All" checkbox state based on individual checkboxes
    $('#datatable_ajax').on('change', '.row-checkbox', function() {
        var totalCheckboxes = $('#datatable_ajax .row-checkbox').length;
        var checkedCheckboxes = $('#datatable_ajax .row-checkbox:checked').length;

        // If all checkboxes are selected, check the "Select All" checkbox
        if (totalCheckboxes === checkedCheckboxes) {
            $('#select-all').prop('checked', true);
        } else {
            $('#select-all').prop('checked', false);
        }
    });
});
</script>
@stop
@stop
