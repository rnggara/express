<div class="fv-row mb-5">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <input type="text" class="form-control" name="{{ $form_name ?? "" }}" {{ $readonly ?? "readonly" }} placeholder="{{ $placeholder ?? "Input placeholder" }}">
</div>
<div class="d-flex justify-content-center">
    <div class="tempusDominus" data-view="clock" id="dt-picker"></div>
</div>
