<div class='card-body'>
    <form action="{{route("crm.pref.attendance.overtime.store")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Overtime Group</h3>
                    <span class="text-muted fs-base">Atur Detail Overtime Group</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($ovt->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Overtime Group ID</label>
                        <input type="text" class="form-control" name="group_id" value="{{ $ovt->group_id }}" placeholder="OTG004">
                        @error('group_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Day Code</label>
                        <select name="day_code" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_detail" data-placeholder="Select Day Code" id="">
                            <option value=""></option>
                            @foreach ($day_codes as $item)
                                <option value="{{ $item->id }}" {{ $ovt->day_code == $item->id ? "SELECTED" : "" }}>{{ $item->day_code." - ".$item->day_name }}</option>
                            @endforeach
                        </select>
                        @error('day_code')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Overtime Group Name</label>
                    <input type="text" class="form-control" name="group_name" value="{{ $ovt->group_name }}" placeholder="Input Data">
                    @error('group_name')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mt-5">
                    <div class="form-check">
                        <input class="form-check-input" onclick="show_calculation_detail()" {{ $ovt->index_kemenaker == 1 ? "checked" : "" }} name="index_kemenaker" type="checkbox" value="1" id="ck{{ rand(1000,9999) }}" />
                        <label class="form-check-label" for="ck{{ rand(1000,9999) }}">
                            Ikuti Index Pengali yang ditentukan oleh kemenaker
                        </label>
                    </div>
                </div>
                <!--begin::Repeater-->
                <div id="index_kemenaker_form_detail" class="d-none mt-10">
                    <div class="d-flex justify-content-between mb-10 align-items-center">
                        <h3 class="text-primary">Calculation</h3>
                        <div class="flex-fill border mx-5 separator"></div>
                        <button type="button" data-repeater-create class="btn text-primary px-0">
                            <i class="fa fa-plus text-primary"></i>
                            Tambah
                        </button>
                    </div>
                    <!--begin::Form group-->
                    <div class="form-group">
                        <div data-repeater-list="calculation">
                            @if ($ovt->calc->count() == 0)
                                <div data-repeater-item class="d-flex justify-content-evenly align-items-center">
                                    <div class="fv-row me-3">
                                        <label class="col-form-label">Index Master</label>
                                        <select name="index" class="form-select" data-kt-repeater="select2" data-placeholder="Select Index" data-dropdown-parent="#kt_drawer_detail">
                                            @foreach ($indexes as $item)
                                                <option value="{{ $item->id }}">{{ $item->ovt_index }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="fv-row me-3">
                                        <label class="col-form-label">Range</label>
                                        <input type="time" name="range_start" class="form-control">
                                    </div>
                                    <div class="fv-row me-3">
                                        <label class="col-form-label">&nbsp;</label>
                                        <input type="time" name="range_end" class="form-control">
                                    </div>
                                    <div class="fv-row">
                                        <label class="col-form-label w-100">&nbsp;</label>
                                        <button type="button" data-repeater-delete class="btn bg-white">
                                            <i class="fi fi-rr-trash text-danger"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                @foreach ($ovt->calc as $cc)
                                    <div data-repeater-item class="d-flex justify-content-between align-items-center">
                                        <div class="fv-row me-3">
                                            <label class="col-form-label">Index Master</label>
                                            <select name="index" class="form-select" data-kt-repeater="select2" data-placeholder="Select Index" data-dropdown-parent="#kt_drawer_detail">
                                                @foreach ($indexes as $item)
                                                    <option value="{{ $item->id }}" {{ $item->id == $cc->ovt_index_id ? "SELECTED" : "" }}>{{ $item->ovt_index }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="fv-row me-3">
                                            <label class="col-form-label">Range</label>
                                            <input type="time" name="range_start" value="{{ $cc->range_from }}" class="form-control">
                                        </div>
                                        <div class="fv-row me-3">
                                            <label class="col-form-label">&nbsp;</label>
                                            <input type="time" name="range_end" value="{{ $cc->range_to }}" class="form-control">
                                        </div>
                                        <div class="fv-row">
                                            <label class="col-form-label w-100">&nbsp;</label>
                                            <button type="button" data-repeater-delete class="btn bg-white">
                                                <i class="fi fi-rr-trash text-danger"></i>
                                            </button>
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
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="type" value="group">
                <input type="hidden" name="id" value="{{$ovt->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_detail_close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
