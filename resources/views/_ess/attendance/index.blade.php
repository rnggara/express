@extends('_ess.layout')

@section('view_content')
<div class="card shadow-none card-p-0 h-100">
    <div class="card-body">
        <div class="d-flex flex-column gap-5 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-50px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-clock-eight-thirty text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Kehadiran</span>
                        <span>Lihat semua riwayat kehadiran dan koreksi Anda</span>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                <li class="nav-item">
                    <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_att">
                        <span class="nav-text">Riwayat Kehadiran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_corr">
                        <span class="nav-text">Riwayat Koreksi</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab_att" role="tabpanel">
                    <div class="card shadow-none">
                        <div class="card-body bg-secondary-crm p-5">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="position-relative me-5">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary border">
                                            <i class="fi fi-rr-filter"></i>
                                            Filter
                                        </button>
                                    </div>
                                </div>
                                <div class="scroll">
                                    <table class="table table-display-2 bg-white" data-ordering="false" id="table-overtime" data-page-length="100">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jadwal</th>
                                                <th>Jam Masuk</th>
                                                <th>Jam Keluar</th>
                                                <th>Lembur</th>
                                                <th>Alasan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($days as $date)
                                                @php
                                                    $n = date("N", strtotime($date));
                                                    $sch = $schedule->where("date", $date)->first();
                                                    $dc = $shDc[$sch['shift_id'] ?? 0] ?? null;
                                                    $attType = $dayCode[$dc] ?? "Off Day";
                                                    $att = $attData[$date] ?? [];
                                                    $timin = "-";
                                                    $timout = "-";
                                                    $overtime = "-";
                                                    if(!empty($att)){
                                                        $timin = !empty($att->timin) ? date("H:i", strtotime($att->timin)) : "-";
                                                        $timout = !empty($att->timout) ? date("H:i", strtotime($att->timout)) : "-";
                                                        $overtime = !empty($att->ovtend) ? date("H:i", strtotime($att->ovtend)) : "-";
                                                    }
                                                    $reasons = $att->reasons ?? [];
                                                @endphp
                                                <tr class="fs-base">
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold">{{ $hariId[$n] }}</span>
                                                            <span>{{ date("d-m-Y", strtotime($date)) }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $attType }} {{ $dc }}</td>
                                                    <td>{{ $timin }}</td>
                                                    <td>{{ $timout }}</td>
                                                    <td>{{ $overtime }}</td>
                                                    <td>
                                                        @if (count($reasons) == 0)
                                                            @if ($timin != "-")
                                                                <span class="badge badge-outline badge-success">{{ $attReason[1] }}</span>
                                                            @else
                                                                @if ($dc == 1)
                                                                    <span class="badge badge-danger badge-outline">Mangkir</span>
                                                                @else
                                                                    <span class="badge badge-secondary">{{ $attType }}</span>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @foreach ($reasons as $rs)
                                                                <span class="me-2 badge badge-outline text-white" style="background-color: {{ $attReasonColor[$rs['id']] }}">{{ $attReason[$rs['id']] }}</span>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                            <i class="fi fi-rr-menu-dots-vertical"></i>
                                                        </button>
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="javascript:;" onclick="correctionAtt('{{$date}}', '{{ $timin }}', '{{ $timout }}')" class="menu-link px-3">
                                                                    Correction
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_corr" role="tabpanel">
                    <div class="card shadow-none">
                        <div class="card-body bg-secondary-crm p-5">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="position-relative me-5">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary border">
                                            <i class="fi fi-rr-filter"></i>
                                            Filter
                                        </button>
                                    </div>
                                </div>
                                <div class="scroll">
                                    <table class="table table-display-2 bg-white fs-base" data-ordering="false" id="table-corr">
                                        <thead>
                                            <tr>
                                                {{-- <th>Nomor Referensi</th> --}}
                                                <th>Tipe Koreksi Kehadiran</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Detil Pengajuan</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($history as $item)
                                                <tr>
                                                    <td>{{ $item['correction_type'] }}</td>
                                                    <td>{{ $item['request_date'] }}</td>
                                                    <td>{!! $item['request_detail'] !!}</td>
                                                    <td>{!! $item['status'] !!}</td>
                                                    <td>{!! $item['action'] !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route("ess.attendance.add") }}" method="post" enctype="multipart/form-data">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modal_create_request_leave
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-sr-comment-pen text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Koreksi Kehadiran</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ Auth::user()->emp_id }}">
            <div class="d-flex align-items-center mb-5">
                <div class="form-check form-check-custom me-5">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" value="attendance" checked name="att_type"/>
                        Jam Masuk & Jam Keluar
                    </label>
                </div>
                <div class="form-check form-check-custom">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" value="leave" name="att_type"/>
                        Cuti
                    </label>
                </div>
            </div>
            <div data-form="attendance">
                <div class="row">
                    <div class="col-12">
                        <label class="col-form-label">Nomor Referensi : <span class="text-primary">{{ $last_ref }}</span></label>
                    </div>
                    <div class="col-6">
                        <div class="fv-row">
                            <label class="col-form-label">Jam Masuk Sekarang</label>
                            <input type="time" name="ccin" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fv-row">
                            <label class="col-form-label">Koreksi Jam Masuk</label>
                            <input type="time" name="cin" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fv-row">
                            <label class="col-form-label">Jam Keluar Sekarang</label>
                            <input type="time" name="ccout" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="fv-row">
                            <label class="col-form-label">Koreksi Jam Keluar</label>
                            <input type="time" name="cout" class="form-control">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="fv-row">
                            <label class="col-form-label required">Alasan</label>
                            <textarea name="reason_att" class="form-control" required id="" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="fv-row">
                            {{-- <label class="col-form-label required">Reference Number</label> --}}
                            <input type="hidden" name="ref_num_att" value="{{ $last_ref ?? "" }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div data-form="leave" class="d-none">
                <div class="row">
                    <div class="col-12">
                        <label class="col-form-label">Nomor Referensi : <span class="text-primary">{{ $last_ref_leave }}</span></label>
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Tipe Cuti</label>
                        <select name="reason" class="form-select" data-control="select2" data-placeholder="Pilih Tipe Cuti" data-dropdown-parent="#modal_create_request_leave" id="">
                            <option value=""></option>
                            @foreach ($reason_types as $item)
                                <option value="{{ $item->id }}" {{ (old("reason") ?? null) == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('reason')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6">
                        <label class="col-form-label fw-bold">Alasan Cuti</label>
                        <select name="leave_used" class="form-select" data-control="select2" data-placeholder="Pilih Alasan Cuti" data-dropdown-parent="#modal_create_request_leave" id="">
                            <option value=""></option>
                            @foreach ($rcon as $item)
                                <option value="{{ $item->id }}" data-tp="{{ $item->reason_type_id }}" {{ (old("leave_used") ?? null) == $item->id ? "SELECTED" : "" }} {{ (old("reason") ?? null) == $item->reason_type_id ? "" : "disabled" }}>{{ $item->reasonName->reason_name }}</option>
                            @endforeach
                            {{-- <option value="annual" {{ (old("leave_used") ?? null) == "annual" ? "SELECTED" : "" }}>Annual Leave</option>
                            <option value="long" {{ (old("leave_used") ?? null) == "long" ? "SELECTED" : "" }}>Long Leave</option>
                            <option value="special" {{ (old("leave_used") ?? null) == "special" ? "SELECTED" : "" }}>Special Leave</option> --}}
                        </select>
                        @error('leave_used')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-12">
                        <label class="col-form-label fw-bold">Tanggal Cuti</label>
                        <input type="text" name="date" drpicker value="{{ old("date") }}" class="form-control">
                        @error('date')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="fv-row col-12">
                        <label class="col-form-label fw-bold">Catatan</label>
                        <textarea name="notes" class="form-control" id="" cols="30" rows="10">{{ old("notes") }}</textarea>
                        @error('notes')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="fv-row col-6 mt-5">
                        <label class="col-form-label fw-bold">Berkas Diunggah</label>
                        <div class="d-flex align-items-center" data-toggle="file">
                            <label class="btn btn-secondary btn-sm me-5">
                                <span>Add File <i class="fi fi-rr-clip"></i></span>
                                <input type="file" name="attachment" data-toggle="file" accept=".jpg, .png, .pdf" class="d-none" id="">
                            </label>
                            <span class="text-primary" data-file-label></span>
                        </div>
                        <div class="d-flex flex-column mt-5 text-muted">
                            <span>File Format : JPG, PNG, PDF</span>
                            <span>Max 25 mb</span>
                        </div>
                    </div>
                    <div class="fv-row col-6">
                        {{-- <label class="col-form-label fw-bold">Reference Number</label> --}}
                        <input type="hidden" name="ref_num" value="{{ $last_ref_leave ?? "" }}" class="form-control" placeholder="Input Data">
                        @error('ref_num')
                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <input type="hidden" name="dt">
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
        @endslot
    @endcomponent
</form>

<div class="modal fade" tabindex="-1" id="modal-detail">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content px-10">

        </div>
    </div>
</div>

@endsection

@section('view_script')
    <script>

        function show_detail(id, url){
            $.ajax({
                url : url,
                type : "get",
                data : {
                    a : "detail",
                    id : id
                },
                dataType : "json"
            }).then(function(resp){
                $("#modal-detail .modal-content").html(resp.view)
                $("#modal-detail").modal("show")
            })
        }

        function correctionAtt(date, timin, timout){
            $("#modal_create_request_leave input[name=ccin]").val(timin)
            $("#modal_create_request_leave input[name=ccout]").val(timout)
            $("#modal_create_request_leave input[name=dt]").val(date)
            $("#modal_create_request_leave").modal("show")
            $("#modal_create_request_leave input[name=att_type][value=attendance]").click()

            $("#modal_create_request_leave input[name=date]").data("daterangepicker").setStartDate(sortDate(date))
            $("#modal_create_request_leave input[name=date]").data("daterangepicker").setEndDate(sortDate(date))
        }

        function sortDate(date){
            var e = date.split("-")

            var d = e[2]+"/"+e[1]+"/"+e[0]

            return d
        }

        function drpicker(header){
            $(`${header} input[drpicker]`).daterangepicker({
                locale: {
                    format: "DD/MM/YYYY"
                }
            });
        }

        $(document).ready(function(){
            $("#modal_create_request_leave input[name=att_type]").click(function(){
                $("#modal_create_request_leave [data-form]").addClass("d-none")
                $("#modal_create_request_leave [data-form]").find("[required]").prop("required", false)
                $(`#modal_create_request_leave [data-form="${$(this).val()}"]`).removeClass("d-none")
            })

            drpicker("#modal_create_request_leave")
            $("#modal_create_request_leave select[name=reason]").change(function(){
                var id = $(this).val()
                $("#modal_create_request_leave select[name=leave_used]").val("").trigger("change")
                $("#modal_create_request_leave select[name=leave_used] option[data-tp]").prop("disabled", true)
                $("#modal_create_request_leave select[name=leave_used] option[data-tp='"+id+"']").prop("disabled", false)
            })

            @if(!empty($_GET['t']))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ $_GET['t'] }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif

            @if(\Session::has("modal"))
                $("#{{ \Session::get('modal') }}").modal("show")
                // type="radio" value="leave" name="att_type"
                $("input[type=radio][name=att_type][value=leave]").click()
            @endif

            // $("a[href='#tab_corr']").click()
        })
    </script>
@endsection
