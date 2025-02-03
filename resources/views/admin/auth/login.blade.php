@extends('admin.layouts.login')
@section('content')
    <!--begin::Main-->


    <div class="loginbg d-flex flex-column flex-root">
        <!--begin::Login-->
        <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid " id="kt_login">
            <!--begin::Aside-->
            <div class="flex-fill loginleft">
                <!--begin: Aside Container-->
                <div class="d-flex flex-row-fluid flex-column align-items-end justify-content-end">
                    <!--begin: Aside header-->

                    <div class="d-none d-xl-inline-flex flex-column-auto flex-column logincontent">
                        <!--begin::Aside header-->

                        <!--end::Aside header-->
                        <!--begin::Aside title-->
                        <h3>
                            Change Your Water, Change Your Life!
                        </h3>
                        <p>Using advanced technology from Japan, our Kangen Water machine is able to convert your ordinary
                            drinking water into delicious alkaline drinking water.</p>
                        <!--end::Aside title-->
                    </div>
                    <!--end: Aside header-->

                    <!--begin: Aside footer for desktop-->

                    <!--end: Aside footer for desktop-->
                </div>
                <!--end: Aside Container-->
            </div>
            <!--begin::Aside-->
            <!--begin::Content-->
            <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden loginwrap">
                <!--begin::Content header-->
            {{-- <div class="position-absolute top-0 right-0 text-right mt-5 mb-15 mb-lg-0 flex-column-auto justify-content-center py-5 px-10">
                <span class="font-weight-bold text-dark-50">Dont have an account yet?</span>
                <a href="javascript:;" class="font-weight-bold ml-2" id="kt_login_signup">Sign Up!</a>
            </div> --}}
            <!--end::Content header-->
                <!--begin::Content body-->
                <div class="d-flex flex-column-fluid flex-center mt-30 mt-lg-0">
                    <!--begin::Signin-->
                    <div class="login-form login-signin">
                        <div class="authpagename">
                            <a href="#" class="logo mb-5">
                                <img src="{{ asset('/images/logo.png') }}"  alt="logo" >
                            </a>
                            <h3>Sign In</h3>
                            <h5>The Distributor Portal</h5>

                        </div>

                        <!--begin::Form-->
                        <form class="form" action="{{ route('backend.login') }}" novalidate="novalidate"
                              id="kt_login_signin_form">
                            <div class="form-group">
                                <input class="form-control" type="email" name="email" placeholder="Email"
                                       autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control " type="password" placeholder="Password" name="password"
                                       autocomplete="off" />
                            </div>
                            <!--begin::Action-->
                            <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                                <a href="javascript:;" class="text-dark-50 text-hover-primary my-3 mr-2"
                                   id="kt_login_forgot">Forgot Password ?</a>
                                <button type="submit" id="kt_login_signin_submit" class="btn btn-primary">Sign In </button>
                            </div>
                            <!--end::Action-->
                        </form>

                        {{--<div class="text-gray-400 fw-bold fs-4">New Here?--}}
                            {{--<a href="javascript:;" class="text-dark-50 my-3 mr-2" id="kt_login_signup">Create an Account ?--}}
                            {{--</a>--}}
                        {{--</div>--}}
                        <!--end::Form-->
                    </div>
                    <!--end::Signin-->
                    <!--begin::Signup-->
                    <div class="login-form login-signup">
                        <div class="authpagename">
                            <a href="#" class="logo mb-5">
                                4UV
                            </a>
                            <h3>Sign Up</h3>
                            <h5>Enter your details to create your account</h5>
                        </div>
                        <!--begin::Form-->
                        <form class="form" action="{{ route('backend.register') }}" novalidate="novalidate"
                              id="kt_login_signup_form">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="First Name" id="firstname"
                                       name="firstname" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control" id="lastname" type="text" placeholder="last Name"
                                       name="lastname" autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="email" placeholder="Email" id="email" name="email"
                                       autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="Password" name="password"
                                       autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="Confirm password" name="cpassword"
                                       autocomplete="off" />
                            </div>

                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Phone number" name="phone"
                                       autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="date" placeholder="Birth date" name="dob"
                                       autocomplete="off" />
                            </div>
                            <div class="form-group">
                                <label class="checkbox mb-0">
                                    <input type="checkbox" name="agree" />
                                    <span></span> &nbsp; I Agree the
                                    <a href="#">&nbsp; terms and conditions</a></label>
                            </div>
                            <div class="form-group d-flex justify-content-end">
                                <button type="button" id="kt_login_signup_submit"
                                        class="btn btn-primary mx-2">Submit</button>

                                <button type="button" id="kt_login_signup_cancel"
                                        class="btn btn-secondary  mx-2 mr-0">Cancel</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Signup-->
                    <!--begin::Forgot-->
                    <div class="login-form login-forgot">
                        <div class="authpagename ">
                            <a href="#" class="logo mb-5">
                                4UV
                            </a>
                            <h3>Forgotten Password ?</h3>
                            <h5>Enter your email to reset your password</h5>
                        </div>
                        <!--begin::Form-->
                        <form class="form" action="{{route('backend.reset.password')}}" novalidate="novalidate"
                              id="kt_login_forgot_form">
                            <div class="form-group">
                                <input class="form-control" type="email" placeholder="Email" name="email"
                                       autocomplete="off" />
                            </div>
                            <div class="form-group d-flex justify-content-end">
                                <button type="submit" id="kt_login_forgot_submit"
                                        class="btn btn-primary mx-2">Submit</button>
                                <button type="button" id="kt_login_forgot_cancel"
                                        class="btn btn-secondary  mx-2 mr-0">Cancel</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Forgot-->
                </div>
                <!--end::Content body-->

            </div>
            <!--end::Content-->
        </div>
        <!--end::Login-->
    </div>

    <!--end::Page Scripts-->

@section('custom_js')
    <script>
        $(document).ready(function() {

            @if(Session::has('success-message'))
                  toastr.info("{{ session('success-message') }}");
            @endif


        });
    </script>
@stop
@stop
