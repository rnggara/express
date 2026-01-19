@extends('_crm.preferences.index')

@section('view_content')
<form action="{{ route('crm.pref.general.password.post') }}" method="post" id="form-password">
    <div class="d-flex flex-column">
        <div class="card">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Kata Sandi</h3>
                </div>
            </div>
            <div class="card-body rounded bg-secondary-crm">
                <!--begin::Main wrapper-->
                <div class="fv-row" data-kt-password-meter="true">
                    <!--begin::Wrapper-->
                    <div class="mb-1">
                        <!--begin::Label-->
                        <label class="form-label fw-semibold fs-6 mb-2">
                            Kata Sandi Baru
                        </label>
                        <!--end::Label-->

                        <!--begin::Input wrapper-->
                        <div class="position-relative mb-3">
                            <input class="form-control"
                                type="password" placeholder="" name="password" autocomplete="off" />

                            <!--begin::Visibility toggle-->
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                data-kt-password-meter-control="visibility">
                                    <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                            <!--end::Visibility toggle-->
                        </div>
                        <!--end::Input wrapper-->

                        <!--begin::Highlight meter-->
                        <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                        </div>
                        <!--end::Highlight meter-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Hint-->
                    <div class="text-muted">
                        Use 8 or more characters with a mix of letters, numbers & symbols.
                    </div>
                    <!--end::Hint-->
                </div>
                <!--end::Main wrapper-->
                <!--begin::Main wrapper-->
                <div class="fv-row" data-kt-password-meter="true">
                    <!--begin::Wrapper-->
                    <div class="mb-1">
                        <!--begin::Label-->
                        <label class="form-label fw-semibold fs-6 mb-2">
                            Masukkan Ulang Kata Sandi Baru
                        </label>
                        <!--end::Label-->

                        <!--begin::Input wrapper-->
                        <div class="position-relative mb-3">
                            <input class="form-control"
                                type="password" placeholder="" name="password_confirmation" autocomplete="off" />

                            <!--begin::Visibility toggle-->
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                data-kt-password-meter-control="visibility">
                                    <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                            <!--end::Visibility toggle-->
                        </div>
                        <!--end::Input wrapper-->

                        <!--begin::Highlight meter-->
                        <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                        </div>
                        <!--end::Highlight meter-->
                    </div>
                    <!--end::Wrapper-->

                    <!--begin::Hint-->
                    <div class="text-muted">
                        Use 8 or more characters with a mix of letters, numbers & symbols.
                    </div>
                    <!--end::Hint-->
                </div>
                <!--end::Main wrapper-->
                <div class="mt-3 d-flex justify-content-end">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        Reset Kata Sandi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('view_script')
    <script>

        $(document).ready(function(){
            var f = document.getElementById("form-password")

            var validator = FormValidation.formValidation(f, {
                fields : {
                    password: {
                        validators: {
                            notEmpty: {
                                message: "The password is required"
                            },
                        }
                    },
                    password_confirmation: {
                        validators: {
                            notEmpty: {
                                message: "The password confirmation is required"
                            },
                            identical: {
                                compare: function () {
                                    return f.querySelector('[name="password"]').value
                                },
                                message: "The password and its confirm are not the same"
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
            })

            $(f).find("button[type=submit]").click(function(e){
                var valid = false
                e.preventDefault()
                validator.validate().then(function(resp) {
                    if(resp == "Valid"){
                        $(f).submit()
                    }
                })
            })
        })
    </script>
@endsection
