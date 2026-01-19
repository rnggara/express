@if (isset($form_name))
    <div class="fv-row col-12">
        <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
        <div class="position-relative">
            <input type="text" class="form-control find-contact pe-15" data-name='{{ $form_name }}' placeholder="{{ $placeholder ?? "Input placeholder" }}">
            <div class="find-result"></div>
            <div class="find-noresult"></div>
            <div class="find-add">
                @if (isset($value) && !empty($value))
                    @foreach ($value as $item)
                        @if (isset($contacts[$item]))
                            <span class="fw-bold cursor-pointer" onclick="show_detail('contacts', {{ $item }})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads_extend">
                                <input type="hidden" name="{{ $form_name }}" value="{{ $item }}">
                                {{$contacts[$item]  }},
                            </span>
                        @endif
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
                <span class="mb-5">Contact 1</span>
                <span class="mb-5">Contact 2</span>
                <span class="">Contact 3</span>
            </div>
        </div>
        <button type="button" class="bg-white btn shadow text-primary shadow-sm">
            <i class="fa fa-plus text-primary"></i>
            Add new input
        </button>
    </div>
@endif
