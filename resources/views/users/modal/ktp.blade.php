@csrf
<input type="hidden" name="id" value="{{ $_detail->id ?? null }}">
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="no_kartu" class="required col-form-label">Nomor KTP</label>
            <input type="text" name="no_kartu" class="form-control inputmask" data-format="9999-9999-9999-9999" required id="no_kartu" value="{{ $_detail->no_kartu ?? "" }}" placeholder="Masukan Nomor KTP">
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
