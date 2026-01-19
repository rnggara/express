<table border="1">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Employee Type</th>
        <th>Position</th>
        <th>Join Date</th>
        <th>Education</th>
        <th>Salary</th>
        <th>Transport</th>
        <th>Meal</th>
        <th>House</th>
        <th>Health</th>
        <th>Position All.</th>
        <th>Voucher</th>
        <th>Field Bonus</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($emp as $i => $item)
            @php
                $jdate = "-";
                $act = $join_date->where("emp_id", $item->id)->first();
                if(!empty($act)){
                    $jdate = $act->act_date;
                }
                $etype = $type->where("id", $item->emp_type)->first();
                $ediv = $div->where("id", $item->division)->first();
                $pos = $item->emp_position;
                $tp = "-";
                if(!empty($etype)){
                    $tp = $etype->name;
                }
                if(!empty($etype) && !empty($ediv)){
                    $pos = "$etype->name $ediv->name";
                }
            @endphp
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->emp_name }}</td>
                <td>{{ $tp }}</td>
                <td>{{ $pos }}</td>
                <td>{{ $jdate }}</td>
                <td>{{ $item->edu ?? "-" }}</td>
                <td align="right">{{ number_format(base64_decode($item->salary)) }}</td>
                <td align="right">{{ number_format(base64_decode($item->transport)) }}</td>
                <td align="right">{{ number_format(base64_decode($item->meal)) }}</td>
                <td align="right">{{ number_format(base64_decode($item->house)) }}</td>
                <td align="right">{{ number_format(base64_decode($item->health)) }}</td>
                <td align="right">{{ number_format($item->allowance_office) }}</td>
                <td align="right">{{ number_format($item->voucher) }}</td>
                <td align="right">{{ number_format($item->fld_bonus) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
