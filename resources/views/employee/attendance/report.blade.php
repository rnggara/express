@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Attendance Report</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('emp.mt.index') }}" class="btn btn-primary btn-icon">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <label for="" class="col-form-label me-3">Period</label>
                        <div class="me-3">
                            <select name="month" data-control="select2" class="form-select" id="">
                                <option value=""></option>
                                @foreach ($idFullMonth as $i => $item)
                                    <option value="{{ $i }}" {{ date("n") == $i ? "SELECTED" : "" }} >{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="me-3">
                            <select name="year" data-control="select2" class="form-select" id="">
                                <option value=""></option>
                                @for ($i = $yearmin5; $i < $yearplus5; $i++)
                                    <option value="{{$i}}" {{ $i == $year ? "SELECTED" : "" }} >{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" onclick="searchTable()">
                                <i class="fi fi-rr-search"></i>
                                Search
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" onclick="exportExcel()">
                        <i class="fi fi-rr-file-excel"></i>
                        Export
                    </button>
                </div>
            </div>
            <hr>
            <div class="row mt-5">
                <div class="col-12 text-center mb-3">
                    <span class="d-none fs-3">Menampilkan data : <span id="lbl-periode"></span></span>
                </div>
                <div class="col-12">
                    <table class="table display">
                        <thead>
                            <tr>
                                <th class="col-export">#</th>
                                <th class="col-export">NIK</th>
                                <th class="col-export">Nama</th>
                                <th class="col-export">Departement</th>
                                <th class="col-export">Hadir</th>
                                <th class="col-export">Total Jam Hadir</th>
                                <th class="col-export">Terlambat</th>
                                <th class="col-export">Total Jam Terlambat</th>
                                <th class="col-export">Mangkir / Tidak absen</th>
                                <th class="col-export">Remark</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    <script>

        var table_report = $("table.display").DataTable({
            ajax : {
                url : "{{ route("emp.mt.report") }}?a=table",
                type : "get",
                dataType : "json",
                data: function(d){
                    d.month = $("select[name=month]").val();
                    d.year = $("select[name=year]").val();
                }
            },
            buttons : [
                {
                    extend: 'excelHtml5',
                    title: '{{ ucwords("Attendance Timesheet") }}',
                }
            ],
            processing : true,
            columns : [
                {"data" : "no"},
                {"data" : "nik"},
                {"data" : "nama"},
                {"data" : "departement"},
                {"data" : "hadir"},
                {"data" : "total_hadir"},
                {"data" : "telat"},
                {"data" : "total_telat"},
                {"data" : "mangkir"},
                {"data" : "remark"},
            ],
            "destroy" : true,
            fnDrawCallback : function(setting, json){
                var api = this.api()
                try {
                    var json = api.ajax.json()
                    $("#lbl-periode").text(json.periode)
                    $("#lbl-periode").parent().removeClass("d-none")
                } catch (error) {

                }
            }
        })

        function exportExcel(){
            table_report.button(0).trigger()
        }

        function searchTable(){
            table_report.ajax.reload()
        }

        $(document).ready(function(){

        })
    </script>
@endsection
