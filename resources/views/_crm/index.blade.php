@extends('layouts.templateCrm', ['menuCrm' => 'menu_crm', 'withoutFooter' => true, 'style' => ['border' => 'border-bottom', 'box-shadow' => 'none']])

{{-- @section('fixaside')
    @include('_crm.leads._aside')
@endsection --}}

@section('content')
    <div class="card card-custom bg-transparent not-rounded h-100">
        <div class="card-body">
            <div class="d-flex flex-column">
                <span class="fs-3 fw-bold">CRM Dashboard</span>
                {{-- <span class="">CRM data dashboard </span> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column">
                <div class="d-flex justify-content-between mb-5">
                    <div class="d-flex align-items-center w-auto">
                        <span class="text-nowrap me-5">Team :</span>
                        <select name="team" id="team" onchange="loadDashboard()" data-control="select2" class="form-select form-select-solid">
                            @foreach ($teams as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <select name="filter" id="filter" onchange="loadDashboard()" data-control="select2" class="form-select form-select-solid">
                            <option value="ytd">Year to Date</option>
                            <option value="qtd">Quartal to Date</option>
                            <option value="mtd">Month to Date</option>
                            <option value="lytd">Last Year to Date</option>
                            <option value="lqtd">Last Quartal to Date</option>
                            <option value="lmtd">Last Month to Date</option>
                        </select>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-5 g-3 mb-5">
                    @if(!isset($set["TOTAL_OPPORTUNITY"]) || (isset($set["TOTAL_OPPORTUNITY"]) && $set["TOTAL_OPPORTUNITY"] == 1))
                    <div class="col rounded">
                        <div class="card card-stretch bg-primary">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="symbol symbol-30px me-3">
                                            <div class="symbol-label bg-light-warning">
                                                <i class="fi fi-rr-briefcase text-warning fs-3 mt-2"></i>
                                            </div>
                                        </div>
                                        <span class="text-white fw-bold">Total Opportunity</span>
                                    </div>
                                    <span class="fs-2tx text-white" id="total-opportunity">0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["CONVERSION_RATE"]) || (isset($set["CONVERSION_RATE"]) && $set["CONVERSION_RATE"] == 1))
                    <div class="col rounded">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="symbol symbol-30px me-3">
                                            <div class="symbol-label bg-light-warning">
                                                <i class="fi fi-rr-percentage text-warning fs-3 mt-2"></i>
                                            </div>
                                        </div>
                                        <span class=" fw-bold">Conversion Rate</span>
                                    </div>
                                    <span class="fs-2tx " id="conversion-rate">{{ 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["OPPORTUNITY_WIN_RATE"]) || (isset($set["OPPORTUNITY_WIN_RATE"]) && $set["OPPORTUNITY_WIN_RATE"] == 1))
                    <div class="col rounded">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="symbol symbol-30px me-3">
                                            <div class="symbol-label bg-light-warning">
                                                <i class="fi fi-rr-money-bill-wave-alt text-warning fs-3 mt-2"></i>
                                            </div>
                                        </div>
                                        <span class=" fw-bold">Opportunity Win Rate</span>
                                    </div>
                                    <span class="fs-2tx " id="opportunity-win">{{ 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["OPPORTUNITY_LOSE_RATE"]) || (isset($set["OPPORTUNITY_LOSE_RATE"]) && $set["OPPORTUNITY_LOSE_RATE"] == 1))
                    <div class="col rounded">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="symbol symbol-30px me-3">
                                            <div class="symbol-label bg-light-warning">
                                                <i class="fi fi-rr-money-bill-wave-alt text-warning fs-3 mt-2"></i>
                                            </div>
                                        </div>
                                        <span class=" fw-bold">Opportunity Lose Rate</span>
                                    </div>
                                    <span class="fs-2tx " id="opportunity-lose">{{ 0 }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["SALES_CYCLE"]) || (isset($set["SALES_CYCLE"]) && $set["SALES_CYCLE"] == 1))
                    <div class="col rounded">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center mb-5">
                                        <div class="symbol symbol-30px me-3">
                                            <div class="symbol-label bg-light-warning">
                                                <i class="fi fi-rr-percentage text-warning fs-3 mt-2"></i>
                                            </div>
                                        </div>
                                        <span class=" fw-bold">Sales Cycle (Day)</span>
                                    </div>
                                    <span class="fs-2tx " id="sales-cyle-day">{{ 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="row g-3 mb-5">
                    @if(!isset($set["ESTIMATE_REVENUE"]) || (isset($set["ESTIMATE_REVENUE"]) && $set["ESTIMATE_REVENUE"] == 1))
                    <div class="col-12 col-md-6">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between mb-5">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold mb-5">Estimate Revenue</span>
                                            <span class="fw-bold">IDR <span id="revenue"></span></span>
                                        </div>
                                        <div>
                                            <select name="pipeline_id" onchange="loadEstimateRevenue()" id="pipeline_id" class="form-select" data-control="select2" data-placeholder="Select Funnel"></select>
                                        </div>
                                    </div>
                                    <div id="funnel-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["OPPORTUNITY_PERFORMANCE"]) || (isset($set["OPPORTUNITY_PERFORMANCE"]) && $set["OPPORTUNITY_PERFORMANCE"] == 1))
                    <div class="col-12 col-md-6">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center">
                                    <div class="d-flex justify-content-start">
                                        <span class="fw-bold mb-5">Opportunity Performance</span>
                                    </div>
                                    <div class="mh-400px" id="opp-perf-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["SALES_CYCLE_CHART"]) || (isset($set["SALES_CYCLE_CHART"]) && $set["SALES_CYCLE_CHART"] == 1))
                    <div class="col-12 col-md-6">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-5">Sales Cycle</span>
                                    <div id="sales-cycle-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["SALES_WIN_RATE"]) || (isset($set["SALES_WIN_RATE"]) && $set["SALES_WIN_RATE"] == 1))
                    <div class="col-12 col-md-6">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-5">Sales Winrate</span>
                                    <div id="sales-win-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["TOP_3_COMPANY"]) || (isset($set["TOP_3_COMPANY"]) && $set["TOP_3_COMPANY"] == 1))
                    <div class="col-12 col-md-6">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-5">Top 3 Companies</span>
                                    <table class="table" id="table-top-3-company">
                                        <thead>
                                            <tr>
                                                <th>Company Name</th>
                                                <th>Opportunity</th>
                                                <th>Opportunity Value</th>
                                                <th>Winrate</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["TOP_3_PRODUCT"]) || (isset($set["TOP_3_PRODUCT"]) && $set["TOP_3_PRODUCT"] == 1))
                    <div class="col-12 col-md-6">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-5">Top 3 Products</span>
                                    <table class="table" id="table-top-3-products">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Opportunity</th>
                                                <th>Opportunity Value</th>
                                                <th>Winrate</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["OPPORTUNITY_WORTH"]) || (isset($set["OPPORTUNITY_WORTH"]) && $set["OPPORTUNITY_WORTH"] == 1))
                    <div class="col-12 col-md-6">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-5">Opportunity Worth</span>
                                    <div id="opportunity-worth-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["OPPORTUNITY_WIN"]) || (isset($set["OPPORTUNITY_WIN"]) && $set["OPPORTUNITY_WIN"] == 1))
                    <div class="col-12 col-md-6">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-5">Opportunity Won</span>
                                    <div id="opportunity-won-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(!isset($set["OPPORTUNITY_LOSE"]) || (isset($set["OPPORTUNITY_LOSE"]) && $set["OPPORTUNITY_LOSE"] == 1))
                    <div class="col-12 col-md-6">
                        <div class="card card-stretch bg-white">
                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold mb-5">Opportunity Lose</span>
                                    <div id="opportunity-lose-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endsection

    @section('custom_script')
        <script src='https://cdn.plot.ly/plotly-2.27.0.min.js'></script>
        <script src="{{ asset("assets/jquery-number/jquery.number.min.js") }}"></script>
        <script>

            var table_company = $("#table-top-3-company").DataTable({
                bInfo : false,
                paging : false,
                ordering: false,
                columns : [
                    {"data" : "name"},
                    {"data" : "opportunity"},
                    {"data" : "value", render: function (data, type, row) {
                        return "IDR. " + $.number(data, 0, ",", ".");
                    }},
                    {"data" : "winrate"},
                ]
            })

            var table_product = $("#table-top-3-products").DataTable({
                bInfo : false,
                paging : false,
                ordering: false,
                columns : [
                    {"data" : "name"},
                    {"data" : "opportunity"},
                    {"data" : "value", render: function (data, type, row) {
                        return "IDR. " + $.number(data, 0, ",", ".");
                    }},
                    {"data" : "winrate"},
                ]
            })

            function barChart(element, x, y){
                // winrate start
                var options = {
                    series: [{
                        data: y
                    }],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    colors: ["#7340E5"],
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                        }
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    xaxis: {
                        categories: x,
                        labels: {
                            formatter: function(value, timestamp, opts) {
                                return value
                            }
                        }
                    },
                    yaxis: {
                        forceNiceScale: true,
                        labels: {
                            formatter: function(value, index) {
                                return $.number((value / 1000000), 0, ",", ".") + " M"
                            },
                            style: {
                                cssClass: "fw-bold"
                            }
                        },
                    },
                    tooltip: {
                        y: {
                            title: {
                                formatter: (seriesName) => "",
                            },
                        },
                    }
                };

                $(element).html("")

                var chart = new ApexCharts(document.querySelector(element), options);
                chart.render();
            }

            function doughnutChart(element, labels, x){
                // Chart data
                var bgColor = ["#7340E5", "#AA8CEF", "#D4C4F7"]
                var data = {
                    labels: labels.reverse(),
                    datasets: [{
                        data : x.reverse(),
                        backgroundColor: bgColor.reverse(),
                        borderJoinStyle : "round",
                        rotation : 270
                        // borderRadius: {
                        //     outerEnd : 100,
                        //     innerEnd : 100
                        // },
                        // spacing : -100,
                        // clip: 5,
                    }]
                };

                // Chart config
                var config = {
                    type: 'doughnut',
                    data: data,
                    options: {
                        events: [],
                        plugins: {
                            title: {
                                display: false,
                            },
                            legend : {
                                reverse : true,
                                position : "bottom",
                                onClick : null,
                                labels : {
                                    generateLabels: (chart) => {
                                        const datasets = chart.data.datasets;
                                        return datasets[0].data.map((data, i) => ({
                                            text: `${chart.data.labels[i]}(${data - 1})`,
                                            fillStyle: datasets[0].backgroundColor[i],
                                            strokeStyle: datasets[0].backgroundColor[i],
                                            index: i,
                                            borderRadius : 4,
                                            lineCap : "round"
                                        }))
                                    }
                                }
                            },
                            tooltip : {
                                enabled : false,
                            }
                        },
                        responsive: true,
                    },
                    plugins : [{
                        afterUpdate: function (chart) {
                                var a=chart.config.data.datasets.length -1;
                                for (let i in chart.config.data.datasets) {
                                    for(var j = chart.config.data.datasets[i].data.length - 1; j>= 0;--j) {
                                        var arc = chart.getDatasetMeta(i).data[j];
                                        arc.round = {
                                            x: (chart.chartArea.left + chart.chartArea.right) / 2,
                                            y: (chart.chartArea.top + chart.chartArea.bottom) / 2,
                                            radius: (arc.outerRadius + arc.innerRadius) / 2,
                                            thickness: (arc.outerRadius - arc.innerRadius) / 2 - 1,
                                            value : x[j],
                                            // thickness: chart.radiusLength / 2 - 1,
                                            backgroundColor: arc.options.backgroundColor
                                        }
                                    }
                                    a--;
                                }
                        },
                        afterDraw: function (chart) {
                                var ctx = chart.ctx;
                                for (let i in chart.config.data.datasets) {
                                    for(var j = chart.config.data.datasets[i].data.length - 1; j>= 0;--j) {
                                        var arc = chart.getDatasetMeta(i).data[j];
                                        // console.log(arc)
                                        var startAngle = Math.PI / 2 - arc.startAngle;
                                        var endAngle = Math.PI / 2 - arc.endAngle;

                                        ctx.save();
                                        ctx.translate(arc.round.x, arc.round.y);
                                        ctx.fillStyle = arc.round.backgroundColor;
                                        ctx.beginPath();
                                        if(j == chart.config.data.datasets[i].data.length - 1) ctx.arc(arc.round.radius * Math.sin(startAngle), arc.round.radius * Math.cos(startAngle), arc.round.thickness, 0, 2 * Math.PI);
                                        if(j != 1) ctx.arc(arc.round.radius * Math.sin(endAngle), arc.round.radius * Math.cos(endAngle), arc.round.thickness, 0, 2 * Math.PI);
                                        ctx.closePath();
                                        ctx.fill();
                                        ctx.restore();
                                    }
                                }
                        },
                    }]
                };

                $("#" + element).html("")

                var canvas = document.createElement('canvas')

                $("#" + element).html(canvas)

                var ctx = canvas.getContext("2d")

                // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
                var myChart = new Chart(ctx, config);
            }

            function loadEstimateRevenue(){
                var filter = $("#filter").val()
                $.ajax({
                    url: "{{ route('crm.index') }}",
                    type: "get",
                    dataType: "json",
                    data: {
                        a: "revenue",
                        pipeline_id : $("#pipeline_id").val(),
                        filter: filter,
                    }
                }).then(function(resp){
                    $("#revenue").text(resp.revenue)
                    var funnelChartLabel = []
                    var funnelChartAmount = []
                    var funnelChartText = []
                    var funnel = resp.funnels
                    for (let index = 0; index < funnel.length; index++) {
                        const element = funnel[index];
                        var opportunity = element['opportunity']
                        funnelChartLabel.push(element['label'] + "(" + opportunity.length + ")")
                        var col = {}
                        col['value'] = 6-index
                        col['amount'] = element['revenue']
                        var million = element['revenue'] / 1000000
                        funnelChartAmount.push(6-index)
                        funnelChartText.push($.number(million.toFixed(0), 0, ",", "."))
                    }

                    console.log(funnelChartLabel)
                    console.log(funnelChartAmount)
                    console.log(funnelChartText)

                    var funnel_colors = ["#7340E5", "#AA8CEF", "#D4C4F7", "#E1D7FA", "#f3effd", "#f3effd", "#E45D6A"];

                    // funnel start
                    var gd = document.getElementById('funnel-chart');
                    $(gd).html("")
                    var data = [{
                        type: 'funnel',
                        marker: {
                            color: funnel_colors,
                            cauto: true,
                            cmin: 0,
                            cmid: 3,
                            cmax: 5,
                            colorbar : {
                                tickcolor : "#AA8CEF"
                            },
                            showscale : true,
                        },
                        connector : {
                            fillcolor: "#AA8CEF",
                            visible : true
                        },
                        legendwidth : 100,
                        text : funnelChartText,
                        texttemplate : "%{text} M",
                        orientation: "v",
                        x: funnelChartLabel,
                        y: funnelChartAmount,
                        hoverinfo: 'x',
                        xperiodalignment : "start",
                        textangle : 0
                    }];

                    console.log(data)

                    var layout = {
                        margin: {
                            l: 0
                        },
                        autosize: true,
                        width: $("#funnel-chart").parents(".card-body").width(),
                        height: 400,
                    }

                    Plotly.newPlot('funnel-chart', data, layout, {responsive: true});
                    // funnel end
                })
            }

            function loadDashboard() {
                var team = $("#team").val()
                var filter = $("#filter").val()

                $.ajax({
                    url: "{{ route('crm.index') }}",
                    type: "get",
                    dataType: "json",
                    data: {
                        team: team,
                        filter: filter,
                        a: "load"
                    },
                }).then(function(resp){
                    console.log(resp)
                    table_company.clear().draw()
                    table_company.rows.add(resp.top_comp)
                    table_company.columns.adjust().draw();

                    table_product.clear().draw()
                    table_product.rows.add(resp.top_prod)
                    table_product.columns.adjust().draw();

                    $("#total-opportunity").text(resp.total_opportunity)
                    $("#opportunity-win").text(resp.total_opportunity == 0 ? "0%" : ((resp.opportunity_win / resp.total_opportunity) * 100).toFixed(0) + "%")
                    $("#opportunity-lose").text(resp.total_opportunity == 0 ? "0%" : ((resp.opportunity_lose / resp.total_opportunity) * 100).toFixed(0) + "%")
                    $("#conversion-rate").text(resp.total_opportunity == 0 ? "0%" : ((resp.sales_cycle.length / resp.total_opportunity) * 100).toFixed(0) + "%")
                    $("#sales-cyle-day").text(resp.sales_cycle.length)

                    $("#pipeline_id option").remove()

                    var newOption = new Option("test", 1, false, false);

                    var pl = resp.pipelines ?? []
                    for (const key in pl) {
                        const element = pl[key];
                        var newOption = new Option(element, key, false, false);
                        $("#pipeline_id").append(newOption)
                    }

                    // winrate start
                    var options = {
                        series: [{
                            data: resp.winrate.data
                        }],
                        chart: {
                            type: 'bar',
                            height: 350
                        },
                        colors: ["#7340E5"],
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        xaxis: {
                            categories: resp.winrate.label,
                            labels: {
                                formatter: function(value, timestamp, opts) {
                                    return value + "%"
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(value, index) {
                                    return value
                                },
                                style: {
                                    cssClass: "fw-bold"
                                }
                            },
                        }
                    };

                    $("#sales-win-chart").html("")

                    var chart = new ApexCharts(document.querySelector("#sales-win-chart"), options);
                    chart.render();
                    // winrate end

                    console.log(resp.stacked)
                    console.log(resp.winrate)

                    // sales cycle start
                    var options = {
                        series: resp.stacked.y,
                        chart: {
                            type: 'bar',
                            height: 350,
                            stacked: true,
                        },
                        colors: resp.stacked.color,
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                dataLabels: {
                                    enabled : false
                                }
                            },
                        },
                        dataLabels : {
                            formatter: function(val) {
                                return val - 1
                            }
                        },
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: resp.stacked.x,
                            labels: {
                                formatter: function(val) {
                                    return val
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: undefined
                            },
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return val - 1
                                }
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        legend: {
                            position: 'bottom',
                            horizontalAlign: 'center',
                            offsetX: 40
                        }
                    };

                    $("#sales-cycle-chart").html("")

                    var chart = new ApexCharts(document.querySelector("#sales-cycle-chart"), options);
                    chart.render();
                    // sales cycle end

                    barChart("#opportunity-worth-chart", resp.opChart, resp.opWorth)
                    barChart("#opportunity-won-chart", resp.opChart, resp.opWin)
                    barChart("#opportunity-lose-chart", resp.opChart, resp.opLose)

                    loadEstimateRevenue()

                    // opp-perf-chart start

                    doughnutChart("opp-perf-chart", resp.opportunity_perf_chart.labels, resp.opportunity_perf_chart.data)
                })
            }

            $(document).ready(function() {
                loadDashboard()
            });
        </script>
    @endsection
