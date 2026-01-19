@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Personal Attendance Report</h3>
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
                <div class="d-flex align-items-center mb-3">
                    <label for="" class="col-form-label me-3">Personnel</label>
                    <div class="me-3">
                        <select name="user" data-control="select2" class="form-select" data-placeholder="Select Personnel" id="">
                            <option value=""></option>
                            @foreach ($users as $i => $item)
                                <option value="{{ $item->id }}" >{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
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
                    <button type="button" class="btn btn-success" id="btn-export">
                        <i class="fi fi-rr-file-excel"></i>
                        Export
                    </button>
                </div>
            </div>
            <hr>
            <div class="row mt-5" id="div-result">

            </div>
        </div>
    </div>

@endsection

@section('custom_script')
    <script>

        // var table_report = $("table.display").DataTable({
        //     ajax : {
        //         url : "{{ route("emp.mt.report") }}?a=table",
        //         type : "get",
        //         dataType : "json",
        //         data: function(d){
        //             d.month = $("select[name=month]").val();
        //             d.year = $("select[name=year]").val();
        //         }
        //     },
        //     buttons : [
        //         {
        //             extend: 'excelHtml5',
        //             title: '{{ ucwords("Attendance Timesheet") }}',
        //         }
        //     ],
        //     processing : true,
        //     columns : [
        //         {"data" : "no"},
        //         {"data" : "nik"},
        //         {"data" : "nama"},
        //         {"data" : "hadir"},
        //         {"data" : "total_hadir"},
        //         {"data" : "telat"},
        //         {"data" : "total_telat"},
        //         {"data" : "mangkir"},
        //         {"data" : "remark"},
        //     ],
        //     "destroy" : true,
        //     fnDrawCallback : function(setting, json){
        //         var api = this.api()
        //         try {
        //             var json = api.ajax.json()
        //             $("#lbl-periode").text(json.periode)
        //             $("#lbl-periode").parent().removeClass("d-none")
        //         } catch (error) {

        //         }
        //     }
        // })

        function exportExcel(){
            // table_report.button(0).trigger()
        }

        function searchTable(){
            var user = $("select[name=user]").val();
            if(user == ""){
                return Swal.fire("User required", "Please select user", 'info')
            } else {
                $.ajax({
                    url : "{{ route("emp.mt.report_personal") }}?a=table",
                    type : "get",
                    data: {
                        month : $("select[name=month]").val(),
                        year : $("select[name=year]").val(),
                        user : $("select[name=user]").val()
                    }
                }).then(function(resp){
                    $("#div-result").html(resp)

                    var table = $("#div-result table.display").DataTable({
                        buttons : [
                            {
                                extend: 'excelHtml5',
                                title: '{{ ucwords("Personal Attendance Report") }}',
                            }
                        ],
                        paging : false,
                        ordering : false,
                        searching : false,
                        bInfo : false
                    })

                    $("#btn-export").click(function(){
                        table.button(0).trigger()
                    })
                })
            }
        }

        $(document).ready(function(){

        })
    </script>
@endsection
