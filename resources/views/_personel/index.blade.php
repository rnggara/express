@extends('_personel.layout', ["bgWrapper" => ""])

@section('view_content')
    <div class="d-flex flex-column gap-8">
        <div class="row gx-8 gy-0">
            <div class="col-9">
                <div class="card card-stretch bg-transparent shadow-none">
                    <div class="card-body p-0">
                        <div class="d-flex flex-column gap-8">
                            <div class="row gx-8 gy-0">
                                <div class="col-4">
                                    <div class="card card-stretch shadow-none bg-primary">
                                        <div class="card-header px-5 border-0">
                                            <div class="card-title">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="symbol symbol-30px">
                                                        <div class="symbol-label bg-light-warning">
                                                            <i class="fi fi-rr-users fs-3 text-warning"></i>
                                                        </div>
                                                    </div>
                                                    <span class="fw-bold text-white">Total Pegawai</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-5 py-0">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="fs-3x text-white fw-bold">{{ $data['total_employee'] }}</span>
                                                <div class="badge {{ $data['total_employee_pctg'] > 0 ? "badge-outline badge-success" : ($data['total_employee_pctg'] == 0 ? "badge-secondary" : "badge-outline badge-danger") }}">
                                                    @if ($data['total_employee_pctg'] > 0)
                                                        <i class="fi fi-sr-arrow-trend-up"></i>
                                                    @elseif($data['total_employee_pctg'] < 0)
                                                        <i class="fi fi-sr-arrow-trend-down"></i>
                                                    @endif
                                                    <span>{{ number_format($data['total_employee_pctg'], 2) }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card card-stretch shadow-none">
                                        <div class="card-header px-5 border-0">
                                            <div class="card-title">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">Pegawai Baru</span>
                                                    <span class="fs-base text-muted">(Bulan ini)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body px-5 py-0">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="fs-3x fw-bold">{{ $data['new_employee'] }}</span>
                                                <div class="badge {{ $data['new_employee_pctg'] > 0 ? "badge-outline badge-success" : ($data['new_employee_pctg'] == 0 ? "badge-secondary" : "badge-outline badge-danger") }}">
                                                    @if ($data['new_employee_pctg'] > 0)
                                                        <i class="fi fi-sr-arrow-trend-up"></i>
                                                    @elseif($data['new_employee_pctg'] < 0)
                                                        <i class="fi fi-sr-arrow-trend-down"></i>
                                                    @endif
                                                    <span>{{ number_format($data['new_employee_pctg'], 2) }}%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card card-stretch shadow-none">
                                        <div class="card-header px-5 border-0">
                                            <div class="card-title">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold">Pergantian Pegawai</span>
                                                    <span class="fs-base text-muted">(Bulan ini)</span>
                                                </div>
                                            </div>
                                            <div class="card-toolbar">
                                                <a href="javascript:;" onclick="showModal('turnover')" class="d-flex align-items-center gap-3"><span>Laporan</span> <i class="fi fi-rr-angle-right mt-1"></i></a>
                                            </div>
                                        </div>
                                        <div class="card-body px-5 py-0">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="fs-3x fw-bold">0</span>
                                                <div class="badge badge-secondary">0%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-8 gy-0">
                                <div class="col-8">
                                    <div class="card card-stretch shadow-none">
                                        <div class="card-body px-5">
                                            <div class="d-flex flex-column gap-5">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-bold">Kepegawaian</span>
                                                        <span class="fs-base text-muted">(Pegawai)</span>
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-3">
                                                        <select name="etloc" class="form-select min-w-200px" onchange="loadEmpTrendChart()" data-control="select2" data-allow-clear="true" data-placeholder="Semua Lokasi" id="">
                                                            <option value=""></option>
                                                            @foreach ($master['locations'] as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <select name="etdep" class="form-select min-w-200px" onchange="loadEmpTrendChart()" data-control="select2" data-allow-clear="true" data-placeholder="Semua Departemen" id="">
                                                            <option value=""></option>
                                                            @foreach ($master['departements'] as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <select name="etyear" class="form-select" onchange="loadEmpTrendChart()" data-control="select2" data-placeholder="{{ date("Y") }}" id="">
                                                            @for ($i = date("Y") - 5; $i < (date("Y") + 5) ; $i++)
                                                                <option value="{{ $i }}" {{ $i == date("Y") ? "SELECTED" : "" }}>{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="emp_trend_chart" class="cursor-pointer" onclick="showModal('employement')" style="height: 350px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card card-stretch shadow-none">
                                        <div class="card-body px-5">
                                            <div class="d-flex flex-column gap-5 h-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span class="fw-bold">Tingkat retensi</span>
                                                        <span class="fs-base text-muted">(Year to Date)</span>
                                                    </div>
                                                </div>
                                                <div class="flex-fill d-flex flex-column align-items-center {{ $retensi == 0 ? "justify-content-center" : "justify-content-between" }}">
                                                    @if ($retensi == 0)
                                                        <i class="fi fi-rr-document text-muted fs-3x"></i>
                                                        <span class="text-muted">Saat ini tidak ada data yang tersedia</span>
                                                    @elseif($retensi < 70)
                                                        <div class="d-flex align-items-center justify-content-center gap-5 mt-10">
                                                            <span class="text-danger fs-4tx">{{ number_format($retensi, 0) }}%</span>
                                                            <div class="badge {{ $rcptg < 0 ? "badge-outline badge-danger" : "badge-outline badge-success" }}">
                                                                <i class="fi fi-sr-arrow-trend-{{ $rcptg < 0 ? "down" : "up" }}"></i>
                                                                <span>{{ number_format($rcptg, 0) }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="rounded p-5 bg-secondary-crm w-100 d-flex flex-column gap-3">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div class="symbol symbol-40px">
                                                                    <div class="symbol-label bg-danger">
                                                                        <i class="fi fi-sr-sad text-white"></i>
                                                                    </div>
                                                                </div>
                                                                <span class="fw-bold">Perlu Adanya perubahan, Mari Mulai!</span>
                                                            </div>
                                                            <p>Saatnya menuju perubahan positif!, Mari kita perbaiki semua ini dan tingkatkan komitmen bersama, mencapai kebahagiaan dan kesuksesan di tempat kerja!</p>
                                                        </div>
                                                    @elseif($retensi < 80 && $retensi >= 70)
                                                        <div class="d-flex align-items-center justify-content-center gap-5 mt-10">
                                                            <span class="text-warning fs-4tx">{{ number_format($retensi, 0) }}%</span>
                                                            <div class="badge {{ $rcptg < 0 ? "badge-outline badge-danger" : "badge-outline badge-success" }}">
                                                                <i class="fi fi-sr-arrow-trend-{{ $rcptg < 0 ? "down" : "up" }}"></i>
                                                                <span>{{ number_format($rcptg, 0) }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="rounded p-5 bg-secondary-crm w-100 d-flex flex-column gap-3">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div class="symbol symbol-40px">
                                                                    <div class="symbol-label bg-warning">
                                                                        <i class="fi fi-sr-smile text-white"></i>
                                                                    </div>
                                                                </div>
                                                                <span class="fw-bold">Jaga Semangat, Tingkatkan Kepuasan!</span>
                                                            </div>
                                                            <p>Kita sedang menuju perubahan yang positif! Inovasi dan inisiatif baru akan membawa suasana baru di tempat kerja. Mari Kita tingkatkan semuanya!</p>
                                                        </div>
                                                    @elseif($retensi >= 80)
                                                        <div class="d-flex align-items-center justify-content-center gap-5 mt-10">
                                                            <span class="text-success fs-4tx">{{ number_format($retensi, 0) }}%</span>
                                                            <div class="badge {{ $rcptg < 0 ? "badge-outline badge-danger" : "badge-outline badge-success" }}">
                                                                <i class="fi fi-sr-arrow-trend-{{ $rcptg < 0 ? "down" : "up" }}"></i>
                                                                <span>{{ number_format($rcptg, 0) }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="rounded p-5 bg-secondary-crm w-100 d-flex flex-column gap-3">
                                                            <div class="d-flex align-items-center gap-3">
                                                                <div class="symbol symbol-40px">
                                                                    <div class="symbol-label bg-success">
                                                                        <i class="fi fi-sr-face-smile-hearts text-white fs-3"></i>
                                                                    </div>
                                                                </div>
                                                                <span class="fw-bold">Linkungan Kerja Istimewa!</span>
                                                            </div>
                                                            <p>Perusahaan kita mempertahankan tingkat retensi tinggi! Di lingkungan yang semangat, kita saling mendukung. Mari terus jaga semangat kolaboratif ini</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card card-stretch shadow-none">
                    <div class="card-body px-5">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex align-items-center scroll-x gap-5" data-emp-detail>
                                @foreach ($master['ecat'] as $i => $item)
                                    <button type="button" onclick="edeclik(this)" data-key="{{ $i }}" class="btn {{ $i == "onboarding" ? "active" : "" }} btn-active-primary border text-nowrap rounded-4">{{ ucwords(str_replace("_", " ", $item)) }}</button>
                                @endforeach
                            </div>
                            <div id="edet-content" class="flex-fill"></div>
                            <div class="d-flex justify-content-end">
                                <a href="javascript:;" onclick="showModal('list')" class="d-flex align-items-center gap-3"><span>Lihat Semua</span> <i class="fi fi-rr-angle-right mt-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-8 gy-0">
            <div class="col-4">
                <div class="card card-stretch shadow-none">
                    <div class="card-body px-5">
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">Ration Pegawai</span>
                                </div>
                                <div class="d-flex justify-content-end gap-3">
                                    <div class="d-flex justify-content-between">
                                        <select name="redep" onchange="loadRatioChart('ratio_employee_chart')" class="form-select min-w-200px" data-control="select2" data-allow-clear="true" data-placeholder="Semua Departemen" id="">
                                            <option value=""></option>
                                            @foreach ($master['departements'] as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="mx-2"></div>
                                        <select name="retype" onchange="loadRatioChart('ratio_employee_chart')" class="form-select" data-control="select2" id="">
                                            <option value="education">Tingkat Pendidikan</option>
                                            <option value="level">Tingkat Pegawai</option>
                                            <option value="division">Divisi</option>
                                            <option value="gender">Jenis Kelamin</option>
                                            <option value="location">Lokasi</option>
                                            <option value="contract">Tipe Kontrak</option>
                                            {{-- <option value="location">Location</option> --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="ratio_employee_chart" class="cursor-pointer" onclick="showModal('ratio')" style="height: 350px; width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card card-stretch shadow-none">
                    <div class="card-body px-5">
                        <div class="d-flex flex-column h-100 gap-5">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">Masa Jabatan</span>
                                </div>
                                <div class="d-flex justify-content-end gap-3">
                                    <select name="tesdep" onchange="loadTimeService()" class="form-select min-w-200px" data-control="select2" data-allow-clear="true" data-placeholder="Pilih Departemen" id="">
                                        <option value=""></option>
                                        @foreach ($master['departements'] as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="time_service_chart" style="height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card card-stretch shadow-none">
                    <div class="card-body px-5">
                        <div class="d-flex flex-column gap-5 h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">Ulang Tahun Pegawai</span>
                                    {{-- <span class="fs-base text-muted">Data Ulang Tahun Pegawai</span> --}}
                                </div>
                            </div>
                            <div class="flex-fill scroll-y mh-300px">
                                @if ($emp_birth->count() == 0)
                                    <div class="d-flex justify-content-center">
                                        <span>Tidak ada data tersedia</span>
                                    </div>
                                @else
                                    <div class="d-flex flex-column gap-3">
                                        @foreach ($emp_birth as $item)
                                            <div class="d-flex align-items-center gap-3 rounded bg-secondary-crm p-3">
                                                <div class="symbol symbol-40px">
                                                    <div class="symbol-label" style="background-image: url('{{ asset($user_img[$item->id] ?? 'images/image_placeholder.png') }}')"></div>
                                                </div>
                                                <div class="flex-fill justify-content-between d-flex">
                                                    <div class="d-flex flex-column gap-1">
                                                        <span class="text-muted">{{ date("d F", strtotime($item->emp_lahir)) }} {{ date("Y") }}</span>
                                                        <span class="fw-bold">{{ $item->emp_name }}</span>
                                                        <span>{{ $item->position->name ?? "-" }}</span>
                                                    </div>
                                                    <div class="d-flex flex-column gap-1 justify-content-center">
                                                        <button type="button" class="btn btn-icon btn-sm btn-primary">
                                                            <i class="fi fi-sr-hands-clapping"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="javascript:;" onclick="showModal('birthday')" class="d-flex align-items-center gap-3"><span>Lihat Semua</span> <i class="fi fi-rr-angle-right mt-1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalView">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen p-10 w-100">
            <div class="modal-content px-10 rounded">

            </div>
        </div>
    </div>
@endsection

@section('view_script')
    <script>

        function showModal(type){
            $.ajax({
                url : "{{ route("personel.index") }}?a=modal&t=" + type,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                $("#modalView div.modal-content").html(resp.view)
                $("#modalView").modal("show")
                $("#modalView table.table-display-2").each(function(){
                    initTable($(this))
                })

                $("#modalView [data-control=select2]").select2()

                if(type == "turnover"){
                    loadTOQuartalChart()
                    loadTOTenureRange()
                    loadToPctg("departement", "#turnover_dep_avg_chart")
                    loadToPctg("level", "#turnover_level_avg_chart")
                    loadToType()
                    $("#modalView select[name=turnover_type]").change(function(){
                        var v = $(this).val()
                        $("#modalView [data-type]").addClass("d-none")
                        $("#modalView [data-type='"+v+"']").removeClass("d-none")
                    })
                }

                if(type == "employement"){
                    var cht = verticalChart([], "#emp_trend_chart_modal")
                    var data = {
                        loc : $("#modalView select[name=etloc]").val(),
                        dep : $("#modalView select[name=etdep]").val(),
                        year : $("#modalView select[name=etyear]").val(),
                    }
                    empModalChart(data).then(function(resp){
                        cht.updateSeries(resp.chart)
                    })

                    $("#modalView select[name=etloc], #modalView select[name=etdep], #modalView select[name=etyear]").change(function(){
                        var ndata = {
                            loc : $("#modalView select[name=etloc]").val(),
                            dep : $("#modalView select[name=etdep]").val(),
                            year : $("#modalView select[name=etyear]").val(),
                        }
                        empModalChart(ndata).then(function(resp){
                            cht.updateSeries(resp.chart)
                        })
                    })

                }

                if(type == "ratio"){
                    loadRatioChart('ratio_employee_chart_modal', false, $("#modalView select[name=retype]").val(), true)
                    $("#modalView select[name=retype]").change(function(){
                        loadRatioChart('ratio_employee_chart_modal', false, $(this).val(), true)
                    })
                }
            })
        }

        function ratioContent(){
            $.ajax({
                url : "{{ route("personel.index") }}?a=modal&t=ratio_content",
                type : "get",
                dataType : "json",
            }).then(function(){

            })
        }

        function empModalChart(data){
            return $.ajax({
                url : "{{ route("personel.index") }}?a=chart&t=employee_trend",
                type : "get",
                data : data,
                dataType : "json"
            })
        }

        $(document).ready(function(){
            loadEmpTrendChart()
            loadTimeService()
            eDetail()
            loadRatioChart('ratio_employee_chart')
        })

        function edeclik(me){
            var b = $(me).parents("div[data-emp-detail]")
            $(b).find("button").removeClass("active")
            $(me).addClass('active')
            eDetail()
        }

        function eDetail(){
            var b = $("div[data-emp-detail]")
            var s = $(b).find("button.active")
            var key = $(s).data("key")
            $.ajax({
                url : "{{ route("personel.index") }}?a=list&t=det",
                data : {
                    key : key
                },
                dataType : "json",
                type : "get"
            }).then(function(resp){
                $("#edet-content").html(resp.view)
            })
        }

        function loadRatioChart(target, legend = null, tp = null, modal = false){
            var data = {
                dep : $("select[name=redep]").val(),
                type : tp ?? $("select[name=retype]").val(),
                modal : modal
            }
            var lg = legend

            var m = $("#"+target).parents("div.modal")

            $.ajax({
                url : "{{ route("personel.index") }}?a=chart&t=ratio_employee",
                type : "get",
                data : data,
                dataType : "json"
            }).then(function(resp){
                doughnutChart(target, resp.labels, resp.x, legend, null, resp.colors)
                if(modal){
                    $(m).find('[data-content="tab"]').html(resp.content.tab)
                    $(m).find('[data-content="table"]').html(resp.content.table)
                    $(m).find('[data-content="table"]').find("select[data-control=select2]").select2()
                    var tb = initTable($("#table-ratio"))
                    $(m).find('[data-content="table"]').find("select[name=div]").change(function(){
                        tb.column(2).search($(this).val()).draw()
                    })

                    var f = $(m).find('[data-content="tab"] [data-filter].active').eq(0)
                    var ff = $(f).find("span[data-text]").text()
                    if(ff != undefined){
                        tb.column(3).search(ff).draw()
                    }

                    $(m).find('[data-content="tab"] [data-filter]').click(function(){
                        var t = $(this).find("span[data-text]")
                        $(m).find('[data-content="tab"] [data-filter]').removeClass("active")
                        $(m).find('[data-content="tab"] [data-filter] span[data-text]').removeClass("active")

                        $(this).addClass("active")
                        $(t).addClass("active")

                        tb.column(3).search($(t).text()).draw()
                    })
                }
            })
        }

        function loadTOQuartalChart(){
            $.ajax({
                url : "{{ route("personel.index") }}?a=chart&t=turnover_per_quartal",
                type : "get",
                dataType : "json"
            }).then(function(resp){
                verticalChart(resp.chart, "#turnover_quartal_chart")
            })
        }

        function loadTOTenureRange(){
            $.ajax({
                url : "{{ route("personel.index") }}?a=chart&t=time_service",
                type : "get",
                dataType : "json"
            }).then(function(resp){
                horizontalChart(resp.data, "#turnover_tenure_chart", resp.label)
            })
        }

        function loadToPctg(type, target){
            $.ajax({
                url : "{{ route("personel.index") }}?a=chart&t=turnover_pctg",
                type : "get",
                data : {
                    type : type
                },
                dataType : "json"
            }).then(function(resp){
                horizontalChart(resp.data, target, resp.label)
            })
        }

        function loadToType(){
            $.ajax({
                url : "{{ route("personel.index") }}?a=chart&t=turnover_type",
                type : "get",
                dataType : "json"
            }).then(function(resp){
                doughnutChart("turnover_type_chart", resp.labels, resp.x, "bottom", true, resp.colors)
            })
        }

        var empChart = verticalChart([], "#emp_trend_chart")

        function loadEmpTrendChart(){
            var data = {
                loc : $("select[name=etloc]").val(),
                dep : $("select[name=etdep]").val(),
                year : $("select[name=etyear]").val(),
            }
            $.ajax({
                url : "{{ route("personel.index") }}?a=chart&t=employee_trend",
                type : "get",
                data : data,
                dataType : "json"
            }).then(function(resp){
                empChart.updateSeries(resp.chart)
            })
        }

        var tmChart = horizontalChart([], "#time_service_chart", "")

        function loadTimeService(){
            // $("#time_service_chart").html("")
            var data = {
                dep : $("select[name=tesdep]").val(),
            }
            $.ajax({
                url : "{{ route("personel.index") }}?a=chart&t=time_service",
                type : "get",
                data : data,
                dataType : "json"
            }).then(function(resp){
                tmChart.updateSeries([{
                    data : resp.data
                }])
                tmChart.updateOptions({
                    xaxis: {
                        categories : resp.label
                    },
                })
            })
        }

        function doughnutChart1(element, labels, x, lPosition = null){
            $("#"+element).html("")
            // Chart data
            var bgColor = ["#DD3545", "#FFC119", "#FEB3B2", "#28A845", "#502AA5", "#5837FF"]
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
                            position : "top",
                            onClick : null,
                            fullSize : true,
                            display : false,
                            labels : {
                                generateLabels: (chart) => {
                                    const datasets = chart.data.datasets;
                                    return datasets[0].data.map((data, i) => ({
                                        text: `${chart.data.labels[i]}`,
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
                    aspectRatio : 1
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

            var canvas = document.createElement('canvas')

            $("#" + element).html(canvas)

            var ctx = canvas.getContext("2d")

            // Init ChartJS -- for more info, please visit: https://www.chartjs.org/docs/latest/
            var myChart = new Chart(ctx, config);
        }

        function horizontalChart(data, element, lbl){
            var h = $(element).height()
            // $(element).html("")
            var options = {
                series: [{
                    data: data
                }],
                chart: {
                    type: 'bar',
                    height: h
                },
                colors: ["#5837FF"],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: true,
                },
                grid : {
                    show : false
                },
                xaxis: {
                    categories : lbl
                },
            };

            var el = document.querySelector(element)
            console.log(element)
            if(!el){
                return;
            }

            var chart = new ApexCharts(document.querySelector(element), options);
            chart.render();

            return chart;
        }

        function verticalChart(data, element){
            var h = $(element).height()
            var options = {
                series: data,
                chart: {
                    type: 'bar',
                    height: h
                },
                colors: ["#5837FF", "#FEB3B2"],
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                    }
                },
                dataLabels: {
                    enabled: true,
                    // formatter: lbl
                },
                grid : {
                    show : false
                },
                yaxis: {
                    type: 'numeric'
                },
                fill: {
                    type: "fill",
                }
            };

            // $(element).html("")

            var el = document.querySelector(element)
            console.log(element)
            if(!el){
                return;
            }

            var chart = new ApexCharts(document.querySelector(element), options);
            chart.render();

            return chart;
        }

        function doughnutChart(element, labels, x, lPosition = null, lgShow = null, bgColor = ["#DD3545", "#FFC119", "#FEB3B2", "#28A845", "#502AA5", "#5837FF"]){
            // Chart data
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
                            // display : null,
                            reverse : true,
                            position : lPosition ?? "right",
                            onClick : null,
                            labels : {
                                generateLabels: (chart) => {
                                    const datasets = chart.data.datasets;
                                    return datasets[0].data.map((data, i) => ({
                                        text: `${chart.data.labels[i]}(${data})`,
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
                                    var startAngle = Math.PI / 2 - arc.startAngle;
                                    var endAngle = Math.PI / 2 - arc.endAngle;

                                    ctx.save();
                                    ctx.translate(arc.round.x, arc.round.y);
                                    ctx.fillStyle = arc.round.backgroundColor;
                                    ctx.beginPath();
                                    ctx.arc(arc.round.radius * Math.sin(startAngle), arc.round.radius * Math.cos(startAngle), arc.round.thickness, 0, 2 * Math.PI);
                                    // if(j != 1) ctx.arc(arc.round.radius * Math.sin(endAngle), arc.round.radius * Math.cos(endAngle), arc.round.thickness, 0, 2 * Math.PI);
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
    </script>
@endsection
