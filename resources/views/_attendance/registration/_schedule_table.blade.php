@foreach ($sch as $i => $item)
    <tr class="border border-gray-200">
        @foreach ($dayFull as $n => $day)
            <td>
                @if (isset($item[$n]))
                    @php
                        $el = $item[$n];
                    @endphp
                    <div class="d-flex flex-column align-items-center">
                        <span class="mb-5">{{ $el['label'] }}</span>
                        <span class="rounded py-2 px-7 {{ $el['shift'] == "OFF" ? "text-dark border" : "text-white" }}" style="background-color: {{ $el['color'] }}">{{ $el['shift'] }}</span>
                    </div>
                @endif
            </td>
        @endforeach
    </tr>
@endforeach
