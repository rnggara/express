<div class='card-body'>
    <form action="{{route("crm.pref.attendance.leave.store")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Leave Group</h3>
                    <span class="text-muted fs-base">{{ $leave->leave_group_id }}</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" {{ ($leave->input ?? null) == 1 ? "checked" : "" }} type="checkbox" role="switch" name="status" value="1" {{ ($leave->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Leave Group ID</label>
                        <input type="text" name="leave_group_id" value="{{ $leave->leave_group_id }}" class="form-control" placeholder="Input Data">
                        @error('leave_group_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Leave Group Name</label>
                        <input type="text" name="leave_group_name" value="{{ $leave->leave_group_name }}" class="form-control" placeholder="Input Data">
                        @error('leave_group_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="mt-5">
                    <div class="form-check">
                        <input class="form-check-input" name="show_type" {{ ($leave->show_type ?? null) == 0 ? "checked" : "" }} type="radio" value="0" id="ckjoindatedetail" />
                        <label class="form-check-label" for="ckjoindatedetail">
                            Muncul Kuota Cuti Setiap tanggal Karyawan bergabung (By Join Date)
                        </label>
                    </div>
                </div>
                <div class="mt-5">
                    <div class="form-check">
                        <input class="form-check-input" name="show_type" {{ ($leave->show_type ?? null) == 1 ? "checked" : "" }} type="radio" value="1" id="ckcutoffdetail" />
                        <label class="form-check-label" for="ckcutoffdetail">
                            Muncul Kuota Cuti berdasarkan start leave period (Cut Off Date)
                        </label>
                    </div>
                </div>
                <div class="row mt-5 cut-off {{ $leave->show_type == 1 ? "" : "d-none" }}">
                    <div class="col-6">
                        <div class="fv-row">
                            <label for="" class="col-form-label">Start Leave Periode</label>
                            <input type="text" value="{{ $leave->start_leave_periode }}" data-format="dd/MM" name="start_leave_periode" id="start_l_edit" class="form-control tempusDominus">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fv-row">
                            <label for="" class="col-form-label">Grant Leave</label>
                            <select name="grant_leave_type" class="form-select" data-control="select2" data-placeholder="Select Type" data-dropdown-parent="#kt_drawer_detail">
                                <option value=""></option>
                                @foreach ($grant_types as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == ($leave->grant_leave_type ?? null) ? "SELECTED" : "" }}>{{ $item->grant_leave_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column mt-5">
                    <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6 px-5 pt-5 nav-fill">
                        <li class="nav-item">
                            <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_annual_leave_detail">
                                <span class="nav-text">Annual Leave</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_long_leave_detail">
                                <span class="nav-text">Long Leave</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_special_leave_detail">
                                <span class="nav-text">Special Leave</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent" style="padding: 0">
                        <div class="tab-pane fade show active" id="tab_annual_leave_detail" role="tabpanel">
                            <!--begin::Repeater-->
                            <div class="repeater">
                                <div class="d-flex justify-content-between mb-10">
                                    <h3 class="text-primary">Total Leaves</h3>
                                    <button type="button" data-repeater-create class="btn text-primary">
                                        <i class="fa fa-plus text-primary"></i>
                                        Tambah
                                    </button>
                                </div>
                                <!--begin::Form group-->
                                <div class="form-group">
                                    <div data-repeater-list="annual_total_leaves">
                                        @if (empty($leave->annual_total_leaves) || count($leave->annual_total_leaves) == 0 )
                                            <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                    <div class="fv-row me-3 flex-fill">
                                                        <label class="col-form-label">Range in Year</label>
                                                        <input type="number" name="range_from" class="form-control" placeholder="5">
                                                    </div>
                                                    <div class="fv-row me-3 flex-fill">
                                                        <label class="col-form-label">&nbsp;</label>
                                                        <input type="number" name="range_to" class="form-control" placeholder="5">
                                                    </div>
                                                    <div class="fv-row me-3">
                                                        <label class="col-form-label">Total Leaves</label>
                                                        <input type="text" name="total_leave" class="form-control" placeholder="20 days">
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label">&nbsp;</label>
                                                        <div>
                                                            <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                                <i class="fi fi-rr-trash text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach ($leave->annual_total_leaves as $item)
                                                <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                                    <div class="d-flex justify-content-between align-items-center w-100">
                                                        <div class="fv-row me-3 flex-fill">
                                                            <label class="col-form-label">Range in Year</label>
                                                            <input type="number" name="range_from" value="{{ $item['range_from'] }}" class="form-control" placeholder="5">
                                                        </div>
                                                        <div class="fv-row me-3 flex-fill">
                                                            <label class="col-form-label">&nbsp;</label>
                                                            <input type="number" name="range_to" value="{{ $item['range_to'] }}" class="form-control" placeholder="5">
                                                        </div>
                                                        <div class="fv-row me-3">
                                                            <label class="col-form-label">Total Leaves</label>
                                                            <input type="text" name="total_leave" value="{{ $item['total_leave'] }}" class="form-control" placeholder="20 days">
                                                        </div>
                                                        <div class="fv-row">
                                                            <label class="col-form-label">&nbsp;</label>
                                                            <div>
                                                                <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                                    <i class="fi fi-rr-trash text-danger"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <!--end::Form group-->
                            </div>
                            <!--end::Repeater-->
                            <div class="row mt-3">
                                <div class="fv-row col-4">
                                    <label class="col-form-label">Annual Leave Expired in month</label>
                                    <input type="number" class="form-control" value="{{ $leave->annual_leave_expired }}" name="annual_leave_expired" placeholder="Input total in month">
                                </div>
                                <div class="fv-row col-4">
                                    <label class="col-form-label">Over Right Leaves (Minus Leave)</label>
                                    <input type="number" class="form-control" value="{{ $leave->annual_over_right }}" name="annual_over_right" placeholder="Input total leave">
                                </div>
                                <div class="fv-row col-4">
                                    <label class="col-form-label">Expired Change Leave in Month</label>
                                    <input type="number" class="form-control" value="{{ $leave->annual_expired_change }}" name="annual_expired_change" placeholder="Input total in month">
                                </div>
                            </div>
                            <div class="mt-5">
                                <div class="form-check">
                                    <input class="form-check-input" name="annual_pay_end_periode" value="1" {{ ($leave->annual_pay_end_periode ?? null) == 1 ? "checked" : "" }} type="checkbox" id="ck{{ rand(1000,9999) }}" />
                                    <label class="form-check-label" for="ck{{ rand(1000,9999) }}">
                                        Ijinkan sisa cuti dibayar pada akhir periode cuti
                                    </label>
                                </div>
                            </div>
                            <div class="my-5">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" name="mass_leave" {{ $leave->mass_leave == 1 ? "checked" : "" }} type="checkbox" value="1" />
                                        Terapkan Mass Leave untuk Grup Cuti Ini
                                    </label>
                                </div>
                            </div>
                            <div class="{{ $leave->mass_leave == 1 ? "" : "d-none" }} row" data-mass-leave>
                                <div class="fv-row col-4">
                                    <label class="col-form-label">Total Mass Leave</label>
                                    <input type="text" name="mass_leave_total" class="form-control" value="{{ $leave->mass_leave_total }}" placeholder="Masukan Total Mass Leave">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_long_leave_detail" role="tabpanel">
                            <!--begin::Repeater-->
                            <div class="repeater">
                                <div class="d-flex justify-content-between mb-10">
                                    <h3 class="text-primary">Total Leaves</h3>
                                    <button type="button" data-repeater-create class="btn text-primary">
                                        <i class="fa fa-plus text-primary"></i>
                                        Tambah
                                    </button>
                                </div>
                                <!--begin::Form group-->
                                <div class="form-group">
                                    <div data-repeater-list="long_total_leaves">
                                        @if (empty($leave->long_total_leaves) || count($leave->long_total_leaves) == 0 )
                                            <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                    <div class="fv-row me-3 flex-fill">
                                                        <label class="col-form-label">Lama Bekerja</label>
                                                        <input type="text" name="lama_kerja" class="form-control" placeholder="5">
                                                    </div>
                                                    <div class="fv-row me-3 flex-fill">
                                                        <label class="col-form-label">Total Leaves</label>
                                                        <input type="text" name="total_leave" class="form-control" placeholder="20 Days">
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label">&nbsp;</label>
                                                        <div>
                                                            <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                                <i class="fi fi-rr-trash text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach ($leave->long_total_leaves as $item)
                                                <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                                    <div class="d-flex justify-content-between align-items-center w-100">
                                                        <div class="fv-row me-3 flex-fill">
                                                            <label class="col-form-label">Lama Bekerja</label>
                                                            <input type="text" name="lama_kerja" value="{{ $item['lama_kerja'] }}" class="form-control" placeholder="5">
                                                        </div>
                                                        <div class="fv-row me-3 flex-fill">
                                                            <label class="col-form-label">Total Leaves</label>
                                                            <input type="text" name="total_leave" value="{{ $item['total_leave'] }}" class="form-control" placeholder="20 Days">
                                                        </div>
                                                        <div class="fv-row">
                                                            <label class="col-form-label">&nbsp;</label>
                                                            <div>
                                                                <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                                    <i class="fi fi-rr-trash text-danger"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <!--end::Form group-->
                            </div>
                            <!--end::Repeater-->
                            <div class="fv-row">
                                <label class="col-form-label">Leave Expired in month</label>
                                <input type="number" class="form-control" value="{{ $leave->long_expired }}" name="long_expired" placeholder="12 month">
                            </div>
                            <div class="mt-5">
                                <div class="form-check">
                                    <input class="form-check-input" name="long_pay_end_periode" {{ ($leave->long_pay_end_periode ?? null) == 1 ? "checked" : "" }} type="checkbox" value="1" id="ck{{ rand(1000,9999) }}" />
                                    <label class="form-check-label" for="ck{{ rand(1000,9999) }}">
                                        Ijinkan sisa cuti dibayar pada akhir periode cuti
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_special_leave_detail" role="tabpanel">
                            <!--begin::Repeater-->
                            <div class="repeater">
                                <div class="d-flex justify-content-between mb-10">
                                    <h3 class="text-primary">Total Leaves</h3>
                                    <button type="button" data-repeater-create class="btn text-primary">
                                        <i class="fa fa-plus text-primary"></i>
                                        Tambah
                                    </button>
                                </div>
                                <!--begin::Form group-->
                                <div class="form-group">
                                    <div data-repeater-list="special_total_leaves">
                                        @if (empty($leave->special_total_leaves) || count($leave->special_total_leaves) == 0 )
                                            <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                    <div class="fv-row me-3 flex-fill">
                                                        <label class="col-form-label">Reason</label>
                                                        <select name="reason" class="form-select" data-kt-repeater="select2" data-placeholder="Cuti Melahirkan" data-dropdown-parent="#tab_special_leave_detail">
                                                            <option value=""></option>
                                                            @foreach ($reason_cond as $item)
                                                                <option value="{{ $item->id }}">{{ $item->leave_reason_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="fv-row me-3 w-50">
                                                        <label class="col-form-label">Total Leaves</label>
                                                        <input type="text" name="total_leaves" class="form-control" placeholder="20 Days">
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label">&nbsp;</label>
                                                        <div>
                                                            <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                                <i class="fi fi-rr-trash text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                    <div class="fv-row me-3 flex-fill">
                                                        <label class="col-form-label">Reason</label>
                                                        <select name="reason" class="form-select" data-kt-repeater="select2" data-placeholder="Cuti Melahirkan" data-dropdown-parent="#tab_special_leave_detail">
                                                            <option value=""></option>
                                                            @foreach ($reason_cond as $item)
                                                                <option value="{{ $item->id }}">{{ $item->reasonName->reason_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="fv-row me-3 w-50">
                                                        <label class="col-form-label">Total Leaves</label>
                                                        <input type="text" name="total_leaves" class="form-control" placeholder="20 Days">
                                                    </div>
                                                    <div class="fv-row">
                                                        <label class="col-form-label">&nbsp;</label>
                                                        <div>
                                                            <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                                <i class="fi fi-rr-trash text-danger"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @foreach ($leave->special_total_leaves as $item)
                                                <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                                    <div class="d-flex justify-content-between align-items-center w-100">
                                                        <div class="fv-row me-3 flex-fill">
                                                            <label class="col-form-label">Reason</label>
                                                            <select name="reason" class="form-select" data-kt-repeater="select2" data-placeholder="Cuti Melahirkan" data-dropdown-parent="#tab_special_leave_detail">
                                                                <option value=""></option>
                                                                @foreach ($reason_cond as $ct)
                                                                    <option value="{{ $ct->id }}" {{ $item['reason'] == $ct->id ? "SELECTED" : "" }}>{{ $ct->reasonName->reason_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="fv-row me-3 w-50">
                                                            <label class="col-form-label">Total Leaves</label>
                                                            <input type="text" name="total_leaves" value="{{ $item['total_leaves'] }}" class="form-control" placeholder="20 Days">
                                                        </div>
                                                        <div class="fv-row">
                                                            <label class="col-form-label">&nbsp;</label>
                                                            <div>
                                                                <button type="button" data-repeater-delete class="btn bg-white btn-icon">
                                                                    <i class="fi fi-rr-trash text-danger"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <!--end::Form group-->
                            </div>
                            <!--end::Repeater-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="id" value="{{$leave->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_detail_close">Cancel</button>
                <button type="submit" name="submit" value="apply" class="btn btn-primary me-3">Apply</button>
                <button type="submit" name="submit" value="edit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
