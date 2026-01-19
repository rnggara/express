<div class="fv-row" id="prop-sel-edit">
    <input type="hidden" name="id" value="{{$prop->id}}">
    <label for="property_type" class="col-form-label required">Property Name</label>
    <select name="property_type" class="form-select" required data-allow-clear="true" data-control="select2" data-dropdown-parent="#prop-sel-edit" data-placeholder="Chose properties type">
        <option value=""></option>
        @foreach ($properties as $id => $item)
            <option value="{{ $id }}" {{ $id == $prop->property_type ? "SELECTED" : "" }}>{{ $item }}</option>
        @endforeach
    </select>
</div>
<div class="fv-row">
    <label for="property_name" class="col-form-label required">Property Name</label>
    <input type="text" name="property_name" onchange="change_preview('#kt_modal_edit')" class="form-control" value="{{$prop->property_name ?? ""}}" required placeholder="Input property name">
</div>
<div class="fv-row" style="display: none" data-section='currency' id="currency-sel-parent-edit">
    <label for="currency" class="col-form-label">Currency</label>
    <select name="currency" onchange="change_preview('#kt_modal_edit')" class="form-select" data-allow-clear="true" data-control="select2" data-dropdown-parent="#currency-sel-parent-edit" data-placeholder="IDR">
        <option value=""></option>
        <option value="IDR" {{ $prop->currency == "IDR" ? "SELECTED" : "" }}>IDR</option>
        <option value="USD" {{ $prop->currency == "USD" ? "SELECTED" : "" }}>USD</option>
    </select>
</div>
<div class="fv-row" style="display: none">
    <label for="input_placeholder" class="col-form-label">Input Placeholder</label>
    <input type="text" name="input_placeholder" onchange="change_preview('#kt_modal_edit')" value="{{$prop->property_placeholder ?? ""}}" class="form-control" placeholder="Input placeholder">
</div>
<div class="fv-row" style="display: none">
    <label for="input_placeholder2" class="col-form-label">Input Placeholder 2</label>
    <input type="text" name="input_placeholder2"  onchange="change_preview('#kt_modal_edit')" class="form-control" placeholder="Input placeholder 2">
</div>
<div data-section="properties-additional">
    {{-- @include("_crm.preferences.properties._option", [
        "additional" => json_decode($prop->additional ?? "[]", true),
        "modal" => "#kt_modal_edit"
    ]) --}}
</div>
