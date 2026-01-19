@php
    $loc = "Work From Anywhere";
    $locClass = "badge-danger";
    if($attendance->location_type == 1){
        $loc = "Work From Office";
        $locClass = "badge-primary";
    } elseif($attendance->location_type == 2){
        $loc = "Work From Branch Office";
        $locClass = "badge-success";
    } elseif($attendance->location_type == 3){
        $loc = "Work From Home";
        $locClass = "badge-warning";
    }

    $condition = "Sangat Sehat";
    $conditionClass = "badge-success";
    if($attendance->condition == 2){
        $condition = "Kurang Sehat";
        $conditionClass = "badge-warning";
    } elseif($attendance->condition == 3){
        $condition = "Tidak Sehat";
        $conditionClass = "badge-danger";
    }


@endphp

<div class="row">
    <div class="col-12">
        <div class="mx-auto p-2 rounded shadow w-200px">
            <img src="{{ str_replace("public", "public_html", asset("media/user/$attendance->images")) }}" class="w-100">
        </div>
    </div>
    <div class="col-12 text-center mt-5">
        <div class="d-flex flex-column align-items-center">
            <span class="badge {{ $locClass }} mb-3">{{ $loc }}</span>
            @if ($type == "clock_in")
                <span class="badge {{ $conditionClass }} mb-3">{{ $condition }}</span>
            @endif
            <label class="font-weight-boldest">{{ strtoupper(str_replace("_", " ", $type)) }}</label> <br>
            <label>{!! date("D, d M Y H:i", strtotime($attendance->clock_time)) !!}</label> <br>
            <label>{!! $attendance->address !!}</label>
        </div>
    </div>
</div>
