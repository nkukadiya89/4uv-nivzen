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
                    <h5 class="text-dark font-weight-bold my-1 mr-5">{{$title}}-{{$user->firstname}}</h5>
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

            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header align-content-center">
                    <div class="card-title">
                    </div>
                    <div class="p-2">

                        @if(auth()->user()->hasRole('Administrator') || auth()->user()->can('prospects add'))
                        <a href="{{ route('prospect-add-form') }}" class="btn btn-primary">
                            <i class="la la-plus"></i>New Prospect</a>
                        @endif

                    </div>
                </div>

                <div class="card-body">
                    <!--begin: Datatable-->
                    <div class="table-bulk-action kt-hide">
                        <label class="kt-mr-20" style="display: inline;">Bulk Action</label>
                        <select class="form-control form-control-sm form-filter kt-input table-group-action-input"
                            title="Select Action" name="bulk_action" style="width: 150px;display: inline;">
                            <option value="">Select Action</option>
                            @if(auth()->user()->hasRole('Administrator') || auth()->user()->can('prospects delete'))
                                <option value="Delete">Delete</option>
                            @endif
                        </select>
                        <button href="javascript:;" type="button"
                            class="btn btn-primary font-weight-bolder btn-sm table-group-action-submit submit-btn"
                            id="bulk_action_submit"><i class="fa fa-check"></i> Submit</button>
                        <input type="hidden" class="table-group-action-url"
                            value="<?php echo 'prospects/bulk-action';?>" />
                    </div>
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_ajax">
                        <thead>
                            <tr>
                                <td><input type="checkbox" class="row-checkbox" id="select-all"></td>

                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Country</th>
                                <th>No. Of Invitation</th>
                                <th>No. Of Demo</th>
                                <th>No. Of Followup</th>
                                <th width="105" class="no-sort text-center">Actions</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr class="filter">
                                <td></td>

                                <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                        name="name"></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                        name="email"></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                        name="mobile_no"></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                           name="city"></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                           name="state"></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                           name="country"></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                           name="invitation_count"></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                           name="demo_count"></td>
                                <td><input type="text" class="form-control form-control-sm form-filter kt-input"
                                           name="follow_up_count"></td>
                                <td>
                                    <button class="btn btn-light-warning font-weight-bolder btn-sm filter-submit"><span><i
                                                class="la la-search"></i><span>Search</span></span></button> &nbsp;
                                    <button
                                        class="btn btn-secondary btn-sm  mt-0 filter-cancel reset-btn search-btn"><span><i
                                                class="la la-close"></i><span>Reset</span></span></button>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
@section('custom_js')
<script>
$(document).ready(function() {

    @if(Session::has('success-message'))
      toastr.info("{{ session('success-message') }}");
    @endif

    var id = '{{$user->id}}'; // Replace with the actual ID value
    var url = '{{ route('prospects-filter-list-ajax') }}' + '?id=' + id;
    //var url = '{{route('prospects-filter-list-ajax')}}';
    DataTables.init('#datatable_ajax', url);

    {{--var id = 32; // Replace with the actual ID value--}}

    {{--var url = "{{ config('constants.ADMIN_URL') }}prospects/list-ajax";--}}

    {{--$('#datatable_ajax').DataTable({--}}
    {{--    processing: true,--}}
    {{--    serverSide: true,--}}
    {{--    ajax: {--}}
    {{--        url: url,--}}
    {{--        type: "POST",--}}
    {{--        data: function(d) {--}}
    {{--            d.user_id = id; // Pass ID dynamically--}}
    {{--        },--}}
    {{--        headers: {--}}
    {{--            "X-CSRF-TOKEN": "{{ csrf_token() }}" // CSRF protection--}}
    {{--        }--}}
    {{--    }--}}
    {{--});--}}
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
