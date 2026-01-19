@extends('layouts.template')

@section('aside')
    <div class="lside min-w-300px mb-5 mb-md-0">
        <div class="card">
            <div class="card-body">
                <!--begin::Menu-->
                <div class="menu menu-column menu-gray-600 flex-row flex-md-column menu-rounded menu-sub-indention fw-semibold fs-6 overflow-auto"
                    id="#kt_aside_menu" data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3 d-none d-md-inline">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="{{ asset($user->user_img ?? "theme/assets/media/avatars/blank.png") }}" class="w-auto" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->name }}
                                    {{-- <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span> --}}
                                </div>
                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">Lihat Profil</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item my-2">
                        <!--begin:Menu link-->
                        <a class="menu-link text-hover-primary {{ $v == 'account_information' ? 'text-active-primary active' : '' }} "
                            href="{{ route('account.setting') }}?v={{ 'account_information' }}">
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
                            <span
                                class="menu-title text-hover-primary text-nowrap {{ $v == 'account_information' ? 'text-primary' : '' }}">{{ __('Akun Saya') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    @if(\Config::get("constants.PORTAL_STATE") != 3)
                    <!--begin:Menu item-->
                    <div class="menu-item my-2">
                        <!--begin:Menu link-->
                        <a class="menu-link text-hover-primary {{ $v == 'company_profile' ? 'text-active-primary active' : '' }} "
                            href="{{ route('account.setting') }}?v={{ 'company_profile' }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-safe-home fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </span>
                            <span
                                class="menu-title text-hover-primary text-nowrap {{ $v == 'company_profile' ? 'text-primary' : '' }}">{{ __('user.company_profile') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item my-2">
                        <!--begin:Menu link-->
                        <a class="menu-link text-hover-primary {{ $v == 'user_colaborator' ? 'text-active-primary active' : '' }} "
                            href="{{ route('account.setting') }}?v={{ 'user_colaborator' }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-people fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </span>
                            <span
                                class="menu-title text-hover-primary text-nowrap {{ $v == 'user_colaborator' ? 'text-primary' : '' }}">{{ __('User Colaborator') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item my-2">
                        <!--begin:Menu link-->
                        <a class="menu-link text-hover-primary {{ $v == 'email_notification' ? 'text-active-primary active' : '' }} "
                            href="{{ route('account.setting') }}?v={{ 'email_notification' }}">
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
                            <span
                                class="menu-title text-hover-primary text-nowrap {{ $v == 'email_notification' ? 'text-primary' : '' }}">{{ __('Email Notifikasi') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    <!--begin:Menu item-->
                    <div class="menu-item my-2">
                        <!--begin:Menu link-->
                        <a class="menu-link text-hover-primary {{ $v == 'delete_account' ? 'text-active-primary active' : '' }} "
                            href="{{ route('account.setting') }}?v={{ 'delete_account' }}">
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
                            <span
                                class="menu-title  text-nowrap {{ $v == 'delete_account' ? 'text-primary' : '' }}">{{ __('user.delete_account') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>
                    <!--end:Menu item-->
                    @endif
                </div>
                <!--end::Menu-->
            </div>
        </div>
    </div>

@endsection

@if (\Helper_function::getProfile() < 100 && \Config::get("constants.IS_PORTAL") == 2)
    @section('rside')
        @php
            $pct = \Helper_function::getProfile();
        @endphp
        <div class="rside d-none d-md-inline">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-column">
                            <span class="fw-bold">Kelengkapan Profil {{ $pct }}%</span>
                            <span class="text-muted">Lengkapkan profilmu untuk meningkatkan kesempatanmu</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pct }}%"
                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-title">Personal Data</span>
                                <span class="menu-arrow"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@endif

@section('content')
    <div class="d-flex flex-column-fluid mt-n5 mt-md-0">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Profile Account Information-->
            <div class="d-flex flex-row">
                <!--begin::Content-->
                <div class="flex-row-fluid ml-lg-8">
                    <!--begin::Card-->
                    @include("users.setting_employer.$v")
                </div>
                <!--end::Content-->
            </div>
            <!--end::Profile Account Information-->
        </div>
        <!--end::Container-->
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset('theme/assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/jquery-number/jquery.number.min.js') }}"></script>
    <script>
        function password_validate(btn) {
            var form = $(btn).parents("form");
        }

        function validation(form) {
            var f = document.getElementById(form)

            var required = $(`#${form}`).find(":required")
            var fields = {}
            required.each(function() {
                var fv = $(this).parents("div.fv-row")
                var lbl = $(fv).find("label.col-form-label")

                var name = $(this).attr('name')

                var validators = {
                    notEmpty: {
                        message: lbl.text() + " harus diisi"
                    }
                }

                var isNpwp = $(this).hasClass("npwp");
                if (isNpwp) {
                    validators.callback = {
                        message: "Invalid NPWP",
                        callback: function(input) {
                            return Inputmask.isValid(input.value, {
                                "mask": "99.999.999.9-999.999"
                            });
                        }
                    }
                }

                fields[name] = {
                    validators: validators
                }
            })

            var validator = FormValidation.formValidation(f, {
                fields: fields,
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            })

            $(`#${form}`).find("select[data-control=select2]").change(function() {
                var name = $(this).attr("name");
                validator.revalidateField(name);
            })

            $(`#${form}`).find("button:submit").click(function(e) {
                e.preventDefault()
                $("textarea.ck-editor:required").each(function(){
                    var id = $(this).attr("id")
                    var editor = _editor[id]
                    $(this).val(editor.getData())
                    if (editor.getData().length == 0) {
                        $(this).parents("div.fv-row").addClass("fv-invalid-required")
                    } else {
                        $(this).parents("div.fv-row").removeClass("fv-invalid-required")
                    }
                    // editor.model.updateElement()
                })
                validator.validate().then(function(status) {
                    console.log(status)
                    if (status == 'Valid') {
                        $(`#${form}`).submit()
                    }
                })
            })
        }

        function removeImg(me){
            var imgDiv = $(me).parents("div.img-multiple")
            $(me).parents("div.cols").remove()
            var id = $(me).data("id")
            if(id != undefined){
                var input = `<input type="hidden" name="remove_img[]" value="${id}">`
                imgDiv.append($(input))
            }
        }

        function imageInput(me){
            var imgInputEdit = $(me).parents(".fv-row")
            var imgInput = $(imgInputEdit).find(".image-input")
            var wrapper = $(imgInput).find(".image-input-wrapper")

            var imgs = $(imgInputEdit).find("input[name='company_images[]']")
            if(imgs.length > 10){
                return Swal.fire("Foto telah mencapai maksimal", "Maksimal foto yang bisa ditambahkan 10 foto", 'warning')
            }

            const file = me.files[0];
            if (file){
                var isMultiple = $(me).data("multiple");
                if(isMultiple){
                    var _mlt = $(me).parents(".img-multiple")
                    var clone = $(_mlt).find(".img-clone:first").clone()
                    console.log(clone)
                    $(imgInput).addClass("me-3")
                    $(_mlt).append(clone)
                    var clone_wrapper = $(clone).find(".image-input-wrapper")
                    clone_wrapper.html("")
                    let reader = new FileReader();
                    reader.onload = function(event){
                        clone_wrapper.css("background-image", "url("+event.target.result+")")
                        clone_wrapper.css("background-size", "cover")
                    }
                    $(clone).find("label.btn.text-primary").hide()
                    $(clone).find("label.btn.text-danger").show()
                    reader.readAsDataURL(file);
                    $(clone).find(".image-input-edit label.btn.text-danger").click(function(){
                        removeImg(this)
                    })
                } else {
                    wrapper.html("")
                    let reader = new FileReader();
                    reader.onload = function(event){
                        wrapper.css("background-image", "url("+event.target.result+")")
                        wrapper.css("background-size", "cover")
                    }
                    reader.readAsDataURL(file);
                }
            }
        }

        function kec_url(){

            var _kec_post = {}
            _kec_post['prov'] = $("#prov_id").val()
            _kec_post['city'] = $("#city_id").val()

            var _kec_url = "{{ route("account.setting")."?v=$v" }}"
            _kec_url += "&t=kecamatan&f=" + JSON.stringify(_kec_post)
            console.log(encodeURI(_kec_url))
            $("#kec_id").select2('destroy')

            var ksel = $("#kec_id").select2({
                ajax : {
                    url : encodeURI(_kec_url),
                    type : "GET",
                    dataType : "json"
                }
            }).change(function(){
                var data = $(this).select2("data")
                var el = data[0]
                $("#city_id").val(el['city_id']).trigger("change")
            })
        }

        $(document).ready(function() {

            Inputmask({
                "mask": "99.999.999.9-999.999"
            }).mask(".npwp");

            Inputmask({
                "mask": "9999"
            }).mask(".mask-year");

            @if ($v == 'company_profile')
                validation("form-company-info")

                // $("#kec_id").select2()

                // kec_url()

                $("#prov_id").change(function(){
                    var id = $(this).val()
                    if(id != ""){
                        // kec_url()
                        if(id != $("#city_id option:selected").data("prov")){
                            $("#city_id").val("").trigger("change")
                        }
                        $("#kec_id").val("").trigger("change")
                        $("#city_id option").prop("disabled", true)
                        $(`#city_id option[data-prov=${id}]`).prop("disabled", false)
                        $("#kode_pos").val("")
                    }
                })

                $("#city_id").change(function(){
                    var id = $(this).val()
                    if(id != ""){
                        // kec_url()
                        var city_data = $("#kec_id").select2("data")
                        console.log(city_data)
                        var data = city_data[0]
                        if(data['city_id'] != id){
                            $("#kec_id").val("").trigger("change")
                        }
                        if($("#prov_id").val() == ""){
                            $("#prov_id").val($("#city_id option:selected").data("prov")).trigger('change')
                        }
                        $("#kode_pos").val("")
                    }
                })

                $("#kec_id").select2({
                    ajax : {
                        url : function(){
                            var _kec_post = {}
                            _kec_post['prov'] = $("#prov_id").val()
                            _kec_post['city'] = $("#city_id").val()
                            var _kec_url = "{{ route("account.setting")."?v=$v" }}"
                            _kec_url += "&t=kecamatan&f=" + JSON.stringify(_kec_post)
                            return encodeURI(_kec_url)
                        },
                        type : "GET",
                        dataType : "json"
                    }
                }).change(function(){
                    var data = $(this).select2("data")
                    var el = data[0]
                    if($(this).val() != "" && $("#city_id").val() == null){
                        $("#city_id").val(el['city_id']).trigger("change")
                    }
                    $("#kode_pos").val("")
                })

                $(".image-input-edit input[type=file]").change(function(){
                    imageInput(this)
                })

            @elseif($v == "user_colaborator")
                validation("form-uc-add")

                @if ($errors->any())
                    Swal.fire("", "{{ $errors->first() }}", "error")
                @endif

                @if (\Session::has("msg"))
                    Swal.fire("", "{{ \Session::get('msg') }}", "success")
                @endif

                $("[data-act=modal]").click(function(){
                    var modal = $(this).data("bs-stacked-modal")
                    var name = $(this).data("name")
                    var id = $(this).data("id")
                    $(modal).find("span.lbl-name").text(name)
                    $(modal).find("input[name=id]").val(id)
                })

            @elseif($v == "account_information")
                var f = document.getElementById("form-password")

                var validator = FormValidation.formValidation(f, {
                    fields: {
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
                                    compare: function() {
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

                $("#btn-change-password").click(function(e) {
                    var valid = false
                    e.preventDefault()
                    validator.validate().then(function(resp) {
                        if (resp == "Valid") {
                            $(f).submit()
                        }
                    })
                })

                $(".btn-update-profile").click(function(e){
                    e.preventDefault()
                    var parents = $(this).parents(".card")
                    var form = parents.find("form")
                    form.submit()
                })
            @endif
        })
    </script>
@endsection
