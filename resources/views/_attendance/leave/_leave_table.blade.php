<table class="table table-display-2 bg-white" data-page-length="50" data-ordering="false" id="table-leave-balance">
    <thead>
        <tr>
            <th>Name</th>
            <th>Leave Group</th>
            <th>Periode Leave</th>
            <th>Kuota</th>
            <th>Terpakai</th>
            <th>Reserve</th>
            <th>Sold</th>
            <th>Minus Leave</th>
            <th>Sisa</th>
            <th>
                <button type="button" class="btn btn-icon btn-sm" onclick="editState(this)">
                    <i class="fi fi-rr-edit text-primary"></i>
                    <span class="d-none">Cancel</span>
                </button>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($leave_balance as $val)
            @php
                $el = $epl[$val->emp_id] ?? [];
                krsort($el);
                $el = collect($el);
                $item = $el->first();
                $jatah = 0;
                $terpakai = 0;
                $reserved = 0;
                $sold = 0;
                $minus = 0;
                $minusLimit = 0;
                foreach ($el as $_val) {
                    if(date("Y-m-d") <= $item->end_periode){
                        $jatah += $_val->jatah ?? 0;
                        $terpakai += $_val->used - $_val->anulir + $_val->unrecorded;
                        $reserved += $_val->reserved ?? 0;
                        $sold += $_val->sold ?? 0;
                        $minus += $_val->minus_used;
                        $minusLimit += $_val->minus_limit;
                    }
                }
                $sisa = $jatah - $terpakai - $reserved - $sold;
                $emp = $val->emp ?? null;
            @endphp
            @if (!empty($item) && !empty($emp))
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-40px me-3">
                                <img src="{{ asset($uImg[$val->emp_id] ?? "theme/assets/media/avatars/blank.png") }}" alt="">
                            </div>
                            <div class="d-flex flex-column">
                                <a class="fw-bold text-dark" href="{{ route("attendance.registration.detail", $val->emp_id) }}">{{ $val->emp->emp_name ?? "" }}</a>
                                <span class="text-muted">{{ $emp->user->uacdepartement->name ?? '' }}</span>
                            </div>
                        </div>
                    </td>
                    <td>{{ $val->leave->leave_group_name }}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-bold mb-1">Total Leave</span>
                            <span class="text-muted">Exp {{ date("F Y", strtotime($item->end_periode ?? null)) }}</span>
                        </div>
                    </td>
                    <td>{{ $jatah }}</td>
                    <td>{{ $terpakai }}</td>
                    <td>{{ $reserved }}</td>
                    <td>{{ $sold }}</td>
                    <td>{{ $minus."/".$minusLimit }}</td>
                    <td>{{ $sisa }}</td>
                    <td>
                        <button type="button" data-action="detail" class="btn btn-icon btn-sm" onclick="openDetail(this, 'detail-{{ $val->emp_id }}')">
                            <i class="fi fi-rr-caret-down text-dark"></i>
                        </button>
                        <button type="button" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail" data-action="edit" class="btn btn-icon btn-sm d-none" onclick="editLeave('{{ $val->emp_id }}')">
                            <i class="fi fi-rr-edit text-primary"></i>
                        </button>
                    </td>
                </tr>
                @foreach ($el as $_val)
                    @php
                        $jatah = $_val->jatah ?? 0;
                        $terpakai = $_val->used - $_val->anulir + $_val->unrecorded;
                        $reserved = $_val->reserved ?? 0;
                        $sold = $_val->sold ?? 0;
                        $sisa = $jatah - $terpakai - $reserved - $sold;
                        $minus = $_val->minus_used;
                        $minusLimit = $_val->minus_limit;
                    @endphp
                    <tr class="d-none bg-light-secondary" data-toggle="detail-{{ $val->emp_id }}">
                        <td colspan="2" align="center">
                            <span class="badge badge-lg badge-{{ date("Y-m-d") >( $_val->end_periode ?? null) ? "secondary" : "light-primary" }}">{{ date("Y-m-d") >( $_val->end_periode ?? null) ? "Expired" : "Active" }}</span>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold mb-1">{{ date("Y", strtotime($_val->start_periode ?? null)) }}</span>
                                <span class="text-muted">Exp {{ date("F Y", strtotime($_val->end_periode ?? null)) }}</span>
                            </div>
                        </td>
                        <td>{{ number_format($jatah, 1, ".", "") }}</td>
                        <td>{{ number_format($terpakai, 1, ".", "") }}</td>
                        <td>{{ number_format($reserved, 1, ".", "") }}</td>
                        <td>{{ number_format($sold, 1, ".", "") }}</td>
                        <td>{{ $minus."/".$minusLimit }}</td>
                        <td>{{ number_format($sisa, 1, ".", "") }}</td>
                        <td></td>
                        <td class="d-none"></td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>
