@csrf
<input type="hidden" name="type" value="{{ $_name }}">
@if (!empty($_detail))
    <input type="hidden" name="id" value="{{ $_detail->id }}">
@endif

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Website</label>
            <input type="text" placeholder="Masukkan Link" name="website" class="form-control"  value="{{ $_detail->website ?? "" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Behance</label>
            <input type="text" placeholder="Masukkan Link" name="behance" class="form-control"  value="{{ $_detail->behance ?? "" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Dribble</label>
            <input type="text" placeholder="Masukkan Link" name="dribble" class="form-control"  value="{{ $_detail->dribble ?? "" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Github</label>
            <input type="text" placeholder="Masukkan Link" name="github" class="form-control"  value="{{ $_detail->github ?? "" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Aplikasi Mobile</label>
            <input type="text" placeholder="Masukkan Link" name="mobile" class="form-control"  value="{{ $_detail->mobile ?? "" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Link lain</label>
            <input type="text" placeholder="Masukkan Link" name="others" class="form-control"  value="{{ $_detail->others ?? "" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label col-12">Upload Dokumen</label>
            <label for="file-upload" class="btn btn-secondary btn-sm">
                <i class="flaticon-attachment"></i>
                Attachments
            </label>
            <span class="text-muted">format : JPG, PNG, PDF</span>
            <input id="file-upload" style="display: none" name="attachments" accept=".jpg, .png, .pdf" type="file"/>
        </div>
    </div>
</div>
