<div class='card-body'>
    <form action="{{route("crm.pref.attendance.holiday_table.store")}}" method="POST">
        <div class="d-flex flex-column">
            <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-rr-alarm-clock text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Edit Holiday</h3>
                    <span class="text-muted fs-base">Update data</span>
                </div>
            </div>
            <div class="p-10 rounded bg-secondary-crm mb-10">
                <div class="d-flex justify-content-between py-3 px-5 bg-white rounded border align-items-center">
                    <label class="col-form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" name="status" value="1" {{ ($holiday->status ?? null) == 1 ? "checked" : "" }}>
                    </div>
                </div>
                <div class="fv-row">
                    <label class="col-form-label fw-bold">Name Holiday</label>
                    <input type="text" class="form-control" name="name" value="{{$holiday->name ?? ""}}" placeholder="Input Data">
                    @error('name')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Date</label>
                        <input type="date" class="form-control" value="{{$holiday->holiday_date ?? ""}}" name="holiday_date" placeholder="Input Data">
                        @error('holiday_date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Holiday Category</label>
                        <select name="category" class="form-select" data-control="select2" data-dropdown-parent="#modal_add_holiday" data-placeholder="Select Holiday Category" id="">
                            <option value=""></option>
                            @foreach($categories as $item)
                                <option value="{{$item->id}}" {{ ($holiday->category_id ?? null) == $item->id ? "SELECTED" : "" }} >{{$item->category_name}}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="fv-row mt-5">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" {{ ($holiday->category_id ?? null) == 1 ? "CHECKED" : "" }} name="send_email" value="1" id="ckm1" />
                        <label class="form-check-label" for="ckm1">
                            Kirim Notifikasi Email
                        </label>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                @csrf
                <input type="hidden" name="id" value="{{$holiday->id}}">
                <button type="button" class="btn text-primary" id="kt_drawer_holiday_close">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>