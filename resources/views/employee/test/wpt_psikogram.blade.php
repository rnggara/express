@extends('layouts.template')

@section('content')
<div class="card card-custom bg-transparent">
    <div class="card-header border-0">
        <h3 class="card-title">Hasil Test {{ $test->label }}</h3>
        <div class="card-toolbar">
            <a href="{{ $back }}" class="btn btn-sm btn-icon btn-success">
                <i class="fa fa-arrow-left"></i>
            </a>
        </div>
    </div>
    <div class="card-body bg-white rounded">
        <div class="d-flex flex-column align-items-center">
            <span class="fs-2 fw-bold mb-8">Hasil Pemeriksaan Psikologis</span>
            <div class="d-flex align-items-center justify-content-center">
                <table class="table table-borderless">
                    <tr>
                        <th>Nama</th>
                        <td>:</td>
                        <td>{{ $profile->name ?? $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Umur</th>
                        <td>:</td>
                        <td>{{ $umur }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>:</td>
                        <td>{{ $profile->gender ?? "-" }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Test</th>
                        <td>:</td>
                        <td>@dateId(date("Y-m-d", strtotime($last_test->result_end))) {{ date("H:i", strtotime(strtotime($last_test->result_end))) }}</td>
                    </tr>
                </table>
            </div>
            <div class="d-flex">
                @for($i = 0; $i < 4; $i++)
                    @php
                        $total_skor = 0;
                    @endphp
                    <div class="mx-2">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Jawab</th>
                                    <th>Skor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($test->questions->skip($i*15)->take(15) as $item)
                                    @php
                                        $_point = $jawaban[$item->id] ?? [];
                                        $_skor = $skor[$item->id] ?? 0;
                                        $order = "";
                                        if(!is_array($_point)){
                                            if(!empty($_point)){
                                                $order = $_point ?? "-";
                                            }
                                        } else {
                                            if(!empty($_point)){
                                                foreach($_point as $key => $_pp){
                                                    $order .= $point[$key].", ";
                                                }
                                            }
                                        }
                                        $total_skor += $_skor;
                                        // if($item->question_type == 1){
                                        //     if(!empty($_point)){
                                        //         $order = $point[$_point];
                                        //     }
                                        // } else {
                                        //     if(!empty($_point)){
                                        //         $order = $_point ?? "-";
                                        //     }
                                        // }
                                    @endphp
                                    <tr>
                                        <td>{{ $item->order_num }}</td>
                                        <td>{{ $order }}</td>
                                        <td>{{ $_skor }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td>{{ $total_skor }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endfor
            </div>
            <div class="d-flex flex-column">
                <table class="table table-borderless">
                    <tr>
                        <td>Jumlah yang salah/ditinggalkan</td>
                        <td>:</td>
                        <td>{{ $wpt->wrong }}</td>
                    </tr>
                    <tr>
                        <td>Jumlah yang benar</td>
                        <td>:</td>
                        <td>{{ $wpt->true }}</td>
                    </tr>
                    <tr>
                        <td>Poin penyesuaian</td>
                        <td>:</td>
                        <td>{{ $wpt->age_point }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Total Skor</td>
                        <td class="fw-bold">:</td>
                        <td class="fw-bold">{{ $wpt->score }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-right" align="right">IQ</td>
                        <td class="fw-bold">:</td>
                        <td class="fw-bold">{{ $iq->iq }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-right" align="right">Interpretasi</td>
                        <td class="fw-bold">:</td>
                        <td class="fw-bold"></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="fw-bold">{{ $interpretasi->label }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
