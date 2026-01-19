@extends('layouts.templateauth')

@section('content')
<style>
    input[type=password]::-ms-reveal,
    input[type=password]::-ms-clear
    {
        display: none;
    }
</style>
<div class="d-flex flex-column flex-lg-row-fluid">
    <!--begin::Wrapper-->
    <div class="py-10 px-10 px-md-20">
        <!--begin::Form-->
        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" method="post" data-kt-redirect-url="{{ URL::to($_GET['redirect'] ?? route("home")) }}" action="{{route('login')}}">
            <!--begin::Heading-->
            <div class="mb-11">
                <a href="{{ route('lp') }}" class="d-flex mb-11 align-items-center">
                    <img src="{{ asset(\Config::get("constants.APP_LOGO")) }}" class="w-200px">
                </a>
                <!--begin::Title-->
                <span class="text-dark fw-bold mb-3" style="font-size: 36px">Masuk Sekarang!</span>
                <!--end::Title-->
                <!--begin::Subtitle-->
                @if (\Config::get("constants.IS_BP") == 0)
                <div class="fw-semibold fs-6">
                    Selamat datang kembali di {{ \Config::get("constants.APP_NAME") }}.<br>
                    Apakah kamu tidak punya akun? Jika iya, Daftar <a href="{{ route('register') }}" class="fw-bold"><u>Disini</u></a>
                </div>
                @endif
                <!--end::Subtitle=-->
            </div>
            <!--begin::Heading-->
            @if (\Config::get("constants.IS_BP") == 0)
            @endif
            <!--begin::Input group=-->
            <div class="fv-row mb-5 mb-md-8 @error('email') fv-plugins-bootstrap5-row-invalid @enderror">
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
                <label for="password" class="col-form-label">Kata Sandi</label>
                <div class="input-group">
                    <input type="password" placeholder="Masukkan Kata Sandi" name="password" autocomplete="off" class="form-control bg-transparent border-right-0" />
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
            <!--begin::Wrapper-->
            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                <div></div>
                <!--begin::Link-->
                <a href="{{ route("password.request") }}">Lupa Kata Sandi?</a>
                <!--end::Link-->
            </div>
            <!--end::Wrapper-->
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
                @if (\Config::get("constants.IS_BP") == 1)
                <input type="hidden" name="role" value="admin">
                @endif
                <input type="hidden" name="role" value="applicant">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                    <!--begin::Indicator label-->
                    <span class="indicator-label">Masuk</span>
                    <!--end::Indicator label-->
                    <!--begin::Indicator progress-->
                    <span class="indicator-progress">Mohon tunggu...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    <!--end::Indicator progress-->
                </button>
            </div>
            <!--end::Submit button-->
            @if (\Config::get("constants.IS_BP") == 0)
            <!--begin::Sign up-->
            <div class="text-gray-500 text-center fw-semibold fs-6">Saya telah membaca dan menyetujui <a href="{{ route("term.page") }}" target="_blank">Ketentuan</a> Penggunaan kerjaku portal dan
                <a href="{{ route("policy.page") }}" target="_blank">Kebijakan pribadi</a></div>
            <!--end::Sign up-->
            @endif
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
@component('layouts.components.wa_button')

@endcomponent
@endsection

@section('custom_script')
<script src="{{ asset("theme/assets/js/custom/authentication/sign-in/general.js")."?v=".date("Ymdhis") }}"></script>
<script>
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
