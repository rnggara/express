<div class='card-body'>
    <form action="{{route("crm.pref.attendance.machine_type.store")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Collect Program</h3>
                    <span class="text-muted fs-base">Atur Detail Collect Program</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($program->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Collect Program ID</label>
                        <input type="text" name="program_id" value="{{ $program->program_id ?? "" }}" class="form-control" placeholder="Input Data">
                        @error('program_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">Method Name</label>
                        <input type="text" name="program_name" value="{{ $program->program_name ?? "" }}" class="form-control" placeholder="Input Data">
                        @error('program_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-4">
                        <label class="col-form-label fw-bold">File Type</label>
                        <select name="program_type" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_detail" data-placeholder="Select Type" id="">
                            <option value=""></option>
                            <option value="txt" {{ ($program->program_type ?? null) == "txt" ? "SELECTED" : "" }}>Text</option>
                            <option value="xlsx" {{ ($program->program_type ?? null) == "xlsx" ? "SELECTED" : "" }}>Excel</option>
                            <option value="csv" {{ ($program->program_type ?? null) == "csv" ? "SELECTED" : "" }}>csv</option>
                        </select>
                        @error('program_type')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="type" value="program">
                <input type="hidden" name="id" value="{{$program->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_detail_close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
