@extends('layouts.template')

@section('content')
    <div class="card card-custom bg-transparent">
        <div class="card-header border-0">
            <h3 class="card-title">Hasil Test Papi Kostik</h3>
            <div class="card-toolbar">
                <a href="{{ $back }}" class="btn btn-sm btn-icon btn-success">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <div class="card-body bg-white rounded">
            <div class="d-flex flex-column align-items-center">
                <span class="fs-2 fw-semibold">PSIKOGRAM</span>
                <span class="fs-2 fw-bold">Sikap dan Potensi Kerja</span>
                <span class=" mb-8">@dateId(date("Y-m-d", strtotime($last_test->result_end))) {{ date("H:i", strtotime(strtotime($last_test->result_end))) }}</span>
                <div class="scroll w-100">
                    <table class="table table-bordered" id="table-psikogram">
                        <thead>
                            <tr class="bg-light-primary text-white">
                                <th>Kategori</th>
                                <th>Aspek</th>
                                @foreach ($cat as $item)
                                    <th>{{ $item }}</th>
                                @endforeach
                                <th>Deskripsi Kepribadian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($param as $item)
                                @php
                                    $result = $psikogram[$item->id] ?? [];
                                @endphp
                                @if (!empty($result))
                                <tr>
                                    <td>{{ $item->type }}</td>
                                    <td class="text-nowrap">{{ $item->nama }}</td>
                                    @foreach ($cat as $_i => $c)
                                        @php
                                            $show = false;
                                            if($_i == $result->category){
                                                $show = true;
                                            }
                                        @endphp
                                        <td class="min-w-30px text-center">
                                            @if ($show)
                                                <i class="fa fa-check text-gray-800"></i>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td>{{ $result->descriptions }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="{{ 3 + count($cat) }}">
                                    <div class="d-flex justify-content-around">
                                        <span>Keterangan :</span>
                                        <span><span class="fw-bold">R</span> = Rendah</span>
                                        <span><span class="fw-bold">K</span> = Kurang</span>
                                        <span><span class="fw-bold">C</span> = Cukup</span>
                                        <span><span class="fw-bold">B</span> = Baik</span>
                                        <span><span class="fw-bold">T</span> = Tinggi</span>
                                    </div>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            var groupColumn = 0
            $("#table-psikogram").DataTable({
                paging : false,
                bInfo : false,
                ordering : false,
                searching: false,
                "columnDefs": [
                    {
                        "visible": false,
                        "targets": groupColumn
                    },
                ],
                "drawCallback": function(settings) {
                    var api = this.api();
                    var rows = api.rows({
                        page: "current"
                    }).nodes();
                    var last = null;

                    api.column(groupColumn, {
                        page: "current"
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                "<tr class=\"group fs-5 fw-bolder bg-secondary\"><td colspan=\"{{ 3 + count($cat) }}\">" + group + "</td></tr>"
                            );

                            last = group;
                        }
                    });
                },
                "fixedHeader": {
					"header":true,
					"headerOffset": 5
				},
            })
        })
    </script>
@endsection
