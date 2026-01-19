@extends('layouts.templateauth', ['offCarousel' => 1])

@section('content')
<div class="d-flex flex-center flex-column flex-lg-row-fluid">
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
        <!--begin::Form-->
        <form class="form w-100" novalidate="novalidate" method="post" id="kt_sign_up_form" action="{{route('password.update')}}">
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <!--begin::Heading-->
            <div class="mb-11">
                <div class="d-flex mb-11 align-items-center">
                    <img src="{{ asset("theme/assets/media/logos/icon-sm.png") }}" class="w-50px">
                    <span class="fw-bold fs-1">{{ \Config::get("constants.APP_NAME") }}</span>
                </div>
                <!--begin::Title-->
                <h1 class="text-dark fw-bolder mb-3">Buat Password Baru</h1>
                <!--end::Title-->
                <!--begin::Subtitle-->
                @if (\Config::get("constants.IS_BP") == 0)
                <div class="text-gray-500 fw-semibold fs-6">
                    Pastikan password baru kamu belum pernah digunakan sebelumnya.
                </div>
                @endif
                <!--end::Subtitle=-->
            </div>
            @error('email')
                <div class="text-danger alert mb-5">
                    {{ $message }}
                </div>
            @enderror
            <div class="fv-row">
                <!--begin::Password-->
                <label for="password" class="col-form-label">Buat Password Baru</label>
                <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent @error('password') is-invalid @enderror" />
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                <!--end::Password-->
            </div>
            <!--end::Input group=-->
            <!--end::Input group=-->
            <div class="fv-row mb-5">
                <!--begin::Password-->
                <label for="password_confirmation" class="col-form-label">Konfirmasi Password</label>
                <input type="password" placeholder="Password" name="password_confirmation" autocomplete="off" class="form-control bg-transparent" />
                <!--end::Password-->
            </div>
            <!--end::Input group=-->
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
                <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
                    <!--begin::Indicator label-->
                    <span class="indicator-label">Lanjutkan</span>
                    <!--end::Indicator label-->
                    <!--begin::Indicator progress-->
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    <!--end::Indicator progress-->
                </button>
            </div>
        </form>
        <!--end::Form-->
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
