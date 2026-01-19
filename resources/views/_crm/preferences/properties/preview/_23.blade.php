<div class="fv-row">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <input type="text" class="form-control" name="{{ $form_name ?? "" }}" {{ $readonly ?? "readonly" }} placeholder="{{ $placeholder ?? "Input placeholder" }}">
</div>
