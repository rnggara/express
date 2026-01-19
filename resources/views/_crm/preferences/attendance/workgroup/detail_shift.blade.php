<div class='card-body'>
    <form action="{{route("crm.pref.attendance.workgroup.store")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Shift</h3>
                    <span class="text-muted fs-base">Atur Detail Shift</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($shift->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Shift ID</label>
                        <input type="text" name="shift_id" value="{{ $shift->shift_id }}" class="form-control" placeholder="Input Data">
                        @error('shift_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Shift Name</label>
                        <input type="text" name="shift_name" value="{{ $shift->shift_name }}" class="form-control" placeholder="Input Data">
                        @error('shift_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6" id="sel-day-code">
                        <label class="col-form-label fw-bold">Day Code</label>
                        <select name="day_code" class="form-select" data-control="select2" data-dropdown-parent="#sel-day-code" data-placeholder="Select Day Code" id="">
                            <option value=""></option>
                            @foreach ($day_codes as $item)
                                <option value="{{ $item->id }}" {{ $item->id == ($shift->day_code ?? null) ? "SELECTED" : "" }}>{{ $item->day_name }}</option>
                            @endforeach
                        </select>
                        @error('day_code')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6" id="sel-shift-color">
                        <label class="col-form-label fw-bold">Shift Color</label>
                        <div class="position-relative" data-control="colorpicker">
                            <input type="text" name="shift_color" value="{{$shift->shift_color}}" data-colorpicker-input class="form-control" placeholder="Select Shift Color" readonly>
                            <div class="position-absolute top-0 w-100">
                                <div class="d-flex align-items-center">
                                    <span class="flex-fill h-20px ms-4 rounded-4" style="background-color: {{$shift->shift_color}}" data-colorpicker-label></span>
                                    <div>
                                        <button type="button" class="btn btn-icon" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                        <!--begin::Menu sub-->
                                        <div class="menu-sub menu-sub-dropdown p-3 w-200px" data-kt-menu="true">
                                            <div class="row row-cols-3 pt-5">
                                                @foreach ($color_palletes as $item)
                                                    <div class="col mb-5">
                                                        <div class="symbol symbol-circle symbol-30px cursor-pointer" data-colorpicker-toggle data-color="{{ $item }}">
                                                            <div class="symbol-label" style="background-color: {{ $item }}">&nbsp;</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('shift_color')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Schedule In</label>
                        <input type="time" name="time_in" value="{{$shift->schedule_in}}" class="form-control" placeholder="Input Data">
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Schedule Out</label>
                        <input type="time" name="time_out" value="{{$shift->schedule_out}}" class="form-control" placeholder="Input Data">
                    </div>
                </div>
                <div class="fv-row mt-5">
                    <div class="d-flex flex-column mb-5 repeater">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" {{$shift->add_break_shift == 1 ? "CHECKED" : ""}} name="add_break_shift" onclick="add_bs(this)" type="checkbox" value="1" id="ckm1" />
                                <label class="form-check-label" for="ckm1">
                                    Tambahkan Break Shift
                                </label>
                            </div>
                            <button type="button" class="btn text-primary {{$shift->add_break_shift == 1 ? "" : "d-none"}}  break-shift" data-repeater-create>
                                <i class="fa fa-plus text-primary"></i>
                                Tambah
                            </button>
                        </div>
                        <div class="form-group {{$shift->add_break_shift == 1 ? "" : "d-none"}} break-shift">
                            <div data-repeater-list="break_shift">
                                @if(empty($shift->break_shifts) && count($shift->break_shifts ?? []) == 0)
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
                                @else
                                    @foreach($shift->break_shifts as $bs)
                                        <div class="row" data-repeater-item>
                                            <div class="col-5">
                                                <div class="fv-row">
                                                    <label for="" class="col-form-label">Break <span class="break-num"></span> Start</label>
                                                    <input type="time" name="start" value="{{$bs['start']}}" class="form-control" id="">
                                                </div>
                                            </div>
                                            <div class="col-5">
                                                <div class="fv-row">
                                                    <label for="" class="col-form-label">Break <span class="break-num"></span> End</label>
                                                    <input type="time" name="end" value="{{$bs['end']}}" class="form-control" id="">
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
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" name="automatic_overtime" {{$shift->automatic_overtime == 1 ? "CHECKED" : ""}} type="checkbox" onclick="add_automatic_overtime(this)" value="1" id="ckm2" />
                        <label class="form-check-label" for="ckm2">
                            Tambahkan Automatic Overtime
                        </label>
                    </div>
                    <div class="{{$shift->automatic_overtime == 1 ? "" : "d-none"}} automatic-overtime">
                        <div class="row">
                            <div class="col-6">
                                <div class="fv-row">
                                    <label for="" class="col-form-label">Automatic Overtime In</label>
                                    <input type="time" name="overtime_in" value="{{$shift->overtime_in}}" class="form-control" id="">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="fv-row">
                                    <label for="" class="col-form-label">Automatic Overtime Out</label>
                                    <input type="time" name="overtime_out" value="{{$shift->overtime_out}}" class="form-control" id="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <div class="d-flex flex-column gap-3">
                    @if ($shift->is_default)
                    <span class="text-danger">Data default tidak bisa diubah</span>
                    @endif
                    <div class="d-flex justify-content-end">
                        <input type="hidden" name="type" value="shift">
                        <input type="hidden" name="id" value="{{$shift->id}}">
                        <button type="button" class="btn text-primary" id="kt_drawer_detail_close">Cancel</button>
                        <button type="submit" {{ $shift->is_default ? "disabled" : "" }} class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
