@extends('layouts.template', ["bgWrapper" => "bg-white"])

@section('css')
<link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />

@section('content')
    @empty($start)
        <div class="card card-custom bg-light-primary">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center container">
                    <img src="{{ asset("images/exam.png") }}" class="h-500px" alt="">
                    <div class="d-flex flex-column ms-10">
                        <span class="fs-2tx fw-bold">{{ $test->label }}</span>
                        <p class="mb-10">{!! $test->descriptions !!}</p>
                        <div class="d-flex flex-column">
                            <div class="d-flex mb-3 align-items-baseline">
                                <span class="me-5">
                                    <i class="fa fa-check-circle text-primary"></i>
                                </span>
                                <span class="menu-title">40 Pertanyaan ( 10 Percakapan, 10 Tata Bahasa, 10 Kosakata, 10 Pemahaman )</span>
                            </div>
                            <div class="d-flex mb-3 align-items-baseline">
                                <span class="me-5">
                                    <i class="fa fa-check-circle text-primary"></i>
                                </span>
                                <span class="menu-title">Waktu pengerjaan 20 menit</span>
                            </div>
                            <div class="d-flex mb-3 align-items-baseline">
                                <span class="me-5">
                                    <i class="fa fa-check-circle text-primary"></i>
                                </span>
                                <span class="menu-title">Anda dapat mengikuti Standart English test hanya sekali setiap 3 Bulan</span>
                            </div>
                            <div class="d-flex mb-3 align-items-baseline">
                                <span class="me-5">
                                    <i class="fa fa-check-circle text-primary"></i>
                                </span>
                                <span class="menu-title">Harap pastikan koneksi internet anda stabil, agar tidak ada hambatan, waktu  test akan terus
                                    berjalan, dan akan terkuci jika waktu habis</span>
                            </div>
                            <div class="mt-3">
                                <form action="{{ route('hrd.text.exam_start') }}" method="post">
                                    @if ($takeCount->count() + 1 <= $test->take_limit)
                                    <input type="hidden" name="id" value="{{ $test->id }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-200px">
                                        Ikuti Test
                                    </button>
                                    @else
                                    <button type="button" disabled class="btn btn-primary w-200px">
                                        Ikuti Test
                                    </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
    <div class="card card-custom">
        <div class="card-header border-0 bg-transparent">
            <span class="card-title"></span>
            <div class="card-toolbar">
                <a href="{{ route("test.page") }}" class="btn btn-outline btn-outline-danger">Kembali</a>
            </div>
        </div>
        <div class="card-body bg-light-primary gradient-linear-test rounded">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                <span class="d-flex flex-column mb-5 mb-md-0">
                    <span class="fw-bold fs-2tx">{{ $test->label }}</span>
                    <span class="mb-5">Instruction : {{ $test->instructions ?? $test->descriptions }}</span>
                    <span>Pertanyaan <span id="test-step" class="fw-bold">{{ $old_step }}</span>/{{ $test->question_per_quiz }}</span>
                </span>
                <div class="rounded p-5" style="background-color: #DD3545; border-radius: 4px!important">
                    <span id="demo" class="fw-bold fs-2 text-white"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom gutter-b card-stretch mt-5 card-px-0">
        <div class="card-body">
            <div class="row">
                <div class="col-12 text-center">
                    @empty($start)
                    @else
                        @if (!$isTimeLimit)
                            <form action="{{ route('hrd.text.exam_result') }}" method="post" id="form-result">
                                @csrf
                                <input type="hidden" name="id" value="{{ $start->id }}">
                                <div id="smartwizard" class="d-flex flex-column align-items-center">
                                    <ul class="nav" style="display: none">
                                        @foreach ($qids as $i=> $item)
                                            <li class="nav-item">
                                                <a class="nav-link" href="#step-{{ $i+1 }}">
                                                    <div class="num">{{ $i+1 }}</div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content w-100">
                                        @foreach ($qids as $i => $qid)
                                            @php
                                                $item = $questions->where("id", $qid)->first();
                                            @endphp
                                            <div id="step-{{ $item->id }}" data-step="{{ $i }}" class="tab-pane" role="tabpanel" aria-labelledby="step-{{ $item->id }}">
                                                <div class="row">
                                                    @if ($test->category_id == 5)
                                                        <div class="col-12">
                                                            <div class="d-flex flex-column align-items-md-center w-100">
                                                                <div class="d-flex w-md-50">
                                                                    <div class="symbol symbol-50px me-5">
                                                                        <div class="symbol-label fs-2 bg-warning fw-semibold text-gray-800">K</div>
                                                                    </div>
                                                                    <div class="symbol symbol-50px me-5">
                                                                        <div class="symbol-label fs-2 bg-success fw-semibold text-gray-800">P</div>
                                                                    </div>
                                                                    <div class="alert border-0 d-flex align-items-center flex-fill" style="background-color: #F5F1FD">
                                                                        <div class="d-flex flex-column justify-content-center">
                                                                            <span class="fs-4 text-gray-900">Gambaran Diri</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @foreach ($item->points()->orderBy("order_num")->get() as $pt)
                                                                    @php
                                                                        $_old = $old[$item->id] ?? [];
                                                                        $kchecked = "";
                                                                        $pchecked = "";
                                                                        if(!empty($_old)){
                                                                            $kchecked = $_old["K"] == $pt->id ? "CHECKED" : "";
                                                                            $pchecked = $_old["P"] == $pt->id ? "CHECKED" : "";
                                                                        }
                                                                    @endphp
                                                                    <div class="d-flex w-md-50">
                                                                        <div class="symbol symbol-50px me-5 cursor-pointer disc-pt" data-q="{{ $item->id }}" data-id="ptk{{ $pt->id }}">
                                                                            <div class="symbol-label fs-2 bg-warning fw-semibold text-gray-800">
                                                                                <input type="radio" data-type="k" {{ $kchecked }} class="cursor-pointer ptk{{ $item->id }}" name="point[{{ $item->id }}][K]" value="{{ $pt->id }}" id="ptk{{ $pt->id }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="symbol symbol-50px me-5 cursor-pointer disc-pt" data-q="{{ $item->id }}" data-id="ptp{{ $pt->id }}">
                                                                            <div class="symbol-label fs-2 bg-success fw-semibold text-gray-800">
                                                                                <input type="radio" data-type="p" {{ $pchecked }} class="cursor-pointer ptp{{ $item->id }}" name="point[{{ $item->id }}][P]" value="{{ $pt->id }}" id="ptp{{ $pt->id }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="alert border d-flex align-items-center flex-fill">
                                                                            <div class="d-flex flex-column justify-content-center">
                                                                                <span class="fs-4 text-gray-900">{{ $pt->label }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-12 text-left mb-5">
                                                            @if (!empty($item->img))
                                                                <img src="{{ asset($item->img) }}" class="img-thumbnail w-100 container mb-3" alt="">
                                                            @endif
                                                            <div>
                                                                @if ($test->category_id == 6)
                                                                    <table class="table table-bordered">
                                                                        <tr>
                                                                            <th>Sentence 1</th>
                                                                            <th>Sentence 2</th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>{{ $item->label }}</td>
                                                                            <td>{{ $item->label2 }}</td>
                                                                        </tr>
                                                                    </table>
                                                                @else
                                                                    {!! $item->label !!}
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            @if ($item->question_type == 2)
                                                            <div class="text-center text-muted">
                                                                Jika ada lebih dari 1 jawaban, tambahkan jawabannya di line baru
                                                            </div>
                                                            <div class="container">
                                                                <textarea name="point[{{ $item->id }}]" class="form-control" placeholder="Isi jawaban anda disini" cols="30" rows="10"></textarea>
                                                            </div>
                                                            @else
                                                            <div class="d-flex flex-column container">
                                                                @foreach ($item->points()->orderBy("order_num")->get() as $pt)
                                                                    @php
                                                                        $checked = isset($res[$item->id]) && isset($res[$item->id][$pt->id]) ? "checked" : '';
                                                                        $class = "primary";
                                                                        if(!empty($res)){
                                                                            if(isset($res[$item->id]) && isset($res[$item->id][$pt->id])){
                                                                                $checked = "checked";
                                                                                if($pt->is_true){
                                                                                    $class = "primary";
                                                                                } else {
                                                                                    $class = "danger";
                                                                                }
                                                                            } else {
                                                                                if($pt->is_true){
                                                                                    $class = "success";
                                                                                    $checked = "checked";
                                                                                } else {
                                                                                    $class = "danger";
                                                                                }
                                                                            }
                                                                        }

                                                                        if($test->category_id == 1 || $test->category_id == 4){
                                                                            if(!empty($old)){
                                                                                if(isset($old[$item->id]) && isset($old[$item->id][$pt->id])){
                                                                                    $checked = "checked";
                                                                                }
                                                                            }
                                                                        } else {
                                                                            if(!empty($old)){
                                                                                if(isset($old[$item->id]) && $old[$item->id] == $pt->id){
                                                                                    $checked = "checked";
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <label for="rb{{ $pt->id }}" class="btn btn-outline role pt{{ $item->id }} btn-outline-{{ $pt->className ?? "primary" }} flex-center w-100 mb-3">
                                                                        {{ $pt->label }}
                                                                        @if (!empty($pt->img))
                                                                            <br>
                                                                            <img src="{{ asset($pt->img) }}" class="img-thumbnail w-300px" alt="">
                                                                        @endif
                                                                        @if ($test->category_id == 4)
                                                                            <input type="checkbox" name="point[{{ $item->id }}][{{ $pt->id }}]" value="{{ $pt->id ?? 1 }}" data-pt="pt{{ $item->id }}" class="btn-check role" {{ $checked }} autocomplete="off" id="rb{{ $pt->id }}">
                                                                        @else
                                                                            <input type="radio" name="point[{{ $item->id }}]" value="{{ $pt->id }}" data-pt="pt{{ $item->id }}" class="btn-check role" {{ $checked }} autocomplete="off" id="rb{{ $pt->id }}">
                                                                        @endif
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                            @endif
                                                            <input type="hidden" name="pid[]" value="{{ $item->id }}">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </form>
                        @endif
                    @endempty
                </div>
            </div>
        </div>
    </div>
    <div id="camera" style="height:auto; width:max-content; text-align:left;right:40px;" class="position-fixed bottom-0 mb-5"></div>
    @endempty
@endsection

@section('custom_script')
    <script src="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script>
        // document.addEventListener('contextmenu', event => event.preventDefault());
        // document.onkeydown = (e) => {
        //     if (e.key == "F12") {
        //         e.preventDefault();
        //     }
        //     if (e.ctrlKey && e.shiftKey && e.key == 'I') {
        //         e.preventDefault();
        //     }
        //     if (e.ctrlKey && e.shiftKey && e.key == 'C') {
        //         e.preventDefault();
        //     }
        //     if (e.ctrlKey && e.shiftKey && e.key == 'J') {
        //         e.preventDefault();
        //     }
        //     if (e.ctrlKey && e.key == 'U') {
        //         e.preventDefault();
        //     }
        // };

        var step_viewed = [];

        function onFinish(){
            let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
            var title = "Are you sure?"
            var msg = ""
            var icon = "question"
            if(step_viewed.length < stepInfo.totalSteps){
                msg = "You still have a question(s) that need to be answered!"
            }

            Swal.fire({
                title: title,
                text: msg,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: "Yes!"
            }).then(function(result) {
                if (result.value) {
                    $("#form-result").submit()
                }
            });
        }

        function start_exam(){
            show_test()
            countdown()
        }

        function attach_camera(){
            try {
                try{
                    Webcam.set({
                        width: 300,
                        height: 280,
                        image_format: 'jpeg',
                        jpeg_quality: 100
                    });
                    Webcam.attach('#camera');
                    var vd = $("#camera").find("video")
                    Webcam.on( 'error', function(err) {
                        // an error occurred (see 'err')
                        $("#smartwizard").hide()
                        $("#demo").html("Please enable your camera to proceed the test")
                    } );
                    Webcam.on( 'live', function() {
                        start_exam()
                    } );
                    console.log('webcam attached')
                } catch (e) {
                    $("#demo").html("Please enable your camera to proceed the test")
                }
            } catch (error) {
                console.log(error)
            }
        }

        function countdown(){
            // Set the date we're counting down to
            @if (!empty($start))
                @if(!$isTimeLimit)
                    var countDownDate = new Date("{{ date("Y-m-d H:i:s", strtotime($start->created_at." + ".$test->time_limit." minutes")) ?? date("Y-m-d H:i:s") }}").getTime();
                @else
                    var countDownDate = new Date("{{ date("Y-m-d H:i:s", strtotime('+ 10 seconds')) ?? date("Y-m-d H:i:s") }}").getTime();
                @endif

            // Update the count down every 1 second
            var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            @if($isTimeLimit)
                document.getElementById("demo").innerHTML = "You will be redirect in " + seconds + "s ";
            @else
                var timeleft =
                document.getElementById("demo").innerHTML = `${hours.toString().padStart(2, "0")}:${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;
            @endif

            // If the count down is finished, write some text
            if (distance <= 0) {
                clearInterval(x);
                @if (empty($res))
                    $("#form-result").submit()
                @else
                    document.getElementById("demo").innerHTML = "Thank You<br>Your Score is {{ round($start->result_point) }}/{{ $questions->sum("point") }}";
                @endif
                @if($isTimeLimit)
                location.reload()
                @endif
            }
            }, 1000);
            @endif
        }

        async function storeProgres(id, step){
            var form = $("#form-result")
            var data = form.serialize()
            return $.ajax({
                url : `{{ route("hrd.test.exam_step") }}/${id}/${step}`,
                type : "post",
                data : data
            })
        }

        function show_test(){
            $("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
                @if (!empty($res))
                    $("#btn-finish").hide();
                @endif

                // Get step info from Smart Wizard
                let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
                var stp = stepInfo.currentStep + 1
                $("#test-step").text(stepIndex + 1)
                if(!step_viewed.includes(stp)){
                    step_viewed.push(stp)
                }

                if(stepIndex == 0){
                    $(".sw-btn-prev").hide()
                } else {
                    $(".sw-btn-prev").show()
                }

                console.log(stepIndex, stepInfo.totalSteps)

                if(stepIndex < (stepInfo.totalSteps - 1)){
                    $(".sw-btn-next").show()
                    // $("#btn-finish").hide()
                    $("#btn-finish").hide()
                } else {
                    $("#btn-finish").show()
                    $(".sw-btn-next").hide()
                }
            });
            $("#smartwizard").on("loaded", function(e) {
                let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
                $(this).find("ul.nav").hide()
                for (let i = 0; i < stepInfo.currentStep + 1; i++) {
                    if(!step_viewed.includes(i)){
                        step_viewed.push(i)
                    }
                }

                @if(!empty($start))
                    $('#smartwizard').smartWizard("goToStep", {{ $old_step }}, true);
                @endif

                @if (!empty($res))
                    for (let i = 0; i < stepInfo.totalSteps + 1; i++) {
                        $('#smartwizard').smartWizard("setState", [i], "done", true);
                        // return true;
                    }
                @endif
            });

            @if(!empty($start))
                $("#smartwizard").on("leaveStep", function(e, anchorObject, currentStepIndex, nextStepIndex, stepDirection) {
                    @if ($test->category_id == 5)
                    var _div = $(`div[data-step=${currentStepIndex}]`)
                    var _ptk = $(_div).find("input[data-type=k]:checked")
                    var _ptp = $(_div).find("input[data-type=p]:checked")
                    if(stepDirection === "forward"){
                        if(_ptk.length == 0 || _ptp.length == 0){
                            $('#smartwizard').smartWizard("setState", [currentStepIndex], 'error', true);
                            Swal.fire("", "P dan K Harus dipilih", 'warning')
                            return false
                        }
                    }
                    @endif

                    let stepInfo = $('#smartwizard').smartWizard("getStepInfo");

                    $('#smartwizard').smartWizard("loader", "show");
                    storeProgres({{ $start->id }}, nextStepIndex).then((res)=> $('#smartwizard').smartWizard("loader", "hide"))
                    // $('#smartwizard').smartWizard("loader", "hide");
                });

            @endif

            $('#smartwizard').smartWizard({
                theme : "round",
                autoAdjustHeight: true,
                enableUrlHash : false,
                toolbar: {
                    position: 'bottom', // none|top|bottom|both
                    showNextButton: true, // show/hide a Next button
                    showPreviousButton: true, // show/hide a Previous button
                    extraHtml: `<button type="button" id="btn-finish" class="btn btn-primary mx-5 sw-btn-finish" onclick="onFinish()">Finish</button>` // Extra html to show on toolbar
                },
                lang: { // Language variables for button
                    next: 'Selanjutnya',
                    previous: 'Sebelumnya'
                },
                style: { // CSS Class settings
                    mainCss: 'sw',
                    navCss: 'nav',
                    navLinkCss: 'nav-link',
                    contentCss: 'tab-content',
                    contentPanelCss: 'tab-pane',
                    themePrefixCss: 'sw-theme-',
                    anchorDefaultCss: 'default',
                    anchorDoneCss: 'done',
                    anchorActiveCss: 'active',
                    anchorDisabledCss: 'disabled',
                    anchorHiddenCss: 'hidden',
                    anchorErrorCss: 'error',
                    anchorWarningCss: 'warning',
                    justifiedCss: 'sw-justified',
                    btnCss: 'mx-5',
                    btnNextCss: 'btn-primary sw-btn-next',
                    btnPrevCss: 'btn-outline btn-outline-primary sw-btn-prev',
                    loaderCss: 'sw-loading',
                    progressCss: 'progress',
                    progressBarCss: 'progress-bar',
                    toolbarCss: 'toolbar',
                    toolbarPrefixCss: 'toolbar-',
                }
            });

            $("#smartwizard .sw-toolbar-elm.toolbar.toolbar-bottom").css("text-align", "left")
        }

        function validate_disc(){
            return false
        }

        $(document).ready(function(){
            @if(!empty($start))
                @if (\Config::get("constants.ENABLE_CAM") == 1)
                    attach_camera()
                @else
                    start_exam()
                @endif
            @endif

            $("input.role").each(function(){
                var checked = this.checked
                var id = $(this).attr("id")
                if(checked){
                    $(`label[for=${id}]`).removeClass("btn-outline btn-outline-primary")
                    $(`label[for=${id}]`).addClass("btn-primary")
                } else {
                    $(`label[for=${id}]`).addClass("btn-outline btn-outline-primary")
                    $(`label[for=${id}]`).removeClass("btn-primary")
                }
            })

            $("input.role").click(function(){
                var checked = this.checked
                var id = $(this).attr("id")
                var pt = $(this).data("pt")
                @if ($test->category_id == 4)
                    if(checked){
                        $(`label[for=${id}]`).removeClass("btn-outline btn-outline-primary")
                        $(`label[for=${id}]`).addClass("btn-primary")
                    } else {
                        $(`label[for=${id}]`).addClass("btn-outline btn-outline-primary")
                        $(`label[for=${id}]`).removeClass("btn-primary")
                    }
                @else
                    if(checked){
                        $(`label.${pt}`).addClass("btn-outline btn-outline-primary")
                        $(`label[for=${id}]`).removeClass("btn-outline btn-outline-primary")
                        $(`label[for=${id}]`).addClass("btn-primary")
                    }
                @endif
            })

            $(".disc-pt").click(function(){
                var _id = $(this).data("id")
                var _i = _id.substring(3)
                var _t = _id.substring(0, 3)
                var _ptk = `ptk${_i}`
                var _ptp = `ptp${_i}`
                var allow = true
                var _q = $(this).data("q")

                var _old = undefined

                if(_t == "ptk"){
                    var isptp = document.getElementById(_ptp).checked
                    _old = $("input.ptk"+_q+":checked")
                    if(isptp){
                        allow = false
                    }
                } else {
                    var isptk = document.getElementById(_ptk).checked
                    _old = $("input.ptp"+_q+":checked")
                    if(isptk){
                        allow = false
                    }
                }
                if (allow) {
                    $(`#${_id}`).prop("checked", true)
                } else {
                    if(_old.length > 0){
                        _old.prop("checked", true)
                    }
                    $(`#${_id}`).prop("checked", false)
                    return Swal.fire("", "Tidak bisa memilih di jawab yang sama", "warning")
                }
            })
        })
    </script>
@endsection
