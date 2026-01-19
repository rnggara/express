@extends('layouts.templateauth2')
@section('title','Register')

@section('login_logo', str_replace("public", "public_html", asset('images/'.$parent_comp->p_logo)))
@section('p_title', (empty($parent_comp->p_title)) ? "CYPHER - ERP" : $parent_comp->p_title)
@section('p_subtitle')
    <div class="font-weight-bolder font-size-h2-md font-size-lg opacity-70" style="color: #ffffff;">
        {!! (empty($parent_comp->p_subtitle)) ? "Manage Your Company <br> Anytime & Anywhere" : $parent_comp->p_subtitle !!}
    </div>
@endsection
@section('p_bg', (empty($parent_comp->p_bg)) ? asset('theme/assets/media/bg/bg_login.jpg') : str_replace('public', 'public_html', asset("images/".$parent_comp->p_bg)))
@section('p_bg_width', (empty($parent_comp->p_bg_width)) ? '100' : $parent_comp->p_bg_width)

@section('content')
<!--begin::Signup-->
<div class="login-form login-signup pt-11">
    <!--begin::Form-->
    <form class="form" action="{{ route("register") }}" method="post" novalidate="novalidate" id="kt_login_signup_form">
        @csrf
        <noscript>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" role="alert">
                       Your browser does not support JavaScript!
                    </div>
                    <div class="modal fade show" style="display: block; padding: 17px; background-color: rgb(0, 0, 0, .5)" id="exampleModalCenter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-danger" role="alert">
                                                Please turn on you JavaScript to access Cypher
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <ul>
                                                <li>
                                                    Chrome :
                                                    <ul>
                                                        <li>Settings > Site Settings > JavaScript : turn on allowed</li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    Microsoft Edge :
                                                    <ul>
                                                        <li>Settings > Cookies & Site Permissions > JavaScript : turn on allowed</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </noscript>
        <!--begin::Title-->
        <div class="text-center pb-8">
            <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign Up</h2>
            <p class="text-muted font-weight-bold font-size-h4">Enter your details to create your account</p>
        </div>
        <!--end::Title-->

        <!--begin::Form group-->
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="text" placeholder="Fullname" name="fullname" autocomplete="off"/>
        </div>
        <!--end::Form group-->

        <!--begin::Form group-->
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="text" placeholder="Username" name="username" autocomplete="off"/>
        </div>
        <!--end::Form group-->

        <!--begin::Form group-->
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off"/>
        </div>
        <!--end::Form group-->

        <!--begin::Form group-->
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="password" placeholder="Password" name="password" autocomplete="off"/>
        </div>
        <!--end::Form group-->

        <!--begin::Form group-->
        <div class="form-group">
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="password" placeholder="Confirm password" name="cpassword" autocomplete="off"/>
        </div>
        <!--end::Form group-->

        <!--begin::Form group-->
        <div class="form-group">
            <select name="register_as" class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6">
                <option value="44">Applicant</option>
                <option value="45">Employer</option>
            </select>
        </div>
        <!--end::Form group-->

        <!--begin::Form group-->
        <div class="form-group">
            <label class="checkbox mb-0">
                <input type="checkbox" name="agree"/>I Agree the <a href="#">terms and conditions</a>.
                <span></span>
            </label>
        </div>
        <!--end::Form group-->

        <!--begin::Form group-->
        <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
            <button type="button" id="kt_login_signup_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Submit</button>
            <button type="button" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Cancel</button>
        </div>
        <!--end::Form group-->
    </form>
    <!--end::Form-->
</div>
<!--end::Signup-->
@endsection
@section('custom_script')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".login-signup").show()
        })

    </script>
@endsection
