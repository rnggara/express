<div class="row">
    <div class="fv-row col-6">
        <label class="col-form-label">Employee Status</label>
        <select name="employee_status" class="form-select" data-control="select2" data-placeholder="Select Employee Status">
            <option value=""></option>
            @foreach ($emp_status as $item)
                <option value="{{ $item->id }}" data-end-date="{{ $item->end_date }}">{{ $item->label }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6 d-none">
        <label class="col-form-label">End Date</label>
        <input type="text" name="emp_status_end_date" class="form-control flatpicker">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Job Grade</label>
        <select name="job_grade" class="form-select" data-control="select2" data-placeholder="Select Job Grade">
            <option value=""></option>
            @foreach ($master['job_type'] as $id => $item)
                <option value="{{ $id }}">{{ $item }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Class</label>
        <select name="class" class="form-select" data-control="select2" data-placeholder="Select Class">
            <option value=""></option>
            @foreach ($class as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Hiring Location</label>
        <select name="location" class="form-select" data-control="select2" data-placeholder="Select Hiring Location">
            @foreach ($locations as $id => $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-12">
        <label class="col-form-label">Upload Document</label>
        <div class="d-flex align-items-center">
            <label class="btn btn-secondary btn-sm">
                <input type="file" data-toggle='file' name="file" class="d-none">
                Attachment
                <i class="fi fi-rr-clip"></i>
            </label>
            <span class="text-primary ms-5" data-file></span>
        </div>
        <span class="text-muted mt-3">File Format : <span data-file-format></span> Max 25 mb</span>
    </div>
</div>
