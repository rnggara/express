
@if (isset($form_name))
    <div class="fv-row col-12">
        <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
        <div class="position-relative">
            <input type="text" class="form-control find-opportunity pe-15" data-multiple="true" data-name='{{ $form_name }}' placeholder="{{ $placeholder ?? "Input placeholder" }}">
            <div class="find-result"></div>
            <div class="find-noresult"></div>
            <div class="find-add">
                @if (isset($value) && !empty($value))
                    @foreach ($value as $item)
                        <div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 opportunity-item">
                            <div class="d-flex align-items-center">
                                <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                <span>{{ $opportunity[$item] ?? "-" }}</span>
                                <input type="hidden" name="{{ $form_name }}" value="{{ $item }}">
                            </div>
                            <button type="button" onclick="removeCompany(this)" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                <i class="fi fi-rr-trash"></i>
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" class="btn btn-primary btn-sm position-absolute end-0 top-0 btn-icon mt-1 me-1" style="display: none" data-bs-toggle="tooltip" title="Add Contact">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
@else
<div class="d-flex flex-column">
    <div class="fv-row mb-5">
        <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
        <input type="text" class="form-control" readonly placeholder="{{ $placeholder ?? "Input placeholder" }}">
    </div>
    <div class="p-3 rounded bg-white shadow mb-5 shadow-sm">
        <div class="d-flex flex-column justify-content-between">
            <span class="mb-5">Opportunity 1</span>
            <span class="mb-5">Opportunity 2</span>
            <span class="">Opportunity 3</span>
        </div>
    </div>
    <button type="button" class="bg-white btn shadow text-primary shadow-sm">
        <i class="fa fa-plus text-primary"></i>
        Add new input
    </button>
</div>
@endif
