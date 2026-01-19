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
                    <h3 class="me-2">Workgroup</h3>
                    <span class="text-muted fs-base">Atur Detail Workgroup</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($workgroup->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Workgroup ID</label>
                        <input type="text" name="workgroup_id" value="{{$workgroup->workgroup_id}}" readonly class="form-control" placeholder="Input Data">
                        @error('workgroup_id')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-6">
                        <label class="col-form-label fw-bold">Workgroup Name</label>
                        <input type="text" name="workgroup_name" value="{{$workgroup->workgroup_name}}" class="form-control" placeholder="Input Data">
                        @error('workgroup_name')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Start Date</label>
                        <input type="date" name='start_date' value="{{$workgroup->start_date}}" class="form-control" placeholder="Select Date">
                        @error('start_date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Type Patern</label>
                        <select name="patern" class="form-select" data-control="select2" data-dropdown-parent="#kt_drawer_detail" data-placeholder="Select Patern" id="">
                            <option value=""></option>
                            @foreach($paterns as $item)
                                <option value="{{$item->id}}" {{$workgroup->patern == $item->id ? "SELECTED" : ""}}>{{$item->patern_name}}</option>
                            @endforeach
                        </select>
                        @error('patern')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-12 col-md-4">
                        <label class="col-form-label fw-bold">Sequence</label>
                        <input type="number" name="sequence" value="{{$workgroup->sequence}}" class="form-control" value="0" placeholder="0">
                    </div>
                </div>
                <div class="fv-row mt-5">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" {{$workgroup->replace_holiday_flag == 1 ? "CHECKED" : ""}} value="1" name="replace_holiday_flag" id="ckm123" />
                        <label class="form-check-label" for="ckm123">
                            Centang untuk me-non-aktifkan tabel Libur
                        </label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="type" value="workgroup">
                <input type="hidden" name="id" value="{{$workgroup->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_detail_close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
