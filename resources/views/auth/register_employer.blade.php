@extends('layouts.template', ['withoutFooter' => true])

@section('content')
<style>
    input[type=password]::-ms-reveal,
    input[type=password]::-ms-clear
    {
        display: none;
    }
</style>
    <div class="container mt-10">
        <div class="d-flex flex-column align-items-center">
            <span class="fs-3 mb-3 fw-bold">Daftar Sekarang!</span>
            <span>Rekrut orang yang anda butuhkan, rekrut sekarang!</span>
            <span>Apakah Anda sudah memiliki Akun? Jika iya, Login <a
                    href="{{ \Config::get('constants.PORTAL_HOST') . '/login' }}">Disini</a></span>
            <!--begin::Stepper-->
            <div class="stepper stepper-pills" id="kt_stepper_example_basic">
                <!--begin::Nav-->
                <div class="stepper-nav flex-center flex-wrap mb-5">
                    <!--begin::Step 1-->
                    <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav">
                        <!--begin::Wrapper-->
                        <div class="stepper-wrapper d-flex align-items-center flex-column">
                            <!--begin::Icon-->
                            <div class="stepper-icon mx-auto w-40px h-40px mb-5">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">1</span>
                            </div>
                            <!--end::Icon-->

                            <!--begin::Label-->
                            <div class="">
                                <h3 class="stepper-title text-active-primary active">
                                    User Profile
                                </h3>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Line-->
                        <div class="stepper-line h-40px"></div>
                        <!--end::Line-->
                    </div>
                    <!--end::Step 1-->

                    <!--begin::Step 2-->
                    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
                        <!--begin::Wrapper-->
                        <div class="stepper-wrapper d-flex align-items-center flex-column">
                            <!--begin::Icon-->
                            <div class="stepper-icon mx-auto w-40px h-40px mb-5">
                                <i class="stepper-check fas fa-check"></i>
                                <span class="stepper-number">2</span>
                            </div>
                            <!--end::Icon-->

                            <!--begin::Label-->
                            <div class="">
                                <h3 class="stepper-title text-active-primary">
                                    Profile Perusahaan
                                </h3>
                            </div>
                            <!--end::Label-->
                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Line-->
                        <div class="stepper-line h-40px"></div>
                        <!--end::Line-->
                    </div>
                    <!--end::Step 2-->
                </div>
                <!--end::Nav-->

                <!--begin::Form-->
                <form action="{{ route('register.employer') }}" class="form w-lg-500px mx-auto bg-white rounded p-5" method="post" novalidate="novalidate" id="kt_stepper_example_basic_form">
                    <!--begin::Group-->
                    <div class="mb-5">
                        <!--begin::Step 1-->
                        <div class="flex-column current p-5" data-kt-stepper-element="content">
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label required">Nama Lengkap</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control" required name="name" placeholder="Masukan Nama Lengkap Anda"
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label">Posisi (Optional)</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control" required name="position" placeholder="Masukan Posisi Anda"
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label required">Email</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="email" class="form-control" required name="email" placeholder="Masukan Email Anda"
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label required">Nomor Handphone</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control" required name="phone" placeholder="Masukan Nomor Handphone Anda"
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <div class="form-password" style="display: none">
                                <!--end::Input group=-->
                                <div class="fv-row" data-kt-password-meter="true">
                                    <!--begin::Wrapper-->
                                    <div class="mb-1">
                                        <!--begin::Input wrapper-->
                                        <label for="password" class="col-form-label required">Password</label>
                                        <div class="position-relative mb-3">
                                            <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent @error('password') is-invalid @enderror" />
                                            <!--begin::Visibility toggle-->
                                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                                data-kt-password-meter-control="visibility">
                                                    <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                    <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </span>
                                            <!--end::Visibility toggle-->
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
                                    <div class="text-muted">Gunakan 8 atau lebih karakter dengan menggabungkan huruf kecil, huruf besar, angka & simbol.</div>
                                    <!--end::Hint-->
                                </div>
                                <!--end::Input group=-->
                                <!--end::Input group=-->
                                <div class="fv-row mb-5">
                                    <!--begin::Password-->
                                    <label for="password_confirmation" class="col-form-label required">Konfirmasi Password</label>
                                    <div class="position-relative mb-3">
                                        <input type="password" placeholder="Password" name="password_confirmation" autocomplete="off" class="form-control bg-transparent" />
                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            data-kt-password-meter-control="visibility">
                                                <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        </span>
                                    </div>
                                    <!--end::Password-->
                                </div>
                                <!--end::Input group=-->
                            </div>
                        </div>
                        <!--begin::Step 1-->

                        <!--begin::Step 1-->
                        <div class="flex-column p-5" data-kt-stepper-element="content">
                            <span class="fw-bold fs-3">Harap isi Data Perusahaan Anda</span>
                            <span class="mb-8">Lorem ipsum dolor sit amet consecctetru adipiscing elit</span>
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label required">Nama Perusahaan Terdaftar</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control" name="company_name" placeholder="Masukan nama perusahaan"
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label">Lokasi Perusahaan</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <select name="prov_id" class="form-select" data-control="select2" data-placeholder="Pilih Lokasi">
                                    <option value=""></option>
                                    @foreach ($prov as $item)
                                        <option value="{{$item->id}}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label">Jumlah Pegawai</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <select name="skala_usaha" class="form-select" data-control="select2" data-placeholder="Pilih Jumlah Pegawai">
                                    <option value=""></option>
                                    <option value="<50">< 50 Orang</option>
                                    <option value="51-100">51-100 Orang</option>
                                    <option value="101-500">101-500 Orang</option>
                                    <option value="501-1000">501-1000 Orang</option>
                                    <option value="1001-5000">1001-5000 Orang</option>
                                    <option value="5000+">5000+ Orang</option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label">Skala Usaha</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <select name="scale" class="form-select" data-control="select2" data-placeholder="Pilih Skala Usaha">
                                    <option value=""></option>
                                    <option value="Mikro">Mikro</option>
                                    <option value="Kecil">Kecil</option>
                                    <option value="Menengah">Menengah</option>
                                    <option value="Besar">Besar</option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label">Industri</label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <select name="industry_id" class="form-select" data-control="select2" data-placeholder="Pilih Industri">
                                    <option value=""></option>
                                    @foreach ($industry as $item)
                                        <option value="{{$item->id}}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--begin::Step 1-->
                    </div>
                    <!--end::Group-->

                    <!--begin::Actions-->
                    <div class="d-flex flex-stack flex-column">
                        @csrf
                        <input type="hidden" name="state">
                        <button type="button" class="btn btn-primary w-100" data-kt-stepper-action="submit">
                            <span class="indicator-label">
                                Kirim
                            </span>
                            <span class="indicator-progress">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>

                        <button type="button" class="btn btn-primary w-100" data-kt-stepper-action="next">
                            <span class="indicator-label">
                                Selanjutnya
                            </span>
                            <span class="indicator-progress">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Stepper-->
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

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
                }
            })
        });

        // Handle previous step
        $(form).find("button[data-kt-stepper-action=submit]").click(function(){
            validator.validate()
            $(this).prop("disabled", true)
            $(this).attr("data-kt-indicator", "on")
            $(form).submit()
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
                                    message: "Password tidak boleh kosong"
                                },
                                callback: {
                                    message: "Password minimal 8 karakter dan harus mengandung huruf kecil, huruf besar, angka dan simbol",
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
