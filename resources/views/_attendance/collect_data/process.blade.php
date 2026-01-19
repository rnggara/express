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
            <span class="fs-3 fw-bold">Collect Data - Validation</span>
            <span class="text-muted">{{ $oldName }}, {{ date("d F Y", strtotime($inputs['start_date'])) }} - {{ date("d F Y", strtotime($inputs['end_date'])) }}</span>
        </div>
    </div>
    <form action="{{ route("attendance.collect_data.finalize_process") }}" method="post">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between mb-5">
                <div class="position-relative">
                    <input type="text" name="search_table" class="form-control ps-10" placeholder="Search" id="">
                    <span class="position-absolute top-25 fi fi-rr-search fs-3 text-muted ms-2"></span>
                </div>
                <div>
                    @csrf
                    <input type="hidden" name="target_file" value="{{ $target_file }}">
                    @foreach ($inputs as $key => $item)
                        <input type="hidden" name="{{ $key }}" value="{{ $item }}">
                    @endforeach
                    <button type="submit" class="btn btn-primary btn-sm">
                        Process
                    </button>
                </div>
            </div>
            <table class='table table-display-2' id='table-row' data-paging="false">
                <thead>
                    <tr>
                        <th>Row</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Absensi Code</th>
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
                                $irow = ($i + 1) - $start_row;
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
                                    $user_name = $_reg->emp->emp_name ?? "";
                                    $show = true;
                                } else {
                                    $keyId = $item[$cell['emp_id']];
                                    $_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[$cell['date']], $timeZone);
                                    $_time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($item[$cell['time']], $timeZone);
                                    $date = date("Y-m-d", strtotime($_date->format("Y-m-d")));
                                    $time = date("H:i", $_time);
                                    $att = $item[$cell['absensi']] ?? null;
                                    $_reg = $reg[$keyId] ?? [];
                                    $user_name = $_reg->emp->emp_name ?? $keyId;
                                    $show = true;
                                    $row = implode(",", $item->toArray());
                                    if($row == ",,,"){
                                        $show = false;
                                    }
                                }

                                $remarks = "";
                                if(empty($user_name)){
                                    $remarks = "Employee ID not registered";
                                } else {
                                    $d = \DateTime::createFromFormat("Y-m-d", $date);

                                    if($d && $d->format("Y-m-d")){

                                        if($date >= $input['start_date']){
                                            $t = \DateTime::createFromFormat("H:i", $time);

                                            if($t && $t->format("H:i")){
                                                if(in_array($att, [$machine->in_code, $machine->out_code, $machine->break_start_code, $machine->break_end_code])){
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
                            @endphp
                            @if ($show)
                                <tr>
                                    <td>{{ $irow }}</td>
                                    <td>{{ $user_name ?? "-" }}</td>
                                    <td>{{ $date }}</td>
                                    <td>{{ $time }}</td>
                                    <td>{{ $att }}</td>
                                    <td>
                                        <span class="{{ $check }}"></span>
                                    </td>
                                    <td>
                                        {{ $remarks }}
                                        <input type="hidden" name="row[{{ $irow }}][emp_id]" value="{{ $keyId }}">
                                        <input type="hidden" name="row[{{ $irow }}][date]" value="{{ $date }}">
                                        <input type="hidden" name="row[{{ $irow }}][time]" value="{{ $time }}">
                                        <input type="hidden" name="row[{{ $irow }}][att]" value="{{ $att }}">
                                        <input type="hidden" name="row[{{ $irow }}][remarks]" value="{{ $remarks }}">
                                        <input type="hidden" name="row[{{ $irow }}][valid]" value="{{$remarks == "Valid" ? 1 : 0 }}">
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
</div>
@endsection

@section('view_script')
    <script>

        $(document).ready(function(){

        })
    </script>
@endsection
