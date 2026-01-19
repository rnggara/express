@extends('_ess.layout')

@section('view_content')
<div class="card shadow-none card-p-0 h-100">
    <div class="card-body">
        <div class="d-flex flex-column gap-5 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-50px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-calendar-clock text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Cuti</span>
                        <span>Anda dapat mengajukan lembur dan juga melihat riwayat lembur Anda</span>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between">
                <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                    <li class="nav-item">
                        <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_leave_balance">
                            <span class="nav-text">Saldo Cuti</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_leave_history">
                            <span class="nav-text">Riwayat Cuti</span>
                        </a>
                    </li>
                </ul>
                <div>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_create_request_leave">
                        <i class="fi fi-rr-plus"></i>
                        Pengajuan Cuti
                    </button>
                </div>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab_leave_balance" role="tabpanel">
                    <div class="card shadow-none">
                        <div class="card-body bg-secondary-crm p-5 rounded">
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
                                    <table class="table table-display-2 bg-white" data-ordering="false" id="table-overtime">
                                        <thead>
                                            <tr>
                                                <th>Tahun</th>
                                                <th>Kadaluwarsa pada</th>
                                                <th>Tipe Cuti</th>
                                                <th>Jatah Cuti</th>
                                                <th>Cuti yang Digunakan</th>
                                                <th>Cuti yang Diajukan</th>
                                                <th>Sisa Cuti</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leave as $item)
                                                @php
                                                    $jatah = $item->jatah;
                                                    $used = $item->used - $item->anulir + $item->unrecorded;
                                                    $reserve = $item->reserve;
                                                    $sisa = $jatah - ($used + $reserve);
                                                @endphp
                                                <tr>
                                                    <td>{{ date("Y", strtotime($item->start_periode)) }}</td>
                                                    <td>{{ date("d-m-Y", strtotime($item->end_periode)) }}</td>
                                                    <td>Cuti {{ ucwords($item->type == "annual" ? "Tahunan" : ($item->type == "mass" ? "Masal" : "Panjang")) }}</td>
                                                    <td>{{ $jatah ?? "-" }} {{ $jatah > 0 ? "hari" : "" }}</td>
                                                    <td>{{ $used ?? "-" }} {{ $used > 0 ? "hari" : "" }}</td>
                                                    <td>{{ $reserve ?? "-" }} {{ $reserve > 0 ? "hari" : "" }}</td>
                                                    <td>{{ $sisa ?? "-" }} {{ $sisa > 0 ? "hari" : "" }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                            <i class="fi fi-rr-menu-dots-vertical"></i>
                                                        </button>
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="javascript:;" onclick="show_detail({{$item->id}})" class="menu-link px-3">
                                                                    Perpanjang masa berlaku cuti
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
                <div class="tab-pane fade" id="tab_leave_history" role="tabpanel">
                    <div class="card bg-secondary-crm">
                        <div class="card-body p-5">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-5">
                                    <div class="position-relative me-5">
                                        <input type="text" class="form-control ps-12" placeholder="Search" name="search_table">
                                        <i class="fi fi-rr-search fs-2 ms-3 position-absolute text-muted top-25"></i>
                                    </div>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#kt_filter_leave_history_body_1">
                                        <i class="fi fi-rr-filter"></i>
                                        Filter
                                    </button>
                                </div>
                                <div class="accordion mb-5" id="kt_filter_leave_history">
                                    <div class="accordion-item bg-transparent border-0">
                                        <div id="kt_filter_leave_history_body_1" class="accordion-collapse collapse" aria-labelledby="kt_filter_leave_history_header_1" data-bs-parent="#kt_filter_leave_history">
                                            <div class="accordion-body px-0">
                                                <div class="d-flex align-items-center">
                                                    <select name="freasontype" class="form-select" data-control="select2" data-placeholder="Select Reason Type" data-allow-clear="true" id="">
                                                        <option value=""></option>
                                                        @foreach ($reason_types as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="mx-3"></div>
                                                    <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Time" data-allow-clear="true" id="">
                                                        <option value=""></option>
                                                    </select>
                                                    <div class="mx-3"></div>
                                                    <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Workgroup" data-allow-clear="true" id="">
                                                        <option value=""></option>
                                                    </select>
                                                    <div class="mx-3"></div>
                                                    <select name="fdepartement" class="form-select" data-control="select2" data-placeholder="Select Departement" data-allow-clear="true" id="">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="scroll">
                                    <table class="table table-display-2 bg-white" data-ordering="false" id="table-leave-request">
                                        <thead>
                                            <tr>
                                                <th>Nomor Referensi</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Tanggal Dimulai</th>
                                                <th>Tanggal Berakhir</th>
                                                <th>Alasan Cuti</th>
                                                <th>Status</th>
                                                <th>Catatan Penolakan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leave_history as $item)
                                                <tr>
                                                    <td>{{ date("d-m-Y", strtotime($item->created_at)) }}</td>
                                                    <td>{{ date("d-m-Y", strtotime($item->start_date)) }}</td>
                                                    <td>{{ date("d-m-Y", strtotime($item->end_date)) }}</td>
                                                    <td><span class="fw-bold">{{ $item->total_day }} Hari</span></td>
                                                    <td>Cuti {{ ucwords($item->leave_used == "annual" ? "Tahunan" : ($item->leave_used == "mass" ? "Masal" : "Panjang")) }}</td>
                                                    <td>{{ $item->rejected_notes ?? "-" }}</td>
                                                    <td>
                                                        @if (empty($item->approved_at) && empty($item->rejected_at))
                                                            <button type="button" class="btn btn-outline btn-outline-warning">Persetujuan</button>
                                                        @else
                                                            @if (!empty($item->approved_at))
                                                                <button type="button" class="btn btn-outline btn-outline-success">Disetujui</button>
                                                            @else
                                                                <button type="button" class="btn btn-outline btn-outline-danger">Ditolak</button>
                                                            @endif
                                                        @endif
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
            </div>
        </div>
    </div>
</div>

<form action="{{ route("attendance.leave.request_leave") }}" method="post" enctype="multipart/form-data">
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
                        <span class="fi fi-rr-comment-pen text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Pengajuan Cuti</h3>
                    <span class="text-muted fs-base"></span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ $personel->id }}">
            <div class="row">
                <div class="col-12">
                    <label class="col-form-label">Nomor Referensi : <span class="text-primary">{{ $last_ref }}</span></label>
                </div>
                <div class="fv-row col-6">
                    <label class="col-form-label fw-bold">Kategori Cuti</label>
                    <select name="reason" class="form-select" data-control="select2" data-placeholder="Pilih Kategori Cuti" data-dropdown-parent="#modal_create_request_leave" id="">
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
                        {{-- <option value="annual" {{ (old("leave_used") ?? null) == "annual" ? "SELECTED" : "" }}>Annual Cuti</option>
                        <option value="long" {{ (old("leave_used") ?? null) == "long" ? "SELECTED" : "" }}>Long Cuti</option>
                        <option value="special" {{ (old("leave_used") ?? null) == "special" ? "SELECTED" : "" }}>Special Cuti</option> --}}
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
                    <textarea name="notes" class="form-control" id="" cols="30" rows="10" placeholder="Masukan catatan">{{ old("notes") }}</textarea>
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
                            <span>Attachment <i class="fi fi-rr-clip"></i></span>
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
                    <input type="hidden" name="ref_num" value="{{ $last_ref }}" class="form-control" placeholder="Input Data">
                    @error('ref_num')
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <input type="hidden" name="type" value="id_card">
            <input type="hidden" name="need_approval" value="1">
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
        @endslot
    @endcomponent
