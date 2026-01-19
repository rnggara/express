@extends('layouts.template', [
    "withoutFooter" => True
])

@section('aside')
    <div class="lside min-w-300px mb-5 mb-md-0 me-md-5" >
        <div class="card">
            <div class="card-body">
                <!--begin::Menu-->
                <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6 overflow-auto flex-row flex-md-column" id="#kt_aside_menu"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 d-none d-md-inline">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ asset( Auth::user()->user_img ?? 'theme/assets/media/avatars/blank.png') }}" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}
                                    {{-- <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span> --}}
                                </div>
                                <a href="#"
                                    class="fw-semibold text-muted text-hover-primary fs-7">Lihat Profil</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item my-2">
                        <!--begin:Menu link-->
                        <a class="menu-link text-hover-primary {{ $v == "account_information" ? 'text-active-primary active' : '' }} "
                            href="{{ route('account.setting') }}?v={{ "account_information" }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-user fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </span>
                            <span class="menu-title text-hover-primary {{ $v == "account_information" ? 'text-primary' : '' }}">{{ __("Informasi Akun") }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item my-2">
                        <!--begin:Menu link-->
                        <a class="menu-link text-hover-primary {{ $v == "password" ? 'text-active-primary active' : '' }} "
                            href="{{ route('account.setting') }}?v={{ "password" }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-lock fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </span>
                            <span class="menu-title text-hover-primary {{ $v == "password" ? 'text-primary' : '' }}">{{ __("Kata Sandi") }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    {{-- <div class="menu-item my-2">
                        <!--begin:Menu link-->
                        <a class="menu-link text-hover-primary {{ $v == "email_notification" ? 'text-active-primary active' : '' }} "
                            href="{{ route('account.setting') }}?v={{ "email_notification" }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-message-notif fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </span>
                            <span class="menu-title text-hover-primary {{ $v == "email_notification" ? 'text-primary' : '' }}">{{ __("Email Notifikasi") }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div> --}}
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item my-2">
                        <!--begin:Menu link-->
                        <a class="menu-link text-hover-primary {{ $v == "delete_account" ? 'text-active-primary active' : '' }} "
                            href="{{ route('account.setting') }}?v={{ "delete_account" }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-trash fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </span>
                            <span class="menu-title {{ $v == "delete_account" ? 'text-primary' : '' }}">{{ __("Hapus Akun") }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                </div>
                <!--end::Menu-->
            </div>
        </div>
    </div>

@endsection

@section('content')
@include("users.setting.$v")
@endsection
@section('custom_script')
    <script src="{{ asset('theme/assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/jquery-number/jquery.number.min.js') }}"></script>
    <script>
        function password_validate(btn){
            var form = $(btn).parents("form");
        }

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

            $("#btn-change-password").click(function(e){
                var valid = false
                e.preventDefault()
                validator.validate().then(function(resp) {
                    if(resp == "Valid"){
                        $(f).submit()
                    }
                })
            })
        })

        $(".btn-update-profile").click(function(e){
            e.preventDefault()
            var parents = $(this).parents(".card")
            var form = parents.find("form")
            form.submit()
        })
    </script>
@endsection
