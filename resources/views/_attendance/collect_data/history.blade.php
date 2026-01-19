@extends('_attendance.layout')

@section('view_content')
<div class="d-flex flex-column">
    <div class="d-flex align-items-center mb-5">
        <div class="symbol symbol-50px me-3">
            <div class="symbol-label bg-primary">
                <i class="fi fi-rr-layer-plus text-white fs-1"></i>
            </div>
        </div>
        <div class="d-flex flex-column">
            <span class="fs-3 fw-bold">Collect Data</span>
            <span class="text-muted">Managemen unggah data machine attendance</span>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="card bg-secondary-crm shadow-none">
                <div class="card-header">
                    <div class="d-flex flex-column card-title fs-base fw-normal border-0">
                        <span class="fw-bold">{{ $machine->machine_name }}</span>
                        <span>{{ date("d F Y", strtotime($history->start_date)) }} - {{ date("d F Y", strtotime($history->end_date)) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row rounded bg-white p-3">
                        <div class="col-6 mb-8 d-flex flex-column">
                            <span class="text-muted">Collec Data Date</span>
                            <span class="">{{ date("d F Y", strtotime($history->created_at)) }}</span>
                        </div>
                        <div class="col-6 mb-8 d-flex flex-column">
                            <span class="text-muted">Daily Process Date</span>
                            <span class="">{{ date("d F Y", strtotime($history->start_date)) }}</span>
                        </div>
                        <div class="col-6 d-flex flex-column">
                            <span class="text-muted">Total Data Collect</span>
                            <span class="">{{ $total_data }}</span>
                        </div>
                        <div class="col-6 d-flex flex-column">
                            <span class="text-muted">Process By</span>
                            <span class="">{{ $user_name->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card bg-secondary-crm shadow-none">
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <div class="position-relative">
                                <input type="text" name="search_table" class="form-control ps-10" placeholder="Search" id="">
                                <span class="position-absolute top-25 fi fi-rr-search fs-3 text-muted ms-2"></span>
                            </div>
                        </div>
                        <table class='table table-display-2 bg-white' id='table-row'>
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Card Id</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>AT Code</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $start_row = $machine->start_row;
                                @endphp
                                @foreach ($data as $i => $item)
                                    @if ($i >= $start_row)
                                        @php
                                            $user_name = "";
                                            $keyId = "";
                                            $check = "fi fi-rr-circle-x text-danger";
                                            if (in_array($machine->program->program_type, ['txt', 'csv'])) {
                                                $keyId = substr($item, $cell['emp_id']['position'], $cell['emp_id']['width']);
                                                if(!is_numeric($keyId)){
                                                    $lzero = 0;
                                                    for ($j=0; $j < strlen($keyId); $j++) {
                                                        $iKey = $keyId[$j];
                                                        if(!is_numeric($iKey)){
                                                            $lzero = $j;
                                                            break;
                                                        }
                                                    }
                                                    $item = str_repeat("0", $cell['emp_id']['width'] - $lzero)."$item";
                                                    $keyId = intval(substr($item, $cell['emp_id']['position'], $cell['emp_id']['width']));
                                                }
                                                $year = substr($item, $cell['date']['year']['position'], $cell['date']['year']['width']);
                                                $month = substr($item, $cell['date']['month']['position'], $cell['date']['month']['width']);
                                                $date = substr($item, $cell['date']['date']['position'], $cell['date']['date']['width']);
                                                $hour = substr($item, $cell['time']['hour']['position'], $cell['time']['hour']['width']);
                                                $minute = substr($item, $cell['time']['minute']['position'], $cell['time']['minute']['width']);
                                                $att = substr($item, $cell['absensi']['position'], $cell['absensi']['width']);
                                                $date = "$year-$month-$date";
                                                $time = "$hour:$minute";
                                                $_reg = $reg[$keyId] ?? [];
                                                $user_name = $_reg->emp->emp_name ?? null;
                                            } else {
                                                $keyId = $item[$cell['emp_id']];
                                                $_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[$cell['date']], $timeZone);
                                                $_time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($item[$cell['time']], $timeZone);
                                                $date = date("Y-m-d", strtotime($_date->format("Y-m-d")));
                                                $time = date("H:i", $_time);
                                                $att = $item[$cell['absensi']];
                                                $_reg = $reg[$keyId] ?? [];
                                                $user_name = $_reg->emp->emp_name ?? null;
                                            }
                    
                                            $remarks = "";
                                            if(empty($user_name)){
                                                $remarks = "Employee ID not registered";
                                            } else {
                                                $d = \DateTime::createFromFormat("Y-m-d", $date);
                    
                                                if($d && $d->format("Y-m-d")){
            
                                                    if($date >= $history['start_date'] && $date <= $history['end_date']){
                                                        $t = \DateTime::createFromFormat("H:i", $time);
                                                    
                                                        if($t && $t->format("H:i")){
                                                            if(in_array($att, [$machine->in_code, $machine->out_code])){
                                                                $check = "fi fi-rr-check-circle text-success";
                                                                $remarks = "Valid";
                                                            } else {
                                                                $remarks = "Absensi Code format is invalid";
                                                            }
                                                        } else {
                                                            $remarks = "Time format is invalid";
                                                        }
                                                    } else {
                                                        $remarks = "Outside date range";
                                                    }
                                                } else {
                                                    $remarks = "Date format is invalid";
                                                }
                                            }
                                            $irow = ($i + 1) - $start_row;
                                        @endphp
                                        <tr>
                                            <td>{{ $irow }}</td>
                                            <td>{{ $user_name ?? "-" }}</td>
                                            <td>{{ $date }}</td>
                                            <td>{{ $time }}</td>
                                            <td>{{ $att == "1" ? "in" : "out" }}</td>
                                            <td>
                                                <span class="{{ $check }}"></span>
                                            </td>
                                            <td>
                                                {{ $remarks }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection