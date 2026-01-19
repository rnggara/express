@extends('_ess.layout')

@section('view_content')
<div class="card shadow-none card-p-0 h-100">
    <div class="card-body">
        <div class="d-flex flex-column gap-5 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-50px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-sack-dollar text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Penarikan Tunai</span>
                        <span>Anda dapat mengajukan permohonan penarikan tunai dan melihat riwayat pengajuan penarikan tunai Anda</span>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal_cad_add">
                        <i class="fi fi-rr-plus"></i>
                        Pengajuan Penarikan Tunai
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
                                        <th>Tipe Penarikan Tunai</th>
                                        <th>Total Nominal Penarikan Tunai (IDR)</th>
                                        {{-- <th>Status</th> --}}
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $r = ["LN01" => "Perjalanan Dinas", "LN02" => "Stationery", "LN03" => "Event"]
                                    @endphp
                                    @foreach ($myCashAdvance as $item)
                                        <tr>
                                            <td>{{ $item->ref_num }}</td>
                                            <td>{{ date("d-m-Y", strtotime($item->created_at)) }}</td>
                                            <td>{{ $r[$item->cash_type] }}</td>
                                            <td>{{ number_format($item->nominal) }}</td>
                                            {{-- <td><span class="badge badge-secondary">Waiting</span></td> --}}
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
                                                        <a href="javascript:;" @if(empty($item->approved_at)) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('ess.cash-advance.delete', ['id' => $item->id]) }}" data-id="{{ $item->id }}" @endif class="menu-link px-3 text-danger {{ !empty($item->approved_at) ? "disabled text-muted" : "" }}">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <!--end::Menu item-->
                                                </div>
                                            </td>
                                        </tr>

                                        @component('layouts._crm_modal', [
                                            "modalSize" => "modal-lg"
                                        ])
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
                                                            <h3 class="me-2">Penarikan Tunai</h3>
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
                                                            <span class="fw-bold">Tipe Penarikan Tunai</span>
                                                            <span>{{ $r[$item->cash_type] }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column gap-2">
                                                            <span class="fw-bold">Nomor Referensi</span>
                                                            <span>{{ $item->ref_num }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-flex flex-column gap-2">
                                                            <span class="fw-bold">Detil Penarikan Tunai</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex flex-column gap-2">
                                                        @foreach ($item->detail_cash as $dt)
                                                            <div class="row">
                                                                <div class="col-6">{{ $dt['detail'] }}</div>
                                                                <div class="col-6">IDR {{ number_format($dt['amount'], 0, ",", ".") }}</div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column gap-2">
                                                            <span class="fw-bold">Total Nominal Penarikan Tunai (IDR)</span>
                                                            <span>{{ number_format($item->nominal, 0, ",", ".") }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="d-flex flex-column gap-2">
                                                            <span class="fw-bold">Tujuan Penarikan Tunai</span>
                                                            <span>{{ $item->reason }}</span>
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

<form action="{{ route("ess.cash-advance.add") }}" method="post" enctype="multipart/form-data">
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
                    <h3 class="me-2">Penarikan Tunai</h3>
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
                    <label class="col-form-label fw-bold required">Tipe Penarikan Tunai</label>
                    <select name="loan_type" required class="form-select" data-control="select2"  data-placeholder="Pilih Tipe Penarikan Tunai" data-dropdown-parent="#modal_cad_add">
                        <option value=""></option>
                        @foreach ($r as $k => $item)
                            <option value="{{ $k }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                <!--begin::Repeater-->
                <div class="fv-row col-12">
                    <label class="col-form-label fw-bold required">Detail Penarikan Tunai</label>
                    <div id="kt_docs_repeater_basic">
                        <!--begin::Form group-->
                        <div class="form-group">
                            <div data-repeater-list="list">
                                <div data-repeater-item>
                                    <div class="form-group row mb-3">
                                        <div class="col-5">
                                            <input type="text" name="detail" required class="form-control mb-2 mb-md-0" placeholder="Detail" />
                                        </div>
                                        <div class="col-5">
                                            <input type="text" name="amount" required data-list class="form-control number mb-2 mb-md-0" placeholder="IDR 0" />
                                        </div>
                                        <div class="col-2 d-flex align-items-center">
                                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-icon btn-light-danger">
                                                <i class="fi fi-sr-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Form group-->
    
                        <!--begin::Form group-->
                        <div class="form-group text-center">
                            <a href="javascript:;" data-repeater-create class="btn text-primary btn-sm">
                                Add more detail
                            </a>
                        </div>
                        <!--end::Form group-->
                    </div>
                </div>
                <!--end::Repeater-->
                <div class="fv-row col-12">
                    <label class="col-form-label fw-bold required">Nominal (IDR)</label>
                    <input type="text" name="nominal" value="{{ old("nominal") }}" readonly class="form-control number" placeholder="0">
                </div>
                <div class="fv-row col-12">
                    <label class="col-form-label fw-bold required">Reason</label>
                    <textarea name="reason" class="form-control" required id="" cols="30" placeholder="Input Reason" rows="10"></textarea>
                </div>
                <div class="fv-row col-12">
                    {{-- <label class="col-form-label fw-bold required">Reference Number</label> --}}
                    <input type="hidden" name="ref_num" readonly value="{{ $last_ref }}" class="form-control" placeholder="Input Reference Number">
                </div>
            </div>
        @endslot
        @slot('modalFooter')
            @csrf
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Back</button>
            <button type="submit" class="btn btn-primary">Save</button>
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
            ["label" => "Request Cash Advance", "url" => "javascript:;", 'toggle' => 'data-bs-toggle="modal" data-bs-target="#modal_cad_add"'],
        ]
    ])
@endcomponent
@endsection

@section('view_script')
    <script src="{{ asset('theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script>
        function archiveItem(me){
            var url = $(me).data("url")
            $("#delete-url").attr("href", url)
            $("#modalDelete").modal("show")
        }

        function sumDetail(){
            $("[data-list]").on("keyup", function(){
                var sum = 0
                $("[data-list]").each(function(){
                    sum += $(this).val() * 1
                })

                $("input[name=nominal]").val(sum)
            })
        }

        $(document).ready(function(){
            $('#kt_docs_repeater_basic').repeater({
                initEmpty: false,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {
                    $(this).slideDown();
                    console.log($(this).find("input.number"))
                    $(this).find("input.number").number(true, 2, ",", ".")
                    sumDetail()
                },
                ready: function (setIndexes) {
                    sumDetail()
                },
                isFirstItemUndeletable: true,

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        })
    </script>
@endsection
