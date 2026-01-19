<div class="fv-row mb-5">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <div class="d-flex align-items-center">
        <input type="text" class="form-control"  name="{{ $form_name[0] ?? "" }}" {{ $readonly ?? "readonly" }} placeholder="{{ $placeholder ?? "Input placeholder" }}">
        <div class="mx-3"></div>
        <input type="text" class="form-control"  name="{{ $form_name[1] ?? "" }}" {{ $readonly ?? "readonly" }} placeholder="{{ $placeholder2 ?? "Input placeholder" }}">
    </div>
</div>
<div class="d-flex justify-content-center">
    <div class="tempusDominus" data-view="clock" id="dt-picker"></div>
</div>
