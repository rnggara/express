@php
    $data = $mat;
@endphp
<table border="1">
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th align="center" colspan="3">Account</th>
            <th align="center" colspan="7">Maturity</th>
        </tr>
        <tr>
            <th>COMPANY NAME</th>
            <th>CLIENT REP / ACCOUNT MANAGER</th>
            <th>SALES MANAGER</th>
            <th>CURRENT</th>
            <th>GOAL</th>
            <th>ENGAGEMENT</th>
            <th>LY REVENUE (IDR)</th>
            <th>CY REVENUE (IDR)</th>
            <th>NO OF OPPORTUNITY</th>
            <th>INITIATIVES TO INCREASE THE MATURITY LEVEL</th>
        </tr>
    </thead>
    <tbody>
        @php
            $num = 1;
        @endphp
        @foreach ($data['company_name'] as $item)
            @php
                $accM = $data['account_manager']->where("target_id", $item->id)->first();
                $accId = json_decode($accM->property_value ?? "[]", true);
            @endphp
            <tr>
                <td>{{ $num++ }}</td>
                <td>{{ $item->company_name }}</td>
                <td>
                    @if (count($accId) > 0)
                        @foreach ($accId as $acid)
                            @if (isset($users[$acid]))
                                {{ $users[$acid] }},
                            @endif
                        @endforeach
                    @else
                        UNMANAGED
                    @endif
                </td>
                <td>UNMANAGED</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
