@extends('layouts.templateauth', ['offCarousel' => 1])

@section('content')
<div class="d-flex flex-center flex-column flex-lg-row-fluid">
    <!--begin::Wrapper-->
    <div class="w-lg-500px p-10">
        <!--begin::Form-->
        <form class="form w-100" novalidate="novalidate" method="post" id="kt_sign_up_form" action="{{route('account.setting.activation_post')}}">
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="id" value="{{ base64_encode($user->id) }}">
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
                <!--end::Subtitle=-->
            </div>
            @error('email')
                <div class="text-danger alert mb-5">
                    {{ $message }}
                </div>
            @enderror
            <div class="fv-row" data-kt-password-meter="true">
                <!--begin::Password-->
                <label for="password" class="col-form-label required">Buat Password Baru</label>
                <div class="mb-1">
                    <input type="password" placeholder="Password" name="password" required autocomplete="off" class="form-control bg-transparent @error('password') is-invalid @enderror" />
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <!--begin::Highlight meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
                <!--end::Highlight meter-->
                <!--begin::Hint-->
                <div class="text-muted">
                    Use 8 or more characters with a mix of letters, numbers & symbols.
                </div>
                <!--end::Hint-->
                <!--end::Password-->
            </div>
            <!--end::Input group=-->
            <!--end::Input group=-->
            <div class="fv-row mb-5">
                <!--begin::Password-->
                <label for="password_confirmation" class="col-form-label required">Konfirmasi Password</label>
                <input type="password" placeholder="Password" name="password_confirmation" required autocomplete="off" class="form-control bg-transparent" />
                <!--end::Password-->
            </div>
            <!--end::Input group=-->
            <!--begin::Submit button-->
            <div class="d-grid mb-10">
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

    $(document).ready(function(){
        var form = document.getElementById("kt_sign_up_form")

        a = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'))
        s = function () {
            return a.getScore() > 50
        }

        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    password: {
                        validators: {
                            notEmpty: {
                                message: "The password is required"
                            },
                            callback: {
                                message: "Please enter valid password",
                                callback: function (e) {
                                    if (e.value.length > 0) return s()
                                }
                            }
                        }
                    },
                    password_confirmation: {
                        validators: {
                            notEmpty: {
                                message: "The password confirmation is required"
                            },
                            identical: {
                                compare: function () {
                                    return form.querySelector('[name="password"]').value
                                },
                                message: "The password and its confirm are not the same"
                            }
                        }
                    }
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger({
                        event : {
                            password: !1
                        }
                    }),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        form.querySelector('input[name="password"]').addEventListener("input", (function () {
            this.value.length > 0 && validator.updateFieldStatus("password", "NotValidated")
        }))

        form.querySelector('input[name="password_confirmation"]').addEventListener("input", (function () {
            this.value.length > 0 && validator.updateFieldStatus("password_confirmation", "NotValidated")
        }))

        $("#kt_sign_up_submit").click(function(e){
            e.preventDefault();
            validator.validate().then(function(r){
                form.submit()
            });
        })
    })
</script>
@endsection
