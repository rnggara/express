<table class="table bg-white" data-ordering="false" id="table-employee">
    <thead>
        <tr>
            <th class="d-none" data-edit></th>
            <th>Name</th>
            <th>Workgroup</th>
            @foreach ($sch as $seq => $item)
                @for($j = 1; $j <= 7; $j++)
                    @php
                        $val = collect($item)->where("n", $j)->first();
                    @endphp
                    @if (!empty($val))
                        <th class="{{ $seq > 0 ? "d-none" : "" }} sequence" data-sequence="{{ $seq }}">
                            <span class="{{ $val['n'] == 7 ? "text-danger" : "" }}">{{ $val['label'] }}</span>
                        </th>
                    @else
                        <th class="{{ $seq > 0 ? "d-none" : "" }} sequence" data-sequence="{{ $seq }}">
                            &nbsp;
                        </th>
                    @endif
                @endfor
            @endforeach
            <th>
                <div class="d-flex align-items-center justify-content-center">
                    <button class="btn btn-sm bg-hover-secondary" data-prev type="button">
                        <i class="fi fi-rr-caret-left"></i>
                    </button>
                    <button class="btn btn-sm bg-hover-secondary" data-next type="button">
                        <i class="fi fi-rr-caret-right"></i>
                    </button>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registrations as $item)
            @php
                $corr = $corrections[$item->emp_id] ?? [];
            @endphp
            @if (!empty($item->emp))
            <tr>
                <td class="d-none" data-edit>
                    <div class="form-check">
                        <input class="form-check-input" name="select" data-name="{{ $item->emp->emp_name }}" data-check data-emp="{{ $item->emp->id }}" type="checkbox" value="1" id="" />
                    </div>
                </td>
                <td style="width: 160px!important;">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-50px me-3">
                            <div class="symbol-label" style="background-image: url({{ asset($uImg[$item->emp_id] ?? "theme/assets/media/avatars/blank.png") }})"></div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-muted text-nowrap">{{ $item->emp->emp_id }}</span>
                            <a class="fw-bold text-dark" href="{{ route("attendance.registration.detail", $item->emp->id) }}">{{ $item->emp->emp_name }}</a>
                            <span class="text-muted">{{ $item->emp->dept->name ?? "-"}}</span>
                        </div>
                    </div>
                </td>
                <td style="width: 160px!important;">{{ $item->wg->workgroup_name }}</td>
                @foreach ($sch as $seq => $dt)
                    @for($j = 1; $j <= 7; $j++)
                        @php
                            $val = collect($dt)->where("n", $j)->first();
                        @endphp
                        @if (!empty($val))
                            @php
                                $date = $val['date'];
                                $label = "N/A";
                                $cl = "#333";
                                $sId = null;
                                if(isset($data_schedule[$item->workgroup])){
                                    $el = $data_schedule[$item->workgroup];
                                    if (isset($el[$date])) {
                                        $label = $el[$date]['shift'];
                                        $cl = $el[$date]['color'];
                                        $sId = $el[$date]['shift_id'];
                                    }
                                }

                                if(isset($corr[$date])){
                                    $label = $corr[$date]['shift'];
                                    $cl = $corr[$date]['color'];
                                    $sId = $corr[$date]['shift_id'];
                                }
                            @endphp
                            <td style="width: 160px!important;" class="{{ $seq > 0 ? "d-none" : "" }} sequence" data-sequence="{{ $seq }}">
                                <div>
                                    <span class="rounded py-2 cursor-pointer px-7 {{ $label == "OFF" ? "text-dark" : "text-white" }}" style="background-color: {{ $cl }}" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 10px">{{ $label }}</span>
                                    <!--begin::Menu sub-->
                                    <div class="menu-sub menu-sub-dropdown p-3 w-200px" data-kt-menu="true">
                                        <div class="d-flex flex-column pt-2">
                                            @foreach ($shifts as $sh)
                                                <div class="d-flex align-shs-center p-2 bg-hover-light-primary rounded {{ $sId == $sh->id ? 'bg-primary text-white' : "" }}" data-shift-toggle
                                                    data-date="{{ $date }}"
                                                    data-lbl="{{ date("d F Y", strtotime($date)) }}"
                                                    data-emp="{{ $item->emp->id }}"
                                                    data-shift-id="{{ $sh->id }}"
                                                    data-shift-old="{{ $label }}"
                                                    data-shift-old-color="{{ $cl }}"
                                                    data-shift-label="{{ $sh->shift_id }}"
                                                    data-shift-color="{{ $sh->shift_color }}">
                                                    <span class="me-5">{{ $sh->shift_id }}</span>
                                                    @if ($dayCode[$sh->day_code] == "Workday")
                                                        <span>{{ date("H:i", strtotime($sh->schedule_in)) }} - {{ date("H:i", strtotime($sh->schedule_out)) }}</span>
                                                    @else
                                                        <span class="fw-bold">{{ $dayCode[$sh->day_code] ?? "Offday" }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                        @else
                            <td style="width: 160px!important;" class="{{ $seq > 0 ? "d-none" : "" }} sequence" data-sequence="{{ $seq }}">
                            </td>
                        @endif
                    @endfor
                @endforeach
                <th>

                </th>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>
