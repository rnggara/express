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
                    <h3 class="me-2">Overtime Index</h3>
                    <span class="text-muted fs-base">Atur Detail Overtime Index</span>
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
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Overtime Index ID</label>
                        <input type="text" class="form-control" name="ovt_id" value="{{ $ovt->ovt_id }}" placeholder="Input Data">
                        @error('ovt_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Index</label>
                        <input type="text" class="form-control" name="ovt_index" value="{{ $ovt->ovt_index }}" placeholder="Input Data">
                        @error('ovt_index')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="type" value="index">
                <input type="hidden" name="id" value="{{$ovt->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_detail_close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
