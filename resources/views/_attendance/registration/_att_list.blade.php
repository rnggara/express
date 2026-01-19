<table class="table bg-white table-row-bordered" data-paging="false" data-ordering="false">
    <thead>
        <tr>
            <th>Day</th>
            <th>Schedule</th>
            <th class="border-right-2">Main Reason</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Break Start</th>
            <th>Break End</th>
            <th>Ovt In</th>
            <th>Ovt Out</th>
            <th>Reason</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($schedules as $item)
            @php
                $_data = $att_data[$item['date']] ?? [];
                $lblIn = null;
                $lblOut = null;
                $tlIn = "";
                $tlOut = "";
                $inH = 0;
                $inM = 0;
                $outH = 0;
                $outM = 0;
                if(!empty($_data->ovthoursin)){
                    $inMinute = $_data->ovthoursin;
                    $inH += floor($_data->ovthoursin / 60);
                    $inM += $inMinute - ($inH * 60);
                    $startIn = date("H:i", strtotime($_data->ovtstartin));
                    $endIn = date("H:i", strtotime($_data->ovtendin));
                    $tlIn .= "<span>OVT : $startIn - $endIn</span>";
                }

                if(!empty($_data->autoOvtIn) && !empty($_data->autoOvtInDetail)){
                    $inMinute = $_data->autoOvtIn;
                    $inH += floor($_data->autoOvtIn / 60);
                    $inM += $inMinute - ($inH * 60);
                    $startIn = date("H:i", strtotime($_data->autoOvtInDetail['start']));
                    $endIn = date("H:i", strtotime($_data->autoOvtInDetail['end']));
                    $tlIn .= "<span>AUTO : $startIn - $endIn</span>";
                }

                if(!empty($_data->ovthours)){
                    $outMinute = $_data->ovthours;
                    $outH += floor($_data->ovthours / 60);
                    $outM += $outMinute - ($outH * 60);
                    $startIn = date("H:i", strtotime($_data->ovtstart));
                    $endIn = date("H:i", strtotime($_data->ovtend));
                    $tlOut .= "<span>OVT : $startIn - $endIn</span>";
                }

                if(!empty($_data->autoOvtOut) & !empty($_data->autoOvtOutDetail)){
                    $outMinute = $_data->autoOvtOut;
                    $outH += floor($_data->autoOvtOut / 60);
                    $outM += $outMinute - ($outH * 60);
                    $startIn = date("H:i", strtotime($_data->autoOvtOutDetail['start']));
                    $endIn = date("H:i", strtotime($_data->autoOvtOutDetail['end']));
                    $tlOut .= "<span>AUTO : $startIn - $endIn</span>";
                }

                if(!empty($inH) || !empty($inM)){
                    if(!empty($inH)){
                        $lblIn = "$inH Hours ";
                    }

                    if(!empty($inM)){
                        $lblIn .= "$inM Minutes";
                    }
                }

                if(!empty($outH) || !empty($outM)){
                    if($outH > 0){
                        $lblOut = "$outH Hours ";
                    }

                    if($outM > 0){
                        $lblOut .= "$outM Minutes";
                    }
                }
            @endphp
            <tr>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ $hariId[date("N", strtotime($item['date']))] }}</span>
                        <span>{{ date("d F Y", strtotime($item['date'])) }}</span>
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ $day_name[$_data->day_code ?? ""] ?? "-" }}</span>
                        <span>{{ $shift_code[$item['shift']] ?? "-" }}</span>
                    </div>
                </td>
                <td class="border border-left-0 border-right border-top-0 border-bottom-dark">
                    @if (empty($_data))
                        -
                    @endif
                    @foreach ($_data['reasons'] ?? [] as $rp)
                        @if (isset($rname[$rp['id']]))
                            <span class="badge badge-outline text-white" style="background-color: {{ $rcolor[$rp['id']] }}">{{ $rname[$rp['id']] }}</span>
                        @endif
                    @endforeach
                </td>
                <td>{{ (empty($_data) || (!empty($_data) && $_data->timin == "0000-00-00 00:00:00") || empty($_data->timin)) ? "-" : date("H:i", strtotime($_data->timin))  }}</td>
                <td>{{ (empty($_data) || (!empty($_data) && $_data->timout == "0000-00-00 00:00:00") || empty($_data->timout)) ? "-" : date("H:i", strtotime($_data->timout))  }}</td>
                <td>{{ (empty($_data) || (!empty($_data) && $_data->break_start == "0000-00-00 00:00:00") || empty($_data->break_start)) ? "-" : date("H:i", strtotime($_data->break_start))  }}</td>
                <td>{{ (empty($_data) || (!empty($_data) && $_data->break_end == "0000-00-00 00:00:00") || empty($_data->break_end)) ? "-" : date("H:i", strtotime($_data->break_end))  }}</td>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <span>{{ $lblIn ?? "-" }}</span>
                        @if (!empty($lblIn))
                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='d-flex flex-column align-items-center'>{!! $tlIn !!}</div>"></span>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <span>{{ $lblOut ?? "-" }}</span>
                        @if (!empty($lblOut))
                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='d-flex flex-column align-items-center'>{!! $tlOut !!}</div>"></span>
                        @endif
                    </div>
                </td>
                <td>
                    @if (isset($ovt_tp[$item['date']]))
                        @foreach ($ovt_tp[$item['date']] as $otp)
                            <span class="badge badge-primary">Overtime {{ $otp->overtime_type }}</span>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-icon btn-sm" onclick="att_correction('{{ $item['date'] }}')" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail">
                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
