@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Attendance Report - {{ $template->name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route("attendance.report") }}" class="btn btn-icon btn-sm btn-success">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <h3>Report Date</h3>
                    <div class="fv-row">
                        <label for="" class="col-form-label">From</label>
                        <input type="text" name="from" id="from" class="form-control tempusDominus" value="{{ date("d/m/Y H:i:s", strtotime($template->from_date)) }}">
                    </div>
                    <div class="fv-row mb-5">
                        <label for="" class="col-form-label">To</label>
                        <input type="text" name="to" id="to" class="form-control tempusDominus" value="{{ date("d/m/Y H:i:s", strtotime($template->to_date)) }}">
                    </div>
                    <div class="fv-row">
                        <button type="button" id="btn-search" class="btn btn-primary">Search</button>
                    </div>
                </div>
                <div class="col-12">
                    <div class="fv-row mb-10 d-flex justify-content-end">
                        <label for="" class="col-form-label me-3">Export : Delimited with </label>
                        <div class="d-flex">
                            <input type="text" class="form-control mw-100px me-5" value="," id="separator">
                            <button type="button" data-toggle="export" data-index="0" class="me-5 btn btn-success">
                                <i class="fa fa-file-excel"></i>
                                Excel
                            </button>
                            <button type="button" data-toggle="export" data-index="1" class="me-5 btn btn-light-success">
                                <i class="fa fa-file-csv"></i>
                                CSV
                            </button>
                            <button type="button" data-toggle="export" data-index="2" class="me-5 btn btn-primary">
                                <i class="fa fa-file-text"></i>
                                Text
                            </button>
                        </div>
                    </div>
                    <table class="table display" id="table-result">
                        <thead>
                            <tr>
                                @foreach ($columns as $item)
                                    <th>{{ ucwords(str_replace("_", " ", $item['column'])) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    <script>

        function export_txt(data, header, ext) {
            var a = document.createElement("a");

            var txt = ""

            var head = []

            for (let i = 0; i < header.length; i++) {
                const element = header[i];
                var th = $(element).text()
                head.push(th.replaceAll(" ", "_").toLowerCase())
                txt += th
                if(i < header.length - 1){
                    txt += $("#separator").val()
                }
            }

            txt += "\n"

            if(data.length > 0){
                data.forEach(element => {
                    var json = []
                    for (let i = 0; i < head.length; i++) {
                        const key = head[i];
                        const el = element[key]
                        json.push(el)
                    }
                    txt += json.join($("#separator").val())
                    txt += "\n"
                });

                var blob = new Blob([txt], {type: "octet/stream"}),
                    url = window.URL.createObjectURL(blob);
                a.href = url;
                a.download = "{{ str_replace("_", " ", $template->name) }}_attendance." + ext;
                a.click();
                window.URL.revokeObjectURL(url);
            }
        }

        $(document).ready(function(){
            var from = $("#from").val()
            var to = $("#to").val()
            var table = $("#table-result").DataTable({
                ajax : {
                    url : `{{ route('attendance.report.result', $template->id) }}?a=table&from=${from}&to=${to}&show=0`,
                    type : "get",
                    dataType : "json"
                },
                columns : [
                    @foreach($columns as $cols)
                    {"data" : "{{ $cols['column'] }}"},
                    @endforeach
                ],
                buttons: [
                    {
                        extend : 'excelHtml5',
                        title : "{{ str_replace("_", " ", $template->name) }}_attendance"
                    },
                    {
                        text: 'csv',
                        action: function ( e, dt, node, config ) {
                            var _data = dt.rows().data().toArray()
                            var _header = dt.columns().header().toArray()
                            export_txt(_data, _header, "csv")
                        }
                    },
                    {
                        text: 'Text',
                        action: function ( e, dt, node, config ) {
                            var _data = dt.rows().data().toArray()
                            var _header = dt.columns().header().toArray()
                            export_txt(_data, _header, "txt")
                        }
                    }
                ]
            })

            $("#btn-search").click(function(){
                var from = $("#from").val()
                var to = $("#to").val()
                table.ajax.url(`{{ route('attendance.report.result', $template->id) }}?a=table&from=${from}&to=${to}&show=1`).load()
            })

            $("button[data-toggle=export]").click(function(){
                var index = $(this).data("index")
                table.button(index).trigger()
            })

            $(".tempusDominus").each(function(){
                var _id = $(this).attr("id")
                var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                    display : {
                        viewMode: "calendar",
                        components: {
                            decades: true,
                            year: true,
                            month: true,
                            date: true,
                            hours: true,
                            minutes: true,
                            seconds: true
                        }
                    },
                    localization: {
                        locale: "id",
                        startOfTheWeek: 1,
                        format: "dd/MM/yyyy HH:mm:ss"
                    }
                });
            })
        })
    </script>
@endsection
