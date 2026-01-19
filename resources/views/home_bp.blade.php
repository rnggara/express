@extends('layouts.template', [
    "withoutFooter" => true
])

@section('content')
    <div class="d-flex flex-column gap-5">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex flex-column">
                <span class="fw-bold fs-1">Dashboard Admin</span>
            </div>
        </div>
        <div class="row">
            <div class="col-4">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fi fi-rr-box-open-full fs-1"></i>
                                <span class="fs-1 fw-bold">New Bookings</span>
                            </div>
                            <span class="text-center fs-2tx fw-bold">
                                {{ $orders->where("status", 0)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fi fi-rr-truck-side fs-1"></i>
                                <span class="fs-1 fw-bold">On Progress</span>
                            </div>
                            <span class="text-center fs-2tx fw-bold">
                                {{ $orders->where("status", 2)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card shadow-none">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fi fi-rr-plane-departure fs-1"></i>
                                <span class="fs-1 fw-bold">Shipping</span>
                            </div>
                            <span class="text-center fs-2tx fw-bold">
                                {{ $orders->where("status", ">=", 3)->count() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column gap-5">
                    <h3>Recent Bookings</h3>
                    <table class="table table-display table-bordered display" data-ordering="false">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Kode Booking</th>
                                <th>User</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders->sortByDesc("created_at") as $item)
                                @php
                                    $book = $item->book ?? [];
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date("d M Y H:i", strtotime($item->created_at)) }}</td>
                                    <td>{{ $item->kode_book }}</td>
                                    <td>{{ $item->user->name ?? "" }}</td>
                                    <td>
                                        @if ($item->status == 0)
                                            <span class="badge badge-secondary">Butuh Konfirmasi</span>
                                        @else
                                            @if ($item->status == 1)
                                                <span class="badge badge-primary">Menunggu Pembayaran</span>
                                            @else
                                                <span class="badge badge-success">Pembayaran Diterima</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <span>Nomor Resi: {{ $item->nomor_resi ?? "N/A" }}</span>
                                            <span>Nomor AWB: {{ $item->nomor_awb ?? "N/A" }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/geodata/data/countries2.js"></script>
    <script>

        async function getChartData(type){
            return $.ajax({
                url : "{{ route('dashboard.chart') }}?type="+ type,
                dataType : "json",
                type : "get"
            })
        }

        var dashboardChart = function() {
            var applicantChart = async function(){
                var element = document.getElementById('kt_apexcharts_1');

                var height = parseInt(KTUtil.css(element, 'height'));
                var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
                var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
                var baseColor = KTUtil.getCssVariableValue('--bs-primary');
                var secondaryColor = KTUtil.getCssVariableValue('--bs-primary-light');

                var dataP = []
                var dataW = []
                var categories = []
                await getChartData('applicant').then(function(resp){
                    dataP = resp.data['Pria']
                    dataW = resp.data['Wanita']
                    categories = resp.categories
                })

                if (!element) {
                    return;
                }

                var options = {
                    series: [{
                        name: 'Pria',
                        data: dataP
                    }, {
                        name: 'Wanita',
                        data: dataW
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'bar',
                        height: height,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: ['50%'],
                            endingShape: 'rounded'
                        },
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: categories,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function (val) {
                                return + val + ' orang'
                            }
                        }
                    },
                    colors: [baseColor, secondaryColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                var chart = new ApexCharts(element, options);
                chart.render();
            }

            var perusahaanChart = async function(){
                var element = document.getElementById('perusahaanChart');

                var height = parseInt(KTUtil.css(element, 'height'));
                var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
                var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
                var baseColor = KTUtil.getCssVariableValue('--bs-primary');
                var secondaryColor = KTUtil.getCssVariableValue('--bs-gray-300');

                if (!element) {
                    return;
                }

                var data = []
                var categories = []
                await getChartData('perusahaan').then(function(resp){
                    data = resp.data
                    categories = resp.categories
                })

                var options = {
                    series: [{
                        name: 'Perusahaan',
                        data: data
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'bar',
                        height: height,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: ['30%'],
                            endingShape: 'rounded'
                        },
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: categories,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function (val) {
                                return  val
                            }
                        }
                    },
                    colors: [baseColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                var chart = new ApexCharts(element, options);
                chart.render();
            }

            var posisiChart = async function(){
                var element = document.getElementById('posisiChart');

                var height = parseInt(KTUtil.css(element, 'height'));
                var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
                var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
                var baseColor = KTUtil.getCssVariableValue('--bs-primary');
                var secondaryColor = KTUtil.getCssVariableValue('--bs-gray-300');

                if (!element) {
                    return;
                }

                var data = []
                var categories = []
                await getChartData('posisi').then(function(resp){
                    data = resp.data
                    categories = resp.categories
                })

                var options = {
                    series: [{
                        name: 'Posisi',
                        data: data
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'bar',
                        height: height,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            columnWidth: ['30%'],
                            endingShape: 'rounded'
                        },
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: categories,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function (val) {
                                return val
                            }
                        }
                    },
                    colors: [baseColor, secondaryColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                var chart = new ApexCharts(element, options);
                chart.render();
            }

            var jobChart = async function(){
                var element = document.getElementById('jobChart');

                var height = parseInt(KTUtil.css(element, 'height'));
                var labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
                var borderColor = KTUtil.getCssVariableValue('--bs-gray-200');
                var baseColor = KTUtil.getCssVariableValue('--bs-primary');
                var secondaryColor = KTUtil.getCssVariableValue('--bs-gray-300');

                if (!element) {
                    return;
                }

                var data = []
                var categories = []
                await getChartData('job').then(function(resp){
                    data = resp.data
                    categories = resp.categories
                })

                var options = {
                    series: [{
                        name: 'Perusahaan',
                        data: data
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'bar',
                        height: height,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            columnWidth: ['30%'],
                            endingShape: 'rounded'
                        },
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: categories,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function (val) {
                                return val
                            }
                        }
                    },
                    colors: [baseColor, secondaryColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                var chart = new ApexCharts(element, options);
                chart.render();
            }

            var mapChart = async function(){

                var continents = {
                    "AF": 0,
                    "AN": 1,
                    "AS": 2,
                    "EU": 3,
                    "NA": 4,
                    "OC": 5,
                    "SA": 6
                }


                am5.ready(function() {

                    // Create root element
                    // https://www.amcharts.com/docs/v5/getting-started/#Root_element
                    var root = am5.Root.new("mapChart");

                    // Set themes
                    // https://www.amcharts.com/docs/v5/concepts/themes/
                    root.setThemes([
                        am5themes_Animated.new(root)
                    ]);

                    // Create the map chart
                    // https://www.amcharts.com/docs/v5/charts/map-chart/
                    var chart = root.container.children.push(am5map.MapChart.new(root, {
                        panX: "rotateX",
                        projection: am5map.geoMercator(),
                        layout: root.horizontalLayout
                    }));

                    loadGeodata("ID");

                    // Create polygon series for continents
                    // https://www.amcharts.com/docs/v5/charts/map-chart/map-polygon-series/
                    var polygonSeries = chart.series.push(am5map.MapPolygonSeries.new(root, {
                        calculateAggregates: true,
                        valueField: "value"
                    }));

                    polygonSeries.mapPolygons.template.setAll({
                        tooltipText: "{name} : {value} Employer",
                        interactive: true,
                        templateField: "polygonSettings"
                        // toggleKey: "active",
                        // fill : am5.color(0xD4C4F7)
                    });

                    polygonSeries.mapPolygons.template.states.create("hover", {
                        fill: am5.color(0x5234BF)
                    });

                    polygonSeries.mapPolygons.template.states.create("active", {
                        tooltipText: "{name}",
                        interactive: true,
                        fill: am5.color(0x5234BF)
                    });

                    polygonSeries.mapPolygons.template.events.on("click", (ev) => {
                        var dataItem = ev.target.dataItem;
                        var data = dataItem.dataContext;
                    })


                    function loadGeodata(country) {

                        // Default map
                        var defaultMap = "usaLow";

                        if (country == "US") {
                            chart.set("projection", am5map.geoAlbersUsa());
                        }
                        else {
                            chart.set("projection", am5map.geoMercator());
                        }

                        // calculate which map to be used
                        var currentMap = defaultMap;
                        var title = "";
                        if (am5geodata_data_countries2[country] !== undefined) {
                            currentMap = am5geodata_data_countries2[country]["maps"][0];

                            // add country title
                            if (am5geodata_data_countries2[country]["country"]) {
                            title = am5geodata_data_countries2[country]["country"];
                            }
                        }

                        am5.net.load("{{ route('dashboard.chart') }}?type=map&map=" + currentMap).then(function(result) {
                            var resp = am5.JSONParser.parse(result.response)
                            var geodata = resp.map;
                            var selected = resp.data;
                            var data = [];
                            for(var i = 0; i < geodata.features.length; i++) {
                                var _colors = am5.color(0xD4C4F7)
                                var emp_count = 0
                                if(selected[geodata.features[i].id] != undefined){
                                    _colors = am5.color(0x5234BF)
                                    emp_count = selected[geodata.features[i].id]
                                }
                                data.push({
                                    id: geodata.features[i].id,
                                    value: emp_count,
                                    polygonSettings: {
                                        fill: _colors,
                                    }
                                });
                            }

                            polygonSeries.set("geoJSON", geodata);
                            polygonSeries.data.setAll(data)
                        })

                        // am5.net.load("https://cdn.amcharts.com/lib/5/geodata/json/" + currentMap + ".json", chart).then(function (result) {

                        // });

                        chart.seriesContainer.children.push(am5.Label.new(root, {
                            x: 5,
                            y: 5,
                            text: title,
                            background: am5.RoundedRectangle.new(root, {
                                fill: am5.color(0xffffff),
                                fillOpacity: 0.2
                            })
                        }))
                    }

                }); // end am5.ready()
            }

            return {
                init: function(){
                    applicantChart()
                    perusahaanChart()
                    posisiChart()
                    jobChart()
                    mapChart()
                }
            }
        }();

        function open_navigate_modal(id){
            $("#modalNavigate").modal("show")
            $("#modalNavigate input[name=company_id]").val(id)
        }

        $(document).ready(function(){
            $("table.display").DataTable()

            dashboardChart.init()
        })
    </script>
@endsection
