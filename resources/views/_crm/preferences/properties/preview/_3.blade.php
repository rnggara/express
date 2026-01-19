<div class="fv-row" id="sel-property-prev{{date("His")}}">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    <select name="{{ $form_name ?? "" }}" {{ $readonly ?? "" }} class="form-select" data-control="select2" data-placeholder="{{ $placeholder ?? "Input placeholder" }}" data-dropdown-parent="#sel-property-prev{{date("His")}}" data-allow-clear="true">
        <option value=""></option>
        @if(isset($additional['option']))
        @foreach ($additional['option'] as $i => $item)
            <option value="{{ $item ?? "Option ".($i+1) }}" {{ isset($value) && $value == $item ? "SELECTED" : "" }}>{{ $item ?? "Option ".($i+1) }}</option>
        @endforeach
        @endif
    </select>
</div>
