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

            <!--begin::Card-->
            <div class="card card-custom gutter-b">
               

                <div class="card-body">
                   {{-- User Details --}}
                    <div class="d-flex  justify-content-between">
                        <div class="w-100">
                            <div class="card col-12 mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="mb-0">Name</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted mb-0">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="mb-0">Email</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted mb-0">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="mb-0">Phone</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted mb-0">{{ $user->phone }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="mb-0">City</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted mb-0">{{ $user->city }}</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-3 col-6">
                                            <p class="mb-0">Address</p>
                                        </div>
                                        <div class="col-lg-9 col-6">
                                            <p class="text-muted mb-0">{{ $user->address1 . ' ' . $user->address2 }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    
                </div>
                
            </div>
            <!--end::Card-->
            <!--begin::Card 2-->
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h1 class="ml-2 mb-3 text-dark text-capitalize">Batch Details </h1>
                    </div>
                    
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="w-100">
                            <div class="card col-12 mb-4">
                                @foreach ($user->batches as $batch)
                                    <h3 class="p-2">{{$batch->course->title}}</h3>
                                    <div class="card-body"> 
                                        <div class="row">
                                            <div class="col-lg-3 col-6">
                                                <p class="mb-0">Title</p>
                                            </div>
                                            <div class="col-lg-9 col-6">
                                                <p class="text-muted mb-0">{!! $batch->title ?? '' !!}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-lg-3 col-6">
                                                <p class="mb-0">Description</p>
                                            </div>
                                            <div class="col-lg-9 col-6">
                                                <p class="text-muted mb-0">{!! $batch->description ?? '' !!}</p>
                                            </div>
                                        </div>
                                        <hr>
                                    
                                    
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card 2-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
@section('custom_js')
  
@stop
@stop

