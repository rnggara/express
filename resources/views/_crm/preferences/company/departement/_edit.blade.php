<form action="{{ route("crm.pref.company.departement.post", base64_encode($company->id)) }}" method="post">
    @component('layouts._crm_modal', [
        "modalId" => "modal_edit",
        "modalSize" => "modal-lg"
    ])
        @slot('modalTitle')
            <div class="d-flex flex-column">
                <span class="fs-1 fw-bold">Edit Department</span>
                <span class="fs-base fw-normal mt-2">&nbsp;&nbsp; </span>
            </div>
        @endslot
        @slot('modalContent')
            <div class="fv-row">
                <label class="col-form-label required">Record ID</label>
                <input type="text" name="record_id" value="{{ $item->record_id }}" placeholder="Input Record ID" class="form-control">
                @error('record_id')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="fv-row">
                <label class="col-form-label required">Department</label>
                <input type="text" name="departement" value="{{ $item->name }}" placeholder="Example: Marketing, Sales, Developer, and others" class="form-control">
                @error('departement')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="fv-row">
                <label class="col-form-label">Parent Department</label>
                <select name="parent_id" data-dropdown-parent="#modal_edit" class="form-select" data-control="select2" data-placeholder="Select Department">
                    <option value=""></option>
                    @foreach ($data->where("id", "!=", $item->id) as $val)
                        <option value="{{ $val->id }}" {{ (old("parent_id") ?? $item->parent_id) == $val->id ? "SELECTED" : "" }}>{{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fv-row">
                <label class="col-form-label">Person In Charge</label>
                <select name="pic" data-dropdown-parent="#modal_edit" class="form-select" data-control="select2" data-placeholder="Select User">
                    <option value=""></option>
                    @foreach ($pic as $val)
                        <option value="{{ $val->id }}" {{ (old("pic") ?? $item->pic) == $val->id ? "SELECTED" : "" }}>{{ $val->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fv-row">
                <label class="col-form-label required">Sub Level Organisasi</label>
                <input type="number" name="sub_level" value="{{ $item->sub_level }}" placeholder="Input Sub Level Organisasi" class="form-control">
                @error('sub_level')
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <input type="hidden" name="id_detail" value="{{ $item->id }}">
            <button type="button" class="btn" data-bs-dismiss="modal">Back</button>
            <button type="submit" name="submit" value="store" class="btn btn-primary">Submit</button>
        @endslot
    @endcomponent
</form>