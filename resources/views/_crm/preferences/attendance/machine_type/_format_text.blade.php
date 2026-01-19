<div class="row">
    <div class="fv-row col-4">
        <label class="col-form-label fw-bold">Key</label>
        <select name="key[type]" class="form-select" data-control="select2" data-dropdown-parent="#{{ $modalId }}" id="">
            <option value="emp_id" {{ ($detail->id_card['type'] ?? '') == "emp_id" ? "SELECTED" : "" }}>Employee ID</option>
            <option value="card_id" {{ ($detail->id_card['type'] ?? '') == "card_id" ? "SELECTED" : "" }}>Card ID</option>
        </select>
    </div>
    <div class="fv-row col-12 col-md-4">
        <label class="col-form-label fw-bold">Key Position</label>
        <input type="number" name="key[position]" value="{{ $detail->id_card['position'] ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
    <div class="fv-row col-12 col-md-4">
        <label class="col-form-label fw-bold">Key Width</label>
        <input type="number" name="key[width]" value="{{ $detail->id_card['width'] ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
</div>
<div class="row">
    <div class="fv-row col-4">
        <label class="col-form-label fw-bold">Absensi Code</label>
        <span></span>
    </div>
    <div class="fv-row col-12 col-md-4">
        <label class="col-form-label fw-bold">Code Position</label>
        <input type="number" name="code[position]" value="{{ $detail->absensi_code['position'] ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
    <div class="fv-row col-12 col-md-4">
        <label class="col-form-label fw-bold">Code Width</label>
        <input type="number" name="code[width]" value="{{ $detail->absensi_code['width'] ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
</div>
<div class="row">
    <div class="fv-row col-12 col-md-6">
        <label class="col-form-label fw-bold">In Code</label>
        <input type="number" name="in_code" value="{{ $detail->in_code ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
    <div class="fv-row col-12 col-md-6">
        <label class="col-form-label fw-bold">Out Code</label>
        <input type="number" name="out_code" value="{{ $detail->out_code ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
</div>
<div class="row">
    <div class="fv-row col-12 col-md-6">
        <label class="col-form-label fw-bold">Break Start Code</label>
        <input type="number" name="break_start_code" value="{{ $detail->break_start_code ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
    <div class="fv-row col-12 col-md-6">
        <label class="col-form-label fw-bold">Break Start Code</label>
        <input type="number" name="break_end_code" value="{{ $detail->break_end_code ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
</div>
<div class="d-flex flex-column">
    <div class="row">
        <div class="col-md-4">
            <label class="col-form-label fw-bold">Date Format</label>
        </div>
        <div class="col-md-4">
            <label class="col-form-label fw-bold">Position</label>
        </div>
        <div class="col-md-4">
            <label class="col-form-label fw-bold">Width</label>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="col-form-label fw-normal">YEAR</label>
        </div>
        <div class="col-md-4">
            <input type="number" name="date[year][position]" value="{{ $detail->date_format['year']['position'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
        <div class="col-md-4">
            <input type="number" name="date[year][width]" value="{{ $detail->date_format['year']['width'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="col-form-label fw-normal">MONTH</label>
        </div>
        <div class="col-md-4">
            <input type="number" name="date[month][position]" value="{{ $detail->date_format['month']['position'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
        <div class="col-md-4">
            <input type="number" name="date[month][width]" value="{{ $detail->date_format['month']['width'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="col-form-label fw-normal">DATE</label>
        </div>
        <div class="col-md-4">
            <input type="number" name="date[date][position]" value="{{ $detail->date_format['date']['position'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
        <div class="col-md-4">
            <input type="number" name="date[date][width]" value="{{ $detail->date_format['date']['width'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
    </div>
</div>
<div class="d-flex flex-column">
    <div class="row">
        <div class="col-md-4">
            <label class="col-form-label fw-bold">Time Format</label>
        </div>
        <div class="col-md-4">
            <label class="col-form-label fw-bold">Position</label>
        </div>
        <div class="col-md-4">
            <label class="col-form-label fw-bold">Width</label>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="col-form-label fw-normal">HOUR</label>
        </div>
        <div class="col-md-4">
            <input type="number" name="time[hour][position]" value="{{ $detail->time_format['hour']['position'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
        <div class="col-md-4">
            <input type="number" name="time[hour][width]" value="{{ $detail->time_format['hour']['width'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label class="col-form-label fw-normal">MINUTE</label>
        </div>
        <div class="col-md-4">
            <input type="number" name="time[minute][position]" value="{{ $detail->time_format['minute']['position'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
        <div class="col-md-4">
            <input type="number" name="time[minute][width]" value="{{ $detail->time_format['minute']['width'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
    </div>
</div>
<div class="fv-row">
    <label class="col-form-label">Start Row</label>
    <input type="number" name="start_row" class="form-control" min="1" value="{{ $detail->start_row ?? 1 }}" id="">
</div>
