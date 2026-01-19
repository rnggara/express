@extends('layouts.template')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/smartwizard@6/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" />

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">{{ $test->label }} - Questions that you answerd incorrectly</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route("hrd.test.exam", base64_encode($test->id)) }}" class="btn btn-sm btn-success btn-icon">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 text-center">
                    <div id="smartwizard">
                        <ul class="nav">
                            @foreach ($qwrong as $i=> $item)
                                <li class="nav-item">
                                    <a class="nav-link" href="#step-{{ $i+1 }}">
                                        <div class="num">{{ $i+1 }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach ($qwrong as $i => $qid)
                                @php
                                    $item = $questions->where("id", $qid['id'])->first();
                                @endphp
                                <div id="step-{{ $item->id }}" class="tab-pane" role="tabpanel" aria-labelledby="step-{{ $item->id }}">
                                    <div class="row">
                                        <div class="col-12 text-left">
                                            @if (!empty($item->img))
                                                <img src="{{ asset($item->img) }}" class="img-thumbnail w-250px" alt="">
                                            @endif
                                            <h4>{{ $item->label }}</h4>
                                        </div>
                                        <div class="col-12 text-left">
                                            <span>Correct answer is :</span>
                                            <div class="checkbox-list">
                                                @foreach ($item->points()->where("is_true", 1)->orderBy("order_num")->get() as $pt)
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
                                                    @endphp
                                                    <label class="checkbox checkbox-2x checkbox-{{ $class }}">
                                                        <input type="checkbox" {{ !empty($res) ? "disabled" : "" }} name="point[{{ $item->id }}][{{ $pt->id }}]" {{ $checked }} />
                                                        <span></span>
                                                        {{ $pt->label }}
                                                        @if (!empty($pt->img))
                                                            <br>
                                                            <img src="{{ str_replace("public", "public_html", asset($pt->img)) }}" class="img-thumbnail w-250px" alt="">
                                                        @endif
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-12 text-left mt-5">
                                            @if (count($qid['answer']) == 0)
                                                <span>You are not answering this question</span>
                                            @else
                                            <span>Your answer is :</span>
                                            <div class="checkbox-list">
                                                @php
                                                    $answ_id = array_keys($qid['answer']);
                                                @endphp
                                                @foreach ($item->points()->whereIn("id", $answ_id)->orderBy("order_num")->get() as $pt)
                                                    @php
                                                        $class = "primary";
                                                        if($pt->is_true != 1){
                                                            $class="danger";
                                                        }
                                                    @endphp
                                                    <label class="checkbox checkbox-2x checkbox-{{ $class }}">
                                                        <input type="checkbox" disabled name="point[{{ $item->id }}][{{ $pt->id }}]" checked />
                                                        <span></span>
                                                        {{ $pt->label }}
                                                        @if (!empty($pt->img))
                                                            <br>
                                                            <img src="{{ str_replace("public", "public_html", asset($pt->img)) }}" class="img-thumbnail w-250px" alt="">
                                                        @endif
                                                    </label>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Include optional progressbar HTML -->
                        <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Add Item</h1>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
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
                toolbar: {
                    position: 'bottom', // none|top|bottom|both
                    showNextButton: true, // show/hide a Next button
                    showPreviousButton: true, // show/hide a Previous button
                    extraHtml: `` // Extra html to show on toolbar
                },
            });

            $("#smartwizard .sw-toolbar-elm.toolbar.toolbar-bottom").css("text-align", "left")
        })
    </script>
@endsection
