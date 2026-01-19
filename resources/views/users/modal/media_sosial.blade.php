@csrf
<input type="hidden" name="type" value="{{ $_name }}">
@if (!empty($_detail))
    <input type="hidden" name="id" value="{{ $_detail->id }}">
@endif

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Linkedin</label>
            <input type="text" placeholder="Masukkan Link" name="linkedin" class="form-control"  value="{{ $_detail->linkedin ?? "-" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Facebook</label>
            <input type="text" placeholder="Masukkan Link" name="facebook" class="form-control"  value="{{ $_detail->facebook ?? "-" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Instagram</label>
            <input type="text" placeholder="Masukkan Link" name="instagram" class="form-control"  value="{{ $_detail->instagram ?? "-" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Twitter</label>
            <input type="text" placeholder="Masukkan Link" name="twitter" class="form-control"  value="{{ $_detail->twitter ?? "-" }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="" class="col-form-label">Tiktok</label>
            <input type="text" placeholder="Masukkan Link" name="tiktok" class="form-control"  value="{{ $_detail->tiktok ?? "-" }}">
        </div>
    </div>
</div>
