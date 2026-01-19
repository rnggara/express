<table class="table table-display-2 bg-white" data-ordering="false">
    <thead>
        <tr>
            <th>Offence</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Given By</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($offences as $item)
            <tr>
                <td>{{ $item->detail->name }}</td>
                <td>{{ date("d-m-Y", strtotime($item->start_date)) }}</td>
                <td>{{ empty($item->end_date) ? "-" : date("d-m-Y", strtotime($item->end_date)) }}</td>
                <td>{{ $item->user->name ?? "-" }}</td>
                <td>
                    <button type="button" class="btn btn-icon" onclick="offence_detail({{ $item->id }})">
                        <i class="fa fa-ellipsis-v text-dark"></i>
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>