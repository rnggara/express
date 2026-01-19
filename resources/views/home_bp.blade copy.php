@extends('layouts.template')

@section('content')
    <div class="d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between mb-5">
            <div class="d-flex flex-column">
                <span class="fw-bold fs-1">Dashboard Admin</span>
                <span>You can see statistic data of employee</span>
            </div>
            <button type="button" class="btn btn-secondary">
                Filter By
                <i class="fa fa-caret-down"></i>
            </button>
        </div>
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card card-stretch-25 mb-3 bg-transparent">
                    <div class="row gx-3">
                        <div class="col-lg-6">
                            <div class="card card-stretch pb-2 bg-primary-active text-white">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-8">
                                            <div class="symbol symbol-25px me-5">
                                                <div class="symbol-label bg-light-warning">
                                                    <i class="fa fa-users text-warning"></i>
                                                </div>
                                            </div>
                                            <span class="fw-bold">Total Job Ads</span>
                                        </div>
                                        <span class="fs-1 fw-bold">{{$job_ads->count()}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-stretch pb-2">
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-8">
                                            <div class="symbol symbol-25px me-5">
                                                <div class="symbol-label bg-light-danger">
                                                    <i class="fa fa-user-minus text-danger"></i>
                                                </div>
                                            </div>
                                            <span class="fw-bold">Job Ad Menunggu di Review</span>
                                        </div>
                                        <span class="fs-1 fw-bold">{{$job_ads_review->count()}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-stretch-75">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-5">
                                <span class="fw-bold">Total Perusahaan</span>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="fw-bold fs-1 me-3">{{ $companies->count() }}</span>
                                    <span class="">(Perusahaan)</span>
                                </div>
                            </div>
                            <div id="perusahaanChart" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card card-stretch-25 mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-8">
                                <div class="symbol symbol-25px me-5">
                                    <div class="symbol-label bg-light-danger">
                                    </div>
                                </div>
                                <span class="fw-bold">Job Ad Tayang</span>
                            </div>
                            <span class="fs-1 fw-bold">{{$job_ads_tayang->count()}}</span>
                        </div>
                    </div>
                </div>
                <div class="card card-stretch-75">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-8">
                                <div class="symbol symbol-25px me-5">
                                    <div class="symbol-label bg-light-danger">
                                    </div>
                                </div>
                                <span class="fw-bold">10 Top Industry banyak di cari</span>
                            </div>
                            <table class="table table-borderless table-row-dashed">
                                <tbody>
                                    @php
                                        $num = 1;
                                    @endphp
                                    @for($i = 0; $i < 10; $i++)
                                        @php
                                            $lbl = ($i + 1).".)";
                                            if(isset($topSpec[$i])){
                                                $tp = $topSpec[$i];
                                                if(isset($spec_name[$tp['id']])){
                                                    $lbl .= " ".$spec_name[$tp['id']];
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{$lbl}}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card card-stretch">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-8 justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-25px me-5">
                                        <div class="symbol-label bg-light-danger">

                                        </div>
                                    </div>
                                    <span class="fw-bold">Applicant</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center me-3">
                                        <div class="symbol symbol-20px me-5">
                                            <div class="symbol-label bg-primary"></div>
                                        </div>
                                        <span>Pria</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-20px me-5">
                                            <div class="symbol-label bg-light-primary"></div>
                                        </div>
                                        <span>Wanita</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border rounded border-primary p-3 mb-5">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <span class="fw-bold fs-1 mb-3">{{ $uProfile->count() }}</span>
                                        <span>(Applicant)</span>
                                    </div>
                                    <div class="separatorbg-primary separator separator-solid w-50px" style="rotate: 90deg"></div>
                                    <div class="d-flex flex-column">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="symbol symbol-20px me-5">
                                                <div class="symbol-label bg-primary"></div>
                                            </div>
                                            <span>{{$uGender["Pria"]}} Pria</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-20px me-5">
                                                <div class="symbol-label bg-light-primary"></div>
                                            </div>
                                            <span>{{$uGender["Wanita"]}} Wanita</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="kt_apexcharts_1" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="card card-stretch">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-8">
                                    <div class="symbol symbol-25px me-5">
                                        <div class="symbol-label bg-light-danger">
                                        </div>
                                    </div>
                                    <span class="fw-bold">10 Top Posisi yang Sering Tayang di Ad</span>
                                </div>
                                <div id="posisiChart" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card card-stretch">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-8">
                                    <div class="symbol symbol-25px me-5">
                                        <div class="symbol-label bg-light-danger">
                                        </div>
                                    </div>
                                    <span class="fw-bold">10 Top Perusahaan yang Sering buat Job Ad</span>
                                </div>
                                <div id="jobChart" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-25px me-5">
                                        <div class="symbol-label bg-light-danger">
                                        </div>
                                    </div>
                                    <span class="fw-bold">Peta Sebaran Employer (Perusahaan)</span>
                                </div>
                                <a href="{{ route("bp.employers.index") }}">Lihat List Perusahaan</a>
                            </div>
                            <div id="mapChart" style="height: 500px;"></div>
                        </div>
                    </div>
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
