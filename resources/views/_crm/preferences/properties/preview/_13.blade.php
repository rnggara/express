<div class="fv-row">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <input type="number" class="form-control form-control-solid border" name="{{ $form_name ?? "" }}" {{ $readonly ?? "readonly" }} placeholder="{{ $placeholder ?? "Input placeholder" }}">
</div>
<div class="fv-row">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <input type="number" class="form-control" placeholder="{{ $placeholder ?? "Input placeholder" }}">
</div>
