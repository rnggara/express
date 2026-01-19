@extends('layouts.template')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Preview Question</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route("hrd.test.question", $question->test_id) }}" class="btn btn-sm btn-success btn-icon">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 text-center">
                    <div id="smartwizard">
                        <ul class="nav" style="display: none">
                            <li class="nav-item">
                                <a class="nav-link" href="#step-1">
                                    <div class="num">1</div>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="step-{{ $question->id }}" class="tab-pane" role="tabpanel" aria-labelledby="step-{{ $question->id }}">
                                <div class="row">
                                    @if ($test->category_id == 5)
                                        <div class="col-12">
                                            <div class="d-flex flex-column align-items-center w-100">
                                                <div class="d-flex w-50">
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
                                                @foreach ($question->points()->orderBy("order_num")->get() as $pt)
                                                    <div class="d-flex w-50">
                                                        <div class="symbol symbol-50px me-5">
                                                            <div class="symbol-label fs-2 bg-warning fw-semibold text-gray-800">
                                                                <input type="radio" name="point[{{ $question->id }}][P]" value="{{ $pt->id }}" id="">
                                                            </div>
                                                        </div>
                                                        <div class="symbol symbol-50px me-5">
                                                            <div class="symbol-label fs-2 bg-success fw-semibold text-gray-800">
                                                                <input type="radio" name="point[{{ $question->id }}][K]" value="{{ $pt->id }}" id="">
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
                                    <div class="col-12 text-left">
                                        @if (!empty($question->img))
                                            <img src="{{ asset($question->img) }}" class="img-thumbnail w-250px" alt="">
                                        @endif
                                        <h4>{{ $question->label }}</h4>
                                    </div>
                                    <div class="col-12 text-left">
                                        @if ($question->question_type == 2)
                                        <textarea name="point[{{ $question->id }}]" class="form-control" placeholder="Isi jawaban anda disini" cols="30" rows="10"></textarea>
                                        @else
                                        <div class="d-flex flex-column container">
                                            @foreach ($question->points()->orderBy("order_num")->get() as $pt)
                                                @php
                                                    $checked = isset($res[$question->id]) && isset($res[$question->id][$pt->id]) ? "checked" : '';
                                                    $class = "primary";
                                                    if(!empty($res)){
                                                        if(isset($res[$question->id]) && isset($res[$question->id][$pt->id])){
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

                                                    if(!empty($old)){
                                                        if(isset($old[$question->id]) && isset($old[$question->id][$pt->id])){
                                                            $checked = "checked";
                                                        }
                                                    }
                                                @endphp
                                                <label for="rb{{ $pt->id }}" class="btn btn-outline role pt{{ $question->id }} btn-outline-primary flex-center w-100 mb-3">
                                                    {{ $pt->label }}
                                                    @if (!empty($pt->img))
                                                        <br>
                                                        <img src="{{ asset($pt->img) }}" class="img-thumbnail w-250px" alt="">
                                                    @endif
                                                    <input type="radio" name="point[{{ $question->id }}][{{ $pt->id }}]" value="employer" data-pt="pt{{ $question->id }}" class="btn-check role" {{ $checked }} autocomplete="off" id="rb{{ $pt->id }}">
                                                </label>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>
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

        $(document).ready(function(){
            $("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
                @if (!empty($res))
                    $("#btn-finish").hide();
                @endif

                // Get step info from Smart Wizard
                let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
                var stp = stepInfo.currentStep + 1
                if(!step_viewed.includes(stp)){
                    step_viewed.push(stp)
                }
            });
            $("#smartwizard").on("loaded", function(e) {
                let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
                for (let i = 0; i < stepInfo.currentStep + 1; i++) {
                    if(!step_viewed.includes(i)){
                        step_viewed.push(i)
                    }
                }

                @if (!empty($res))
                    for (let i = 0; i < stepInfo.totalSteps + 1; i++) {
                        $('#smartwizard').smartWizard("setState", [i], "done", true);
                        // return true;
                    }
                @endif
            });
            $('#smartwizard').smartWizard({
                theme : "dots",
                autoAdjustHeight: true,
                enableUrlHash : false,
                toolbar: {
                    position: 'bottom', // none|top|bottom|both
                    showNextButton: false, // show/hide a Next button
                    showPreviousButton: false, // show/hide a Previous button
                    extraHtml: `` // Extra html to show on toolbar
                },
            });

            $("#smartwizard .sw-toolbar-elm.toolbar.toolbar-bottom").css("text-align", "left")
        })
    </script>
@endsection
