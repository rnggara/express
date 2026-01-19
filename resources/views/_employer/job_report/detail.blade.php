@extends('layouts.template')

@section('content')
    <div class="d-flex flex-column">
        <div class="card mb-8">
            <div class="card-header border-0">
                <h3 class="card-title">{{ $job->position }}</h3>
                <div class="card-toolbar">
                    <a href="{{route("job_report.index")}}">
                        <i class="fa fa-arrow-left text-primary"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-md-center flex-column flex-md-row justify-content-between mb-8">
                        <div class="d-flex align-items-center button-selected">
                            <button type="button" disabled onclick="actionModal(2)" class="btn btn-outline w-50 w-md-auto btn-outline-primary me-3">
                                <i class="fa fa-video"></i>
                                Interview
                            </button>
                            <button type="button" disabled onclick="actionModal(-1)" class="btn btn-outline w-50 w-md-auto btn-outline-danger">
                                <i class="fa fa-ban"></i>
                                Reject
                            </button>
                        </div>
                        <button type="button" class="btn btn-outline btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalExport">
                            <i class="fa fa-file-excel"></i>
                            Export
                        </button>
                    </div>
                    <div class="overflow-auto">
                        <table class="table rounded table-row-bordered" id="table-job">
                            <thead>
                                <tr class="bg-transparent">
                                    <th class="text-nowrap">
                                        <div class="form-check form-check-custo form-check-sm">
                                            <input class="form-check-input" type="checkbox" value="" id="selectAll"/>
                                            <label class="form-check-label text-dark" for="selectAll">

                                            </label>
                                        </div>
                                    </th>
                                    <th class="text-nowrap">Nama Kandidat</th>
                                    <th class="text-nowrap">Lokasi</th>
                                    <th class="text-nowrap">Posisi</th>
                                    <th class="text-nowrap">Pendidikan</th>
                                    <th class="text-center text-nowrap">Pengalaman</th>
                                    <th class="text-center text-nowrap">Total Applied</th>
                                    <th class="text-center text-nowrap"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applicant as $item)
                                    @php
                                        $us = $users->where("id", $item->user_id)->first();
                                    @endphp
                                    @if (!empty($us))
                                    <tr>
                                        <td>
                                            <div class="form-check {{ $item->status == 1 ? "" : "form-check-solid" }}">
                                                <input class="form-check-input" data-job="{{"$item->job_id"}}" {{ $item->status == 1 ? "" : "disabled" }} type="checkbox" value="{{$item->id}}" id="ck{{ $item->id }}" />
                                                <label class="form-check-label" for="ck{{ $item->id }}">
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span href="" class="fw-bold">{{ $us->name }}</span>
                                                <span class="">{{ $us->email }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $province[$us->profile->prov_id ?? null] ?? "-" }}</td>
                                        <td>{{ $job->position }}</td>
                                        <td>
                                            {{ $us->edu }}
                                        </td>
                                        <td class="text-center">
                                            {{ $us->yoe }} Tahun
                                        </td>
                                        <td class="text-center">{{ $us->applied->count() }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 10px">
                                                <i class="fa fa-ellipsis-vertical text-dark"></i>
                                            </button>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="{{ route("job_report.detail_applicant", $item->id)."?a=applicant" }}" class="menu-link px-3">
                                                        Lihat Detail Applicant
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                        {{-- <td>
                                            @if ($item->status == 0)
                                                <span class="badge badge-blue">Lamar</span>
                                            @elseif($item->status == 1)
                                                <span class="badge badge-outline badge-warning">Dipilih</span>
                                            @elseif($item->status == 2)
                                                <span class="badge badge-outline badge-warning">Interview</span>
                                            @elseif($item->status == 3)
                                                <span class="badge badge-outline badge-warning">Review</span>
                                            @elseif($item->status == -1)
                                                <span class="badge badge-red">Reject</span>
                                            @endif
                                        </td> --}}
                                        {{-- <td>
                                            {{ date("Y/m/d", strtotime($item->created_at)) }}
                                        </td> --}}
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalInterview">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('job_report.backlog') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-2tx mb-3">Jadwalkan Interview</span>
                            <div class="alert alert-custom mb-3">
                                <span class="alert-text alert-selected"></span>
                            </div>
                            <div class="alert alert-custom alert-danger mb-5 d-flex align-items-center">
                                <i class="fa fa-information text-danger me-3"></i>
                                <span class="alert-text">Silahkan pilih jenis interview yang ingin anda lakukan, anda dapat mengundang interview seacra perorangan maupun secara grup</span>
                            </div>
                            <span class="fw-bold mb-5">Silahkan pilih jenis interview yang ingin anda lakukan</span>
                            <div class="d-flex flex-column flex-md-row align-items-center justify-content-around">
                                <!--begin::Col-->
                                <div class="fv-row">
                                    <!--begin::Google link=-->
                                    <label for="role1" class="btn btn-outline mb-5 mb-md-0 active w-200px h-200px role d-flex flex-column btn-outline-primary border-active-primary border-secondary text-dark btn-active-light-primary text-hover-primary flex-center ">
                                        <i class="fs-1 fa fa-user text-primary mb-3"></i>
                                        <span class="text-active-primary active">Perorangan</span>
                                    </label>
                                    <input type="radio" name="interview" checked value="1" class="btn-check role" autocomplete="off" id="role1">
                                    <!--end::Google link=-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="fv-row">
                                    <!--begin::Google link=-->
                                    <label for="role2" class="btn btn-outline w-200px h-200px role d-flex flex-column btn-outline-primary border-active-primary border border-secondary text-dark btn-active-light-primary text-hover-primary flex-center ">
                                        <i class="fs-1 fa fa-users text-primary mb-3"></i>
                                        <span class="text-active-primary">Group</span>
                                    </label>
                                    <input type="radio" name="interview" value="2" class="btn-check role" autocomplete="off" id="role2">
                                    <!--end::Google link=-->
                                </div>
                                <!--end::Col-->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="list_id">
                        <input type="hidden" name="id_job" value="{{$job->id}}">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Selanjutnya</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalInterviewReject">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('job_report.reject_applicant') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column">
                            <span class="fw-bold fs-2tx mb-3">Reject Interview</span>
                            <div class="alert alert-custom mb-3">
                                <span class="alert-text alert-selected"></span>
                            </div>
                            <div class="alert alert-custom alert-danger mb-5 d-flex align-items-center">
                                <i class="fa fa-information text-danger me-3"></i>
                                <span class="alert-text">Apakah anda yakin akan mereject kandidat terpilih?</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="list_id">
                        <input type="hidden" name="id_job" value="{{$job->id}}">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Selanjutnya</a>
                    </div>
                </form>
            </div>
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
                        <input type="hidden" name="type" value="job">
                        <input type="hidden" name="id" value="{{ $job->id }}">
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

        var table_job = $("#table-job").DataTable({
            dom : `<"d-flex flex-column align-items-baseline"<"d-flex align-items-center justify-content-between justify-content-start"f>>t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">l><"col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"ip>>`,
            colReorder: true,
            columDefs : [
                {"targets" : 0, "orderable": false}
            ],
            order: [],
            select: {
                style:    'multi',
                selector: 'td:first-child'
            },
            drawCallback: function(){
                var tb = this.api()
                $('.paginate_button:not(.disabled)', tb.table().container()).on('click', function(){
                    $("#table-job tbody tr").each(function(){
                        if($(this).hasClass("selected")){
                            $(this).find(".form-check-input").prop('checked', true)
                        } else {
                            $(this).find(".form-check-input").prop('checked', false)
                        }
                    })

                    $("#table-job tbody tr td:first-child").click(function(){
                        var tr = $(this).parents("tr")
                        var isDisabled =  $(this).find(".form-check-input").is(":disabled")
                        if(!isDisabled){
                            if($(tr).hasClass("selected")){
                                $(this).find(".form-check-input").prop('checked', false)
                            } else {
                                $(this).find(".form-check-input").prop('checked', true)
                            }
                        }
                        checkSelected()
                    })
                });

                tb.on( 'select', function ( e, dt, type, indexes ) {
                    if ( type === 'row' ) {
                        var tr = tb.rows( indexes ).data()[0][0];
                        var isDisabled =  $(tr).find(".form-check-input").is(":disabled")
                        if(isDisabled){
                            tb.row(indexes).deselect()
                        } else {
                            $(tr).find(".form-check-input").prop('checked', true)
                        }
                    }
                    checkSelected()
                } );

                tb.on( 'deselect', function ( e, dt, type, indexes ) {
                    if ( type === 'row' ) {
                        var tr = tb.rows( indexes ).data()[0][0];
                        var isDisabled =  $(tr).find(".form-check-input").is(":disabled")
                        $(tr).find(".form-check-input").prop('checked', false)
                    }
                    checkSelected()
                } );
            },
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
                console.log(_selParent)
                _selParent.append("<button class='btn d-none d-md-inline  btn-secondary btn-sm ms-5'><i class='la la-filter fs-2'></i> Filter</button>")

                var _selParentDiv = $(_selParent).parent()
                var _filterColumn = "<div class='d-flex d-none d-md-flex align-items-center w-100 justify-content-between' id='filter-by-column'></div>"
                _selParentDiv.append(_filterColumn)
                _selParentDiv.addClass("mb-5")

                var columns = this.api().columns()

                this.api()
                    .columns()
                    .every(function (index) {
                        let column = this;

                        if(index > 1 && index < columns[0].length -1){
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
                            $(div).addClass('d-none d-md-flex min-w-lg-300px mni-w-md-150px')
                            $(div).html(select)
                            $("#filter-by-column").append(div)
                            // column.footer().replaceChildren(select);

                            // Apply listener for user change in value
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
                                    select.add(new Option(d));
                                });
                        }
                    });
            }
        })

        function getSelected(){
            var emp_id_sel = table_job.rows(".selected")[0]
            var selected = []
            for (let i = 0; i < emp_id_sel.length; i++) {
                var _i = emp_id_sel[i]
                const element = table_job.row(_i).data();
                var dom_nodes = $($.parseHTML(element[0]));
                var ck = $(dom_nodes).find(".form-check-input")
                selected.push(ck.val())
            }

            return selected
        }

        function checkSelected(){
            var buttons = $(".button-selected button")

            var selected = getSelected()

            if(selected.length > 0){
                buttons.prop("disabled", false)
                buttons.removeClass("btn-outline")
                buttons.each(function(){
                    if($(this).hasClass("btn-outline-primary")){
                        $(this).addClass("btn-primary").removeClass("btn-outline-primary")
                    } else if($(this).hasClass("btn-outline-danger")) {
                        $(this).addClass("btn-danger").removeClass("btn-outline-danger")
                    }
                })
            } else {
                buttons.prop("disabled", true)
                buttons.addClass("btn-outline")
                buttons.each(function(){
                    if($(this).hasClass("btn-primary")){
                        $(this).addClass("btn-outline-primary").removeClass("btn-primary")
                    } else if($(this).hasClass("btn-danger")) {
                        $(this).addClass("btn-outline-danger").removeClass("btn-danger")
                    }
                })
            }
            // $("input[name=emp_id_table]").val(JSON.stringify(selected))
        }

        function actionModal(n){
            var selected = getSelected()
            if(n == 2){
                $("#modalInterview").modal("show")
                $("#modalInterview .alert-selected").text(`${selected.length} Kandidat Terpilih`)
                $("#modalInterview input[name=list_id]").val(JSON.stringify(selected))
            } else {
                $("#modalInterviewReject").modal("show")
                $("#modalInterviewReject .alert-selected").text(`${selected.length} Kandidat Terpilih`)
                $("#modalInterviewReject input[name=list_id]").val(JSON.stringify(selected))
            }
        }

        $(document).ready(function(){
            $("table").addClass("gy-7 gs-7 border").removeClass("table-striped")

            $("table thead tr").addClass("fw-semibold fs-6 text-gray-800 border-bottom border-gray-200").css("background-color", "#FAFAFA")

            $("#table-job tbody tr td:first-child").click(function(){
                var tr = $(this).parents("tr")
                var isDisabled =  $(this).find(".form-check-input").is(":disabled")
                if(!isDisabled){
                    if($(tr).hasClass("selected")){
                        $(this).find(".form-check-input").prop('checked', false)
                    } else {
                        $(this).find(".form-check-input").prop('checked', true)
                    }
                }
                checkSelected()
            })

            $("#selectAll").click(function(){
                var checked = this.checked
                if(checked){
                    var data = table_job.data()
                    table_job.order([[6, 'asc']]).draw()
                    for (let i = 0; i < data.length; i++) {
                        const element = data[i];
                        var tr = element[0]
                        var ck = $(tr).find(".form-check-input")
                        var isDisabled = ck.is(":disabled")
                        if(!isDisabled){
                            table_job.rows(i).select()
                        }
                    }
                } else {
                    table_job.rows().deselect()
                    table_job.order(7, 'asc').draw()
                }

                $("#table-job tbody tr td:first-child").each(function(){
                    var tr = $(this).parents("tr")
                    var isDisabled =  $(this).find(".form-check-input").is(":disabled")
                    if($(tr).hasClass("selected")){
                        $(this).find(".form-check-input").prop('checked', true)
                    } else {
                        $(this).find(".form-check-input").prop('checked', false)
                    }
                })

                checkSelected()
            })

            $("input[name=interview]").click(function(){
                var btn = $(this).prev()
                $(this).parents(".d-flex").find("label.btn").removeClass("active")
                $(this).parents(".d-flex").find("label.btn").find("span").addClass("active")
                $(btn).addClass("active")
                $(btn).find("span").addClass("active")
            })

            @if ($errors->any())
                Swal.fire("", "{{ $errors->first() }}", "error")
            @endif

            @if (\Session::has("msg"))
                Swal.fire("{{ \Session::get('msg')['title'] }}", "{{ \Session::get('msg')['message'] }}", "success")
            @endif
        })
    </script>
@endsection

