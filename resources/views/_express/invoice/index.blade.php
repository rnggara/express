@extends('_express.layout')

@section('view_content')
    <div class="d-flex flex-column gap-5">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column">
                <span class="fw-bold fs-1">Booking</span>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-display table-bordered display" data-ordering="false">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Kode Booking</th>
                            <th>Nomor Resi</th>
                            <th>Nomor AWB</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $item)
                            @php
                                $book = $item->book ?? [];
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date("d M Y H:i", strtotime($item->created_at)) }}</td>
                                <td>{{ $item->kode_book }}</td>
                                <td>
                                    <a href="{{ route("cek.resi", $item->nomor_resi) }}" class="badge badge-warning">
                                        {{ $item->nomor_resi }}
                                    </a>
                                </td>
                                <td>{{ $item->nomor_awb }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
    <script>
        $("table.display").DataTable()

        $(document).ready(function(){
            $('[data-toggle="virtual"]').click(function(){
                var modal = $(this).parents("div.modal").eq(0)
                var target = $(this).attr("data-target")
                $(modal).find('[data-virual]').addClass('d-none')
                modal.find('[data-virual-detail="'+target+'"]').removeClass("d-none")
                modal.find('[data-virual-button]').removeClass("d-none")
            })
        })

    </script>
@endsection

