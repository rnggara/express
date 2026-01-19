@extends('layouts.templateauth', ['img' => asset("images/forgot_password.png"), 'offCarousel' => 1, "img_size" => "70%"])

@section('content')
<div class="d-flex flex-center flex-column flex-lg-row-fluid">
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
        <!--begin::Form-->
        <form class="form w-100" novalidate="novalidate" method="post" id="kt_sign_up_form" action="{{route('password.email')}}">
            <!--begin::Heading-->
            <div class="mb-11">
                <div class="d-flex mb-11 align-items-center">
                    <img src="{{ asset("images/logos/logo.png") }}" class="w-200px">
                </div>
                <!--begin::Title-->
                <h1 class="text-dark fw-bolder mb-3">Lupa Kata Sandi</h1>
                <!--end::Title-->
                <!--begin::Subtitle-->
                <div class="text-gray-500 fw-semibold fs-6">
                    Silahkan masukkan alamat emailmu.
                    Kami akan mengirimkan link untuk mengatur ulang kata sandi kamu.
                </div>
                <!--end::Subtitle=-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group=-->
            <div class="fv-row mb-8">
                <!--begin::Email-->
                <label for="email" class="col-form-label">Email</label>
                <input type="text" placeholder="Input Email Anda" name="email" autocomplete="off" class="form-control bg-transparent @error('email') is-invalid @enderror" />
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <!--end::Email-->
            </div>
            <div class="my-5" id="recaptcha_reg"></div>
            <!--begin::Submit button-->
            <div class="d-grid mb-10">
                @if(!empty($who))
                <input type="hidden" name="company_id" value="{{$who->id}}">
                <input type="hidden" name="tag" value="{{ $who->tag }}">
                @else
                <input type="hidden" name="id_company" id="id_company">
                @endif
                <input type="hidden" name="locale" id="locale">
                @csrf
                <button type="button" id="kt_sign_up_submit" data-sitekey="{{ config("services.recaptcha_v3.siteKey") }}" class="btn btn-primary d-none g-recaptcha">
                    <!--begin::Indicator label-->
                    <span class="indicator-label">Kirim</span>
                    <!--end::Indicator label-->
                    <!--begin::Indicator progress-->
                    <span class="indicator-progress">Mohon Tunggu...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    <!--end::Indicator progress-->
                </button>
            </div>
            <div class="text-gray-500 text-center fw-semibold fs-6">Tidak Lupa Kata Sandi?
            <a href="{{ route("login") }}" class="text-primary">Silahkan Masuk Di Sini</a></div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
@endsection

@section('custom_script')
{{-- <script src="{{ asset("theme/assets/js/custom/authentication/sign-up/general.js") }}"></script> --}}
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script>
    $("input.is-invalid").on("keyup", function(){
        $(this).removeClass("is-invalid")
    })

    var widget1;

    var verifyCallback = function(response) {
        var res = grecaptcha.getResponse(widget1)
        if(res.length == 0){
            Swal.fire("reCaptcha", "Failed to verify", 'warning')
        } {
            $("#kt_sign_up_submit").prop("type", "submit")
            $("#kt_sign_up_submit").removeClass("d-none")
        }
    };

    var onloadCallback = function() {
        widget1 = grecaptcha.render('recaptcha_reg', {
            'sitekey' : '{{ config("services.recaptcha_v3.siteKey") }}',
            'callback' : verifyCallback,
        });
    };
</script>
@endsection
