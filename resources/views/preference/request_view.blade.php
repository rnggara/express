@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">PE/SE Expiration</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered  w-100">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Modul</th>
                                <th class="text-center">Approval</th>
                                <th class="text-center">Yellow Label (days) || On/Off</th>
                                <th class="text-center">Red Label (days) || On/Off</th>
                                <th class="text-center">Auto Reject (days) || On/Off</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($row as $key => $item)
                                <tr>
                                    <td style="vertical-align: middle" align="center" rowspan="{{ count($item) }}">{{ $i++ }}</td>
                                    <td style="vertical-align: middle" align="center" rowspan="{{ count($item) }}">{{ $key }}</td>
                                    <td style="vertical-align: middle" align="center">{{ $item[0]->approval }}</td>
                                    <td style="vertical-align: middle" align="center">
                                        <div class="d-flex justify-content-center">
                                            <span class="col-form-label px-5">{{ $item[0]->yellow }}</span>
                                            <span class="switch switch-sm switch-outline switch-icon switch-success">
                                                <label>
                                                <input type="checkbox" {{ ($item[0]->yellow_status == 1) ? "checked" : "" }} disabled name="select"/>
                                                <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle" align="center">
                                        <div class="d-flex justify-content-center">
                                            <span class="col-form-label px-5">{{ $item[0]->red ?? "-" }}</span>
                                            <span class="switch switch-sm switch-outline switch-icon switch-success">
                                                <label>
                                                <input type="checkbox" {{ ($item[0]->red_status == 1) ? "checked" : "" }} disabled name="select"/>
                                                <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle" align="center">
                                        <div class="d-flex justify-content-center">
                                            <span class="col-form-label px-5">{{ $item[0]->reject ?? "-" }}</span>
                                            <span class="switch switch-sm switch-outline switch-icon switch-success">
                                                <label>
                                                <input type="checkbox" {{ ($item[0]->reject_status == 1) ? "checked" : "" }} disabled name="select"/>
                                                <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle" align="center">
                                        <button type="button" data-toggle="modal" data-target="#modalEdit" onclick="_modal_edit('{{ $item[0]->modul }}', '{{ $item[0]->approval }}')" class="btn btn-sm btn-primary">Edit</button>
                                    </td>
                                </tr>
                                @for($j = 1; $j < count($item); $j++)
                                    <tr>
                                        <td style="vertical-align: middle" align="center">{{ $item[$j]->approval }}</td>
                                        <td style="vertical-align: middle" align="center">
                                            <div class="d-flex justify-content-center">
                                                <span class="col-form-label px-5">{{ $item[$j]->yellow }}</span>
                                                <span class="switch switch-sm switch-outline switch-icon switch-success">
                                                    <label>
                                                    <input type="checkbox" {{ ($item[$j]->yellow_status == 1) ? "checked" : "" }} disabled name="select"/>
                                                    <span></span>
                                                    </label>
                                                </span>
                                            </div></td>
                                        <td style="vertical-align: middle" align="center">
                                            <div class="d-flex justify-content-center">
                                                <span class="col-form-label px-5">{{ $item[$j]->red ?? "-" }}</span>
                                                <span class="switch switch-sm switch-outline switch-icon switch-success">
                                                    <label>
                                                    <input type="checkbox" {{ ($item[$j]->red_status == 1) ? "checked" : "" }} disabled name="select"/>
                                                    <span></span>
                                                    </label>
                                                </span>
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle" align="center">
                                            <div class="d-flex justify-content-center">
                                                <span class="col-form-label px-5">{{ $item[$j]->reject ?? "-" }}</span>
                                                <span class="switch switch-sm switch-outline switch-icon switch-success">
                                                    <label>
                                                    <input type="checkbox" {{ ($item[$j]->reject_status == 1) ? "checked" : "" }} disabled name="select"/>
                                                    <span></span>
                                                    </label>
                                                </span>
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle" align="center">
                                            <button type="button" data-toggle="modal" data-target="#modalEdit" onclick="_modal_edit('{{ $item[$j]->modul }}', '{{ $item[$j]->approval }}')" class="btn btn-sm btn-primary">Edit</button>
                                        </td>
                                    </tr>
                                @endfor
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEdit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Edit</h1>
                    <button class="close" type="button" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
                <form action="{{ route('pref.req.post') }}" method="post">
                    <div class="modal-body">
                        <div id="edit-div"></div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        function _modal_edit(mod, app){
            $("#edit-div").html("")
            $.ajax({
                url : "{{ route('pref.req.index') }}?a=edit&mod="+mod+"&app="+app,
                type : "get",
                success : function(response){
                    $("#edit-div").html(response)
                }
            })
        }

        $(document).ready(function(){
            $("table.dtable").DataTable()
        })
    </script>
@endsection
