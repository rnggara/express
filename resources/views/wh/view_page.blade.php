@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Location Detail
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('wh.index') }}" class="btn btn-sm btn-icon btn-success ml-3"><i
                            class="fa fa-arrow-left"></i></a>
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column">
                <table class="table table-borderless mb-8">
                    <tr>
                        <th>Location Name</th>
                        <th>:</th>
                        <th>{{ $wh->name }}</th>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <th>:</th>
                        <th>{{ $wh->address }}</th>
                    </tr>
                    <tr>
                        <th>Latitude</th>
                        <th>:</th>
                        <th>{{ $wh->latitude }}</th>
                    </tr>
                    <tr>
                        <th>Longitude</th>
                        <th>:</th>
                        <th>{{ $wh->longitude }}</th>
                    </tr>
                    <tr>
                        <th>Radius</th>
                        <th>:</th>
                        <th>{{ $wh->longitude2 }} m</th>
                    </tr>
                </table>
                <div class="separator separator-solid"></div>
                <table class="table display" id="table-list">
                    <thead>
                        <tr>
                            <th style="width: 10%">#</th>
                            <th>Employee Name</th>
                            <th class="text-center" style="width: 10%">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#addItem"
                                    class="btn btn-icon btn-sm btn-primary">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($list_emp as $i => $item)
                            @isset($emp_name[$item->emp_id])
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $emp_name[$item->emp_id] }}</td>
                                    <td align="center">
                                        <a href="{{ route('wh.delete_user', $item->id) }}"
                                            onclick="return confirm('Delete from list?')"
                                            class="btn btn-icon btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endisset
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                </div>
                <form method="post" action="{{ route('wh.add_user') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="fv-row">
                            <label class="col-form-label">Employee</label>
                            <select name="emp_id[]" multiple class="form-control" data-control="select2" id=""
                                data-placeholder="Select Employees">
                                <option value=""></option>
                                @foreach ($emp as $item)
                                    <option value="{{ $item->id }}">{{ $item->emp_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table display" id="table-modal">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%" class="text-center">
                                                <div class="form-check form-check-primary">
                                                    <input class="form-check-input" type="checkbox" value="" id="check_all" />
                                                <label class="form-check-label" for="check_all">
                                                </div>
                                            </th>
                                            <th>#</th>
                                            <th>Employee Name</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($emp as $i => $item)
                                            <tr>
                                                <td class="text-center" style="width : 10%">
                                                    <div class="form-check form-check-primary">
                                                        <input class="form-check-input" type="checkbox" data-id="{{ $item->id }}" name="emp_id_sel[]" value="{{ $item->id }}" id="flexCheckChecked{{ $item->id }}" />
                                                    <label class="form-check-label" for="flexCheckChecked{{ $item->id }}">
                                                    </div>
                                                </td>
                                                <td>{{ $i + 1 }}</td>
                                                <td>{{ $item->emp_name }}</td>
                                                <td>{{ $item->id }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="wh_id" value="{{ $wh->id }}">
                        <input type="hidden" name="emp_id_table">
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit" id="btn-add-user" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            $("#table-list").DataTable({
                dom: "ft<'row'<'col-md-6 col-sm-12'i><'col-md-6 col-sm-12'p>>",
                columnDefs: [{
                    targets: [2],
                    orderable: false,
                    sorting: false,
                }],
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                }
            })

            var table_modal = $("#table-modal").DataTable({
                dom: "ft<'row'<'col-md-6 col-sm-12'i><'col-md-6 col-sm-12'p>>",
                columnDefs: [{
                    orderable: false,
                    // className: 'select-checkbox',
                    targets: 0
                }, {
                    targets: 3,
                    visible: false
                }],
                order: [[1, 'asc']],
                select: {
                    style:    'multi',
                    selector: 'td:first-child'
                },
                drawCallback: function(){
                    $('.paginate_button:not(.disabled)', this.api().table().container()).on('click', function(){
                        $("#table-modal tbody tr").each(function(){
                            if($(this).hasClass("selected")){
                                $(this).find(".form-check-input").prop('checked', true)
                            } else {
                                $(this).find(".form-check-input").prop('checked', false)
                            }
                        })

                        $("#table-modal tbody tr td:first-child").click(function(){
                            var tr = $(this).parents("tr")
                            if($(tr).hasClass("selected")){
                                $(this).find(".form-check-input").prop('checked', false)
                            } else {
                                $(this).find(".form-check-input").prop('checked', true)
                            }
                        })
                    });
                }
            })

            $("#table-modal tbody tr td:first-child").click(function(){
                var tr = $(this).parents("tr")
                if($(tr).hasClass("selected")){
                    $(this).find(".form-check-input").prop('checked', false)
                } else {
                    $(this).find(".form-check-input").prop('checked', true)
                }
            })

            $("#table-modal thead input[type=checkbox]").click(function(){
                var checked = this.checked
                if(checked){
                    table_modal.rows().select()
                    $("#table-modal tbody").find(".form-check-input").prop('checked', true)
                } else {
                    table_modal.rows().deselect()
                    $("#table-modal tbody").find(".form-check-input").prop('checked', false)
                }
            })

            $("#btn-add-user").click(function(e){
                var emp_id_sel = table_modal.rows(".selected")[0]
                console.log(emp_id_sel)
                var selected = []
                for (let i = 0; i < emp_id_sel.length; i++) {
                    var _i = emp_id_sel[i]
                    const element = table_modal.row(_i).data();
                    var dom_nodes = $($.parseHTML(element[0]));
                    var ck = $(dom_nodes).find(".form-check-input")
                    selected.push(ck.val())

                }
                $("input[name=emp_id_table]").val(JSON.stringify(selected))
            })
        })
    </script>
@endsection
