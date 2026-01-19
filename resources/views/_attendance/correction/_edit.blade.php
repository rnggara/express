<div class='card-body'>
    <form action="{{ route("attendance.correction.attendance") }}" method="post" enctype="multipart/form-data">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-100px me-5">
                    <div class="symbol-label" style="background-image: url({{ asset($personel->user->user_img ?? "images/image_placeholder.png") }})">
                    </div>
                </div>
                <div class="d-flex justify-content-between w-100 align-items-baseline">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="fs-3 fw-bold me-5">{{ $personel->emp_name }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span>{{ $hariId[date("N", strtotime($date))] }} - {{ date("d F Y", strtotime($date)) }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        @if (!empty($att_record))
                            @if (empty($att_record['reasons']))
                            <span class="badge badge-secondary">
                                {{ $day_code->where("id", $_shift->day_code)->first()->day_name }}
                            </span>
                            @endif
                            @foreach ($att_record['reasons'] ?? [] as $rp)
                                @if (isset($rname[$rp['id']]))
                                    <span class="badge badge-outline text-white" style="background-color: {{ $rcolor[$rp['id']] }}">{{ $rname[$rp['id']] }}</span>
                                @endif
                            @endforeach
                        @else
                            <span class="badge badge-secondary">
                                {{ $day_code->where("id", $_shift->day_code)->first()->day_name }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card bg-secondary-crm mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Workday</label>
                            <select name="workday" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_detail">
                                @foreach ($day_code as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($_shift->day_code ?? "") ? "SELECTED" : "" }} {{ !empty($holidays) && $item->id == 3 ? "SELECTED" : "" }}>{{ $item->day_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3 fv-row">
                            <label class="col-form-label">Schedule Time In</label>
                            <input type="text" value="{{ date("H:i", strtotime($_shift->schedule_in)) }}" disabled class="form-control">
                        </div>
                        <div class="col-3 fv-row">
                            <label class="col-form-label">Schedule Time Out</label>
                            <input type="text" value="{{ date("H:i", strtotime($_shift->schedule_out)) }}" disabled class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Time In</label>
                            <input type="text" value="{{ !empty($att_record) ? (!empty($att_record->timin) ? date("H:i", strtotime($att_record->timin)) : "-") : "-" }}" disabled class="form-control">
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Time Out</label>
                            <input type="text" value="{{ !empty($att_record) ? (!empty($att_record->timout) ? date("H:i", strtotime($att_record->timout)) : "-") : "-" }}" disabled class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-secondary-crm mb-5">
                <div class="card-header">
                    <h3 class="card-title">
                        Time In & Out
                    </h3>
                    <div class="card-toolbar">
                        <i class="fi fi-rr-caret-down"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Actual Time In</label>
                            <input type="datetime-local" min="{{ date("Y-m-d", strtotime($date)) }}" value="{{ $att_record->timin ?? "" }}" name="actual_timin" class="form-control">
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Actual Time Out</label>
                            <input type="datetime-local" min="{{ date("Y-m-d", strtotime($date)) }}" value="{{ $att_record->timout ?? "" }}" name="actual_timout" class="form-control">
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Actual Break Start</label>
                            <input type="datetime-local" min="{{ date("Y-m-d", strtotime($date)) }}" value="{{ $att_record->break_start ?? "" }}" name="actual_break_start" class="form-control">
                        </div>
                        <div class="col-6 fv-row">
                            <label class="col-form-label">Actual Break End</label>
                            <input type="datetime-local" min="{{ date("Y-m-d", strtotime($date)) }}" value="{{ $att_record->break_end ?? "" }}" name="actual_break_end" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-secondary-crm mb-5">
                <div class="card-header">
                    <h3 class="card-title">
                        Overtime
                    </h3>
                    <div class="card-toolbar">
                        @if (empty($overtime))
                            <i class="fa fa-add text-primary"></i>
                        @else
                            <button class="btn btn-icon text-danger" type="button" onclick="batalkanOvt({{ $overtime->id }}, 'cancel')">
                                <i class="fi fi-rr-trash"></i>
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="fv-row col-4" id="sel-ovt">
                            <label class="col-form-label fw-bold">Overtime Type</label>
                            <select name="overtime_type" class="form-select" data-control="select2" data-placeholder="Select Overtime Type" data-dropdown-parent="#sel-ovt">
                                <option value=""></option>
                                @foreach (\Config::get("constants.overtime_type") as $key => $item)
                                    <option value="{{ $key }}" {{ ($overtime->overtime_type ?? "") == $key ? "SELECTED" : ""}} {{ $_shift->day_code == 1 ? (stripos($key, "off") !== false ? "disabled" : "" ) : (stripos($key, "off") !== false ? "" : "disabled" ) }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label fw-bold">Start Date</label>
                            <input type="time" name="start_date" value="{{ $overtime->overtime_start_time ?? "" }}" class="form-control" id="add-start-date" disabled placeholder="Day - 00:00">
                        </div>
                        <div class="fv-row col-4">
                            <label class="col-form-label fw-bold">End Date</label>
                            <input type="time" name="end_date" value="{{ $overtime->overtime_end_time ?? "" }}" class="form-control" id="add-end-date" disabled placeholder="Day - 00:00">
                        </div>
                    </div>
                    <div class="fv-row my-3">
                        <div class="d-flex flex-column mb-5 repeater">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" {{ ($overtime->add_break ?? "") == "1" ? "CHECKED" : "" }} disabled name="break_overtime" onclick="add_bs(this)" type="checkbox" value="1" />
                                        Tambahkan Break Overtime
                                    </label>
                                </div>
                                <button type="button" class="btn text-primary {{ ($overtime->add_break ?? "") == "1" ? "" : "d-none" }} break-shift" data-repeater-create>
                                    <i class="fa fa-plus text-primary"></i>
                                    Tambah
                                </button>
                            </div>
                            <div class="form-group {{ ($overtime->add_break ?? "") == "1" ? "" : "d-none" }} break-shift">
                                @if (!empty($overtime) && !empty($overtime->breaks))
                                    @foreach ($overtime->breaks as $i => $item)
                                        <div data-repeater-list="break_shift">
                                            <div class="row" data-repeater-item>
                                                <div class="col-5">
                                                    <div class="fv-row">
                                                        <label for="" class="col-form-label">Break <span class="break-num">{{ $i+1 }}</span> Start</label>
                                                        <input type="time" value="{{ $item['start'] }}" name="start" class="form-control" id="">
                                                    </div>
                                                </div>
                                                <div class="col-5">
                                                    <div class="fv-row">
                                                        <label for="" class="col-form-label">Break <span class="break-num">{{ $i+1 }}</span> End</label>
                                                        <input type="time" value="{{ $item['end'] }}" name="end" class="form-control" id="">
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <div class="fv-row">
                                                        <label for="" class="col-form-label w-100">&nbsp;</label>
                                                        <button data-repeater-delete class="bg-hover-light-danger bg-white btn btn-icon" type="button">
                                                            <i class="fi fi-rr-trash text-danger"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                <div data-repeater-list="break_shift">
                                    <div class="row" data-repeater-item>
                                        <div class="col-5">
                                            <div class="fv-row">
                                                <label for="" class="col-form-label">Break <span class="break-num"></span> Start</label>
                                                <input type="time" name="start" class="form-control" id="">
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="fv-row">
                                                <label for="" class="col-form-label">Break <span class="break-num"></span> End</label>
                                                <input type="time" name="end" class="form-control" id="">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="fv-row">
                                                <label for="" class="col-form-label w-100">&nbsp;</label>
                                                <button data-repeater-delete class="bg-hover-light-danger bg-white btn btn-icon" type="button">
                                                    <i class="fi fi-rr-trash text-danger"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="fv-row col-6" id="sel-pd">
                            <label class="col-form-label fw-bold">Paid By</label>
                            <select name="paid_type" class="form-select" data-control="select2" disabled data-placeholder="Select" data-dropdown-parent="#sel-pd">
                                <option value=""></option>
                                <option value="money" {{ ($overtime->paid ?? "") == "money" ? "SELECTED" : "" }}>Money</option>
                                <option value="days" {{ ($overtime->paid ?? "") == "days" ? "SELECTED" : "" }}>Days</option>
                                <option value="no paid" {{ ($overtime->paid ?? "") == "no paid" ? "SELECTED" : "" }}>No Paid</option>
                            </select>
                        </div>
                        <div class="fv-row col-4 d-none">
                            <label class="col-form-label fw-bold">&nbsp;</label>
                            <input type="number" name="day" class="form-control" min="1" disabled value="1">
                        </div>
                        <div class="fv-row col-6" id="sel-dept">
                            <label class="col-form-label fw-bold">Allocation Departement</label>
                            <select name="departement" class="form-select" data-control="select2" disabled data-placeholder="Select Departement" data-dropdown-parent="#sel-dept">
                                <option value=""></option>
                                @foreach ($depts as $item)
                                    <option value="{{ $item->id }}" {{ ($overtime->departement ?? "") == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label fw-bold">Reference Number</label>
                        <input type="text" name="reference" placeholder="OVT/thnblntgl/no urut" value="{{ $overtime->reference ?? "" }}" disabled class="form-control">
                    </div>
                    <div class="fv-row d-flex flex-column mt-5">
                        <div class="d-flex align-items-center">
                            <label class="btn btn-secondary ">
                                <input type="file" data-required name="file" accept=".jpg,.png,.pdf" class="d-none">
                                Attachment
                                <i class="fi fi-rr-clip"></i>
                            </label>
                            <span class="text-primary ms-5" data-file></span>
                        </div>
                        <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
                    </div>
                </div>
            </div>
            <div class="card bg-secondary-crm mb-5">
                <div class="card-body">
                    <!--begin::Accordion-->
                    <div class="accordion accordion-icon-collapse" id="kt_accordion_add_leave">
                        <!--begin::Item-->
                        <div class="mb-5">
                            <!--begin::Header-->
                            <div class="accordion-header py-3 d-flex justify-content-between collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_add_leave_item_1">
                                <h3 class="fs-4 fw-semibold mb-0 ms-4">Add Form Leave</h3>
                                <span class="accordion-icon">
                                    <i class="fa fa-plus text-primary fs-3 accordion-icon-off"></i>
                                    <i class="fa fa-minus text-primary fs-3 accordion-icon-on"></i>
                                </span>
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div id="kt_accordion_add_leave_item_1" class="fs-6 collapse" data-bs-parent="#kt_accordion_add_leave">
                                <div class="card shadow-none">
                                    <div class="card-body bg-secondary-crm">
                                        <div class="row">
                                            <div class="fv-row col-6">
                                                <label class="col-form-label fw-bold">Reason Type</label>
                                                <select name="leave[reason]" class="form-select" data-control="select2" data-placeholder="Select Reason Type" data-dropdown-parent="#kt_accordion_add_leave_item_1" id="">
                                                    <option value=""></option>
                                                    @foreach ($reason_types ?? [] as $item)
                                                        <option value="{{ $item->id }}" {{ (old("reason") ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label fw-bold">Reason Used</label>
                                                <select name="leave[leave_used]" class="form-select" data-control="select2" data-placeholder="Select Reason Used" data-dropdown-parent="#kt_accordion_add_leave_item_1" id="">
                                                    <option value=""></option>
                                                    @foreach ($rcon as $item)
                                                        <option value="{{ $item->id }}" data-tp="{{ $item->reason_type_id }}" disabled>{{ $item->reasonName->reason_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fv-row col-6">
                                                <label class="col-form-label fw-bold">Date</label>
                                                <input type="text" name="leave[date]" value="{{ $date }}" readonly class="form-control">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label fw-bold">Reference Number</label>
                                                <input type="text" name="leave[ref_num]" value="{{ old("ref_num") }}" class="form-control" placeholder="Input Data">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fv-row col-12">
                                                <label class="col-form-label fw-bold">Detail Note</label>
                                                <textarea name="leave[notes]" class="form-control" id="" cols="30" rows="10">{{ old("notes") }}</textarea>
                                            </div>
                                            <div class="fv-row col-12 mt-5">
                                                <div class="d-flex align-items-center" data-toggle="file">
                                                    <label class="btn btn-secondary btn-sm me-5">
                                                        <span>Add File <i class="fi fi-rr-clip"></i></span>
                                                        <input type="file" name="leave[file]" data-toggle="file" accept=".jpg, .png, .pdf" class="d-none" id="">
                                                    </label>
                                                    <span class="text-primary" data-file-label></span>
                                                </div>
                                                <div class="d-flex flex-column mt-5 text-muted">
                                                    <span>File Format : JPG, PNG, PDF</span>
                                                    <span>Max 25 mb</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Item-->
                    </div>
                </div>
            </div>
            {{-- @if (date("Y-m-d") >= $date && empty($holidays)) --}}
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="emp_id" value="{{ $personel->id }}">
                <button type="submit" class="btn btn-primary">
                    Save
                </button>
            </div>
            {{-- @endif --}}
        </div>
    </form>
</div>
