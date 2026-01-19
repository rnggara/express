@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Client List</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-outline btn-outline-success d-none" data-bs-toggle="modal" data-bs-target="#modalExport">
                    <i class="fa fa-file-excel"></i>
                    Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table display" id="table-applicant">
                <thead>
                    <tr>
                        <th class="text-nowrap">#</th>
                        <th class="text-nowrap">Nama Client</th>
                        {{-- <th class="text-nowrap">Lokasi</th>
                        <th class="text-nowrap">Posisi</th>
                        <th class="text-nowrap">Pendidikan</th>
                        <th class="text-center text-nowrap">Pengalaman</th>
                        <th class="text-center text-nowrap">Total Applied</th> --}}
                        <th class="text-center text-nowrap">Tangga Registrasi</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $i => $item)
                        @php
                            $jb = $job_applied->where("user_id", $item->id)->first();
                        @endphp
                        <tr>
                            <td>
                                {{$i+1}}
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div>
                                        <a href="{{route('bp.applicants.detail', $item->id)}}" class="fw-bold text-dark" data-bs-toggle="tooltip" data-bs-html="true"
                                            title="<div class='d-flex flex-column'><span>{{ $item->name }}</span><span class='fw-bold'>{{ $item->email }}</span><span>{{ $item->profile->phone ?? "-" }}</span></div>">{{ $item->name }}</a>
                                    </div>
                                    <span class="">{{ $item->email }}</span>
                                </div>
                            </td>
                            {{-- <td>{{ $province[$item->profile->prov_id ?? null] ?? "-" }}</td>
                            <td>{{ $job_name[$jb->job_id ?? null] ?? "-" }}</td>
                            <td>
                                {{ $item->edu ?? "-" }}
                            </td>
                            <td class="text-center">
                                {{ $item->yoe }} Tahun
                            </td>
                            <td class="text-center">{{ $item->applied->count() }}</td> --}}
                            <td class="text-center">@dateId($item->created_at)</td>
                            <td>
                                @if(empty($item->email_verified_at))
                                    <span class="badge badge-secondary">Unverified</span>
                                @else
                                    <span class="badge badge-{{ $item->suspended == 1 ? "danger" : "success" }}">{{ $item->suspended == 1 ? "Suspended" : "Active" }}</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 10px">
                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                </button>
                                <!--begin::Menu-->
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="{{ route('bp.applicants.status', $item->id) }}" class="menu-link px-3">
                                            {{ $item->suspended == 1 ? "Activate this account" : "Suspend this account" }}
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu-->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalExport">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('job_report.export.excel') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-3 mb-5">Pilih kolom yang akan di export</span>
                        <table class="table table-row-bordered">
                            <thead>
                                <tr>
                                    <th>Kolom</th>
                                    <th>Tampil</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($columnExport as $key => $item)
                                    @if ($key == "test_result")
                                        @foreach ($item as $test_id)
                                            <tr>
                                                <td>{{ $test_name[$test_id] }}</td>
                                                <td>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="column[{{ $key }}][{{ $test_id }}]" checked value="1" id="flexCheckDefault" />
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td>{{ $item }}</td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="column[{{ $key }}]" checked value="1" id="flexCheckDefault" />
                                                    <label class="form-check-label" for="flexCheckDefault">
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="type" value="bp">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Export</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            var table_job = $("#table-applicant").DataTable({
            dom : `<"d-flex flex-column align-items-baseline"<"d-flex align-items-center justify-content-between justify-content-start"f>>t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">l><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"ip>>`,
            colReorder: true,
            columDefs : [
                {"targets" : 0, "orderable": false}
            ],
            order: [],
            initComplete: function () {

                var _selDataTable = $(".dataTables_length").find("select")
                _selDataTable.addClass("border-0 bg-secondary")
                _selDataTable.removeClass("form-select-solid")
                // _selDataTable.parent().addClass("border-bottom border-dark")
                var _filterDataTable = $(".dataTables_filter")
                _filterDataTable.find("input[type=search]").removeClass("form-control-solid")
                var _filterLabel = _filterDataTable.find("label")
                _filterLabel.each(function(){
                    var id = $(this).parents(".dataTables_filter").attr("id")
                    var id_split = id.split("_")
                    var id_split2 = id_split[0].split("-")
                    var _html = $(this).html()
                    var _exp = _html.split(":")
                    var input = $(this).find("input")
                    var _input = $(input).addClass("ps-10")
                    var el = '<i class="fs-3 fa fa-search ms-4 position-absolute text-gray-500 top-50 translate-middle-y"></i>'
                    _input.attr("placeholder", "Search ")
                    $(this).contents().filter(function(){ return this.nodeType != 1; }).remove();
                    $(el).insertBefore(input)
                    $(this).addClass("d-lg-block mb-5 mb-md-0 mb-lg-0 position-relative w-100")
                })

                var _selParent = $(".dataTables_filter").parent()
                _selParent.append("<button class='btn d-none d-md-inline  btn-secondary btn-sm ms-5'><i class='la la-filter fs-2'></i> Filter</button>")

                var _selParentDiv = $(_selParent).parent()
                var _filterColumn = "<div class='d-none d-md-flex align-items-center w-100' id='filter-by-column'></div>"
                _selParentDiv.append(_filterColumn)
                _selParentDiv.addClass("mb-5")

                var columns = this.api().columns()

                this.api()
                    .columns()
                    .every(function (index) {
                        let column = this;

                        if(index > 1 && index < columns[0].length - 1){
                            var head = column.header()
                            // Create select element
                            let select = document.createElement('select');
                            select.add(new Option(''));
                            $(select).attr("data-control", "select2")
                            $(select).attr("data-placeholder", $(head).text())
                            $(select).attr("data-allow-clear", true)
                            $(select).addClass("form-select")
                            // $(select).addClass("")
                            var div = document.createElement("div")
                            if(index > 2 && index < columns[0].length - 2){
                                $(div).addClass('d-none d-md-flex col mx-2')
                            }
                            $(div).addClass('d-none d-md-flex col')
                            let tgl = document.createElement("input")
                            $(tgl).addClass("form-control")
                            $(tgl).attr("placeholder", $(head).text())
                            if(index == 7){
                                $(div).html(tgl)
                            } else {
                                $(div).html(select)
                            }
                            $("#filter-by-column").append(div)
                            // column.footer().replaceChildren(select);

                            // Apply listener for user change in value
                            if(index != 7){
                                $(select).change(function () {
                                    var val = DataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                                // Add list of options
                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function (d, j) {
                                        var v = d
                                        if(index == columns[0].length - 2){
                                            v = $(d).text()
                                        }
                                        select.add(new Option(v));
                                    });
                            } else {
                                $(tgl).keyup(function () {
                                    var val = DataTable.util.escapeRegex($(this).val());

                                    column
                                        .search($(this).val(), true, false)
                                        .draw();
                                });
                            }
                        }
                    });

                $("select[data-control=select2]").select2()
            }
        })
        })
    </script>
@endsection
