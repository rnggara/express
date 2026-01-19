<div class="d-flex flex-column">
    <div class="row">
        <div class="fv-row col-6">
            <label class="col-form-label">Employee ID</label>
            <input type="hidden" name="id" value="{{ $pr->id }}">
            <input type="text" name="emp_id" disabled class="form-control" value="{{ $pr->emp_id }}" id="">
        </div>
        <div class="fv-row col-6">
            <label class="col-form-label">Employee Name</label>
            <input type="text" name="emp_name" disabled class="form-control" value="{{ $pr->emp_name }}" id="">
        </div>
        <div class="fv-row col-6">
            <label class="col-form-label required">Offence</label>
            <select name="offence" class="form-select" data-control="select2" {{ $disabled ?? "" }} required data-placeholder="Select offence" data-dropdown-parent="#modal_offence">
                <option value=""></option>
                @foreach ($offence_reason as $item)
                    <option value="{{ $item->id }}" {{ ($offence->offence_reason ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="fv-row col-6">
            <label class="col-form-label required">Given By</label>
            <select name="given_by" class="form-select" data-control="select2" {{ $disabled ?? "" }} required data-placeholder="Select user" data-dropdown-parent="#modal_offence">
                <option value=""></option>
                @foreach ($approval as $item)
                    <option value="{{ $item->id }}" {{ ($offence->given_by ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="fv-row col-6">
            <label class="col-form-label required">Start Date</label>
            <input type="date" required {{ $disabled ?? "" }} name="start_date" value="{{ $offence->start_date ?? "" }}" class="form-control">
        </div>
        <div class="fv-row col-6">
            <label class="col-form-label">End Date</label>
            <input type="date" {{ $disabled ?? "" }} name="end_date" value="{{ $offence->end_date ?? "" }}" class="form-control">
        </div>
        <div class="fv-row col-12">
            <label class="col-form-label">Remarks</label>
            <textarea {{ $disabled ?? "" }} name="remarks" class="form-control" placeholder="Input remarks" cols="30" rows="5">{{ $offence->remarks ?? "" }}</textarea>
        </div>
        <div class="fv-row col-6">
            <label class="col-form-label">Reference Number</label>
            <input type="input" {{ $disabled ?? "" }} name="reference" value="{{ $offence->reference ?? "" }}" placeholder="Input reference number" class="form-control">
        </div>
        <div class="fv-row col-6">
            <label class="col-form-label">Upload File</label>
            <div class="d-flex align-items-center">
                <label class="btn btn-secondary btn-sm {{ $disabled ?? "" }}">
                    <input type="file" data-toggle='file' accept=".jpg,.png,.pdf" name="file" class="d-none">
                    Attachment
                    <i class="fi fi-rr-clip"></i>
                </label>
                @php
                    $fname = explode("_", $offence->lampiran ?? "");
                    $ff = count($fname) > 1 ? end($fname) : "";
                @endphp
                <span class="text-primary ms-5" data-file>
                    @if ($ff != "")
                        <a href="{{ asset($offence->lampiran) }}" download="download" target="_blank">{{ $ff }}</a>
                    @endif
                </span>
            </div>
            <span class="text-muted mt-3">File Format : JPG,PNG,PDF Max 25 mb</span>
        </div>
    </div>
    <div class="my-5"></div>
</div>