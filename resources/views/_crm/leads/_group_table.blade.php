<table class="table table-striped display" id="table-perusahaan">
    <thead>
        <tr>
            <th></th>
            <th>Hari Kerja</th>
            <th>Jumlah Akun Perusahaan</th>
            <th>Jumlah Leads</th>
            <th>Nilai Leads</th>
            <th>Nilai Potensi Profit</th>
            <th>Rata-Rata<br/>Leads Per Hari<br />Kerja</th>
            <th>Rata-Rata<br/>Leads Per Akun</th>
            <th>% Perubahan<br>Status Leads</th>
            <th>Rata-rata Sales<br>Cycle (Hari)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $uid = 0;
        $udays = 0;
        $ucompany = 0;
        $uleads = 0;
        $ulk = 0;
        $ula = 0;
        $unominal = 0;
        $uprof = 0;
        $upctg = 0;
        $usales = 0;
        ?>
        @foreach($u_data as $k)
            <?php
            $uid += 1;
            $udays += $k['DAYS'];
            $ucompany += $k['COMPANY'];
            $uleads += $k['LEADS'];
            $unominal += $k['NOMINAL'];
            $ulk += $k['LK'];
            $ula += $k['LA'];
            $uprof += $k['PROFIT'];
            $upctg += $k['PCTG'];
            $usales += $k['SALES'];
            ?>
            <tr>
                <td>{{$k['UNAME']}}</td>
                <td>{{$k['DAYS']}}</td>
                <td>{{$k['COMPANY']}}</td>
                <td>{{$k['LEADS']}}</td>
                <td>{{ number_format($k['NOMINAL'],2)}}</td>
                <td>{{ number_format($k['PROFIT'],3)}}</td>
                <td>{{ number_format($k['LK'])}}</td>
                <td>{{ number_format($k['LA'])}}</td>
                <td>{{ number_format($k['PCTG'])}}</td>
                <td>{{ number_format($k['SALES'])}}</td>
            </tr>
        @endforeach
    </tbody>
    <thead>
        <tr>
            <td></td>
            <td><?php echo floatval($udays / ($uid == 0 ? 1 : $uid));?></td>
            <td><?php echo number_format(floatval($ucompany / ($uid == 0 ? 1 : $uid)),2, ".", "");?></td>
            <td><?php echo number_format(floatval($uleads / ($uid == 0 ? 1 : $uid)),2, ".", "");?></td>
            <td><?php echo number_format($unominal,2);?></td>
            <td><?php echo number_format($uprof,2);?></td>
            <td><?php echo number_format($ulk,3);?></td>
            <td><?php echo number_format(($ula / ($uid == 0 ? 1 : $uid)), 2, ".", "");?></td>
            <td><?php echo number_format(($upctg / ($uid == 0 ? 1 : $uid)), 2, ".", "");?></td>
            <td><?php echo number_format(($usales / ($uid == 0 ? 1 : $uid)), 2, ".", "");?></td>
        </tr>
    </thead>
</table>
