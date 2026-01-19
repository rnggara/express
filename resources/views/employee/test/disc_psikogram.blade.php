@extends('layouts.template')

@section('content')
    <div class="card card-custom bg-transparent">
        <div class="card-header border-0">
            <h3 class="card-title">Hasil Test {{ $test->label }}</h3>
            <div class="card-toolbar">
                <a href="{{ $back }}" class="btn btn-sm btn-icon btn-success">
                    <i class="fa fa-arrow-left"></i>
                </a>
            </div>
        </div>
        <div class="card-body bg-white rounded">
            <div class="d-flex flex-column align-items-center">
                <span class="fs-2 fw-semibold">D I S C</span>
                <span class="fs-2 fw-bold mb-8">Personality System Graph Page</span>
                <div class="d-flex align-items-center justify-content-center">
                    <table class="table table-borderless">
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{ $profile->name ?? $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Umur</th>
                            <td>:</td>
                            <td>{{ $umur }}</td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>:</td>
                            <td>{{ $profile->gender ?? "-" }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Test</th>
                            <td>:</td>
                            <td>@dateId(date("Y-m-d", strtotime($last_test->result_end))) {{ date("H:i", strtotime(strtotime($last_test->result_end))) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Line</th>
                                @foreach ($disc as $item)
                                    <th>{{ $item }}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($psikogram as $i => $item)
                                @php
                                    $_total = 0;
                                    foreach ($disc as $_disc) {
                                        if(isset($item[$_disc])){
                                            $_total += $item[$_disc];
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td>Line {{ $i }}</td>
                                    @foreach ($disc as $_disc)
                                        <td align="center" @if(!isset($item[$_disc])) class="bg-secondary" @endif>{{ $item[$_disc] ?? "" }}</td>
                                    @endforeach
                                    @if ($i < 3)
                                        <td align="center">{{ $_total }}</td>
                                    @else
                                        <td align="center" class="bg-secondary"></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-around mb-10 px-2">
                    <div class="card card-custom card-stretch border mb-5 mb-md-0 me-md-5">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center">
                                <span class="fw-bold">GRAPH 1 MOST</span>
                                <span class="fw-semibold">Mask Public Self</span>
                            </div>
                            <div class="mt-10">
                                <canvas id="chart_line_1" class="h-500px mh-md-500px init_chart" data-i="1"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card card-custom card-stretch border mb-5 mb-md-0 me-md-5">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center">
                                <span class="fw-bold">GRAPH 2 LEAST</span>
                                <span class="fw-semibold">Core Private Self</span>
                            </div>
                            <div class="mt-10">
                                <canvas id="chart_line_2" class="h-500px mh-md-500px init_chart" data-i="2"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card card-custom card-stretch border mb-5 mb-md-0 me-md-5">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center">
                                <span class="fw-bold">GRAPH 3 CHANGE</span>
                                <span class="fw-semibold">Mirror Perceived Self</span>
                            </div>
                            <div class="mt-10">
                                <canvas id="chart_line_3" class="h-500px mh-md-500px init_chart" data-i="3"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-around flex-column flex-md-row">
                    <div class="card card-custom card-stretch border me-md-5 mb-5 mb-md-0">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center">
                                <span class="fw-bold">Kepribadian Saat di Publik</span>
                                <span class="fw-semibold">Mask Public Self</span>
                            </div>
                            <div class="mt-10">
                                @php
                                    $_desc1 = $desc_line[1];
                                    $_label1 = explode(",", $_desc1->descriptions ?? "");
                                @endphp
                                <div class="d-flex flex-column">
                                    @foreach ($_label1 as $lbl)
                                        <span>{{ trim($lbl) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-custom card-stretch border me-md-5 mb-5 mb-md-0">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center">
                                <span class="fw-bold">Kepribadian Saat Mendapat Tekanan</span>
                                <span class="fw-semibold">Core Private Self</span>
                            </div>
                            <div class="mt-10">
                                @php
                                    $_desc2 = $desc_line[2];
                                    $_label2 = explode(",", $_desc2->descriptions ?? "");
                                @endphp
                                <div class="d-flex flex-column">
                                    @foreach ($_label2 as $lbl)
                                        <span>{{ trim($lbl) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card card-custom card-stretch border me-md-5 mb-5 mb-md-0">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center">
                                <span class="fw-bold">Kepribadian Asli/Sesungguhnya</span>
                                <span class="fw-semibold">Mirror Perceived Self</span>
                            </div>
                            <div class="mt-10">
                                @php
                                    $_desc3 = $desc_line[3];
                                    $_label3 = explode(",", $_desc3->descriptions ?? "");
                                @endphp
                                <div class="d-flex flex-column">
                                    @foreach ($_label3 as $lbl)
                                        <span>{{ trim($lbl) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-custom w-100 mb-5">
                    <div class="card-header border-0">
                        <h3 class="card-title">Deskripsi Kepribadian</h3>
                    </div>
                    <div class="card-body border rounded">
                        {!! $desc_kepribadian->descriptions ?? "" !!}
                    </div>
                </div>
                <div class="card card-custom w-100 mb-5">
                    <div class="card-header border-0">
                        <h3 class="card-title">Job Match</h3>
                    </div>
                    <div class="card-body border rounded">
                        {!! $desc_job->descriptions ?? "" !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        async function get_data_line(i){
            return $.ajax({
                url : "{{ route("test.disc.psikogram", $last_test->id) }}?a=chart&l=" + i,
                type : "get",
                dataType : "json"
            })
        }

        function init_chart(){
            $(".init_chart").each(async function(){
                var _id = $(this).attr("id")
                var ctx = document.getElementById(_id);

                var _i = $(this).data("i")
                var _data = await get_data_line(_i)

                // Define colors
                var primaryColor = KTUtil.getCssVariableValue('--kt-primary');
                var dangerColor = KTUtil.getCssVariableValue('--kt-danger');

                // Define fonts
                var fontFamily = KTUtil.getCssVariableValue('--bs-font-sans-serif');

                // Chart labels
                const labels = ['D', 'I', 'S', 'C'];

                // Chart data
                const data = {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Dataset 1',
                            data: _data,
                            borderColor: primaryColor,
                            backgroundColor: 'transparent'
                        },
                    ]
                };

                // Chart config
                const config = {
                    type: 'line',
                    data: data,
                    options: {
                        layouts: {
                            autoPadding : true
                        },
                        plugins: {
                            title: {
                                display: false,
                            },
                            legend : {
                                display : false
                            },
                            tooltip : {
                                enabled : false
                            }
                        },
                        responsive: true,
                        aspectRatio : 1/2,
                        scales : {
                            y: {
                                min: -10,
                                max: 10,
                                ticks : {
                                    stepSize: 1,
                                    autoSkip: false,
                                    padding : 0
                                }
                            },
                            x : {
                                lineWidth : 1
                            }
                        }
                    },
                };

                // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
                var myChart = new Chart(ctx, config);
            })
        }

        $(document).ready(function(){
            init_chart()
        })
    </script>
@endsection
