@extends('layouts.template', ['bgWrapper' => "bg-white"])

@section('content')
    <div class="card card-custom bg-light-primary gradient-card-test mb-5">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-center">
                <img src="{{ asset("images/test.png") }}" class="w-100 w-md-400px" alt="">
                <div class="d-flex flex-column ms-5">
                    <span class="fw-bold fs-3tx">Tes yang meningkatkan peluang kerja Anda</span>
                    <span class="fw-semibold">Cobalah tes yang sudah kami sediakan secara gratis. Tes berbagai kemampuan profesional Anda, serta kepribadian dan kecocokan Anda dalam lingkungan kerja.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column">
        <div class="mb-5">
            <span class="fs-3 fw-bold">Tes yang wajib Anda coba! </span>
        </div>
        <div class="row g-3">
            @foreach ($test as $item)
                @php
                    $last = $res->where("test_id", $item->id)->first();
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
                <div class="col-md-6 col-sm-12 mb-5">
                    <div class="card card-stretch border">
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <div class="align-center d-flex flex-column flex-md-row justify-content-between mb-5 align-items-md-center">
                                    <div class="d-flex align-items-center mb-3 mb-md-0">
                                        <div class="symbol">
                                            <div class="symbol-label bg-primary"><i class="fa fa-{{ $item->icon ?? "book" }} text-white fs-2"></i></div>
                                        </div>
                                        <span class="ms-3 fw-bold fs-2">{{ $item->label }}</span>
                                    </div>
                                    <div class="d-none d-md-inline">
                                        @if ($takeTest)
                                            <a href="{{ route("test.take_exam", base64_encode($item->id)) }}" class="btn btn-outline btn-outline-primary h-100">Ambil Tes</a>
                                        @else
                                            <button type="button" disabled class="btn btn-secondary h-100">Ambil Tes</button>
                                        @endif
                                    </div>
                                </div>
                                <p>{!! $item->descriptions !!}</p>
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
                                            $last_wpt = $wpt->where("test_result_id", $last->id)->first();
                                            $true = $last_wpt->true ?? 0;
                                            $wrong = $last_wpt->wrong ?? 0;
                                            $rpoint = ($last_wpt->score ?? 0) + ($last_wpt->age_point ?? 0);
                                        }

                                        $answered = 0;

                                        if($item->category_id == 6){
                                            $answered = $last->att_detail_result;
                                        }

                                    @endphp
                                    <div class="mt-5">
                                        <span class="text-danger">Exp Date: @dateId($exp_date) <a href="#" data-bs-toggle="modal" data-bs-target="#modalTest{{ $item->id }}">Lihat Jawaban</a></span>
                                    </div>
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
                                                                        <span class="fs-2 fw-bold">{{ number_format($wpt_iq[$rpoint] ?? 0, 0) }}</span>
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
                                                                    foreach($wpt_interpretasi as $imin => $ilabel){
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
                                @endif
                                <div class="d-inline d-md-none w-100">
                                    @if ($takeTest)
                                        <a href="{{ route("test.take_exam", base64_encode($item->id)) }}" class="btn btn-outline btn-outline-primary w-100 h-100">Ambil Tes</a>
                                    @else
                                        <button type="button" disabled class="btn btn-secondary h-100">Ambil Tes</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
