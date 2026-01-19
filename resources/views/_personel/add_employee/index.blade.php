@extends('_personel.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center">
        <div class="w-10 p-10 h-100px bg-danger">
            <div class="d-flex flex-column align-items-center cursor-pointer" onclick="closeMe()">
                <h3 class="text-white">X</h3>
            </div>
        </div>
        @foreach ($steps as $key => $item)
            <div class="flex-fill p-10 h-100px bg-{{ $key == "primary_data" ? "light-primary" : "secondary" }}" data-step-header>
                <div class="d-flex flex-column align-items-center">
                    <h3 class="text-active-primary {{ $key == "primary_data" ? "active" : "text-muted" }}">{{ ucwords(str_replace("_", " ", $key)) }}</h3>
                    <div class="d-flex align-items-center justify-content-center w-100 {{ $key == "primary_data" ? "" : "d-none" }}" data-step="{{ $key }}">
                        @php
                            $num = 1;
                        @endphp
                        @foreach ($item as $section => $val)
                            <i data-section="{{ $section }}" class="fa fa-circle text-active-primary {{ $num == 1 ? "active" : "" }}" style="color: #C6B1F5"></i>
                            @if ($num < count($item))
                                <div class="mx-3"></div>
                            @endif
                            @php
                                $num++
                            @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="card shadow-none">
        <div class="card-body">
            {{-- <div class="d-flex">

                <div class="flex-row-fluid">
                </div>
            </div> --}}
            <!--begin::Stepper-->
            @foreach ($steps as $key => $item)
                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-lg-row {{ $key == "primary_data" ? "" : "d-none" }}" data-form="{{ $key }}" id="stepper_{{ $key }}">
                    <!--begin::Aside-->
                    <div class="d-flex flex-row-auto flex-column w-100 w-lg-300px">
                        <div class="d-flex flex-column mb-5">
                            <h3>{{ ucwords(str_replace("_", " ", $key)) }}</h3>
                            <span>Add New Employee</span>
                        </div>
                        <!--begin::Nav-->
                        <div class="stepper-nav flex-center">
                            @php
                                $num = 1
                            @endphp
                            @foreach ($item as $sec => $val)
                                <!--begin::Step 1-->
                                <div class="stepper-item me-5 {{ $num++ == 1 ? "current" : "" }}" data-step-sec="{{ $sec }}" data-kt-stepper-element="nav">
                                    <!--begin::Wrapper-->
                                    <div class="stepper-wrapper d-flex align-items-center">
                                        <!--begin::Icon-->
                                        <div class="stepper-icon w-40px h-40px">
                                            <i class="stepper-check fas fa-check"></i>
                                            <span class="stepper-number">
                                                <i class="fi {{ $val['icon'] }}"></i>
                                            </span>
                                        </div>
                                        <!--end::Icon-->

                                        <!--begin::Label-->
                                        <div class="stepper-label">
                                            <h3 class="stepper-title">
                                                {{ ucwords(str_replace("_", " ", $sec)) }}
                                            </h3>

                                            <div class="stepper-desc">
                                                {{ $val['desc'] }}
                                            </div>
                                        </div>
                                        <!--end::Label-->
                                    </div>
                                    @if ($num <= count($item))
                                        <!--begin::Line-->
                                        <div class="stepper-line h-40px"></div>
                                        <!--end::Line-->
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!--begin::Content-->
                    <div class="flex-fill">
                        <!--begin::Group-->
                        <div class="mb-5">
                            @php
                                $num = 1
                            @endphp
                            @foreach ($item as $sec => $val)
                                <!--begin::Step 1-->
                                <div class="card shadow-none {{ $num++ == 1 ? "current" : "" }}" data-kt-stepper-element="content">
                                    <div class="card-header border-0 px-0">
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-40px me-5">
                                                <div class="symbol-label bg-light-primary">
                                                    <i class="fi {{ $val['icon'] }} text-primary fs-3"></i>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="fs-3 fw-bold">{{ ucwords(str_replace("_", " ", $sec)) }}</span>
                                                <span>{{ $val['desc'] }}</span>
                                            </div>
                                        </div>
                                        @if ($val['skip'])
                                            {{-- <div class="card-toolbar">
                                                <button type="button" class="btn text-primary" onclick="nextSection(this)">
                                                    Skip
                                                </button>
                                            </div> --}}
                                        @endif
                                    </div>
                                    <div class="card-body rounded bg-secondary-crm">
                                        <form action="{{ route("personel.step") }}" id="form_{{ $sec }}" method="post">
                                            @csrf
                                            @include('_personel.add_employee.forms.'.$sec)
                                        </form>
                                    </div>
                                </div>
                                <!--begin::Step 1-->
                            @endforeach
                        </div>
                        <!--begin::Actions-->
                        <div class="d-flex flex-stack">
                            <!--begin::Wrapper-->
                            <div class="me-2">
                                <button type="button" class="btn btn-light btn-active-light-primary" data-kt-stepper-action="previous">
                                    Back
                                </button>
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Wrapper-->
                            <div>
                                <button type="button" class="btn btn-primary" onclick="nextSection(this)" data-kt-stepper-action="submit" data-key="{{ $key }}">
                                    <span class="indicator-label">
                                        {{ $key != "payroll_data" ? "Submit to next section" : "Submit and done" }}
                                    </span>
                                    <span class="indicator-progress">
                                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                                <button type="button" class="btn btn-primary" onclick="updateSection(this)" data-kt-stepper-action="next" data-key="{{ $key }}">
                                    Submit to next section
                                </button>
                                <form action="{{ route("personel.add_employee.post") }}" method="post" id="form-post">
                                    @csrf
                                </form>
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Actions-->
                    </div>
                </div>
            @endforeach
            <!--end::Stepper-->
        </div>
    </div>
    </div>
@endsection

@section('view_script')
<script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
<script>

    function closeMe(){
        window.location = "{{ route("personel.employee_table.index") }}"
    }

    function save_multiple(me){
        var card = $(me).parents("[data-clone]")

        var hed = $(card).find("[data-head]")
        var bd = $(card).find("[data-form-add]")
        var lbl = $(card).find("[data-label]")
        var txt = ""
        console.log(lbl)

        $(lbl).each(function(){
            if($(this).is("input")){
                txt += $(this).val()
            } else {
                txt += $(this).find("option:selected").text()
            }

            txt += " "
        })

        $(card).find("input[name='saved[]']").val(1)
        $(card).find("input[data-saved]").val(1)

        $(hed).find("span.fs-3").text(txt)
        $(hed).removeClass("d-none")
        $(bd).addClass("d-none")
        $(me).addClass("d-none")
    }

    function cloneForm(me){
        var form = $(me).parents("form")
        var f = $(form).find("div[data-form-clone]")

        var target = $(f).find("[data-clone]").eq(0).clone()

        $(target).find("[data-head]").find("span.fs-3").text("")
        $(target).find("[data-head]").addClass("d-none")
        $(target).find("[data-head]").find("[data-accr='expand']").removeClass("d-none")
        $(target).find("[data-head]").find("[data-accr='collapse']").addClass("d-none")
        $(target).find("[data-form-add]").removeClass("d-none")

        $(target).find("input, select").val("").trigger("change")
        $(target).find("[data-file]").text('')
        $(target).find("button[data-m-save]").removeClass("d-none")
        $(target).find("input[type=file][data-toggle=file]").change(function(){
            var val = $(this).val().split("\\")

            $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
        })

        $(target).find(' [data-toggle="still"]').click(function(){
            var checked = this.checked
            if(checked){
                $(f).find('[data-target="still"]').addClass("d-none")
                $(f).find('[data-target="still"]').find("select").prop("required", false)
            } else {
                $(f).find('[data-target="still"]').removeClass("d-none")
                $(f).find('[data-target="still"]').find("select").prop("required", true)
            }
        })

        $(f).append(target)
        $(f).find(".select2-container").remove()
        $(f).find("select[data-control=select2]").select2()
    }

    function accrd(me){
        var expand = $(me).find("[data-accr='expand']")
        var collapse = $(me).find("[data-accr='collapse']")
        var card = $(me).parents("div[data-clone]")
        var bd = $(card).find("[data-form-add]")
        if($(expand).hasClass("d-none")){
            $(expand).removeClass("d-none")
            $(collapse).addClass("d-none")
            $(bd).addClass("d-none")
            $(card).find("button[data-m-save]").addClass("d-none")
        } else {
            $(collapse).removeClass("d-none")
            $(expand).addClass("d-none")
            $(bd).removeClass("d-none")
            $(card).find("button[data-m-save]").removeClass("d-none")
        }
    }

    function nextSection(me){
        var key = $(me).data("key")

        var step = $("[data-step]")

        var steps = []
        $(step).each(function(){
            steps.push($(this).data("step"))
        })

        var _ind = $("[data-step='"+key+"']")

        var ind = steps.findIndex((i) => i == $(_ind).data("step"))

        var sec = []

        $(_ind).find("i[data-section]").each(function(){
            sec.push($(this).data('section'))
        })

        var _key = sec[sec.length - 1]

        submitStep(_key).then((function(resp){
            if(key == "payroll_data"){
                return $("#form-post").submit()
            }
        }))

        $(_ind).parents("[data-step-header]").removeClass("bg-light-primary").addClass("bg-primary")
        $(_ind).parents("[data-step-header]").find("h3").addClass("text-white").removeClass("active")
        var next = steps[ind + 1]
        var $next = $("[data-step='"+next+"']").parents("[data-step-header]")
        $($next).removeClass("bg-secondary").addClass("bg-light-primary")
        $($next).find("[data-step]").removeClass("d-none")
        $($next).find("h3").removeClass("text-muted").addClass("active")
        $("[data-form='"+$(_ind).data("step")+"']").addClass("d-none")
        $("[data-form='"+next+"']").removeClass("d-none")
    }

    function updateSection(me){
        var key = $(me).data("key")
        var sec = $("[data-section='"+key+"']")
        $(sec).addClass('active')
    }

    function submitStep(key){
        var form = $("#form_" + key)

        var fileuploaddata = new FormData($(form)[0])
        fileuploaddata.append("form_type", key)
        return $.ajax({
            url: $(form).attr("action"),
            type: "POST",
            data: fileuploaddata,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(result) {

            }
        })
    }

    function skipSection(){

    }

    function stepFn(target){
        // Stepper lement
        var element = document.querySelector(target);

        // Initialize Stepper
        var stepper = new KTStepper(element);

        // Handle next step
        stepper.on("kt.stepper.next", function (stepper) {
            var ind = stepper.getCurrentStepIndex();
            var ksec = stepper.steps[ind - 1]
            var _key = $(ksec).data("step-sec")
            var form = $("#form_" + _key)
            console.log(form, _key, ksec, ind, stepper.steps)
            var requireds = []
            $(form).find("label.required").each(function(){
                var fv = $(this).parents("div.fv-row").eq(0)
                var input = $(fv).find("input, select")
                $(input).each(function(){
                    if($(this).val() != ""){
                        requireds.push($(this).val())
                    }
                })
            })

            if(requireds.length < $(form).find("label.required").length){
                $(form).find("label.required").each(function(){
                    var fv = $(this).parents("div.fv-row").eq(0)
                    var input = $(fv).find("input, select")
                    var sel2 = $(fv).find("span.form-select")
                    $(input).each(function(){
                        if($(this).val() == ""){
                            $(this).addClass("is-invalid")
                            if($(this).is("select")){
                                $(sel2).addClass("is-invalid")
                            }
                        }
                    })
                })
                return false
            } else {
                submitStep(_key)
                stepper.goNext(); // go next step
                var _sec = stepper.steps[ind]
                var key = $(_sec).data("step-sec")
                $("[data-section='"+key+"']").addClass("active")
            }
        });

        // Handle previous step
        stepper.on("kt.stepper.previous", function (stepper) {
            stepper.goPrevious(); // go previous step
            var ind = stepper.getCurrentStepIndex();
            var _sec = stepper.steps[ind]
            var key = $(_sec).data("step-sec")
            $("[data-section='"+key+"']").removeClass("active")
        });

        $(target + " input.number").number(true, 2)

        $(target + ' [data-toggle="still"]').click(function(){
            var checked = this.checked
            var form = $(this).parents("form")
            if(checked){
                $(form).find('[data-target="still"]').addClass("d-none")
                $(form).find('[data-target="still"]').find("select").prop("required", false)
            } else {
                $(form).find('[data-target="still"]').removeClass("d-none")
                $(form).find('[data-target="still"]').find("select").prop("required", true)
            }
        })

        $(target + " input[name=resident_identity]").click(function(){
            var checked = this.checked

            if(checked){
                var address = $(target + " [name='identity[address]']")
                var zip_code = $(target + " [name='identity[zip_code]']")
                var country = $(target + " [name='identity[country]']")
                var city = $(target + " [name='identity[city]']")
                var province = $(target + " [name='identity[province]']")

                $(target + " [name='resident[address]']").val($(address).val())
                $(target + " [name='resident[zip_code]']").val($(zip_code).val())
                $(target + " [name='resident[country]']").val($(country).val())
                $(target + " [name='resident[city]']").val($(city).val())
                $(target + " [name='resident[province]']").val($(province).val())
            }
        })

        $(target + " label.required").each(function(){
            var fv = $(this).parents("div.fv-row").eq(0)
            var input = $(fv).find("input, select")
            var sel2 = $(fv).find("span.form-select")
            $(input).on("keyup change", function(){
                $(this).removeClass("is-invalid")
                if($(this).is("select")){
                    $(sel2).removeClass("is-invalid")
                }
            })
        })

        $(target).find("select[name=employee_status]").change(function () {
            var opt = $(this).find("option:selected")
            var end_date = $(opt).data("end-date")
            var f = $(this).parents("form").eq(0)
            var edate = $(f).find("input[name=emp_status_end_date]")
            var fv = $(edate).parents("div.fv-row").eq(0)
            $(fv).addClass("d-none")
            console.log(end_date, fv, edate)
            if(end_date == 1){
                $(fv).removeClass("d-none")
            }
        })
    }


    $("div[data-toggle=imageInput]").each(function(){
        var input = $(this).find("input[type=file]")
        var wrapper = $(this).find("div.img-wrapper")
        $(input).change(function(){
            const file = this.files[0];
            let reader = new FileReader();
            reader.onload = function(event){
                wrapper.css("background-image", "url("+event.target.result+")")
                wrapper.css("background-size", "cover")
            }
            reader.readAsDataURL(file);
        })
    })

    $("input[type=file][data-toggle=file]").change(function(){
        var val = $(this).val().split("\\")

        $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
    })

    $(document).ready(function(){
        var step = @JSON($steps);
        for (const key in step) {
            stepFn("#stepper_"+key)
        }

        $("input[name=mobile_att]").click(function(){
            $("[data-mobile]").addClass("d-none")
            if($(this).val() == "1"){
                $("[data-mobile]").removeClass("d-none")
            }
        })
    })

</script>
@endsection
