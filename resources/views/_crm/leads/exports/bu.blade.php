<table border="1">
    <thead>
    <tr>
        <th>No</th>
        <th>Opportunity Owner</th>
        <th>Company</th>
        <th>Contact Person</th>
        <th>Opportunity Name</th>
        <th>Est. Revenue (Rp)</th>
        <th>Est. Gross Profit (Rp)</th>
        <th>Product/Solution</th>
        <th>Est. Closing Date</th>
        <th>Pipeline Status</th>
        <th>Sales Confidence</th>
        <th>Priority</th>
    </tr>
    </thead>
    <tbody>
        @php
            $scaleTitle = [1=> 'Nice To', "Run Through", "Best Case", "Commit"];
            $prioTitle = [1=> 'Low', 2=> 'Medium', 3=> 'High'];
        @endphp
        @foreach ($leads as $i => $item)
            @php
                $collabs = json_decode($item->contacts ?? "[]", true);
                $prd = json_decode($item->products ?? "[]", true);
            @endphp
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $users[$item->partner] ?? "-" }}</td>
                <td>{{ $company_name[$item->id_client] ?? "-" }}</td>
                <td>
                    @foreach ($collabs as $idC)
                        @if (isset($cp[$idC]))
                            {{ $cp[$idC] }},
                        @endif
                    @endforeach
                </td>
                <td>{{ $item->leads_name }}</td>
                <td>Rp. {{ number_format($item->nominal, 2) }}</td>
                <td>Rp. {{ number_format($item->estimasi_profit, 2) }}</td>
                <td>
                    @foreach ($prd as $idC)
                        @if (isset($products[$idC]))
                            {{ $products[$idC] }},
                        @endif
                    @endforeach
                </td>
                <td>
                    {{ $item->end_date ?? "" }}
                </td>
                <td>
                    {{ $funnel_name[$item->funnel_id] }}
                </td>
                <td>
                    {{ $scaleTitle[$item->sales_confidence] ?? "Nice To" }}
                </td>
                <td>
                    {{ $prioTitle[$item->level_priority] ?? "Low" }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
