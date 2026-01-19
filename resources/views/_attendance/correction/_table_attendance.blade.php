<table class="table bg-white" data-ordering="false" id="table-attendance">
    <thead>
        <tr>
            <th>Employee</th>
            <th>Departement</th>
            @foreach ($reasons as  $item)
                <th>{{ $item->reason_id }}</th>
            @endforeach
            <th>
                
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registrations as $item)
            <tr>
                <td>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ $item->emp->emp_name }}</span>
                        <span>{{ $item->emp->emp_id }}</span>
                    </div>
                </td>
                <td>
                    {{ $item->emp->dept->name ?? "-" }}
                </td>
                @foreach ($reasons as  $res)
                    @php
                        $cnt = 0;
                        $att = $att_data[$item->emp_id] ?? [];
                        $attCnt = $att[$res->id] ?? [];
                        $cnt = count($attCnt);
                    @endphp
                    <th>{{ $cnt }}</th>
                @endforeach
                <th>
                    <a href="{{ route("attendance.correction.detail", $item->id) }}?p={{ base64_encode($periode->id) }}" class="btn btn-icon btn-sm">
                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                    </a>
                </th>
            </tr>
        @endforeach
    </tbody>
</table>