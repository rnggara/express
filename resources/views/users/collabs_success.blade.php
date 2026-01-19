@extends('layouts.templateauth', ['img' => asset("images/forgot-password3.png"), 'img_size' => "100%", 'offCarousel' => 1])

@section('content')
<div class="d-flex flex-center flex-column flex-lg-row-fluid">
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
        <!--begin::Heading-->
        <div class="mb-11">
            <div class="d-flex mb-11 align-items-center">
                <img src="{{ asset("theme/assets/media/logos/icon-sm.png") }}" class="w-50px">
                <span class="fw-bold fs-1">{{ \Config::get("constants.APP_NAME") }}</span>
            </div>
            <!--begin::Title-->
            <h1 class="text-dark fw-bolder mb-3">Akun berhasil dibuat</h1>
            <!--end::Title-->
            <!--begin::Subtitle-->
            <div class="text-gray-500 fw-semibold fs-6">
                Anda dapat login ke website
            </div>
            <!--end::Subtitle=-->
        </div>
        <!--begin::Heading-->
        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <a href="{{ route("login") }}" id="kt_sign_up_submit" class="btn btn-primary">
                <!--begin::Indicator label-->
                <span class="indicator-label">Login Sekarang</span>
                <!--end::Indicator label-->
            </a>
        </div>
    </div>
    <!--end::Wrapper-->
</div>
@endsection

@section('custom_script')
{{-- <script src="{{ asset("theme/assets/js/custom/authentication/sign-up/general.js") }}"></script> --}}
<script>
    $("input.is-invalid").on("keyup", function(){
        $(this).removeClass("is-invalid")
    })
</script>
@endsection
