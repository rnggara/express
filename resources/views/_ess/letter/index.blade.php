@extends('_ess.layout')

@section('view_content')
<div class="card shadow-none card-p-0 h-100">
    <div class="card-body">
        <div class="d-flex flex-column gap-5 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-50px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-file-invoice text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Surat Keterangan Kerja</span>
                        <span>Anda dapat meminta surat kerja dan melihat riwayat permintaan surat kerja Anda</span>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_cad_add">
                        <i class="fi fi-rr-plus"></i>
                        Pengajuan Surat Keterangan Kerja
                    </button>
                </div>
            </div>
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
                            <table class="table table-display-2 bg-white" data-ordering="false" id="table-overtime">
                                <thead>
                                    <tr>
                                        <th>Nomor Referensi</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Tipe Surat Keterangan Kerja</th>
                                        <th>File</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $lname = [1=> "Surat Keterangan Kerja Standart", "Surat Keterangan Kerja KITAS", "Surat Keterangan Kerja Agunan"];
                                    @endphp
                                    @foreach ($letter_request as $item)
                                        <tr>
                                            <td>{{ $item->ref_num }}</td>
                                            <td>{{ date("d-m-Y", strtotime($item->created_at)) }}</td>
                                            <td>{{ $lname[$item->letter_type] ?? "-" }}</td>
                                            <td>
                                                @if (empty($item->approved_at))
                                                    -
                                                @else
                                                    <a href="javascript:;" onclick="printmv('frame{{ $item->letter_id }}')" style="text-decoration: underline">
                                                        Download File Here
                                                    </a>
                                                    <iframe id="frame{{ $item->letter_id }}" name="frame{{ $item->letter_id }}" src="{{ route('personel.fl.print', $item->letter_id) }}" width="0" height="0" frameborder="0"></iframe>
                                                @endif
                                            </td>
                                            {{-- <td>
                                                @if (empty($item->approved_at))
                                                <span class="badge badge-secondary">Waiting</span>
                                                @else
                                                <span class="badge badge-success badge-outline">Approved</span>
                                                @endif
                                            </td> --}}
                                            <td>
                                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                    <i class="fi fi-rr-menu-dots-vertical"></i>
                                                </button>
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_detail_cad_{{ $item->id }}" class="menu-link px-3">
                                                            Detail
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                    <!--begin::Menu item-->
                                                    <div class="menu-item px-3">
                                                        <a href="javascript:;" @if(empty($item->approved_at)) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('ess.employment-letter.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ !empty($item->approved_at) ? "disabled text-muted" : "" }}">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                            </td>
                                        </tr>

                                        @component('layouts._crm_modal')
                                            @slot('modalId')
                                                modal_detail_cad_{{ $item->id }}
                                            @endslot
                                            @slot('modalTitle')
                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                    <div class="d-flex align-items-center">
                                                        <div class="symbol symbol-50px me-5">
                                                            <div class="symbol-label bg-light-primary">
                                                                <span class="fi fi-sr-envelope-open-dollar text-primary"></span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <h3 class="me-2">Pengajuan Surat Keterangan Kerja</h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endslot
                                            @slot('modalToolbar')
                                            <span class="badge badge-secondary">Menunggu</span>
                                            @endslot
                                            @slot('modalContent')
                                                <div class="row row-gap-3 p-5">
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column gap-2">
                                                            <span class="fw-bold">Tipe Surat Keterangan Kerja</span>
                                                            <span>{{ $lname[$item->letter_type] ?? "-" }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column gap-2">
                                                            <span class="fw-bold">Tujuan Penggunaan</span>
                                                            <span>{{ $item->reason }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column gap-2">
                                                            <span class="fw-bold">Reference Number</span>
                                                            <span>{{ $item->ref_num }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endslot
                                            @slot('modalFooter')
                                                @csrf
                                                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Close</button>
                                            @endslot
                                        @endcomponent
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

<form action="{{ route("ess.employment-letter.add") }}" method="post" enctype="multipart/form-data">
    @component('layouts._crm_modal', [
        'modalSize' => "modal-lg"
        ])
        @slot('modalId')
            modal_cad_add
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-light-primary">
                        <span class="fi fi-sr-envelope-open-dollar text-primary"></span>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <h3 class="me-2">Pengajuan Surat Keterangan Kerja</h3>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <input type="hidden" name="emp" value="{{ Auth::user()->emp_id }}">
            <div class="row">
                <div class="col-12">
                    <label class="col-form-label">Nomor Referensi : <span class="text-primary">{{ $last_ref }}</span></label>
                </div>
                <div class="fv-row col-12">
                    <label class="col-form-label fw-bold required">Tipe Surat Keterangan Kerja</label>
                    <select name="loan_type" required class="form-select" data-control="select2"  data-placeholder="Pilih Tipe Surat Keterangan Kerja" data-dropdown-parent="#modal_cad_add">
                        <option value=""></option>
                        @foreach ($lname as $k => $item)
                            <option value="{{ $k }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fv-row col-12">
                    <label class="col-form-label fw-bold required">Tujuan Penggunaan</label>
                    <textarea name="reason" class="form-control" required id="" cols="30" placeholder="Masukan Tujuan Penggunaan" rows="10"></textarea>
                </div>
                <div class="fv-row col-12">
                    {{-- <label class="col-form-label fw-bold required">Reference Number</label> --}}
                    <input type="hidden" name="ref_num" readonly value="{{ $last_ref }}" class="form-control" placeholder="Input Reference Number">
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
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


<div class="modal fade" tabindex="1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3 text-center">Apakah Anda yakin ingin membatalkan pengajuan Overtime ini?</span>
                    <span class="text-center">Melakukan ini dapat mempengaruhi data kehadiran dari employee</span>
                    <div class="d-flex align-items-center mt-5">
                        <a href="#" name="submit" value="cancel" id="delete-url" class="btn btn-white">Lanjutkan</a>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batalkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@component('layouts.components.fab', [
        "fab" => [
            ["label" => "Request Employment Letter", "url" => "javascript:;", 'toggle' => 'data-bs-toggle="modal" data-bs-target="#modal_cad_add"'],
        ]
    ])
@endcomponent
@endsection

@section('view_script')
    <script>
        function archiveItem(me){
            var url = $(me).data("url")
            $("#delete-url").attr("href", url)
            $("#modalDelete").modal("show")
        }
        function printmv(divname){
            window.frames[divname].focus();
            window.frames[divname].print();
        }
    </script>
@endsection
