@extends('admin.layouts.master')
@section('content')
<style>
    .select2-drop li {
  white-space: pre;
}
</style>
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
                        <form class="form-horizontal" name="frmAdd" id="frmAdd" action="{{ config('constants.ADMIN_URL')}}manage-home-page" enctype="multipart/form-data">

                            <div class="card-body">
                                <div id="multiple_div">
                                    <div class="dynamic_data">
                                        <?php
                                            $count = 0;
                                            if (count($homepage) > 0) {
                                        ?>
                                        <div id="parent_div">
                                                <?php
                                                    $count = 0;
                                                    foreach ($homepage as $key => $value) {
                                                    $type_comp_ids_array = explode(',', $value->type_comp_ids);
                                                  
                                                    // $type_comp_ids_array_for_js[$count] = $type_comp_ids_array;
                                                ?>

                                                <div class="plan_row">


                                                    <div class="row plan_row_dd">
                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <label for="select_category">Select Type</label>
                                                                <select class="type custom-select {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type[{{$count}}][type_name]" id="type">
                                                                    <option value="">[-- Select Type --]</option>
                                                                    <option value="top_trending" {{$value->type == "top_trending" ? 'selected' : ''}}>Top trending </option>
                                                                    <option value="trending_tab" {{$value->type == "trending_tab" ? 'selected' : ''}}>Trending tab</option>
                                                                    <option value="trending_tab_banner" {{$value->type == "trending_tab_banner" ? 'selected' : ''}}>Trending Tab banner</option>


                                                                    <option value="concert_tab" {{$value->type == "concert_tab" ? 'selected' : ''}}>Concert tab </option>
                                                                    <option value="concert_tab_banner" {{$value->type == "concert_tab_banner" ? 'selected' : ''}}>Concert tab banner</option>
                                                                    <option value="sports_tab" {{$value->type == "sports_tab" ? 'selected' : ''}}>Sports tab </option>
                                                                    <option value="sports_tab_banner" {{$value->type == "sports_tab_banner" ? 'selected' : ''}}>Sports tab bannner</option>
                                                                    <option value="trending_clubs" {{$value->type == "trending_clubs" ? 'selected' : ''}}>Trending clubs</option>
                                                                    <option value="popular_events" {{$value->type == "popular_events" ? 'selected' : ''}}>Popular events</option>

                                                                    <option value="popular_events_banner" {{$value->type == "popular_events_banner" ? 'selected' : ''}}>Popular events banner</option>
                                                                    <option value="popular_cities" {{$value->type == "popular_cities" ? 'selected' : ''}}>Popular Cities</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-3 cat_dd">
                                                            <div class="form-group">
                                                                <label for="select_category">Select Category</label>
                                                                <select class="select_category select_category_id custom-select {{ $errors->has('select_category') ? 'is-invalid' : '' }}" name="select_category[{{$count}}][name]" id="select_category_id">
                                                                    <option value="">[-- Select Category --]</option>

                                                                    <option value="category" {{$value->type_components == "category" ? 'selected' : ''}}>Category</option>
                                                                    <option value="club" {{$value->type_components == "club" ? 'selected' : ''}}>Club</option>
                                                                    <option value="tournament" {{$value->type_components == "tournament" ? 'selected' : ''}}>Tournament</option>
                                                                    <option value="event" {{$value->type_components == "event" ? 'selected' : ''}}>Event</option>
                                                                    <option value="city" {{$value->type_components == "city" ? 'selected' : ''}}>City</option>
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <div class="remove_sections">
                                                                    <label for="achivement_count">Select Option</label>
                                                                    <a href="javascript:void(0);" class="delete_row" style="cursor: pointer;" id="remove_row"><i style="" class="fas fa-times-circle text-danger"></i></a>
                                                                </div>
                                                                <?php
                                                                $options = isset($thirdOptions[$value->type_components]) ? $thirdOptions[$value->type_components] : [];
                                                                // $converted_options_for_js = array();
                                                                // foreach ($options as $value => $text) {
                                                                //     $converted_options_for_js[] = array('id' => $value, 'text' => $text);
                                                                // }
                                                                // $options_for_js[$count] = $converted_options_for_js;

                                                             
                                                                ?>

                                                                <select class="type_comp_ids  example" name="type_comp_ids[{{$count}}][]" id="type_comp_ids_{{$count}}" multiple="multiple">
                                                                    @foreach($options as $key => $option)
                                                                         <option value="{{$key}}" <?php echo in_array($key, $type_comp_ids_array) ? 'selected' : ''; ?> >{{$option}}</option>
                                                                    @endforeach
                                                                </select>
                                                                

                                                            </div>


                                                        </div>

                                                    </div>

                                                </div>

                                                <?php
                                                    $count++;
                                                }
                                                ?>

                                            <?php } else { ?>

                                                <div class="plan_row">
                                                    <div class="row plan_row_dd">
                                                        <div class="col-3">
                                                            <div class="form-group">
                                                                <label for="select_category">Select Type </label>
                                                                <select class="type custom-select {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type[{{$count}}][type_name]" id="type">
                                                                    <option value="">[-- Select Type --]</option>
                                                                    <option value="top_trending">Top trending </option>
                                                                    <option value="trending_tab">Trending tab</option>
                                                                    <option value="trending_tab_banner">Trending Tab banner</option>


                                                                    <option value="concert_tab">Concert tab </option>
                                                                    <option value="concert_tab_banner">Concert tab banner</option>
                                                                    <option value="sports_tab">Sports tab </option>
                                                                    <option value="sports_tab_banner">Sports tab bannner</option>
                                                                    <option value="trending_clubs">Trending clubs</option>
                                                                    <option value="popular_events">Popular events</option>

                                                                    <option value="popular_events_banner">Popular events banner</option>
                                                                    <option value="popular_cities">Popular Cities</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-3 cat_dd">
                                                            <div class="form-group">
                                                                <label for="select_category">Select Category</label>
                                                                <select class="select_category select_category_id custom-select {{ $errors->has('select_category') ? 'is-invalid' : '' }}" name="select_category[{{$count}}][name]" id="select_category_id">
                                                                    <option value="">[-- Select Category --]</option>

                                                                    <option value="category">Category</option>
                                                                    <option value="club">Club</option>
                                                                    <option value="tournament">Tournament</option>
                                                                    <option value="event">Event</option>
                                                                    <option value="city">City</option>

                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <div class="remove_sections">
                                                                    <label for="achivement_count">Select Option</label>
                                                                    {{-- <a href="javascript:void(0);" class="delete_row" style="cursor: pointer;"  id="remove_row"><i style=""class="fas fa-times-circle text-danger"></i></a> --}}
                                                                </div>
                                                                <select class="type_comp_ids chooser" name="type_comp_ids[{{$count}}][]" id="type_comp_ids_{{$count}}" multiple="multiple">
                                                                    <option value="">[-- Select Type --]</option>

                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div id="child_div" style="margin-top: 14px;"></div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer pl-0">
                                    <button type="submit" class="btn btn-warning">
                                        Save
                                    </button>

                                    <a class="btn btn-warning" style="float: right;" onclick="add_row()">Add More</a>

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

@stop
@stop

