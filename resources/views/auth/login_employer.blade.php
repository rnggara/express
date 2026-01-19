@php
    $page_title = "Welcome to ".\Config::get("constants.APP_NAME")."";
    $page_image = asset("images/logo-white.png");
    $_page = [
        "title" => \Config::get("constants.APP_NAME"),
        "subtitle" => $page_title,
        "image" => $page_image
    ];
@endphp

@extends('layouts.templateauth', ["hris" => 1, "offCarousel" => 1])

@section("css")
<style>
    input[type=password]::-ms-reveal,
    input[type=password]::-ms-clear
    {
        display: none;
    }

    .login-ep {
        padding-left: 10rem;
        padding-right: 10rem;
    }

    /* Extra small devices (phones, 600px and down) */
    @media only screen and (max-width: 600px) {
        .login-ep {
            padding-left: 0rem;
            padding-right: 0rem;
        }

        .rside {
            display:none!important;
        }
    }

    /* Small devices (portrait tablets and large phones, 600px and up) */
    @media only screen and (min-width: 600px) {
        .login-ep {
            padding-left: 0rem;
            padding-right: 0rem;
        }

        .rside {
            display:none!important;
        }
    }

    /* Medium devices (landscape tablets, 768px and up) */
    @media only screen and (min-width: 768px) {
        .login-ep {
            padding-left: 5rem;
            padding-right: 5rem;
        }

        .rside {
            display:none!important;
        }
    }

    /* Large devices (laptops/desktops, 992px and up) */
    @media only screen and (min-width: 992px) {
        .login-ep {
            padding-left: 5rem;
            padding-right: 5rem;
        }

        .rside {
            display:flex!important;
        }
    }

    /* Extra large devices (large laptops and desktops, 1200px and up) */
    @media only screen and (min-width: 1200px) {
        .login-ep {
            padding-left: 10rem;
            padding-right: 10rem;
        }

        .rside {
            display:flex!important;
        }
    }

</style>
@endsection

@section('content')

<div class="d-flex flex-column flex-lg-row-fluid">
    <!--begin::Wrapper-->
    <div class="py-20 login-ep">
        <!--begin::Form-->
        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" method="post" data-kt-redirect-url="{{ route("home") }}" action="{{route('login')}}">
            <!--begin::Heading-->
            <div class="mb-7">
                <div class="d-flex mb-11 align-items-center">
                    <img src="{{ asset(\Config::get("constants.APP_LOGO")) }}" class="w-200px">
                </div>
                <!--begin::Title-->
                <span class="text-dark fw-bold mb-3" style="font-size: 36px">Login</span>
                <!--end::Title-->
                <!--begin::Subtitle-->
                @if (\Config::get("constants.IS_BP") == 0)
                <div class="fw-semibold fs-6">
                    Hallo Selamat datang kembali, silahkan login untuk masuk ke sistem
                </div>
                @endif
                <!--end::Subtitle=-->
            </div>
            <!--begin::Heading-->
            @if (\Config::get("constants.IS_BP") == 0)
            <!--begin::Login options-->
            <!--end::Login options-->
            @endif
            <!--begin::Input group=-->
            <div class="fv-row mb-8 @error('email') fv-plugins-bootstrap5-row-invalid @enderror">
                <!--begin::Email-->
                <label for="email" class="col-form-label">Email</label>
                <input type="text" placeholder="Masukkan alamat email" name="email" autocomplete="off" class="form-control bg-transparent" />
                @error('email')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        <div data-field="email" data-validator="notEmpty">{{$message}}</div>
                    </div>
                @enderror
                <!--end::Email-->
            </div>
            <!--end::Input group=-->
            <div class="fv-row mb-3 @error('password') fv-plugins-bootstrap5-row-invalid @enderror">
                <!--begin::Password-->
                <label for="password" class="col-form-label">Password</label>
                <div class="input-group">
                    <input type="password" placeholder="Masukkan password" name="password" autocomplete="off" class="form-control bg-transparent border-right-0" />
                    <span class="input-group-text bg-transparent border-left-0">
                        <i class="fa fa-eye" style="display: none"></i>
                    </span>
                </div>
                @error('password')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        <div data-field="password" data-validator="notEmpty">{{$message}}</div>
                    </div>
                @enderror
                <!--end::Password-->
            </div>
            <!--end::Input group=-->
            @if (\Config::get("constants.IS_BP") == 0)
            @endif
            <!--begin::Submit button-->
            <div class="d-grid mb-10">
                @if(!empty($who))
                <input type="hidden" name="company_id" value="{{$who->id}}">
                <input type="hidden" name="tag" value="{{ $who->tag }}">
                @else
                <input type="hidden" name="id_company" id="id_company">
                @endif
                <input type="hidden" name="locale" id="locale" value="{{ $locale ?? "id" }}">
                @csrf
                <input type="hidden" name="role" value="admin">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                    <!--begin::Indicator label-->
                    <span class="indicator-label">Sign In</span>
                    <!--end::Indicator label-->
                    <!--begin::Indicator progress-->
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    <!--end::Indicator progress-->
                </button>
            </div>
            <!--end::Submit button-->
            @if (\Config::get("constants.IS_BP") == 0)
            <!--begin::Sign up-->
            <div class="text-gray-500 text-center fw-semibold fs-6">Saya telah membaca dan menyetujui <a href="#">Ketentuan</a> Penggunaan kerjaku portal dan
                <a href="#">Kebijakan pribadi</a></div>
            <!--end::Sign up-->
            @endif
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-md-center">
        <a href="https://play.google.com/store/apps/details?id=com.kerjaku.mobile" class="w-25" target="_blank" data-bs-toggle="tooltip" data-bs-placement="Top" title="Download for mobile attendance">
            <img src="{{ asset("images/playstore.png") }}" class="w-100" alt="">
        </a>
        <a href="https://apps.apple.com/us/app/kerjaku/id6468767568" class="w-25" target="_blank" data-bs-toggle="tooltip" data-bs-placement="Top" title="Download for mobile attendance">
            <img src="https://cdn.freebiesupply.com/logos/large/2x/download-on-the-app-store-apple-logo-black-and-white.png" class="w-100">
        </a>
    </div>
</div>
@endsection

@section('custom_script')
<script src="{{ asset("theme/assets/js/custom/authentication/sign-in/general.js") }}"></script>
<script>

    var fs = window.RequestFileSystem || window.webkitRequestFileSystem;
    if (!fs) {
      console.log("check failed?");
    } else {
      fs(window.TEMPORARY,
         100,
         console.log.bind(console, "not in incognito mode"),
         console.log.bind(console, "incognito mode"));
    }
    $(document).ready(function(){

        $("input[name=password]").on("keyup", function(){
            var val = $(this).val()
            var i = $(this).next().find("i")
            if(val != ""){
                i.show()
            } else {
                i.hide()
            }
        })

        $("input[name=password]").next().click(function(){
            const type = $("input[name=password]").attr("type") === 'password' ? 'text' : 'password';
            $("input[name=password]").attr("type", type)
            if(type == "text"){
                $(this).find("i").removeClass("fa-eye")
                $(this).find("i").addClass("fa-eye-slash")
            } else {
                $(this).find("i").addClass("fa-eye")
                $(this).find("i").removeClass("fa-eye-slash")
            }
        })

    })
</script>
@endsection
