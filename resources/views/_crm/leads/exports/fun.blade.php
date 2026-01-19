@php
    $data = $fun;
@endphp
<table border="1">
    @php
        $total = [];
    @endphp
    <thead>
        <tr>
            <th>FUNNEL</th>
            @for($i = 1; $i <= 12; $i++)
                @php
                    $total[$i] = 0;
                @endphp
                <th>
                    {{ date("M", strtotime(date("Y")."-".sprintf("%02d", $i))) }}
                </th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($data['funnels'] as $item)
            <tr>
                <td>{{ $item->label }}</td>
                @for($i = 1; $i <= 12; $i++)
                    @php
                        $fcount = count($data['fcount'][$item->id][$i] ?? []);
                        $total[$i] += $fcount;
                    @endphp
                    <td>{{ $fcount }}</td>
                @endfor
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>TOTAL FUNNEL</th>
            @for($i = 1; $i <= 12; $i++)
                <th>{{ $total[$i] }}</th>
            @endfor
        </tr>
    </tfoot>
</table>
