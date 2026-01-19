@foreach ($sch as $i => $item)
    <tr>
        @foreach ($dayFull as $n => $day)
            <td>
                @if (isset($item[$n]))
                    @php
                        $el = $item[$n];
                    @endphp
                    <div class="symbol symbol-40px">
                        <div class="symbol-label bg-{{ $el['color'] }} {{ !empty($el['status']) ? "text-white" : "" }}">
                            {{ $el['label'] }}
                        </div>
                    </div>
                @endif
            </td>
        @endforeach
    </tr>
@endforeach
