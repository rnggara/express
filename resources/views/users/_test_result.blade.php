<div class="d-flex flex-column ms-md-5">
    <h3 class="card-title my-5">{{ __("user.test_result") }}</h3>
    <div class="d-flex flex-column">
        @foreach ($data['test'] as $item)
            @php
                $last = $data['res']->where("test_id", $item->id)->first();
                $delay_take_test = Session::get('delay_retake_test') ?? 0;
                $elTime = "";
                $takeTest = true;
                if(!empty($last)){
                    if(!empty($last->result_detail)){
                        $takeTest = false;
                    }
                    $exp_date = date("Y-m-d", strtotime("$delay_take_test days ".date("Y-m-d", strtotime($last->created_at))));
                    $d1 = date_create($last->created_at);
                    $d2 = date_create($last->result_end);
                    $diff = date_diff($d1, $d2);
                    $H = $diff->format("%h");
                    $m = $diff->format("%i");
                    $elTime = $m."m";
                    if($H > 0){
                        $elTime = $H."j, ".$m."m";
                    }
                    if(date("Y-m-d") > $exp_date){
                        $takeTest = true;
                    }
                }
            @endphp
            <div class="w-100 bg-white rounded border p-5 mb-5">
                <div class="row">
                    <div class="col-md-10 mb-md-5 mb-5">
                        <div class="d-flex flex-column">
                            <span class="fw-bold">{{ $item->label }}</span>
                            <span class="">{{ $item->descriptions }}</span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="align-items-center d-flex h-100 justify-content-center">
                            <div>
                                @if(!empty($last) && !empty($last->result_detail))
                                <button type="button" class="btn btn-secondary" disabled>
                                    Ambil Tes
                                </button>
                                @else
                                <a href="{{  route("test.take_exam", base64_encode($item->id)) }}" class="btn btn-primary">
                                    Ambil Tes
                                </a>
                                @endempty
                            </div>
                        </div>
                    </div>
                    @if (!empty($last) && !empty($last->result_detail))
                        @php
                            $test_uri = "";
                            $rpoint = $last->result_point;
                            $last_wpt = [];
                            if($item->category_id == 3){
                                $test_uri = route('test.papikostik.psikogram', $last->id);
                            } elseif($item->category_id == 5){
                                $test_uri = route('test.disc.psikogram', $last->id);
                            } elseif($item->category_id == 2){
                                $test_uri = route("test.mbti.psikogram", $last->id);
                            } elseif($item->category_id == 4){
                                $test_uri = route("test.wpt.psikogram", $last->id);
                                $last_wpt = $data['wpt']->where("test_result_id", $last->id)->first();
                                $true = $last_wpt->true ?? 0;
                                $wrong = $last_wpt->wrong ?? 0;
                                $rpoint = ($last_wpt->score ?? 0) + ($last_wpt->age_point ?? 0);
                            }

                            $answered = 0;

                            if($item->category_id == 6){
                                $answered = $last->att_detail_result;
                            }

                        @endphp
                        <div class="col-12 mt-5">
                            <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold">
                                <li class="breadcrumb-item text-danger">Exp Date: @dateId($exp_date)</li>
                                <li class="breadcrumb-item"><a href="#" class="" data-bs-toggle="modal" data-bs-target="#modalTest{{ $item->id }}">Lihat Jawaban</a></li>
                            </ol>
                            <div class="modal fade" id="modalTest{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1>Hasil Test {{ $item->label }}</h1>
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex flex-column">
                                                @if (!in_array($item->category_id, [2, 3, 5, 7]))
                                                <div class="d-flex justify-content-around">
                                                    @if ($item->category_id == 6)
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fs-2 fw-bold">{{ number_format($answered, 0) }}/100</span>
                                                            <span class="mb-3">Answered</span>
                                                            <span class="fw-bold">{{ $answered < 50 ? "SLOW" : (($answered >= 50 && $answered < 75) ? "FAST" : "QUICK") }} </span>
                                                        </div>
                                                    @endif
                                                    @if($item->category_id == 4)
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fs-2 fw-bold">{{ number_format($data['wpt_iq'][$rpoint] ?? 0, 0) }}</span>
                                                            <span class="mb-3">IQ</span>
                                                        </div>
                                                    @else
                                                        <div class="d-flex flex-column align-items-center">
                                                            <span class="fs-2 fw-bold">{{ number_format($rpoint, 0) }}{{$item->category_id != 4 ? "/100" : ""}}</span>
                                                            <span class="mb-3">Score</span>
                                                            @if ($item->category_id == 6)
                                                                <span class="fw-bold">{{ $rpoint < 60 ? "BUNGLING" : (($rpoint >= 60 && $rpoint < 85) ? "PRESSURE" : "MICRO") }} </span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="fs-2 fw-bold">{{ $elTime }}</span>
                                                        <span class="mb-5">Elapsed Time</span>
                                                    </div>
                                                </div>
                                                @if($item->category_id == 4)
                                                    <div class='my-5 text-center'>
                                                        @php
                                                        $interpretasi = "-";
                                                        foreach($data['wpt_interpretasi'] as $imin => $ilabel){
                                                            if($rpoint <= $imin){
                                                                $interpretasi = $ilabel;
                                                                break;
                                                            }
                                                        }
                                                        @endphp
                                                        {{$interpretasi}}
                                                    </div>
                                                @endif
                                                {{-- <p class="">Note:</p>
                                                <p class="">Lorem ipsum dolor sit amet consectetur. Egestas hac ultricies est odio dolor ullamcorper. At mollis morbi pellentesque varius.</p> --}}
                                            @endif
                                                @if ($item->category_id == 7)
                                                    @php
                                                        $cb_res = json_decode($last->color_blind_result ?? "[]", true);
                                                    @endphp
                                                    <div class="d-flex flex-column align-items-center">
                                                        <span class="fw-semibold fs-2 mb-5">Kondisi mata anda adalah</span>
                                                        <span class="fw-bold fs-1">{{ $cb_res['text'] ?? "-" }}</span>
                                                    </div>
                                                @endif
                                                @if ($test_uri != "")
                                                    <a href="{{ $test_uri }}" class="btn btn-primary">Lihat Hasil</a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="card card-custom bg-transparent">
    <div class="card-header border-bottom-0">

    </div>
    <div class="card-body">

    </div>
</div>
