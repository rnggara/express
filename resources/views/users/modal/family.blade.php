@csrf
<input type="hidden" name="id" value="{{ $_detail->id ?? null }}">
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="nama" class="required col-form-label">Nama</label>
            <input type="text" name="nama" class="form-control" required id="nama" value="{{ $_detail->name ?? "" }}" placeholder="Masukan nama">
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="hubungan" class="required col-form-label">Hubungan</label>
            <select name="hubungan" id="hubungan" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Pilih Hubungan" required>
                <option value=""></option>
                <option {{ !empty($_detail) && $_detail->hubungan == "Orang Tua" ? "SELECTED" : "" }} value="Orang Tua">Orang Tua</option>
                <option {{ !empty($_detail) && $_detail->hubungan == "Saudara" ? "SELECTED" : "" }} value="Saudara">Saudara</option>
                <option {{ !empty($_detail) && $_detail->hubungan == "Suami" ? "SELECTED" : "" }} value="Suami">Suami</option>
                <option {{ !empty($_detail) && $_detail->hubungan == "Istri" ? "SELECTED" : "" }} value="Istri">Istri</option>
                <option {{ !empty($_detail) && $_detail->hubungan == "Anak" ? "SELECTED" : "" }} value="Anak">Anak</option>
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="nama" class="required col-form-label">Status Pernikahan</label>
            <select name="marital_id" id="marital_id" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Pilih Hubungan" required>
                <option value=""></option>
                @foreach ($marital_status as $item)
                    <option value="{{ $item->id }}" {{ !empty($_detail) && $_detail->marital_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="jenis_kelamin" class="required col-form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Pilih Jenis Kelamin" required>
                <option value=""></option>
                @foreach ($gender as $item)
                    <option value="{{ $item->id }}" {{ !empty($_detail) && $_detail->jenis_kelamin == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="jenis_kelamin" class="required col-form-label">Tanggal Lahir</label>
            <div class="input-group" id="inputDatesModal" data-td-target-input="nearest" data-td-target-toggle="nearest">
                <input id="inputDatesModal_input" readonly name="tgl_lahir" value="{{ !empty($_detail) ? date("d-m-Y", strtotime($_detail->tgl_lahir)) : "" }}" type="text" class="form-control tempusDominus" data-td-target="#inputDatesModal"/>
                <span class="input-group-text" data-td-target="#inputDatesModal" data-td-toggle="datetimepicker">
                    <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="no_telp" class="required col-form-label">Nomor Telepon</label>
            <input type="text" name="no_telp" class="form-control no-telp" required id="no_telp" value="{{ $_detail->no_telp ?? "" }}" placeholder="Masukan Nomor Telepon">
        </div>
    </div>
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label col-12">Upload Dokumen</label>
            <label for="file-upload" class="btn btn-secondary btn-sm">
                <i class="flaticon-attachment"></i>
                Lampiran
            </label>
            <span class="text-muted">format : JPG, PNG, PDF</span>
            <input id="file-upload" style="display: none" name="attachments" accept=".jpg, .png, .pdf" type="file"/>
        </div>
    </div>
</div>
