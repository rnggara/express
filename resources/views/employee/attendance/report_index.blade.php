@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Attendance Report</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('attendance.report.add') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                        New Template
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Report Template</th>
                                <th>Date & Created By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list as $i => $item)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>
                                        <a href="{{ route("attendance.report.result", $item->id) }}">{{ $item->name }}</a>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span>@dateId($item->created_at)</span>
                                            <span>{{ $cby[$item->created_by] ?? "-" }}</span>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <a href="{{ route("attendance.report.edit", $item->id) }}" class="btn btn-outline btn-outline-dark btn-icon btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('attendance.report.delete', $item->id) }}" onclick="return confirm('Delete?')" class="btn btn-outline btn-outline-danger btn-icon btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>
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
    <script>

        function btn_direction(){
            $(".btn-directions").hide()

            $("button.btn-up").each(function(index){
                var _length = $("button.btn-up").length
                if(index > 0){
                    $(this).show()
                }
            })

            $("button.btn-down").each(function(index){
                var _length = $("button.btn-down").length
                console.log(_length)
                if(index < _length && _length > 1 && index < _length - 1){
                    $(this).show()
                }
            })
        }

        function change_order(btn, direction){
            var tr = $(btn).closest("tr")
            console.log(tr, direction)
            if(direction == "next"){
                var target = $(tr).next()
                tr.insertAfter(target)
            } else {
                var target = $(tr).prev()
                tr.insertBefore(target)
            }

            btn_direction()
        }

        function remove_row(btn){
            var tr = $(btn).closest("tr")
            tr.remove()

            btn_direction()
        }

        function add_row(){
            $.ajax({
                url : "{{ route("attendance.report") }}?a=row",
                type : "get",
            }).then(function(resp){
                $("#table-kol tbody").append(resp)
                $("select[data-control=select2]").select2()
                $("select[name='columns[]']").change(function(){
                    var opt = $(this).find("option:selected")
                    var tr = $(this).parents("tr")
                    console.log(opt)
                    $(tr).find(".format-columns input").attr("name", "formats[]")
                    $(tr).find(".format-columns select").attr("name", "formats[]")
                    $(tr).find(".format-columns").hide()
                    var _format = $(opt).data('format')
                    $(tr).find(".format-"+_format).show()
                    if(_format == "text"){
                        $(tr).find(".format-"+_format).find("input").attr('name', "format_selected[]")
                    } else {
                        $(tr).find(".format-"+_format).find("select").attr('name', "format_selected[]")
                    }
                })

                btn_direction()
            })
        }

        $(document).ready(function(){
            $("table.display").DataTable({
                dom : "ftip"
            })
        })
    </script>
@endsection
