<div class="row">
    <div class="fv-row col-6">
        <label class="col-form-label">{{ $lbl[$type] ?? $tpLabel }} Lama</label>
        <input type="text" name="old" disabled class="form-control" id="" value="{{ $oldLabel }}">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">{{ $lbl[$type] ?? $tpLabel }} Baru</label>
        <select name="new" class="form-select" data-control="select2" data-placeholder="Pilih {{ $lbl[$type] ?? $tpLabel }}" data-dropdown-parent="#transfer-form-content">
            <option value=""></option>
            @foreach ($data as $item)
                <option value="{{ $item->id }}" {{ ($_edit['new']['val'] ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Tanggal Mulai*</label>
        <input type="date" class="form-control" name="start_date" value="{{ $_edit['start_date']['val'] ?? "" }}" required id="">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label">Tanggal Berakhir</label>
        <input type="date" class="form-control" name="end_date" id="" value="{{ $_edit['end_date']['val'] ?? "" }}">
    </div>
    <div class="fv-row col-12">
        <label class="col-form-label">Alasan</label>
        <textarea name="reason" class="form-control" id="" cols="30" rows="5">{{ $_edit['reason']['val'] ?? "" }}</textarea>
    </div>
    @if ($needApproval == 1)
        <div class="fv-row col-12">
            <label class="col-form-label">Harus disetujui oleh*</label>
            <select name="approved_by" class="form-select" required data-control="select2" data-placeholder="Pilih Pengguna" data-dropdown-parent="#transfer-form-content">
                <option value=""></option>
                @foreach ($approval as $item)
                    <option value="{{ $item->id }}" {{ ($_edit['approved_by']['val'] ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
    </div>
    @else
        <input type="hidden" name="bypass_approve" value="1">
    @endif
    <div class="fv-row col-12">
        <label class="col-form-label">Nomor Referensi</label>
        <input type="text" class="form-control" name="reference" id="" placeholder="Masukan Nomor Referensi" value="{{ $_edit['reference']['val'] ?? "" }}">
    </div>
</div>
