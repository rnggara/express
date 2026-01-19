@extends('_personel.layout')

@section('view_content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Formal Letter</h3>
            <div class="card-toolbar gap-3">
                <button type="button" data-bs-toggle="modal" data-bs-target="#modalFields" class="btn btn-info btn-sm">
                    <i class="fi fi-rr-settings"></i>
                    Fields
                </button>
                <a href="{{ route('personel.fl.add') }}" class="btn btn-primary btn-sm">
                    <i class="fi fi-rr-add"></i>
                    Add Template
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-display-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Template Name</th>
                        <th>Issued By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($template as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $user_name[$item->created_by] ?? "-" }}</td>
                            <td>
                                <a href="{{ route('personel.fl.add')."?tid=$item->id" }}" class="btn btn-sm btn-icon btn-primary"><i class="fa fa-edit"></i></a>
                                <a href="{{ route("personel.fl.delete", $item->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm btn-icon"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalFields" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Fields</h1>
                    <button class="btn btn-icon" data-bs-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <h3>Add Fields</h3>
                            <hr>
                            <div class="fv-row">
                                <h3 class="col-form-label">Field Name</h3>
                                <div class="">
                                    <input type="text" class="form-control" name="f_name">
                                </div>
                            </div>
                            <div class="fv-row">
                                <h3 class="col-form-label">Field Description</h3>
                                <div class="">
                                    <input type="text" class="form-control" name="desc">
                                </div>
                            </div>
                            <div class="fv-row" id="selFt">
                                <h3 class="col-form-label">Field Type</h3>
                                <div class="">
                                    <select name="f_type" class="form-control" data-control="select2" data-placeholder="Select Field Type" data-dropdown-parent="#selFt">
                                        <option value="text">Text</option>
                                        <option value="int">Number</option>
                                        <option value="currency">Currency</option>
                                        <option value="time">Time</option>
                                        <option value="date">Date</option>
                                    </select>
                                </div>
                            </div>
                            <div class="fv-row">
                                <h3 class="col-form-label">Field Length</h3>
                                <div class="">
                                    <input type="number" min="1" step=".01" class="form-control" name="f_length">
                                </div>
                            </div>
                            <div class="fv-row" id="selEData">
                                <h3 class="col-form-label">Employee Data</h3>
                                <div class="">
                                    <select name="emp_field" class="form-control" data-control="select2" data-dropdown-parent="#selEData">
                                        <option value="">Free Text</option>
                                        @foreach ($columns as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="fv-row">
                                <h3 class="col-form-label"></h3>
                                <div class=" text-right">
                                    <button type="button" id="btn-add-fields" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add</button>
                                </div>
                            </div>

                            <hr>
                        </div>
                        <div class="col-12" id="tbModal">
                            <table class="table tabl-bordered table-hover" id="table-fields">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Field Name</th>
                                        <th class="text-center">Field Description</th>
                                        <th class="text-center">Field Type</th>
                                        <th class="text-center">Field Length</th>
                                        <th class="text-center">Employee Data</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
    <script>
        var t = $("#table-fields").DataTable({
            ajax : {
                url : "{{ route('personel.fl.ajaxField') }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    type : "table",
                }
            },
            columnDefs : [
                {"targets" : "_all", "className" : "text-center"}
            ],
            fnDrawCallback : function(){
                $("select[data-control=select2]").select2()
            }
        })

        function editEntry(id){
            $.ajax({
                url : "{{ route('personel.fl.ajaxField') }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    type : "entry_edit",
                    id : id
                }
            }).then(function(resp){
                $("#modalEditEntry [data-content]").html(resp.view)
                $("#modalEditEntry").modal("show")
            })
        }

        function _delete(x){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url : "{{ route('personel.fl.ajaxField') }}",
                        type : "post",
                        dataType : "json",
                        data : {
                            _token : "{{ csrf_token() }}",
                            id : x,
                            type : "delete"
                        },
                        success : function(response){
                            if(response.success){
                                // reload datatable
                                t.ajax.reload()
                            } else {
                                Swal.fire("Error", "Please contact your Sys Admin", "error")
                            }
                        }
                    })
                }
            });
        }

        function editField(me){
            var id = $(me).data("id")
            var tr = $(me).parents("tr").eq(0)
            var ll = $(tr).find("input[name=f_length]").val()
            var efiled = $(tr).find("select[name=emp_field]").val()

            $.ajax({
                url : "{{ route('personel.fl.ajaxField') }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    f_length : ll,
                    f_name : $(tr).find("input[name=f_name]").val(),
                    desc : $(tr).find("input[name=desc]").val(),
                    emp_field : efiled,
                    id : id,
                    type : "edit",
                },
                success : function(response){
                    if(response.success){
                        // reload datatable
                        Swal.fire("Success", "Data has been updated", "success")
                        t.ajax.reload()
                    } else {
                        Swal.fire("Error", "Please contact your Sys Admin", "error")
                    }
                }
            })
        }

        $(document).ready(function(){
            $("#btn-add-fields").click(function(){
                var _i = $(this).html()
                var _name = $("input[name=f_name]").val()
                if(_name == ""){
                    return Swal.fire('Required', "Field Name is Required", "warning")
                }

                var _length = $("input[name=f_length]").val()
                if(_length == ""){
                    return Swal.fire('Required', "Field Length is Required", "warning")
                }
                $.ajax({
                    url : "{{ route('personel.fl.ajaxField') }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        f_name : $("#modalFields input[name=f_name]").val(),
                        f_type : $("#modalFields select[name=f_type]").val(),
                        f_length : $("#modalFields input[name=f_length]").val(),
                        desc : $("#modalFields input[name=desc]").val(),
                        emp_field : $("#modalFields select[name=emp_field]").val(),
                        type : "add",
                    },
                    beforeSend : function(){
                        $("#btn-add-fields").prop('disabled', true).text("Loading...").addClass('spinner spinner-left')
                    },
                    success : function(response){
                        $("#btn-add-fields").prop('disabled', false).html(_i).removeClass('spinner spinner-left')
                        if(response.success){
                            // reload datatable
                            t.ajax.reload()
                        } else {
                            Swal.fire("Error", "Please contact your Sys Admin", "error")
                        }
                    }
                })
            })
        })
    </script>
@endsection
