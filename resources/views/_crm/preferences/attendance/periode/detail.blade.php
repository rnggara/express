<div class='card-body'>
    <form action="{{route("crm.pref.attendance.periode.store")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Edit Periode</h3>
                    <span class="text-muted fs-base">Update data</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($periode->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Period Name</label>
                    <input type="text" name="pname" value="{{$periode->name}}" class="form-control" placeholder="Input Data">
                    @error('pname')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Start Date</label>
                        <input type="date" name="start_date" value="{{$periode->start_date}}" class="form-control" placeholder="Input Data">
                        @error('start_date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">End Date</label>
                        <input type="date" name="end_date" value="{{$periode->end_date}}" class="form-control" placeholder="Input Data">
                        @error('end_date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="id" value="{{$periode->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_periode_close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>