</form>

<div class="modal fade" tabindex="-1" id="modalDetail">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content px-10">

        </div>
    </div>
</div>

@component('layouts.components.fab', [
        "fab" => [
            ["label" => "Take attendance", "url" => "javascript:;", 'toggle' => 'onclick="take_attendance()"'],
            ["label" => "Pengajuan Cuti", "url" => "javascript:;", 'toggle' => 'data-bs-toggle="modal" data-bs-target="#modal_create_request_leave"'],
            ["label" => "Pengajuan Lembur", "url" => route("ess.overtime.index")."?modal=modal_add_overtime"],
        ]
    ])
@endcomponent

@endsection

@section('view_script')
    <script>
        function drpicker(header){
            $(`${header} input[drpicker]`).daterangepicker({
                locale: {
                    format: "DD/MM/YYYY"
                }
            });
        }

        $(document).ready(function(){
            drpicker("#modal_create_request_leave")
            $("#modal_create_request_leave select[name=reason]").change(function(){
                var id = $(this).val()
                $("#modal_create_request_leave select[name=leave_used]").val("").trigger("change")
                $("#modal_create_request_leave select[name=leave_used] option[data-tp]").prop("disabled", true)
                $("#modal_create_request_leave select[name=leave_used] option[data-tp='"+id+"']").prop("disabled", false)
            })

            @if(\Session::has("modal"))
                $("#{{ \Session::get('modal') }}").modal("show")
            @endif
        })
    </script>
@endsection
