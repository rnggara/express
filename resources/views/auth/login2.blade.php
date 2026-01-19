@extends('layouts.templateauth2')
@section('title','Login')

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
<!--begin::Signin-->
<div class="login-form login-signin py-11">
    <!--begin::Form-->
    <form class="form" method="POST" action="{{route('login')}}" id="kt_login_signin_form">
        <!--begin::Title-->
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
        <div class="text-center pb-8">
            <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Sign In</h2>
            @if(!empty($who))
            <span class="font-weight-bold font-size-h4" style="color: {{$who->bgcolor}}">
                {{$who->company_name}}
            </span>
            @else
            <span class="font-weight-bold font-size-h4" id="company_name">

            </span>
            @endif
        </div>
        <div class="d-flex align-items-center mb-6 mx-auto">
            <div class="mx-auto text-center">
                @foreach($companies as $key => $value)
                    <div class="symbol symbol-40 mt-5 symbol-light-primary mr-5">
                        <span class="symbol-label">
                            <a href="javascript:;" onclick="getIdCompany({{$value->id}})">
                                  <span class="svg-icon svg-icon-lg svg-icon-primary">
                                      <!--begin::Svg Icon | path:assets/media/svg/icons/Home/Library.svg-->
                                      <img src='{{str_replace("public", "public_html", asset('images/'.$value->app_logo))}}' style="align-content: center;" max-width='95%' @if($value->tag == 'RCN') height='15px' @else height='30px' @endif  alt="{{$value->company_name}}"/> &nbsp;&nbsp;
                                      <!--end::Svg Icon-->
                                  </span>
                            </a>
                        </span>
                    </div>
                @endforeach
            </div>

        </div>
        <!--end::Title-->
        <!--begin::Form group-->
        <div class="form-group">
            <label class="font-size-h6 font-weight-bolder text-dark">Username</label>
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" type="text" id="txt-user" name="username" required />
            @error('username')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
        <!--end::Form group-->
        <!--begin::Form group-->
        <div class="form-group">
            <div class="d-flex justify-content-between mt-n5">
                <label class="font-size-h6 font-weight-bolder text-dark pt-5">Password</label>
                @if (\Config::get("constants.IS_BP") == 0)
                    <a href="javascript:;" class="text-primary font-size-h6 font-weight-bolder text-hover-primary pt-5" id="kt_login_forgot">
                        Forgot Password ?
                    </a>
                @endif
            </div>
            <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg" id="pw" type="password" name="password" required />

            @error('password')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <!--end::Form group-->
        <!--begin::Action-->
        <div class="text-center pt-2">
            @if(!empty($who))
            <input type="hidden" name="company_id" value="{{$who->id}}">
            <input type="hidden" name="tag" value="{{ $who->tag }}">
            @else
            <input type="hidden" name="id_company" id="id_company">
            @endif
            <input type="hidden" name="locale" id="locale">
            <button type="submit" id="kt_login_signin_submit" class="btn btn-primary btn-sm font-weight-bolder font-size-h6 px-8 py-4 my-3">Sign In </button>
        </div>
        @if (\Config::get("constants.IS_BP") == 0)
            <div class="text-center">
                OR
            </div>
            <div class="text-center pt-2">
                <a href="javascript;" id="kt_login_signup" class="btn btn-primary btn-sm font-weight-bolder font-size-h6 px-8 py-4 my-3">Create an Account</a>
            </div>
        @endif
        <!--end::Action-->
    </form>
    <!--end::Form-->
</div>
<!--end::Signin-->

