@if (isset($form_name))

@else
    <div class="d-flex flex-column">
        <div class="fv-row mb-5">
            <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
            <input type="text" class="form-control" readonly placeholder="{{ $placeholder ?? "Input placeholder" }}">
        </div>
        <div class="p-3 rounded bg-white shadow mb-5 shadow-sm">
            <div class="d-flex flex-column justify-content-between">
                <span class="mb-5">Example 1</span>
                <span class="mb-5">Example 2</span>
                <span class="">Example 3</span>
            </div>
        </div>
        <button type="button" class="bg-white btn shadow text-primary shadow-sm">
            <i class="fa fa-plus text-primary"></i>
            Add new input
        </button>
    </div>
@endif
