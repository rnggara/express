<table>
    <thead>
        <tr>
            <td>#</td>
            <td>Tanggal</td>
            @foreach ($columns as $item)
                <td>{{ $item == "name" ? "Nama" : ucwords($item) }}</td>
            @endforeach
            <td>Departemen</td>
            <td>Absen Tipe</td>
            <td>Absen Lokasi</td>
            <td>Absen Waktu</td>
        </tr>
    </thead>
    <tbody>
        @php
            $num = 1;
        @endphp
        @foreach ($row as $date => $item)
            @foreach ($attCols as $atCols)
                @if (isset($item[$atCols]))
                    @php
                        $el = $item[$atCols]
                    @endphp
                    @foreach ($el as $val)
                        @php
                            $att = $val[0];
                            if(in_array($atCols, ["break_in", "clock_out"])){
                                $att = end($val);
                            }
                        @endphp
                        @php
                            $lok = "Anywhere";
                            if($att['lokasi'] == 1){
                                $lok = "Office";
                            } elseif($att['lokasi'] == 2){
                                $lok = "Customer";
                            } elseif($att['lokasi'] == 3){
                                $lok = "Home";
                            }
                        @endphp
                        <tr>
                            <td>{{ $num++ }}</td>
                            <td>{{date("d/m/Y", strtotime($date))}}</td>
                            @foreach ($columns as $col)
                                <td>{{ $col == "name" ? ucwords($att[$col]) : $att[$col] }}</td>
                            @endforeach
                            <td>{{$att['division']}}</td>
                            <td>{{ $atCols }}</td>
                            <td>{{ $lok }}</td>
                            <td>{{ $att['time'] }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        @endforeach
    </tbody>
</table>
