@php
    $data = $top;
@endphp
<table border="1">
    <thead>
        <tr>
            <th>NO</th>
            <th>OPPORTUNITY NAME</th>
            <th>END USER / CUSTOMER</th>
            <th>TIMELINE</th>
            <th>TARGET REVENUE (IDR)</th>
            <th>TARGET COGS (IDR)</th>
            <th>TARGET GROSS PROFIT (IDR)</th>
            <th>BRIEF SOLUTION</th>
            <th>BUSINESS MODEL</th>
            <th>ACTION PLAN</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['leads'] as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->leads_name }}</td>
                <td>{{ $data['company_name'][$item->id_client] ?? "-" }}</td>
                <td>{{ date("M") }}</td>
                <td>{{ $item->nominal }}</td>
                <td></td>
                <td>{{ $item->estimasi_profit }}</td>
                <td>{{ "" }}</td>
                <td>{{ "Traditional" }}</td>
                <td>{{ "" }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
