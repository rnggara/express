<div class="d-flex align-items-center flex-column">
    <h3 class="mb-3">Menampilkan data : {{ $user->name }} - {{ date("F Y", strtotime($period)) }}</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th colspan="5">Rangkuman</th>
            </tr>
            <tr>
                <th>Hadir</th>
                <th>Total Jam Hadir</th>
                <th>Terlambat</th>
                <th>Total Jam Terlambat</th>
                <th>Mangkir / Tidak Absen</th>
            </tr>
        </thead>
        @php
            $totalhadir = "00:00";
            $hIn = floor($tHadir / 60);
            $mIn = $tHadir - ($hIn * 60);
            $totalHadir = sprintf("%02d", $hIn).":".sprintf("%02d", $mIn);

            $totalTelat = "00:00";
            $telatMinutes = $tTelat;
            $h = floor($telatMinutes / 60);
            $m = $telatMinutes - ($h * 60);

            $totalTelat = sprintf("%02d", $h).":".sprintf("%02d", $m);
        @endphp
        <tbody>
            <tr>
                <td>{{$hadir}}</td>
                <td>{{ $totalHadir }}</td>
                <td>{{ $telat }}</td>
                <td>{{ $totalTelat }}</td>
                <td>{{ $mangkir }}</td>
            </tr>
        </tbody>
    </table>
    <hr>
    <table class="table display table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Mulai Istirahat</th>
                <th>Jam Kembali Kerja</th>
                <th>Jam Pulang</th>
                <th>Terlambat (menit)</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 1; $i <= $t; $i++)
                @php
                    $dt = "$period-".sprintf("%02d", $i);
                    $bgClass = "";
                    $remarks = "";
                    if(date('N', strtotime($dt)) >= 6){
                        $bgClass = "bg-warning";
                        $remarks = "Libur";
                    }

                    $el = $dataHadir[$dt] ?? [];
                    $cIn = "-";
                    $cOut = "-";
                    $bIn = "-";
                    $bOut = "-";
                    $_telat = 0;
                    if(!empty($el)){
                        $cIn = date("H:i:s", strtotime($el['clock_in']->clock_time));
                        $_telat = $el['clock_in']->total_telat;
                        if(isset($el['clock_out'])){
                            $cOut = date("H:i:s", strtotime($el['clock_out']->clock_time));
                        } else {
                            $remarks = "Tidak Absen Pulang";
                        }

                        if(isset($el['break_in'])){
                            $bIn = date("H:i:s", strtotime($el['break_in']->clock_time));
                        }

                        if(isset($el['break_out'])){
                            $bOut = date("H:i:s", strtotime($el['break_out']->clock_time));
                        }
                    } else {
                        if($dt < date("Y-m-d") && date('N', strtotime($dt)) < 6){
                            $remarks = "Tidak Absen Masuk";
                        }
                    }

                    if($_telat > 0){
                        $remarks = $remarks != "" ? "Terlambat dan $remarks" : "Terlambat";
                    }

                @endphp
                <tr class="{{ $bgClass }}">
                    <td>{{ $i }}</td>
                    <td>{{ $hari[date('N', strtotime($dt))] }}</td>
                    <td>{{ date("j M Y", strtotime($dt)) }}</td>
                    <td>{{ $cIn }}</td>
                    <td>{{ $bOut }}</td>
                    <td>{{ $bIn }}</td>
                    <td>{{ $cOut }}</td>
                    <td>{{ $_telat }}</td>
                    <td>{{ $remarks }}</td>
                </td>
            @endfor
        </tbody>
    </table>
</div>
