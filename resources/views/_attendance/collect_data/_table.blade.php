<div class='d-flex justify-content-between'>
    <div class="d-flex flex-column mb-5">
        <span class="fw-bold">{{$collect->file_name ?? "-"}}</span>
        <span class="text-muted">{{date("d F Y", strtotime($start_date))}} - {{date("d F Y", strtotime($end_date))}}</span>
    </div>
    <div class="position-relative me-5">
        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
    </div>
</div>
<table class="table table-row-bordered bg-white" id="table-list">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Card ID</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Reason</th>
        </tr>
    </thead>
    <tbody>
        @foreach($row as $item)
            <tr>
                <td>
                    <div class='d-flex flex-column'>
                        <span class="fw-bold">{{$item['emp_name']}}</span>
                        <span>{{$item['emp_id']}}</span>
                    </div>
                </td>
                <td>{{$item['card_id']}}</td>
                <td>{{date("d F Y", strtotime($item['date']))}}</td>
                <td>{{$item['time_in'] == "00:00:00" ? "-" : $item['time_in']}}</td>
                <td>{{$item['time_out'] == "00:00:00" ? "-" : $item['time_out']}}</td>
                <td>
                    {!! $item['remarks'] !!}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
