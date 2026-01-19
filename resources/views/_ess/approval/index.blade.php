@extends('_ess.layout')

@section('view_content')
<div class="card shadow-none card-p-0 h-100">
    <div class="card-body">
        <div class="d-flex flex-column gap-5 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-50px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-time-quarter-past text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Persetujuan</span>
                        <span>Anda dapat melihat semua pengajuan Anda dan pengajuan yang memerlukan Persetujuan Anda</span>
                    </div>
                </div>
            </div>
            @if ($posChild->count() > 0)
            <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                <li class="nav-item">
                    <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_my_request">
                        <span class="nav-text">Pengajuan Saya</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_approval">
                        <span class="nav-text">Butuh Persetujuan</span>
                    </a>
                </li>
            </ul>
            @endif
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tab_my_request" role="tabpanel">
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
                                                <th>Tipe Pengajuan</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Detil Pengajuan</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($myRequest as $item)
                                                <tr>
                                                    <td>{{ $item['ref_num'] ?? "-" }}</td>
                                                    <td>{{ $item['request_type'] }}</td>
                                                    <td>{{ $item['request_date'] }}</td>
                                                    <td>{!! $item['request_detail'] !!}</td>
                                                    <td>{!! $item['status'] !!}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                            <i class="fi fi-rr-menu-dots-vertical"></i>
                                                        </button>
                                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                            <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="javascript:;" onclick="showDetail({{ $item['id'] }}, '{{ $item['url'] }}')" class="menu-link px-3">
                                                                    Detail
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item-->
                                                            {{-- <!--begin::Menu item-->
                                                            <div class="menu-item px-3">
                                                                <a href="javascript:;" @if(empty($item['approved_at'])) data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('ess.employment-letter.delete', ['id' => $item['id']]) }}" data-id="{{ $item['id'] }}" @endif class="menu-link px-3 text-danger disabled text-muted {{ !empty($item['approved_at']) ? "disabled text-muted" : "" }}">
                                                                    Delete
                                                                </a>
                                                            </div>
                                                            <!--end::Menu item--> --}}
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
                <div class="tab-pane fade" id="tab_approval" role="tabpanel">
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
                                                <th>Nama Karyawan</th>
                                                <th>Nomor Referensi</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Tipe Pengajuan</th>
                                                <th>Detil Pengajuan</th>
                                                <th>Persetujuan</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($approval as $item)
                                                @php
                                                    $_emp = $empUnder[$item['emp_id']] ?? [];
                                                    $_user = $_emp->user ?? [];
                                                @endphp
                                                @if (!empty($_emp))
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex gap-3 align-items-center">
                                                                <div class="symbol symbol-40px">
                                                                    <div class="symbol-label" style="background-image: url('{{ asset($_user->user_img ?? 'images/image_placeholder.png') }}')"></div>
                                                                </div>
                                                                <div class="d-flex flex-column">
                                                                    <span>{{ $_emp->emp_name }}</span>
                                                                    <span class="text-muted">{{ $_emp->emp_id }}</span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $item['ref_num'] }}</td>
                                                        <td>{{ $item['request_date'] }}</td>
                                                        <td>{{ $item['request_type'] }}</td>
                                                        <td>{!! $item['request_detail'] !!}</td>
                                                        <td>
                                                            <form action="{{ route('ess.approval.approve', ['type' => $item['type']]) }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $item['id'] }}">
                                                                <button type="submit" name="submit" value="approve" class="btn btn-sm btn-success">Setujui</button>
                                                                <button type="button" onclick="rejectRequest('{{ $item['id'] }}', '{{ $item['type'] }}')" class="btn btn-sm btn-danger">Tolak</button>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                                                <i class="fi fi-rr-menu-dots-vertical"></i>
                                                            </button>
                                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                                <!--begin::Menu item-->
                                                                <div class="menu-item px-3">
                                                                    <a href="javascript:;" onclick="showDetail({{ $item['id'] }}, '{{ $item['url'] }}&approval=1')" class="menu-link px-3">
                                                                        Detail
                                                                    </a>
                                                                </div>
                                                                <!--end::Menu item-->
                                                            </div>
                                                        </td>
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
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal-detail">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content px-10">

        </div>
    </div>
</div>

<form action="" id="formReject" method="post">
    <div class="modal fade" tabindex="-1" id="modalReject">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content px-10">
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header border-0 px-0">
                            <h3 class="card-title">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50px me-5">
                                            <div class="symbol-label bg-light-primary">
                                                <span class="fi fi-sr-envelope-open-dollar text-primary"></span>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h3 class="me-2">Persetujuan Detail</h3>
                                        </div>
                                    </div>
                                </div>
                            </h3>
                        </div>
                        <div class="bg-secondary-crm card-body rounded">
                            <div class="fv-row">
                                <label class="col-form-label required">Catatan Penolakan</label>
                                <textarea name="rejected_notes" class="form-control" required id="" cols="30" rows="10" placeholder="Masukan Catatan Penolakan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-0 modal-footer">
                    @csrf
                    <input type="hidden" name="id">
                    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit" value="reject" class="btn btn-primary">Kirim</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" tabindex="1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3 text-center">Apakah Anda yakin ingin membatalkan pengajuan ini?</span>
                    <div class="d-flex align-items-center mt-5">
                        <a href="#" name="submit" value="cancel" id="delete-url" class="btn btn-white">Lanjutkan</a>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batalkan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('view_script')
    <script>
        function showDetail(id, url){
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

        function rejectRequest(id, type){
            $("#formReject").attr("action", "{{ route("ess.approval.approve") }}/" + type)
            $("#modalReject input[name=id]").val(id)
            $("#modalReject").modal("show")
        }

        function archiveItem(me){
            var url = $(me).data("url")
            $("#delete-url").attr("href", url)
            $("#modalDelete").modal("show")
        }

        $(document).ready(function(){
            @if(!empty($_GET['t']))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ $_GET['t'] }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif

            @if(\Session::has("tab"))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#{{ Session::get("tab") }}']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif
        })
    </script>
@endsection
