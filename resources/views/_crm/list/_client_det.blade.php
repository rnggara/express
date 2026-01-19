@if ($type == "op")
<div class="d-flex flex-column">
    <div class="align-items-center bg-secondary d-flex justify-content-between p-5 rounded-top">
        <span class="fw-bold">Related to</span>
        <button class="btn btn-icon btn-sm" data-bs-dismiss="modal">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="d-flex align-items-center flex-wrap p-3">
        @foreach ($data as $op)
            <div class="symbol symbol-circle symbol-30px me-3" data-bs-toggle="tooltip" data-bs-html="true"
            title="<div class='d-flex flex-column'><span>{{ $op->leads_name }}</span><span class='text-primary'>{{ number_format($op->nominal ?? 0, 0, ",", ".") }}</span></div>">
                <div class="symbol-label bg-light-primary">
                    <i class="fi fi-rr-briefcase text-primary"></i>
                </div>
            </div>
        @endforeach
    </div>
</div>
@else
<div class="d-flex flex-column">
    <div class="align-items-center bg-secondary d-flex justify-content-between p-5 rounded-top">
        <span class="fw-bold">Related to</span>
        <button class="btn btn-icon btn-sm" data-bs-dismiss="modal">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="d-flex align-items-center flex-wrap p-5">
        @foreach ($data as $op)
            <label class="text-nowrap fw-bold" data-bs-toggle="tooltip" data-bs-html="true"
            title="<div class='d-flex flex-column'><span class='fw-bold'>{{ ucwords($op->name) }}</span><span class='text-primary'>{{ $cl->company_name }}</span>
                <span class='fw-bold'>{{ $op->no_telp ?? "-" }}</span><span class='fw-bold'>{{ $op->email ?? "-" }}</span></div>">{{ ucwords($op->name) }}, </label>
        @endforeach
    </div>
</div>
@endif
