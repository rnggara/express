<div class='card-body'>
    <form action="{{route("crm.pref.attendance.reason_name.store")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Reason Condition</h3>
                    <span class="text-muted fs-base">Atur Detail Reason Condition</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($condition->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="row">
                    <div class="row">
                        <div class="fv-row col-12 col-md-6">
                            <label class="col-form-label fw-bold">Reason Name</label>
                            <select name="reason_name_id" class="form-select" data-toggle="form" data-control="select2" data-dropdown-parent="#kt_drawer_periode" data-placeholder="Select Type">
                                <option value=""></option>
                                @foreach ($reason_names as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($condition->reason_name_id ?? null) ? "SELECTED" : "" }}>{{ $item->reason_name }}</option>
                                @endforeach
                            </select>
                            @error('reason_name_id')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="fv-row col-12 col-md-6">
                            <label class="col-form-label fw-bold">Reason Type</label>
                            <select name="reason_type" class="form-select" data-toggle="form" data-control="select2" data-dropdown-parent="#kt_drawer_periode" data-placeholder="Select Type">
                                <option value=""></option>
                                @foreach ($reason_types as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($condition->reason_type_id ?? null) ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('reason_type')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="fv-row col-12 col-md-4">
                            <label class="col-form-label fw-bold">Process Sequence</label>
                            <input type="number" name="process_sequence" class="form-control" value="{{ $condition->process_sequence }}" data-toggle="form" placeholder="Input Data">
                            @error('process_sequence')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="fv-row col-12 col-md-4">
                            <label class="col-form-label fw-bold">Reason Sequence</label>
                            <input type="number" name="reason_sequence" class="form-control" value="{{ $condition->reason_sequence }}" data-toggle="form" placeholder="Input Data">
                            @error('reason_sequence')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="fv-row col-12 col-md-4">
                            <label class="col-form-label fw-bold">Report Type</label>
                            <select name="report_type" class="form-select" data-toggle="form" data-control="select2" data-dropdown-parent="#kt_drawer_periode" data-placeholder="Select Type">
                                <option value=""></option>
                                @foreach ($report_types as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($condition->report_type_id ?? null) ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('report_type')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="fv-row mt-5">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" onclick="reasonLeave(this)" {{ 1 == ($condition->cut_leave ?? null) ? "CHECKED" : "" }} name="cut_leave" value="1" id="ckmdetail1" />
                            <label class="form-check-label" for="ckmdetail1">
                                Reason memotong Kuota Cuti
                            </label>
                        </div>
                        <div class="mb-5 {{ 1 == ($condition->cut_leave ?? null) ? "" : "d-none" }}" data-reason-leave>
                            <div class="row">
                                <div class="fv-row col-6">
                                    <label class="col-form-label fw-bold">Leave Type</label>
                                    <select name="leave_type" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_periode" data-placeholder="Select Type">
                                        <option value=""></option>
                                        @foreach ($leave_types as $item)
                                            <option value="{{ $item->leave_used }}" {{ $condition->leave_type == $item->leave_used ? "SELECTED" : "" }}>{{ $item->leave_reason_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="col-form-label fw-bold">Total Day</label>
                                    <input type="number" name="leave_days" step=".1" value="{{ $condition->leave_days }}" class="form-control" min="0" id="">
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" {{ 1 == ($condition->ess ?? null) ? "CHECKED" : "" }} name="ess" value="1" id="ckm2" />
                            <label class="form-check-label" for="ckm2">
                                Hubungkan dengan Aplikasi ESS
                            </label>
                        </div>
                        <div class="fv-row mb-5" data-form="additional">
                            <label class="col-form-label fw-bold">Condition</label>
                            <div class="position-relative">
                                <input type="text" class="form-control" name="condition_formula" placeholder="{{ !empty($condition->conditions) ? "" : "Select Formula From Library Board" }}" readonly>
                                <div class="position-absolute top-25 d-flex align-items-center mx-5">
                                    @foreach ($condition->conditions ?? [] as $key => $item)
                                        <span class="badge badge-{{$bgColor[$key]}} me-2">{{ ucwords(str_replace("_", " ", $key)) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @php
                            $cond = array_keys($condition->conditions ?? []);
                        @endphp
                        <div class="fv-row mb-5">
                            <label class="fw-bold w-100">Custom Reason Board</label>
                            <span>Select & Input Board To Formula</span>
                            <div class="w-100 scroll d-flex align-items-start overflow-auto">
                                <div class="card w-250px flex-row-auto">
                                    <div class="card-body rounded border">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Schedule</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[schedule]" {{ in_array("schedule", $cond ?? []) ? "checked" : "" }} onclick="switch_on(this)" data-color="primary" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <select name="schedule_id" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_periode" data-placeholder="Select Schedule">
                                                <option value=""></option>
                                                @foreach ($day_codes as $item)
                                                    <option value="{{ $item->id }}" {{ $condition->schedule_id == $item->id ? "SELECTED" : "" }}>{{ $item->day_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mx-3"></div>
                                <div class="card w-250px flex-row-auto">
                                    <div class="card-body rounded border">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Shift Code</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[shift_code]" {{ in_array("shift_code", $cond) ? "checked" : "" }} onclick="switch_on(this)" data-color="info" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <select name="shift_code[]" class="form-select" multiple data-control="select2" data-dropdown-parent="#kt_drawer_periode" data-placeholder="Select Sift">
                                                <option value=""></option>
                                                @foreach ($shifts as $item)
                                                    <option value="{{ $item->id }}"  {{ in_array($item->id, $condition->shift_code ?? []) ? "SELECTED" : "" }}>{{ $item->shift_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mx-3"></div>
                                <div class="card w-250px flex-row-auto">
                                    <div class="card-body rounded border">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Time In</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[time_in]" {{ in_array("time_in", $cond ?? []) ? "checked" : "" }} onclick="switch_on(this)" data-color="warning" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="time_in[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#kt_drawer_periode">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}" {{ $condition->time_in_condition == $opt ? "SELECTED" : "" }}>{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="time_in[time]" class="form-control" value="{{ $condition->time_in ?? "" }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mx-3"></div>
                                <div class="card w-250px flex-row-auto">
                                    <div class="card-body rounded border">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Time Out</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[time_out]" {{ in_array("time_out", $cond ?? []) ? "checked" : "" }} onclick="switch_on(this)" data-color="danger" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="time_out[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#kt_drawer_periode">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}" {{ $condition->time_out_condition == $opt ? "SELECTED" : "" }}>{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="time_out[time]" class="form-control" value="{{ $condition->time_out ?? "" }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mx-3"></div>
                                <div class="card w-250px flex-row-auto">
                                    <div class="card-body rounded border">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Late In</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[late_in]" {{ in_array("late_in", $cond ?? []) ? "checked" : "" }} onclick="switch_on(this)" data-color="light-warning" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="late_in[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#kt_drawer_periode">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}" {{ $condition->late_in_condition == $opt ? "SELECTED" : "" }}>{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="late_in[time]" class="form-control" value="{{ $condition->late_in ?? "" }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mx-3"></div>
                                <div class="card w-250px flex-row-auto">
                                    <div class="card-body rounded border">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Fast Out</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[fast_out]" {{ in_array("fast_out", $cond ?? []) ? "checked" : "" }} onclick="switch_on(this)" data-color="light-danger" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="fast_out[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#kt_drawer_periode">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}" {{ $condition->fast_out_condition == $opt ? "SELECTED" : "" }}>{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="fast_out[time]" class="form-control" value="{{ $condition->fast_out ?? "" }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mx-3"></div>
                                <div class="card w-250px flex-row-auto">
                                    <div class="card-body rounded border">
                                        <div class="d-flex justify-content-between mb-5">
                                            <span class="fs-5 fw-bold">Overtime</span>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="condition[overtime]" {{ in_array("overtime", $cond ?? []) ? "checked" : "" }} onclick="switch_on(this)" data-color="light-primary" data-toggle="switch" value="1" type="checkbox" role="switch">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <select name="overtime[condition]" class="form-select me-2" data-control="select2" data-dropdown-parent="#kt_drawer_periode">
                                                @foreach (\Config::get("constants.reason_operator") ?? [] as $opt => $op)
                                                    <option value="{{ $opt }}" {{ $condition->overtime_condition == $opt ? "SELECTED" : "" }}>{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="time" name="overtime[time]" class="form-control" value="{{ $condition->overtime ?? "" }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" onclick="reasonPengganti(this)" type="checkbox" {{ 1 == ($condition->reason_pengganti ?? null) ? "CHECKED" : "" }} name="reason_pengganti" value="1" id="ckm3" />
                            <label class="form-check-label" for="ckm3">
                                Reason Pengganti
                            </label>
                        </div>
                        <div class="mb-5 {{ $condition->reason_pengganti == 1 ? "" : 'd-none' }}" data-reason-pengganti id="sel-reason-pengganti-edit">
                            <div class="fv-row">
                                <select name="rp_detail[]" class="form-select" data-placeholder="Select Reason Pengganti" multiple data-control="select2" data-dropdown-parent="#sel-reason-pengganti-edit" id="">
                                    @foreach ($rconditions->where("status", 1) as $item)
                                        <option value="{{ $item->id }}" {{ in_array($item->id, $condition->rp_detail ?? []) ? "selected" : "" }}>{{ $item->reasonName->reason_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="type" value="condition">
                <input type="hidden" name="id" value="{{$condition->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_periode_close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
