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
                    <h3 class="me-2">Reason Name</h3>
                    <span class="text-muted fs-base">Atur Detail Reason Name</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center mb-5">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($reason->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="row row-gap-5">
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Reason Color</label>
                        <div class="position-relative" data-control="colorpicker">
                            <input type="text" name="reason_color" data-colorpicker-input value="{{$reason->color}}" class="form-control" placeholder="Select Shift Color" readonly>
                            <div class="position-absolute top-0 w-100">
                                <div class="d-flex align-items-center">
                                    <span class="flex-fill h-20px ms-4 rounded-4" style="background-color: {{$reason->color}}" data-colorpicker-label></span>
                                    <div>
                                        <button type="button" class="btn btn-icon" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <i class="fa fa-chevron-down"></i>
                                        </button>
                                        <!--begin::Menu sub-->
                                        <div class="menu-sub menu-sub-dropdown p-3 w-200px" data-kt-menu="true">
                                            <div class="row row-cols-3 pt-5">
                                                @foreach (\Config::get("constants.COLOR_PALLET") ?? [] as $item)
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
                        @error('reason_color')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Reason ID</label>
                        <input type="text" name="reason_id" required value="{{$reason->reason_id}}" class="form-control" placeholder="Input Data">
                        @error('reason_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Reason Name</label>
                        <input type="text" name="reason_name" required value="{{$reason->reason_name}}" class="form-control" placeholder="Input Data">
                        @error('reason_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox"  name="show_dashboard" {{ ($reason->status ?? null) == 1 && ($reason->show_dashboard ?? null) == 1 ? "checked" : "" }} value="1" />
                                Tampilkan Reason pada Dashboard Preview Today
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <div class="d-flex flex-column gap-3">
                    @if ($reason->is_default)
                    <span class="text-danger">Data default tidak bisa diubah</span>
                    @endif
                    <div class="d-flex justify-content-end">
                        <input type="hidden" name="type" value="name">
                        <input type="hidden" name="id" value="{{$reason->id}}">
                        <button type="button" class="btn text-primary" id="kt_drawer_periode_close">Cancel</button>
                        <button type="submit" {{ $reason->is_default ? "disabled" : "" }} class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
