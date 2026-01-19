<div class="fv-row">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <input type="text" class="form-control input-currency form-control-solid border" name="{{ $form_name ?? "" }}" {{ $readonly ?? "readonly" }} placeholder="{{ $placeholder ?? "Input placeholder" }}">
</div>
@if (!isset($form_name))
<div class="fv-row">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <div class="position-relative">
        <input type="text" class="form-control input-currency" placeholder="{{ $placeholder ?? "Input placeholder" }}">
        <span class="mt-4 position-absolute ps-4 top-0" style="display: none">{{ $currency ?? "IDR" }}</span>
    </div>
</div>
@endif
