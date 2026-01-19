@extends('_crm.preferences.index')

@section('view_content')
<div class="d-flex flex-column">
    <div class="card">
        <div class="card-header border-bottom-0 px-0">
            <div class="d-flex flex-column">
                <h3 class="card-title">Transfer</h3>
                <div class="d-flex align-items-center mb-5">
                    <span class="text-muted">Setting <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                    <span class="text-muted">Approval <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                    <span class="fw-semibold">Transfer</span>
                </div>
            </div>
        </div>
        <div class="card-body bg-secondary-crm rounded">
            <form action="{{ route('crm.pref.personel.approval.save') }}" method="post">
                <div class="d-flex flex-column gap-5">
                    <span class="fs-3 fw-bold">Need Approval</span>
                    <div class="row g-5">
                        @foreach ($list as $item)
                            @php
                                $_data = $data[$item] ?? 1;
                                $checked = $_data == 1 ? "checked" : "";
                            @endphp
                            <div class="col-6">
                                <div class="fv-row">
                                    <div class="form-check form-check-solid form-check-custom">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" {{ $checked }} name="transfer[{{ $item }}]" value="1" />
                                            <span class="fw-bold">{{ ucwords(str_replace("_", " ", $item)) }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
