<table border="1">
    <thead>
    <tr>
        @foreach ($columns as $key => $item)
            @if (isset($columnSelected[$key]))
                @if ($key == "test_result")
                    @foreach ($item as $test_id)
                        @if (isset($columnSelected[$key][$test_id]))
                            <th>{{ $test_name[$test_id] }}</th>
                        @endif
                    @endforeach
                @else
                    <th>{{ $item }}</th>
                @endif
            @endif
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach ($users as $val)
            <tr>
                @foreach ($columns as $key => $item)
                    @if (isset($columnSelected[$key]))
                        @if ($key == "test_result")
                            @php
                                $_test = $val[$key];
                            @endphp
                            @foreach ($item as $test_id)
                                @if (isset($columnSelected[$key][$test_id]))
                                    <td>{{ $_test[$test_id] ?? 0 }}</td>
                                @endif
                            @endforeach
                        @else
                            <td>{{ $val[$key] ?? "-" }}</td>
                        @endif
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
