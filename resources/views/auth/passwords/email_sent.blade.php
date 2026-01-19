@extends('layouts.templateauth', ['img' => asset("images/forgot-password2.png"), 'img_size' => "70%", 'offCarousel' => 1])

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
            <h1 class="text-dark fw-bolder mb-3">Email untuk merubah
                password berhasil di kirim</h1>
            <!--end::Title-->
            <!--begin::Subtitle-->
            <div class="text-gray-500 fw-semibold fs-6">
                Ikuti langkah-langkah berikut untuk mengatur ulang passsword
            </div>
            <!--end::Subtitle=-->
        </div>
        <!--begin::Heading-->
        <!--begin::Input group=-->
        <div class="fv-row mb-8">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-2">
                    <div class="symbol-label fs-2 fw-semibold bg-primary">
                        <i class="fa fa-inbox text-white"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold">Langkah 1</span>
                    <span class="text-muted">Buka Inbox Email Kamu</span>
                </div>
            </div>
        </div>
        <!--begin::Submit button-->
        <!--begin::Input group=-->
        <div class="fv-row mb-8">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-2">
                    <div class="symbol-label fs-2 fw-semibold bg-primary">
                        <i class="fa fa-link text-white"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold">Langkah 2</span>
                    <span class="text-muted">Klik Link tautan di email</span>
                </div>
            </div>
        </div>
        <!--begin::Submit button-->
        <!--begin::Input group=-->
        <div class="fv-row mb-8">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-2">
                    <div class="symbol-label fs-2 fw-semibold bg-primary">
                        <i class="fa fa-lock text-white"></i>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold">Langkah 3</span>
                    <span class="text-muted">Lanjutkan pengaturan ulang password kamu di {{ \Config::get("constants.APP_NAME") }}</span>
                </div>
            </div>
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
