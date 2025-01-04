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
                        <form class="form-horizontal"  name="frmAdd" id="frmAdd" action="{{ config('constants.ADMIN_URL')}}footer">

                            <div class="card-body">
                                <?php $count=0; ?>

                                @if(count($footer) > 0)
                                    @foreach($footer as $data)
                                    <div id="title_div" count="{{$count}}">
                                        <div class="card">
                                            <div class="card-header">
                                                <button type="button" class="btn btn-danger delete_title_row">Delete Title</button>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input id="" type="text" class="form-control required"  name="title[{{$count}}]" value="{{ $data->title }}" placeholder="Title">
                                                </div>
                                                <div id="sub_div">
                                                    @php $j=0; @endphp
                                                    @foreach($footer_sub_data as $key=>$sub_data)
                                                        @if($data->id == $sub_data->parent_id)
                                                            <div class="plan_row">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <div class="form-group">
                                                                            <label for="name">Name</label>
                                                                            <input id="" type="text" class="form-control required" name="name[{{$count}}][{{$j}}]" value="{{ $sub_data->name }}" placeholder="Name" >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-5">
                                                                        <div class="form-group">
                                                                            <label for="link">Link</label>
                                                                            <input id="" type="text" class="form-control required" name="link[{{$count}}][{{$j}}]" value="{{ $sub_data->link }}" placeholder="Link">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-1 delete_div"><label for="name">&nbsp;</label><button type="button" class="btn btn-danger delete_row">Delete</button></div>
                                                                </div>
                                                            </div>
                                                            @php $j++; @endphp
                                                        @endif
                                                    @endforeach
                                                    <div class="row" id="add_row_div">
                                                        <div class="col-12">
                                                            <button class="add_row btn btn-warning" style="float: right;">Add More Row</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $count++; ?>
                                    @endforeach
                                    @else
                                    <div id="title_div" count="{{$count}}">
                                        <div class="card">
                                            <div class="card-header">
                                                <button type="button" class="btn btn-danger delete_title_row">Delete Title</button>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="title">Title</label>
                                                    <input id="" type="text" class="form-control required"  name="title[{{$count}}]" value="" placeholder="Title">

                                                </div>
                                                <div id="sub_div">
                                                    <div class="plan_row">
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <div class="form-group">
                                                                    <label for="name">Name</label>
                                                                    <input id="" type="text"  class="form-control required" name="name[{{$count}}][{{$count}}]" value="" placeholder="Name">

                                                                </div>
                                                            </div>
                                                            <div class="col-5">
                                                                <div class="form-group">
                                                                    <label for="link">Link</label>
                                                                    <input id="" type="text"  class="form-control required" name="link[{{$count}}][{{$count}}]" value="" placeholder="Link">
                                                                 </div>
                                                            </div>
                                                            <div class="col-1 delete_div" style="display:none;"><label for="name">&nbsp;</label><button type="button" class="btn btn-danger delete_row">Delete</button></div>
                                                        </div>
                                                    </div>
                                                    <div class="row" id="add_row_div">
                                                        <div class="col-12">
                                                            <button class="add_row btn btn-warning" style="float: right;">Add More Row</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="row" id="add_more_title_div">
                                    <div class="col-6">
                                        <button class="add_title_btn btn btn-warning" >Add More Title</button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-warning mr-2">Save</button>


                                    </div>
                                </div>
                            </div>
                            <!-- /.card-footer -->
                        </form>

                        <div id="sample_div" style="display:none;">
                            <div id="title_div" count="%i%">
                                <div class="card">
                                    <div class="card-header">
                                        <button type="button" class="btn btn-danger delete_title_row">Delete Title</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="title">Title</label>
                                            <input id="" type="text"  class="form-control required" name="title[%i%]" value="" placeholder="Title">

                                        </div>
                                        <div id="sub_div">
                                            <div class="plan_row">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label for="name">Name</label>
                                                            <input id=""  type="text" class="form-control required" name="name[%i%][0]" value="" placeholder="Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-5">
                                                        <div class="form-group">
                                                            <label for="link">Link</label>
                                                            <input id=""  type="text" class="form-control required" name="link[%i%][0]" value="" placeholder="Link">

                                                        </div>
                                                    </div>
                                                    <div class="col-1 delete_div" style="display:none;"><label for="name">&nbsp;</label><button type="button" class="btn btn-danger delete_row">Delete</button></div>
                                                </div>
                                            </div>
                                            <div class="row" id="add_row_div">
                                                <div class="col-12">
                                                    <button class="add_row btn btn-warning" style="float: right;">Add More Row</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    $(document).ready(function() {

        @if(Session::has('success-message'))
            toastr.info("{{ session('success-message') }}");
        @endif

    });


    $(document).on('click','.add_row',function(e){
            e.preventDefault();

            var title_count = $(this).closest('#title_div').attr('count');

            var count1 = $(this).closest('#sub_div').find('.plan_row').length;

            var item = '<div class="plan_row">';
            item += '<div class="row"><div class="col-6"><div class="form-group"><label for="name">Name</label>';
            item += '<input id=""  type="text" class="form-control required" name="name['+title_count+']['+count1+']" value="" placeholder="Name">';
            item += '</div></div>';
            item += '<div class="col-5"><div class="form-group"><label for="link">Link</label>';
            item += '<input id=""  type="text" class="form-control required" name="link['+title_count+']['+count1+']" value="" placeholder="Link">';
            item += '</div></div>';
            item += '<div class="col-1 delete_div"><label for="name">&nbsp;</label><button type="button" class="btn btn-danger delete_row">Delete</button></div>';
            item += '</div></div>';

            $(this).closest('#add_row_div').before(item);
            $(this).closest('#title_div').find('.delete_div').show();
        });

        $(document).on('click','.add_title_btn',function(e){
                e.preventDefault();

                var html = $('#sample_div').html();

                var title_count = $(document).find('.form-horizontal').find('[id=title_div]').length;

                html = html.replace(/\%i\%/g,title_count);
                $('#add_more_title_div').before(html);
        });

        $(document).on('click','.delete_row',function(){
            $(this).closest('.plan_row').remove();
        });

        $(document).on('click','.delete_title_row',function(){
            // alert(0);
            swal.fire({
                title: 'Are you sure You want to Delete this title and its content?',
                icon: 'warning',
                buttons: true,
            }).then((isConfirm) => {
                if (isConfirm) {
                    $(this).closest('#title_div').remove();
                }
            });


        });
  </script>
@stop
@stop

