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
                    <h3 class="me-2">Machine Name</h3>
                    <span class="text-muted fs-base">Atur Detail Machine Name</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($machine_name->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Machine ID</label>
                        <input type="text" name="machine_id" value="{{ $machine_name->machine_id ?? "-" }}" class="form-control" placeholder="013232">
                        @error('machine_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Machine Name</label>
                        <input type="text" name="machine_name" value="{{ $machine_name->machine_name ?? "-" }}" class="form-control" placeholder="Face Attendace CX">
                        @error('machine_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Collect Program</label>
                        <select name="program_id" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_detail" data-placeholder="Select Type" id="">
                            <option value=""></option>
                            @foreach ($programs as $item)
                                <option value="{{ $item->id }}" {{ $item->id == ($machine_name->program_id ?? null) ? "SELECTED" : "" }}>{{ $item->program_name }}</option>
                            @endforeach
                        </select>
                        @error('program_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div data-content-program></div>
                {{-- <div class="row">
                    <div class="fv-row col-12 col-md-3">
                        <label class="col-form-label fw-bold">In Code</label>
                        <input type="number" name="in_code" value="{{ $machine_name->in_code }}" class="form-control" placeholder="Input Data">
                    </div>
                    <div class="fv-row col-12 col-md-3">
                        <label class="col-form-label fw-bold">Out Code</label>
                        <input type="number" name="out_code" value="{{ $machine_name->out_code }}" class="form-control" placeholder="Input Data">
                    </div>
                </div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Absensi Code</label>
                    <input type="text" name="absensi_code" value="{{ $machine_name->absensi_code ?? "-" }}" class="form-control" placeholder="Input Data">
                </div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Date Format</label>
                    <input type="text" name="date_format" value="{{ $machine_name->date_format ?? "-" }}" class="form-control" placeholder="Input Data">
                </div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Time Format</label>
                    <input type="text" name="time_format" value="{{ $machine_name->time_format ?? "-" }}" class="form-control" placeholder="Input Data">
                </div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">ID Card</label>
                    <input type="text" name="id_card" value="{{ $machine_name->id_card ?? "-" }}" class="form-control" placeholder="Input Data">
                </div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Error Pathfile</label>
                    <input type="text" name="error_pathfile" value="{{ $machine_name->error_pathfile ?? "-" }}" class="form-control" placeholder="Input Data">
                </div> --}}
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="type" value="name">
                <input type="hidden" name="id" value="{{$machine_name->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_detail_close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
