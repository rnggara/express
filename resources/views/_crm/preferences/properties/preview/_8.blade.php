@if (isset($form_name))
    <div class="fv-row col-12" id="prop{{ str_replace(" ", "_", $name) }}">
        <label for="" class="col-form-label">{{ ucwords($name ?? "Select Collaborator") }}</label>
        <select name="{{ $form_name }}" multiple class="form-select" data-control="select2" data-dropdown-parent="#prop{{ str_replace(" ", "_", $name) }}" data-placeholder="{{ $placeholder ?? "Select Collaborator" }}">
            <option value=""></option>
            @if(isset($additional['option']))
                @foreach ($additional['option'] as $i => $item)
                    <option value="{{ $i ?? "Option ".($i+1) }}" {{ in_array($i, $value ?? []    ) ? "SELECTED" : "" }}>{{ $item ?? "Option ".($i+1) }}</option>
                @endforeach
            @endif
        </select>
    </div>
@else
<div class="d-flex flex-column">
    <div class="fv-row mb-5">
        <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
        <input type="text" class="form-control" readonly placeholder="{{ $placeholder ?? "Input placeholder" }}">
    </div>
    <div class="p-3 rounded bg-white shadow mb-5 shadow-sm">
        <div class="d-flex flex-column justify-content-between">
            <span class="mb-5">Collaborator 1</span>
            <span class="mb-5">Collaborator 2</span>
            <span class="">Collaborator 3</span>
        </div>
    </div>
    <button type="button" class="bg-white btn shadow text-primary shadow-sm">
        <i class="fa fa-plus text-primary"></i>
        Add new input
    </button>
</div>
@endif
