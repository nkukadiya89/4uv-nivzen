@extends('admin.layouts.login')
@section('content')
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Aside-->
        <div class="login-aside d-flex flex-row-auto bgi-size-cover bgi-no-repeat p-10 p-lg-10" style="background-color: #F2C98A;">
            <!--begin: Aside Container-->
            <div class="d-flex flex-row-fluid flex-column justify-content-between">
                <!--begin: Aside header-->
               
                <div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
                    <!--begin::Aside header-->
                    <a href="#" class="text-center mb-10">
                        {{-- <img src="assets/media/logos/logo-letter-1.png" class="max-h-70px" alt=""> --}}
                    </a>
                    <!--end::Aside header-->
                    <!--begin::Aside title-->
                    <h3 class="font-weight-bolder text-center font-size-h4 font-size-h1-lg" style="color: #986923;">Discover Amazing Metronic
                    <br>with great build tools</h3>
                    <!--end::Aside title-->
                </div>
                <!--end: Aside header-->
                <!--begin: Aside content-->
                <div class="flex-column-fluid d-flex flex-column justify-content-center">
                   
                </div>
                <!--end: Aside content-->
                <!--begin: Aside footer for desktop-->
                <div class="d-none flex-column-auto d-lg-flex justify-content-between mt-10">
                    <div class="opacity-70 font-weight-bold text-black">© {{date('Y')}} Edulake</div>
                   
                </div>
                <!--end: Aside footer for desktop-->
            </div>
            <!--end: Aside Container-->
        </div>
        <!--begin::Aside-->
        <!--begin::Content-->
        <div class="d-flex flex-column flex-row-fluid position-relative p-7 overflow-hidden">
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
                    <div class="text-center mb-5 mb-lg-5">
                        <h3 class="font-size-h1">Sign In</h3>
                        <p class="text-muted font-weight-bold">Enter your username and password</p>
                        <div class="text-gray-400 fw-bold fs-4">New Here?
                             <a href="javascript:;" class="text-dark-50 text-hover-primary my-3 mr-2" id="kt_login_signup">Create an Account ?
                             </a>
                        </div>

                    </div>
                    
                    <!--begin::Form-->
                    <form class="form"  action="{{ route('backend.login') }}" novalidate="novalidate" id="kt_login_signin_form">
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" type="email" name="email" placeholder="Email" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Password" name="password" autocomplete="off" />
                        </div>
                        <!--begin::Action-->
                        <div class="form-group d-flex flex-wrap justify-content-between align-items-center">
                            <a href="javascript:;" class="text-dark-50 text-hover-primary my-3 mr-2" id="kt_login_forgot">Forgot Password ?</a>
                            <button type="submit" id="kt_login_signin_submit" class="btn btn-warning font-weight-bold px-9 py-4 my-3">Sign In </button>
                        </div>
                        <!--end::Action-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Signin-->
                <!--begin::Signup-->
                <div class="login-form login-signup">
                    <div class="text-center mb-5 mb-lg-5">
                        <h3 class="font-size-h1">Sign Up</h3>
                        <p class="text-muted font-weight-bold">Enter your details to create your account</p>
                    </div>
                    <!--begin::Form-->
                    <form class="form" action="{{ route('backend.register') }}" novalidate="novalidate" id="kt_login_signup_form">
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" type="text" placeholder="First Name" id="firstname" name="firstname" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" id="lastname" type="text" placeholder="last Name" name="lastname" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" type="email" placeholder="Email" id="email" name="email" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Password" name="password" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" type="password" placeholder="Confirm password" name="cpassword" autocomplete="off" />
                        </div>
                        
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" type="text" placeholder="Phone number" name="phone" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" type="date" placeholder="Birth date" name="dob" autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label class="checkbox mb-0">
                            <input type="checkbox" name="agree" />
                            <span></span> &nbsp; I Agree the
                            <a href="#">&nbsp; terms and conditions</a></label>
                        </div>
                        <div class="form-group d-flex flex-wrap flex-center">
                            <button type="button" id="kt_login_signup_submit" class="btn btn-warning font-weight-bold px-9 py-4 my-3 mx-4">Submit</button>
                          
                            <button type="button" id="kt_login_signup_cancel" class="btn btn-light-warning font-weight-bold px-9 py-4 my-3 mx-4">Cancel</button>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Signup-->
                <!--begin::Forgot-->
                <div class="login-form login-forgot">
                    <div class="text-center mb-10 mb-lg-20">
                        <h3 class="font-size-h1">Forgotten Password ?</h3>
                        <p class="text-muted font-weight-bold">Enter your email to reset your password</p>
                    </div>
                    <!--begin::Form-->
                    <form class="form" action="{{route('backend.reset.password')}}" novalidate="novalidate" id="kt_login_forgot_form">
                        <div class="form-group">
                            <input class="form-control form-control-solid h-auto py-5 px-6" type="email" placeholder="Email" name="email" autocomplete="off" />
                        </div>
                        <div class="form-group d-flex flex-wrap flex-center">
                            <button type="submit" id="kt_login_forgot_submit" class="btn btn-warning font-weight-bold px-9 py-4 my-3 mx-4">Submit</button>
                            <button type="button" id="kt_login_forgot_cancel" class="btn btn-light-warning font-weight-bold px-9 py-4 my-3 mx-4">Cancel</button>
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
