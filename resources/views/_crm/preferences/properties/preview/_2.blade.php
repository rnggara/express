<div class="fv-row">
    <label for="" class="col-form-label">{{ $name ?? "Property name" }}</label>
    <textarea class="form-control" name="{{ $form_name ?? "" }}" {{ $readonly ?? "readonly" }} placeholder="{{ $placeholder ?? "Input placeholder" }}">{{ $value ?? "" }}</textarea>
</div>
