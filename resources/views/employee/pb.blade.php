@extends('layouts.template')
@section('content')
    <!--begin::Subheader-->
    <!--end::Subheader-->
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Employee
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">

                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <button type="button" onclick="getData(0)" class="btn btn-light-info font-weight-bold type btn-type" id="type0" name="type" style="width: 190px" value="0">
                All
            </button>
            <button type="button" onclick="getData(-1)" class="btn btn-light-info font-weight-bold type btn-type" id="typebank" name="type" style="width: 190px" value="-1">
                Expeled
            </button>
            <br><br>
            <div class="row">
            @foreach($emptypes as $key => $value)
                <div class="col-md-1 col-sm-12">
                    <button type="button" class="btn btn-block btn-light-info font-weight-bold type btn-type" id="type{{$value->id}}" name="type" value="{{$value->id}}">
                        {{$value->name}}
                    </button>
                </div>
            @endforeach
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                    <a href="{{ route('employee.report.view') }}" class="btn btn-light-info font-weight-bold">
                        Report
                    </a>
                </div>
            </div>

        </div>
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover display font-size-sm data_emp" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th nowrap="nowrap" style="width: 25%">Name</th>
                        <th nowrap="nowrap" class="text-center">Type</th>
                        <th nowrap="nowrap" class="text-center">ID</th>
                        <th nowrap="nowrap" class="text-center">Level</th>
                        <th nowrap="nowrap" class="text-center">Division</th>
                        <th nowrap="nowrap" class="text-center">Performance Bonus</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEdit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" id="modal-content-fld">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1>Edit Performance Bonus</h1>
                        <button type="button" class="close" data-dismiss="modal">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <form action="{{ route('employee.pb.post') }}" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="pb" class="col-form-label">Performance Bonus</label>
                                <input type="text" name="pb" id="pb" class="form-control number" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="emp_id" id="emp-id">
                            @csrf
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script src="{{asset('theme/assets/js/signature_pad.js')}}"></script>
    <script>

        @if (\Session::has("career"))
            Swal.fire("Employee Update", "{{ \Session::get("career") }}", "success")
        @endif

        function edit_pb(id, pb){
            $("#pb").val(pb)
            $("#emp-id").val(id)
            $("#modalEdit").modal("show")
        }

        $(document).ready(function () {

            $(".number").number(true)

            @if (\Session::has('link'))
                @if (\Session::get('link') == "error")
                    Swal.fire("Error", "", "error")
                @else
                    Swal.fire("{{ \Session::get('link') }}", "Share the link above to the Employee for signing", "success")
                @endif
            @endif

            var _table = $("table.data_emp").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                pageLength: 100,
                @actionStart('employee', 'read')
                ajax : {
                    url: "{{route('employee.getdata_post')}}?type=0",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                    },
                    error: function (xhr, error, thrown) {
                        // Swal.fire("Page need to be reload", "Please reload this page", "error")
                    }
                },
                columns : [
                    { "data": "no" },
                    { "data": "emp_name" },
                    { "data": "emp_type" },
                    { "data": "emp_id" },
                    { "data": "emp_position" },
                    { "data": "division" },
                    { "data": "pb" },
                ],
                columnDefs: [
                    {
                        "targets": [1],
                        "className": "text-left",
                    },
                    {
                        "targets": "_all",
                        "className": "text-center",
                    }
                ],
                createdRow: function (row, data, index) {
                    var is_expel = $(row).find(".is-expel")
                    if(is_expel.length > 0){
                        $(row).addClass("table-warning")
                    }

                    var freeze = $(row).find(".unfreeze")
                    if(freeze.length > 0){
                        $(row).addClass("table-primary")
                    }
                }
                @actionEnd
            })

            $(".btn-type").click(function(){
                _table.clear().draw()
                _table.ajax.url("{{route('employee.getdata_post')}}?type="+$(this).val()).load()
            })

            $("select.select2").select2({
                width : "100%"
            })
        });
    </script>
@endsection
