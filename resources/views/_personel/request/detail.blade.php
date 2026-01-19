<div class="d-flex flex-column">
    @foreach ($req as $item)
        <div class='d-flex flex-column mb-5 data-item'>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class='fv-row col-6'>
                            <label class='col-form-label w-100'>Old {{ ucwords(str_replace("_", " ", $item->type)) }}</label>
                            <label class='mt-1'>{{ $req_data[$item->type ?? ''][$item->old] ?? ($item->old ?? "-") }}</label>
                        </div>
                        <div class='fv-row col-6'>
                            <label class='col-form-label w-100'>New {{ ucwords(str_replace("_", " ", $item->type)) }}</label>
                            <label class='mt-1'>{{ $req_data[$item->type ?? ''][$item->new] ?? ($item->new ?? "-") }}</label>
                        </div>
                        <div class='fv-row col-6'>
                            <label class='col-form-label w-100'>Start date</label>
                            <label class='mt-1'>{{ date("d F Y", strtotime($item->start_date)) }}</label>
                        </div>
                        <div class='fv-row col-6'>
                            <label class='col-form-label w-100'>End date</label>
                            <label class='mt-1'>{{ empty($item->end_date) ? "-" : date("d F Y", strtotime($item->end_date)) }}</label>
                        </div>
                        <div class='fv-row col-6'>
                            <label class='col-form-label w-100'>Must Approve By</label>
                            <label class='mt-1'>{{ $item->approve->name ?? "-" }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="d-flex align-items-center">
        <div class="flex-fill">
            @if ($approval)
                @csrf
                <input type="hidden" name="emp_id" value="{{ $emp_id }}">
                <input type="hidden" name="date" value="{{ $date }}">
                <button type="submit" class="btn btn-sm w-100 btn-success">Approve</button>
            @else
                <button type="button" data-bs-dismiss="modal" class="btn btn-sm w-100 btn-primary">Close</button>
            @endif
        </div>
    </div>
</div>