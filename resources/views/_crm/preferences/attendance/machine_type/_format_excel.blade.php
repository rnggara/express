<div class="row">
    <div class="fv-row col-6">
        <label class="col-form-label fw-bold">Key</label>
        <select name="key[type]" class="form-select" data-control="select2" data-dropdown-parent="#{{ $modalId }}" id="">
            <option value="emp_id" {{ ($detail->id_card['type'] ?? '') == "emp_id" ? "SELECTED" : "" }}>Employee ID</option>
            <option value="card_id" {{ ($detail->id_card['type'] ?? '') == "card_id" ? "SELECTED" : "" }}>Card ID</option>
        </select>
    </div>
    <div class="fv-row col-12 col-md-6">
        <label class="col-form-label fw-bold">Key Column</label>
        <input type="text" name="key[column]" value="{{ $detail->id_card['column'] ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
</div>
<div class="row">
    <div class="fv-row col-12">
        <label class="col-form-label fw-bold">Absensi Code Column</label>
        <input type="text" name="code[column]" value="{{ $detail->absensi_code['column'] ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
</div>
<div class="row">
    <div class="fv-row col-12 col-md-6">
        <label class="col-form-label fw-bold">In Code</label>
        <input type="text" name="in_code" value="{{ $detail->in_code ?? "" }}" class="form-control" placeholder="Input Data">
    </div>
    <div class="fv-row col-12 col-md-6">
        <label class="col-form-label fw-bold">Out Code</label>
        <input type="text" name="out_code" value="{{ $detail->out_code ?? "" }}" class="form-control" placeholder="Input Data">
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
        <div class="fv-row col-12">
            <label class="col-form-label fw-bold">Date Column</label>
            <input type="text" name="date[column]" value="{{ $detail->date_format['column'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
    </div>
    <div class="row">
        <div class="fv-row col-12">
            <label class="col-form-label fw-bold">Time Column</label>
            <input type="text" name="time[column]" value="{{ $detail->time_format['column'] ?? "" }}" class="form-control" placeholder="Input Data">
        </div>
    </div>
</div>
<div class="fv-row">
    <label class="col-form-label">Start Row</label>
    <input type="number" name="start_row" class="form-control" min="1" value="{{ $detail->start_row ?? 1 }}" id="">
</div>
