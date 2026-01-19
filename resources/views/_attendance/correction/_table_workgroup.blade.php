<table class="table bg-white" data-ordering="false" id="table-workgroup">
    <thead>
        <tr>
            <th>Workgroup</th>
            <th></th>
            @foreach ($sch as $seq => $item)
                @foreach ($item as $date => $val)
                    <th class="{{ $seq > 0 ? "d-none" : "" }} sequence" data-sequence="{{ $seq }}">
                        <span class="{{ $val['n'] == 7 ? "text-danger" : "" }}">{{ $val['label'] }}</span>
                    </th>
                @endforeach
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
        @foreach ($workgroups as $item)
            <tr>
                <td>
                    <b>{{ $item->workgroup_name }}</b>
                </td>
                <td> Employee</td>
                @foreach ($sch as $seq => $dt)
                    @foreach ($dt as $date => $val)
                        @php
                            $label = "N/A";
                            $cl = "#333";
                            $sId = null;
                            if(isset($data_schedule[$item->id])){
                                $el = $data_schedule[$item->id];
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
                        <td class="{{ $seq > 0 ? "d-none" : "" }} sequence" data-sequence="{{ $seq }}">
                            <div>
                                <span class="rounded py-2 cursor-pointer px-7 {{ $label == "OFF" ? "text-dark" : "text-white" }}" style="background-color: {{ $cl }}" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 10px">{{ $label }}</span>
                                <!--begin::Menu sub-->
                                <div class="menu-sub menu-sub-dropdown p-3 w-200px" data-kt-menu="true">
                                    <div class="d-flex flex-column pt-2">
                                        @foreach ($shifts as $sh)
                                            <div class="d-flex align-shs-center p-2 bg-hover-light-primary rounded {{ $sId == $sh->id ? 'bg-primary text-white' : "" }}" data-shift-toggle
                                                data-date="{{ $date }}"
                                                data-lbl="{{ date("d F Y", strtotime($date)) }}"
                                                data-shift-id="{{ $sh->id }}"
                                                data-shift-old="{{ $label }}"
                                                data-name="{{ $item->workgroup_name }}"
                                                data-id="{{ $item->id }}"
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
                    @endforeach
                @endforeach
                <th>

                </th>
            </tr>
        @endforeach
    </tbody>
</table>
