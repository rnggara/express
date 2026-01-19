<h3>Hallo, {{ $name }} !</h3>
@if($isRescheduled)
    <p>Permintaan untuk jadwal ulang interview untuk posisi {{ $posisi }} telah disetujui dan akan dilaksanakan pada jam {{ $int_start }} tanggal @dateId($int_date)</p>
@else
    <p>Anda telah diundang sebagai untuk melakukan interview untuk posisi {{ $posisi }} pada jam {{ $int_start }} tanggal @dateId($int_date)</p>
@endif
<p>Silahkan klik link <a href="{{ $link }}">disini</a> untuk melakukan konfirmasi kehadiran atau melakukan perubahan jadwal</p>
