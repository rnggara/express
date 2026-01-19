@extends('layouts.templateauth', ['img' => asset("images/registration_complete.png"), 'img_size' => "70%", 'offCarousel' => 1])

@section('content')
<div class="d-flex flex-center flex-column flex-lg-row-fluid">
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
        <!--begin::Heading-->
        <div class="mb-11">
            <a href="https://karir.kerjaku.cloud" class="d-flex mb-11 align-items-center text-dark">
                <img src="{{ asset(\Config::get("constants.APP_ICON")) }}" class="w-50px">
                <span class="fw-bold fs-1">{{ \Config::get("constants.APP_NAME") }}</span>
            </a>
            <!--begin::Title-->
            <h1 class="text-dark fw-bolder mb-3">{{ __('Konfirmasi Alamat Email Anda') }}</h1>
            <!--end::Title-->
            <!--begin::Subtitle-->
            <div class="text-gray-500 fw-semibold fs-6">
                {{-- Ikuti langkah-langkah berikut untuk mengatur ulang passsword --}}
            </div>
            <!--end::Subtitle=-->
        </div>
        <!--begin::Heading-->
        <!--begin::Input group=-->
        <div class="fv-row mb-8">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('Link verifikasi sudah dikirim ke email.') }}
                </div>
            @endif

            {{-- {{ __('Link verifikasi dapat Anda temukan di "Pesan Masuk" di Email yang Anda daftarkan.') }}
            {{ __('Jika Anda belum menerima Email') }} --}}
            <div class="mb-10">{{ __('Ikuti langkah langkah berikut untuk konfirmasi email Anda') }}</div>
            <div class="d-flex flex-column">
                <div class="d-flex align-items-center mb-5">
                    <div class="symbol symbol-50px me-3">
                        <div class="symbol-label bg-primary">
                            <i class="fa fa-message text-white fs-2"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-between h-100">
                        <span class="fw-bold">Langkah 1</span>
                        <span>Buka kotak masuk {{ $email ?? (Auth::user()->email ?? "") }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-5">
                    <div class="symbol symbol-50px me-3">
                        <div class="symbol-label bg-primary">
                            <i class="fa fa-paperclip text-white fs-2"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-between h-100">
                        <span class="fw-bold">Langkah 2</span>
                        <span>Cari email yang berjudul "Verify Email Address" Jika Anda tidak melihatnya, Silahkan cek folder sampah/spam Anda.</span>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-5">
                    <div class="symbol symbol-50px me-3">
                        <div class="symbol-label bg-primary">
                            <i class="fa fa-lock text-white fs-2"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-between h-100">
                        <span class="fw-bold">Langkah 3</span>
                        <span>Di email, klik tombol "Verify Email Address"</span>
                    </div>
                </div>
            </div>
            @if (!empty($email) || !empty(Auth::user()))
                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email ?? (Auth::user()->email ?? "") }}">
                    <button type="submit" class="btn btn-outline btn-outline-primary align-baseline">{{ __('Kirim ulang email') }}</button>.
                </form>
            @endif
        </div>
        @auth
        <div class="separator separator-content my-14">
            <span class="w-125px text-gray-500 fw-semibold fs-7">Atau</span>
        </div>
        <form action="{{ route('logout') }}" class="px-5 menu-link" method="POST">
            @csrf
            <div class="d-grid mb-10">
                <button type="submit" class="btn btn-primary">
                    Keluar
                </button>
            </div>
        </form>
        @endauth
        @guest
        <div class="d-grid mb-10">
            <a href="{{ route("login") }}" id="kt_sign_up_submit" class="btn btn-primary">
                <!--begin::Indicator label-->
                <span class="indicator-label">Masuk Sekarang</span>
                <!--end::Indicator label-->
            </a>
        </div>
        @endguest
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
