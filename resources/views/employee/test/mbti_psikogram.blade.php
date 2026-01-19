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
            <div class="d-flex flex-column">
                @php
                    $psikogram_res = json_decode($mbti_result->psikogram_result, true);
                    $tagIdentifier = [];
                @endphp
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-danger">
                            <th class="text-white">No</th>
                            <th colspan="4" class="text-center text-white">Dimensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($psikogram_res as $i => $item)
                            @php
                                $lTag = $tag[$item['left']['identifier']];
                                $rTag = $tag[$item['right']['identifier']];
                                $lPctg = $item['left']['%'];
                                $rPctg = $item['right']['%'];
                                if($lPctg > $rPctg){
                                    $tagIdentifier[] = $lTag;
                                } else {
                                    $tagIdentifier[] = $rTag;
                                }
                            @endphp
                            <tr>
                                <td class="bg-light-primary">{{ $i+1 }}</td>
                                <td class="bg-light-info">{{ ucwords($item['left']['label']) }} ({{ $lTag }})</td>
                                <td class="bg-warning text-center">{{ $item['left']['%'] }}%</td>
                                <td class="bg-warning text-center">{{ $item['right']['%'] }}%</td>
                                <td class="bg-light-info">({{ $rTag }}) {{ ucwords($item['right']['label']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="border p-5 d-flex flex-column" style="background-color: #bbe7f1">
                    <span>Tipe Kepribadian :</span>
                    <div class="d-flex justify-content-center">
                        <span class="fw-bold fs-1">{{ implode(" ", $tagIdentifier) }}</span>
                    </div>
                </div>
                <table class="table table-borderless">
                    @foreach ($_desc as $idesc => $label)
                        <thead>
                            <tr>
                                <th><span class="fs-2 fw-bold">{{ $label }}</span></th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            @foreach ($analysis[$idesc] as $i => $item)
                                <tr class="border">
                                    <td class="p-2">
                                        <div class="d-flex">
                                            <span class="me-3">{{ $i+1 }}.</span>
                                            <span>{{ $item->descriptions }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