@if (\Config::get("constants.IS_BP") == 0)
    <!--begin::Signup-->
    <div class="login-form login-signup pt-11">
        <!--begin::Form-->
        <form class="form" action="{{ route("register") }}" method="post" novalidate="novalidate" id="kt_login_signup_form">
            @csrf
            @php
                $errorSignup = $errors->register;
            @endphp
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
                <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @if($errorSignup->has("username")) is-invalid @endif" type="text" placeholder="Username" name="username" autocomplete="off"/>
                @if($errorSignup->has("username"))
                    <div class="fv-plugins-message-container">
                        <div data-field="username" data-validator="notEmpty" class="fv-help-block">{{ $errorSignup->first("username") }}</div>
                    </div>
                @endif
            </div>
            <!--end::Form group-->

            <!--begin::Form group-->
            <div class="form-group">
                <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @if($errorSignup->has("email")) is-invalid @endif" type="email" placeholder="Email" name="email" autocomplete="off"/>
                @if($errorSignup->has("email"))
                    <div class="fv-plugins-message-container">
                        <div data-field="email" data-validator="notEmpty" class="fv-help-block">{{ $errorSignup->first("email") }}</div>
                    </div>
                @endif
            </div>
            <!--end::Form group-->

            <!--begin::Form group-->
            <div class="form-group">
                <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6 @if($errorSignup->has("password")) is-invalid @endif" type="password" placeholder="Password" name="password" autocomplete="off"/>
                @if($errorSignup->has("password"))
                    <div class="fv-plugins-message-container">
                        <div data-field="password" data-validator="notEmpty" class="fv-help-block">{{ $errorSignup->first("password") }}</div>
                    </div>
                @endif
            </div>
            <!--end::Form group-->

            <!--begin::Form group-->
            <div class="form-group">
                <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="password" placeholder="Confirm password" name="password_confirmation" autocomplete="off"/>
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
                <button type="button" id="kt_login_signup_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Register</button>
                <button type="button" id="kt_login_signup_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Cancel</button>
            </div>
            <!--end::Form group-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Signup-->

    <!--begin::Forgot-->
    <div class="login-form login-forgot pt-11">
        <!--begin::Form-->
        <form class="form" action="{{ route("password.email") }}" method="post" novalidate="novalidate" id="kt_login_forgot_form">
            @csrf
            <!--begin::Title-->
            <div class="text-center pb-8">
                <h2 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Forgotten Password ?</h2>
                <p class="text-muted font-weight-bold font-size-h4">Enter your email to reset your password</p>
            </div>
            <!--end::Title-->

            <!--begin::Form group-->
            <div class="form-group">
                <input class="form-control form-control-solid h-auto py-7 px-6 rounded-lg font-size-h6" type="email" placeholder="Email" name="email" autocomplete="off"/>
            </div>
            <!--end::Form group-->

            <!--begin::Form group-->
            <div class="form-group d-flex flex-wrap flex-center pb-lg-0 pb-3">
                <button type="button" id="kt_login_forgot_submit" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Forgot Password</button>
                <button type="button" id="kt_login_forgot_cancel" class="btn btn-light-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mx-4">Cancel</button>
            </div>
            <!--end::Form group-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Forgot-->
@endif
@endsection
@section('custom_script')
    <script type="text/javascript">

        @if (\Session::has("status"))
            Swal.fire("Password Reset", "{{ Session::get('status') }}", "success")
        @endif

        function getIdCompany(x){
            if(confirm('Switch Company. Are you sure?')){
                $('#id_company').val(x)
                company_name(x)
            }
            // console.log($('#id_company').val())
        }

        @error("delete_account")
            Swal.fire("Delete Account", "{{ $message }}", "info")
        @enderror

        function company_name(x) {
            $.ajax({
                url: "{{route('home.get_company')}}/"+x,
                type: "get",
                dataType: "json",
                cache: false,
                success: function(response){
                    $("#company_name").text(response.company_name)
                    $("#company_name").css('color', response.bgcolor)
                }
            })
        }
        $(document).ready(function(){

            var _locale = "{{ $locale ?? "" }}"
            $("#locale").val(_locale != "" ? _locale : window.navigator.languages[1])

            company_name(1)
            $("#txt-user").focus()

            @if ($errors->register->any())
                KTLogin.showForm("signup")
            @endif


            @if (!empty($user))
                $("#txt-user").val("{{ $user }}")
                $("#pw").val("{{ $pass }}")
                $("#btn-sign-in").click()
            @endif
        })

    </script>
@endsection
