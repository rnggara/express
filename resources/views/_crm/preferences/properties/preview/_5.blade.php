<div class="fv-row" id="sel-property-prev">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    @if(isset($additional['option']))
    @foreach ($additional['option'] as $i => $item)
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" {{ isset($value) && in_array($item, $value == "" ? [] : $value) ? "checked" : "" }} name="{{ $form_name ?? "check_property[]" }}" {{ $readonly ?? "readonly" }} value="{{ $item ?? "Option ".($i+1) }}" id="flexCheckDefault{{ $i }}" />
            <label class="form-check-label" for="flexCheckDefault{{ $i }}">
                {{ $item ?? "Option ".($i+1) }}
            </label>
        </div>
    @endforeach
    @endif
</div>
