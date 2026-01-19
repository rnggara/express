@extends('layouts.templateauth')

@section('content')
<div class="d-flex flex-column flex-lg-row-fluid">
    <!--begin::Wrapper-->
    <div class="py-10 px-10 px-md-20">
        <!--begin::Form-->
            <!--begin::Heading-->
            <div class="mb-11">
                <a href="{{ route('lp') }}" class="d-flex mb-11 align-items-center">
                    <img src="{{ asset(\Config::get("constants.APP_LOGO")) }}" class="w-200px">
                </a>
                <!--begin::Title-->
                <span class="text-dark fw-bold mb-3" style="font-size: 36px">Daftar Sekarang!</span>
                <!--end::Title-->
                <!--begin::Subtitle-->
                @if (\Config::get("constants.IS_BP") == 0)
                <div class="fw-semibold fs-6" id="lbl-app">
                    Kirim mudha, cepat, dan aman<br>
                    Apakah kamu sudah memiliki akun? Jika iya, Masuk <a href="{{ route('login') }}" class="fw-bold"><u>Disini</u></a>
                </div>
                @endif
                <!--end::Subtitle=-->
            </div>
        <form class="form w-100" novalidate="novalidate" method="post" id="kt_sign_up_form" data-kt-redirect-url="{{ URL::to("/registration-complete") }}" action="{{route('register')}}">
            <!--begin::Input group=-->
            <div class="fv-row">
                <!--begin::Nama Lengkap-->
                <label for="fullname" class="col-form-label">Nama Lengkap</label>
                <input type="text" placeholder="Input Nama Lengkap Anda" name="fullname" autocomplete="off" class="form-control bg-transparent border-primary @error('fullname') is-invalid @enderror" />
                @error('fullname')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
                <!--end::Nama Lengkap-->
            </div>
            <!--end::Input group=-->
            <!--begin::Input group=-->
            <div class="fv-row">
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
            <!--end::Input group=-->
            <div class="fv-row" data-kt-password-meter="true">
                <!--begin::Wrapper-->
                <div class="mb-1">
                    <!--begin::Input wrapper-->
                    <label for="password" class="col-form-label">Kata Sandi</label>
                    <div class="position-relative mb-3">
                        <input type="password" placeholder="Kata Sandi" name="password" autocomplete="off" class="form-control bg-transparent border-primary @error('password') is-invalid @enderror" />
                        <!--begin::Visibility toggle-->
                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                        data-kt-password-meter-control="visibility">
                                <i class="fa fa-eye-slash fs-3"></i>
                                <i class="fa fa-eye d-none fs-3"></i>
                        </span>
                        <!--end::Visibility toggle-->
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <!--end::Input wrapper-->
                    <!--begin::Meter-->
                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                    </div>
                    <!--end::Meter-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Hint-->
                <div class="text-muted">Gunakan 8 atau lebih karakter dengan menggabungkan huruf, angka & simbol.</div>
                <!--end::Hint-->
            </div>
            <!--end::Input group=-->
            <!--end::Input group=-->
            <div class="fv-row mb-5" data-kt-password-meter="true">
                <!--begin::Password-->
                <label for="password_confirmation" class="col-form-label">Konfirmasi Kata Sandi</label>
                <div class="position-relative mb-3">
                    <input type="password" placeholder="Kata Sandi" name="password_confirmation" autocomplete="off" class="form-control bg-transparent" />
                    <!--begin::Visibility toggle-->
                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                    data-kt-password-meter-control="visibility">
                        <i class="fa fa-eye-slash fs-3"></i>
                        <i class="fa fa-eye d-none fs-3"></i>
                    </span>
                    <!--end::Visibility toggle-->
                </div>
                <!--end::Password-->
            </div>
            <!--end::Input group=-->
            <div id="recaptcha_reg"></div>
            <!--begin::Submit button-->
            <div class="d-grid mb-10 mt-5">
                @if(!empty($who))
                <input type="hidden" name="company_id" value="{{$who->id}}">
                <input type="hidden" name="tag" value="{{ $who->tag }}">
                @else
                <input type="hidden" name="id_company" id="id_company">
                @endif
                <input type="hidden" name="locale" id="locale">
                @csrf
                <input type="hidden" name="register_as" value="44">
                <input type="hidden" name="role" id="applicant">
                <button type="button" data-sitekey="{{ config("services.recaptcha_v3.siteKey") }}" id="kt_sign_up_submit" class="btn btn-primary g-recaptcha d-none">
                    <!--begin::Indicator label-->
                    <span class="indicator-label">Daftar</span>
                    <!--end::Indicator label-->
                    <!--begin::Indicator progress-->
                    <span class="indicator-progress">Mohon Tunggu...
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
<script src="{{ asset("theme/assets/js/custom/authentication/sign-up/general.js") }}?v={{ time() }}"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script>
    $("input.is-invalid").on("keyup", function(){
        $(this).removeClass("is-invalid")
    })

    var widget1;
    var widget2;

    var verifyCallback = function(response) {
        var res = grecaptcha.getResponse(widget1)
        if(res.length == 0){
            Swal.fire("reCaptcha", "Failed to verify", 'warning')
        } {
            $("#kt_sign_up_submit").prop("type", "submit")
            $("#kt_sign_up_submit").removeClass("d-none")
        }
    };

    var verifyCallback2 = function(response) {
        var res = grecaptcha.getResponse(widget2)
        if(res.length == 0){
            Swal.fire("reCaptcha", "Failed to verify", 'warning')
        } {
            $("#kt_btn_send").removeClass("d-none")
        }
    };

    var onloadCallback = function() {
        widget1 = grecaptcha.render('recaptcha_reg', {
            'sitekey' : '{{ config("services.recaptcha_v3.siteKey") }}',
            'callback' : verifyCallback,
        });
        widget2 = grecaptcha.render('recaptcha_reg2', {
            'sitekey' : '{{ config("services.recaptcha_v3.siteKey") }}',
            'callback' : verifyCallback2,
        });
    };

    const form = document.getElementById('kt_stepper_example_basic_form');

        var a, s = function () {
            return a.getScore() > 80
        }

        validator = FormValidation.formValidation(
            form, {
                fields: {
                    'name': {
                        validators: {
                            notEmpty: {
                                message: 'Silahkan masukan nama lengkap terlebih dahulu'
                            }
                        }
                    },
                    'email': {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message: "Email tidak valid"
                            },
                            notEmpty: {
                                message: 'Silahkan masukan email terlebih dahulu'
                            }
                        }
                    },
                    'phone': {
                        validators: {
                            notEmpty: {
                                message: 'Silahkan masukan nomor handphone terlebih dahulu'
                            }
                        }
                    },
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        )

        // Stepper lement
        var element = document.querySelector("#kt_stepper_example_basic");

        // Initialize Stepper
        var stepper = new KTStepper(element);

        // Handle next step
        stepper.on("kt.stepper.next", function(stepper) {
            validator.validate().then(function(status){
                if(status == "Valid"){
                    stepper.goNext();
                    validator.addField("company_name", {
                        validators: {
                            notEmpty: {
                                message: 'Silahkan masukan nama perusahaan terlebih dahulu'
                            }
                        }
                    })
                    validator.addField("position", {
                        validators: {
                            notEmpty: {
                                message: 'Silahkan masukan posisi anda di perusahaan terlebih dahulu'
                            }
                        }
                    })
                    validator.addField("s_k", {
                        validators: {
                            notEmpty: {
                                message: 'Required'
                            }
                        }
                    })
                }
            })
        });

        // Handle previous step
        $(form).find("button[data-kt-stepper-action=submit]").click(function(e){
            e.preventDefault()
            validator.validate().then(function(status){
                if(status == "Valid"){
                    $(this).prop("disabled", true)
                    $(this).attr("data-kt-indicator", "on")
                    $(form).submit()
                }
            })
        })

        $("#kt_stepper_example_basic_form input[name=email]").change(function(){
            var btnNext = $(form).find("[data-kt-stepper-action=next]")
            btnNext.prop("disabled", true)
            btnNext.attr("data-kt-indicator", "on")
            $.ajax({
                url : "{{ route("register.check_email") }}",
                type : "post",
                data : {
                    email : $(this).val(),
                    _token : "{{ csrf_token() }}"
                },
                dataType : "json"
            }).then(function(resp){
                btnNext.prop("disabled", false)
                btnNext.removeAttr("data-kt-indicator")
                var pwdiv = $(form).find(".form-password")
                var fields = validator.getFields()
                if(resp.success){
                    $(form).find("input[name=state]").val("1")
                    $(pwdiv).hide()
                    if(fields.password != undefined){
                        validator.removeField("password")
                        validator.removeField("password_confirmation")
                    }

                    if(resp.isRegistered){
                        btnNext.prop("disabled", true)
                        Swal.fire("Akun sudah terdaftar", "Email yang anda masukkan sudah terdaftar", "warning")
                    }

                } else {
                    $(form).find("input[name=state]").val("0")
                    $(pwdiv).show()
                    a = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'))
                    validator.addField(
                        "password",
                        {
                            validators : {
                                notEmpty: {
                                    message: "Kata Sandi tidak boleh kosong"
                                },
                                callback: {
                                    message: "Kata Sandi minimal 8 karakter dan harus mengandung huruf kecil, huruf besar, angka dan simbol",
                                    callback: function (e) {
                                        if (e.value.length > 0) return s()
                                    }
                                }
                            }
                        }
                    )

                    validator.addField(
                        "password_confirmation",
                        {
                        validators: {
                                notEmpty: {
                                    message: "Konfirmasi password tidak boleh kosong"
                                },
                                identical: {
                                    compare: function () {
                                        return form.querySelector('[name="password"]').value
                                    },
                                    message: "Konfirmasi password tidak sama dengan password"
                                }
                            }
                        },
                    )
                }

                console.log(fields)
            })
        })
</script>
@endsection
