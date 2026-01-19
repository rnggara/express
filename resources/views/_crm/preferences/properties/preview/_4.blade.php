<div class="fv-row" id="sel-property-prev">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    @foreach ($additional['option'] as $i => $item)
        <div class="form-check mb-3">
            <input class="form-check-input" type="radio" {{ isset($value) && $value == $item ? "checked" : "" }} name="{{ $form_name ?? "radio_property" }}" {{ $readonly ?? "readonly" }} value="{{ $item ?? "Option ".($i+1) }}" id="flexCheckDefault{{ $i }}" />
            <label class="form-check-label" for="flexCheckDefault{{ $i }}">
                {{ $item ?? "Option ".($i+1) }}
            </label>
        </div>
    @endforeach
</div>
