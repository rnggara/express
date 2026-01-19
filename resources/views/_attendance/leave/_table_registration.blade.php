<div class="col-12">
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Period</th>
                <th>Total Cuti</th>
                <th>Sisa Cuti</th>
                <th>Cuti Bisa di Jual</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($emp_leaves as $item)
                @php
                    $jatah = $item->jatah;
                    $used = ($item->used + $item->sold + $item->reserve) - $item->anulir + $item->unrecorded;

                    $sisa = $jatah - $used;
                    $label = date("Y", strtotime($item->start_periode));
                    if($item->type == "long"){
                        $label = round($item->leave->long_expired / 12) . " Years";
                    }
                @endphp
                <tr>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="mb-1 fw-bold">{{ $label }}</span>
                            <span class="text-muted">Exp {{ date("F Y", strtotime($item->end_periode)) }}</span>
                        </div>
                    </td>
                    <td>{{ $jatah }}</td>
                    <td>{{ $used }}</td>
                    <td>{{ $sisa }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
