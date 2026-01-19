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
            <div class="form-group">
                <label for="organisasi" class="required col-form-label">Organisasi Penerbit</label>
                <input type="text" name="organisasi" class="form-control" required id="organisasi" value="{{ $_detail->name ?? "" }}" placeholder="Masukan Organisasi Penerbit">
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="tgl_penerbitan" class="required col-form-label">Tanggal Penerbitan</label>
            <div class="input-group tempusDominus" id="dateTerbit" data-td-target-input="nearest" data-td-target-toggle="nearest">
                <input id="dateTerbit_input" placeholder="Pilih Tanggal" readonly name="tgl_penerbitan" required value="{{ !empty($_detail) ? date("d-m-Y", strtotime($_detail->tgl_penerbitan)) : "" }}" type="text" class="form-control" data-td-target="#dateTerbit"/>
                <span class="input-group-text" data-td-target="#dateTerbit" data-td-toggle="datetimepicker">
                    <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="tgl_kadaluarsa" class="col-form-label">Tanggal Kadaluwarsa</label>
            <div class="input-group tempusDominus" id="dateKadaluwarsa" data-td-target-input="nearest" data-td-target-toggle="nearest">
                <input id="dateKadaluwarsa_input" placeholder="Pilih Tanggal" readonly name="tgl_kadaluarsa" value="{{ !empty($_detail) ? date("d-m-Y", strtotime($_detail->tgl_kadaluarsa)) : "" }}" type="text" class="form-control" data-td-target="#dateKadaluwarsa"/>
                <span class="input-group-text" data-td-target="#dateKadaluwarsa" data-td-toggle="datetimepicker">
                    <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="no_lisensi" class="required col-form-label">Nomor Lisensi</label>
            <input type="text" name="no_lisensi" class="form-control" required id="no_lisensi" value="{{ $_detail->no_lisensi ?? "" }}" placeholder="Masukan Nomor Lisensi">
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="url" class="col-form-label">URL</label>
            <input type="text" name="url" class="form-control" id="url" value="{{ $_detail->url ?? "" }}" placeholder="Masukan Link lisensi">
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
