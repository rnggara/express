@extends('layouts.template')

@section('content')
    @php
        $d1 = date_create($last->created_at);
        $d2 = date_create($last->result_end);
        $diff = date_diff($d1, $d2);
        $H = $diff->format("%h");
        $m = $diff->format("%i");
        $elTime = $m."m";
        if($H > 0){
            $elTime = $H."j, ".$m."m";
        }

        $test_uri = "";
        $last_wpt = [];
        $rpoint = $last->result_point;
        if($test->category_id == 3){
            $test_uri = route('test.papikostik.psikogram', $last->id);
        } elseif($test->category_id == 5){
            $test_uri = route('test.disc.psikogram', $last->id);
        } elseif($test->category_id == 2){
            $test_uri = route("test.mbti.psikogram", $last->id);
        } elseif($test->category_id == 4){
            $test_uri = route("test.wpt.psikogram", $last->id);
            $last_wpt = $wpt->where("test_result_id", $last->id)->first();
            $true = $last_wpt->true ?? 0;
            $wrong = $last_wpt->wrong ?? 0;
            $rpoint = ($last_wpt->score ?? 0) + ($last_wpt->age_point ?? 0);
        }

        $answered = 0;

        if($test->category_id == 6){
            $answered = $last->att_detail_result;
        }

    @endphp
    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex flex-column align-items-center">
                <img src="{{ asset("images/test.png") }}" alt="">
                <span class="mt-3 fs-2tx">Tes telah usai!</span>
                <span>Terima kasih sudah menyelesaikan tes.</span>
                <span>Silahkan lihat hasil tes kamu dan jangan lupa untuk mengambil tes lainnya!</span>
                <div class="mt-5 d-flex flex-column align-items-center">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalTest" class="btn btn-primary mb-5">Lihat Hasil Tes</button>
                    <a href="{{ route("test.page") }}" class="fw-semibold">Ikut Tes Lain</a>
                </div>
                <div class="modal fade" id="modalTest" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1>Hasil Test {{ $test->label }}</h1>
                                <!--begin::Close-->
                                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                </div>
                                <!--end::Close-->
                            </div>
                            <div class="modal-body">
                                <div class="d-flex flex-column">
                                    @if (!in_array($test->category_id, [2, 3, 5, 7]))
                                        <div class="d-flex justify-content-around">
                                            @if ($test->category_id == 6)
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="fs-2 fw-bold">{{ number_format($answered, 0) }}/100</span>
                                                    <span class="mb-3">Answered</span>
                                                    <span class="fw-bold">{{ $answered < 50 ? "SLOW" : (($answered >= 50 && $answered < 75) ? "FAST" : "QUICK") }} </span>
                                                </div>
                                            @endif
                                            @if($test->category_id == 4)
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="fs-2 fw-bold">{{ number_format($wpt_iq[$rpoint] ?? 0, 0) }}</span>
                                                    <span class="mb-3">IQ</span>
                                                </div>
                                            @else
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="fs-2 fw-bold">{{ number_format($rpoint, 0) }}{{$test->category_id != 4 ? "/100" : ""}}</span>
                                                <span class="mb-3">Score</span>
                                                @if ($test->category_id == 6)
                                                    <span class="fw-bold">{{ $rpoint < 60 ? "BUNGLING" : (($rpoint >= 60 && $rpoint < 85) ? "PRESSURE" : "MICRO") }} </span>
                                                @endif
                                            </div>
                                            @endif
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="fs-2 fw-bold">{{ $elTime }}</span>
                                                <span class="mb-5">Elapsed Time</span>
                                            </div>
                                        </div>
                                        @if($test->category_id == 4)
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
                                    @if ($test->category_id == 7)
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
        </div>
    </div>
@endsection
