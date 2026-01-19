<div class="fv-row">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <input type="text" name="{{ $form_name ?? "" }}" {{ $readonly ?? "readonly" }} value="{{ $value ?? "" }}" class="form-control" placeholder="{{ $placeholder ?? "Input placeholder" }}">
</div>
