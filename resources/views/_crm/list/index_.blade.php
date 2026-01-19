@extends('layouts.templateCrm', ["menuCrm" => "menu_crm", 'withoutFooter' => true, 'bgWrapper' => 'bg-white', "style" => ["border" => "border-bottom", "box-shadow" => "none"]])

@section('content')
<div class="card card-custom not-rounded h-100">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="symbol symbol-50px me-5">
                <div class="symbol-label bg-primary">
                    <i class="fa fa-building fs-2 text-white"></i>
                </div>
            </div>
            <div class="d-flex flex-column justify-content-between">
                <span class="fw-bold fs-2">List</span>
                <span @if($t != "perusahaan")  style="display: none" @endif class="input-search" id="search-perusahaan">You can see the list company client</span>
                <span @if($t != "kontak")  style="display: none" @endif class="input-search" id="search-kontak">You can see the list contacts</span>
                <span @if($t != "file")  style="display: none" @endif class="input-search" id="search-file">You can see the list files</span>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div class="d-flex align-items-center">
                {{-- <span class="fa fa-filter me-5"></span> --}}
                <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 pt-5">
                    <li class="nav-item">
                        <a class="nav-link text-active-dark {{ $t == "perusahaan" ? "active" : "" }}" onclick="change_tab('perusahaan')" data-bs-toggle="tab" href="#tab_company">
                            <span class="nav-text">Company</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark {{ $t == "kontak" ? "active" : "" }}" onclick="change_tab('kontak')" data-bs-toggle="tab" href="#tab_kontak">
                            <span class="nav-text">Contact</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark {{ $t == "file" ? "active" : "" }}" onclick="change_tab('file')" data-bs-toggle="tab" href="#tab_files">
                            <span class="nav-text">File</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="d-flex align-items-center">
                <button type="button" onclick="exportToExcel()" class="btn btn-outline btn-outline-primary me-5">
                    <i class="fa fa-download"></i>
                    Download
                </button>
                <button type="button" onclick="uploadCsv()" class="btn btn-primary me-5">
                    <i class="fa fa-upload"></i>
                    Upload CSV
                </button>
                <button type="button" class="btn btn-primary btn-add" id="btn-add-perusahaan" @if($t != "perusahaan") style='display: none' @endif data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <i class="fa fa-file-medical"></i>
                    Add Company
                </button>
                <button type="button" class="btn btn-primary btn-add" id="btn-add-kontak" @if($t != "kontak") style='display: none' @endif data-bs-toggle="modal" data-bs-target="#modalAddKontak">
                    <i class="fa fa-file-medical"></i>
                    Add Contact
                </button>
                <button type="button" class="btn btn-primary btn-add" id="btn-add-file" @if($t != "file") style='display: none' @endif data-bs-toggle="modal" data-bs-target="#modalAddFile">
                    <i class="fa fa-file-medical"></i>
                    Add File
                </button>
            </div>
        </div>
    </div>
    <div class="card-body h-100 rounded" style="background-color: rgba(247, 248, 250, 1)">
        <div class="tab-content scroll" id="myTabContent" style="padding: 0">
            <div class="tab-pane fade {{ $t == "perusahaan" ? "show active" : "" }}" id="tab_company" role="tabpanel">
                <table class="table bg-white display-list" data-exclude='[2,4]' data-col-filter="3,4" id="table-perusahaan">
                    <thead>
                        <tr>
                            <th>Perusahaan</th>
                            <th>Kontak</th>
                            <th>Related To</th>
                            <th>Tipe</th>
                            <th>Pembuat</th>
                            <th>Update Terakhir</th>
                            <th></th>
                            <th class="d-none">Completeness</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($company as $item)
                            @php
                                $completeness = 0;
                                $_tags = [];
                                $js_tag = json_decode($item->tags ,true);
                                if(is_array($js_tag)){
                                    $_tags = $js_tag;
                                } else {
                                    if($item->tags != "null"){
                                        $col = [];
                                        $col['value'] = $item->tags;
                                        $_tags[] = $col;
                                    }
                                }
                                $opClient = collect($opp[$item->id] ?? []);
                                $cpClient = collect($cp[$item->id] ?? []);
                                $completeData = "<a href='javascript:;' data-kt-drawer-show=true data-kt-drawer-target=#kt_drawer_example_basic onclick=\"show_detail('#drawer-content', 'company', $item->id)\"><u>Incomplete data</u></a>";
                            @endphp
                            <tr>
                                <td>
                                    <span class="text-dark fw-bold cursor-pointer" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="show_detail('#drawer-content', 'company', {{ $item->id }})">{{ $item->company_name }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if (!empty($item->phones))
                                            <span class="fw-bold">{{ $item->phones[0]['phone'] }}</span>
                                        @else
                                            <span class="fw-bold">{{ $item->pic_number }}</span>
                                        @endif
                                        @if (!empty($item->emails))
                                            <span>{{ $item->emails[0] }}</span>
                                        @else
                                            <span>{{ $item->email }}</span>
                                        @endif
                                        @if (empty($item->phones) && empty($item->pic_number) && empty($item->emails) && empty($item->email))
                                            {!! $completeData !!}
                                            @php
                                                $completeness++
                                            @endphp
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if ($opClient->count() > 0 || $cpClient->count() > 0)
                                            <div class="d-flex align-items-center">
                                                @foreach ($opClient->take(5) as $op)
                                                    <div class="symbol symbol-circle symbol-30px me-3" data-bs-toggle="tooltip" data-bs-html="true"
                                                    title="<div class='d-flex flex-column'><span>{{ $op->leads_name }}</span><span class='text-primary'>{{ number_format($op->nominal ?? 0, 0, ",", ".") }}</span></div>">
                                                        <div class="symbol-label bg-light-primary">
                                                            <i class="fi fi-rr-briefcase text-primary"></i>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @if ($opClient->count() > 5)
                                                    <span data-bs-toggle="modal" class="cursor-pointer" data-bs-target="#modalOp{{ $item->id }}">...</span>
                                                @endif
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    @foreach ($cpClient->take(3) as $op)
                                                        <label class="text-nowrap fw-bold" data-bs-toggle="tooltip" data-bs-html="true"
                                                        title="<div class='d-flex flex-column'><span class='fw-bold'>{{ ucwords($op->name) }}</span><span class='text-primary'>{{ $item->company_name }}</span>
                                                            <span class='fw-bold'>{{ $op->no_telp ?? "-" }}</span><span class='fw-bold'>{{ $op->email ?? "-" }}</span></div>">{{ ucwords($op->name) }}, </label>
                                                    @endforeach
                                                    @if ($cpClient->count() > 3)
                                                        <span data-bs-toggle="modal" class="cursor-pointer" data-bs-target="#modalContact{{ $item->id }}">...</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            {!! $completeData !!}
                                            @php
                                                $completeness++
                                            @endphp
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    {!! $item->type ?? $completeData !!}
                                    @if (empty($item->type))
                                        @php
                                            $completeness++
                                        @endphp
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $users[$item->created_by] ?? "-" }}</td>
                                <td>{{ date("d/m/Y", strtotime($item->updated_at ?? $item->created_at)) }}</td>
                                <td align="center" class="text-nowrap">
                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                    </button>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.list.archive', ["type" => "company", "id" => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->company_name }}" class="menu-link px-3 text-danger">
                                                Archive
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        @if (!empty($item->parent) || $item->childs->count() > 0)
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="javascript:;" onclick="showHierarchy({{ $item->id }})" class="menu-link px-3">
                                                    Show Hierarchy
                                                </a>
                                            </div>
                                            <!--end::Menu item-->
                                        @endif
                                    </div>
                                </td>
                                <td class="d-none">{{ $completeness > 0 ? -1 : 1 }}</td>
                            </tr>

                            <div class="modal fade" tabindex="-1" id="modalOp{{ $item->id }}">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <div class="d-flex flex-column">
                                                <div class="align-items-center bg-secondary d-flex justify-content-between p-5 rounded-top">
                                                    <span class="fw-bold">Related to</span>
                                                    <button class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap p-3">
                                                    @foreach ($opClient->take(5) as $op)
                                                        <div class="symbol symbol-circle symbol-30px me-3" data-bs-toggle="tooltip" data-bs-html="true"
                                                        title="<div class='d-flex flex-column'><span>{{ $op->leads_name }}</span><span class='text-primary'>{{ number_format($op->nominal ?? 0, 0, ",", ".") }}</span></div>">
                                                            <div class="symbol-label bg-light-primary">
                                                                <i class="fi fi-rr-briefcase text-primary"></i>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" tabindex="-1" id="modalContact{{ $item->id }}">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-body p-0">
                                            <div class="d-flex flex-column">
                                                <div class="align-items-center bg-secondary d-flex justify-content-between p-5 rounded-top">
                                                    <span class="fw-bold">Related to</span>
                                                    <button class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap p-5">
                                                    @foreach ($cpClient->take(5) as $op)
                                                        <label class="text-nowrap fw-bold" data-bs-toggle="tooltip" data-bs-html="true"
                                                        title="<div class='d-flex flex-column'><span class='fw-bold'>{{ ucwords($op->name) }}</span><span class='text-primary'>{{ $item->company_name }}</span>
                                                            <span class='fw-bold'>{{ $op->no_telp ?? "-" }}</span><span class='fw-bold'>{{ $op->email ?? "-" }}</span></div>">{{ ucwords($op->name) }}, </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade {{ $t == "kontak" ? "show active" : "" }}" id="tab_kontak" role="tabpanel">
                <table class="table bg-white table-striped display-list" data-col-filter="4" id="table-kontak">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Posisi</th>
                            <th>Nomor Telepon</th>
                            <th>Email</th>
                            <th>Tipe</th>
                            <th>Update Terakhir</th>
                            <th></th>
                            <th class="d-none">Completeness</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $item)
                            @php
                                $completeness = 0;
                                $completeData = "<a href='javascript:;' data-kt-drawer-show=true data-kt-drawer-target=#kt_drawer_example_basic onclick=\"show_detail('#drawer-content', 'contacts', $item->id)\"><u>Incomplete data</u></a>";
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bold cursor-pointer mb-3" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="show_detail('#drawer-content', 'contacts', {{ $item->id }})">{{ $item->name }}</span>
                                        <span class="text-primary cursor-pointer" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="show_detail('#drawer-content', 'company', {{ $item->comp_id }})">{{ $comPluck[$item->comp_id] ?? "-" }}</span>
                                    </div>
                                </td>
                                <td>
                                    {!! $item->position ?? $completeData !!}
                                    @php
                                        if (empty($item->position)) {
                                            $completeness++;
                                        }
                                    @endphp
                                </td>
                                <td>
                                    {!! $item->no_telp ?? $completeData !!}
                                    @php
                                        if (empty($item->no_telp)) {
                                            $completeness++;
                                        }
                                    @endphp
                                </td>
                                <td>
                                    {!! $item->email ?? $completeData !!}
                                    @php
                                        if (empty($item->email)) {
                                            $completeness++;
                                        }
                                    @endphp
                                </td>
                                <td>
                                    {!! $item->type ?? $completeData !!}
                                    @php
                                        if (empty($item->type)) {
                                            $completeness++;
                                        }
                                    @endphp
                                </td>
                                <td>{{ date("d/m/Y", strtotime($item->updated_at)) }}</td>
                                <td align="center">
                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                    </button>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.list.archive', ["type" => "contact", "id" => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->name }}" class="menu-link px-3 text-danger">
                                                Archive
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" class="menu-link px-3">
                                                Download
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                </td>
                                <td class="d-none">{{ $completeness > 0 ? -1 : 1 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade {{ $t == "file" ? "show active" : "" }}" id="tab_files" role="tabpanel">
                <table class="table bg-white table-striped display-list" data-col-filter="1, 2" id="table-file">
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>File Type</th>
                            <th>File</th>
                            <th>File URL</th>
                            <th>Related to</th>
                            <th>Upload Date</th>
                            <th></th>
                            <th class="d-none"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($files as $item)
                            @php
                                $completeness = 0;
                                $fname = explode("/", $item->file_address);
                                $opFile = collect($op_file[$item->id_lead] ?? []);
                                $completeData = "<a href='javascript:;' data-kt-drawer-show=true data-kt-drawer-target=#kt_drawer_example_basic onclick=\"show_detail('#drawer-content', 'file', $item->id)\"><u>Incomplete data</u></a>";
                            @endphp
                            <tr>
                                <td>
                                    <a href='javascript:;' data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="show_detail('#drawer-content', 'file', {{ $item->id }})"><u>{{ $item->file_name }}</u></a>
                                </td>
                                <td>
                                    @php
                                        $_ex = explode(".", $item->file_address);
                                    @endphp
                                    {{ strtoupper(end($_ex)) }}
                                </td>
                                <td>
                                    <a href="{{ asset($item->file_address) }}" class="btn btn-sm btn-primary">
                                        {{ end($fname) }}
                                    </a>
                                </td>
                                <td>
                                    @if (empty($item->file_url))
                                        {!! $completeData !!}
                                        @php
                                            $completeness++
                                        @endphp
                                    @else
                                        <a href="{{ $item->file_url }}">{{ $item->file_url }}</a>
                                    @endif
                                </td>
                                <td class="fw-bold">
                                    @if (!empty($opFile))
                                        <div class="symbol symbol-circle symbol-30px me-3" data-bs-toggle="tooltip" data-bs-html="true"
                                        title="<div class='d-flex flex-column'><span>{{ $opFile['leads_name'] ?? "-" }}</span><span class='text-primary'>{{ number_format($opFile['nominal'] ?? 0, 0, ",", ".") }}</span></div>">
                                            <div class="symbol-label bg-light-primary">
                                                <i class="fi fi-rr-briefcase text-primary"></i>
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $completeness++
                                        @endphp
                                        {!! $completeData !!}
                                    @endif
                                </td>
                                <td>{{ date("d/m/Y", strtotime($item->created_at)) }}</td>
                                <td align="center">
                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                    </button>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.list.archive', ["type" => "file", "id" => $item->id]) }}" data-id="{{ $item->id }}" data-name="{{ $item->file_name }}" class="menu-link px-3 text-danger">
                                                Archive
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" class="menu-link px-3">
                                                Download
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                </td>
                                <td class="d-none">{{ $completeness > 0 ? -1 : 1 }}</td>
                            </tr>

                            <div class="modal fade" tabindex="-1" id="modalDelete{{ $item->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                                                    <span class="fw-bold fs-2">Hapus File</span>
                                                </div>
                                                <span>Apakah kamu yakin akan menghapus {{ $item->file_name }}?</span>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <a href="{{ route('crm.list.delete', ["type" => "file", "id" => $item->id]) }}" class="btn btn-danger">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h1 class="modal-title">Tambah Perusahaan</h1>
                </div> --}}
                <form action="{{ route('crm.list.add', ['type' => "company"]) }}" method="post">
                    <div class="modal-body px-15 py-10">
                        <div class="d-flex mb-5 align-items-center">
                            <div class="symbol symbol-50px me-5">
                                <div class="symbol-label bg-light-primary"><i class="fa fa-building text-primary fs-2"></i></div>
                            </div>
                            <div class="d-flex flex-column justify-content-end">
                                <span class="fs-3 fw-bold">Add Data Company</span>
                                <span>Register data for List Company</span>
                            </div>
                        </div>
                        <div class="px-10 py-5 rounded mb-5" style="background-color: rgba(247, 248, 250, 1)">
                            <div class="fv-row">
                                <label for="company_name" class="col-form-label required">Company Name</label>
                                <input type="text" name="company_name" id="company_name" class="form-control" required placeholder="Input Company Name">
                            </div>
                            <div class="fv-row">
                                <label for="no_telp" class="col-form-label">Phone Number</label>
                                <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="Input Phone Number">
                            </div>
                            <div class="fv-row" id="modal-add-type">
                                <label for="tipe" class="col-form-label">Type</label>
                                <select name="tipe" class="form-select" data-dropdown-parent="#modal-add-type" data-control="select2" data-placeholder="Lead">
                                    <option value=""></option>
                                    <option value="Competitor">Competitor</option>
                                    <option value="Lead">Lead</option>
                                    <option value="Customer">Customer</option>
                                    <option value="Ex-customer">Ex-customer</option>
                                    <option value="Partner">Partner</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="separator separator-solid mt-5"></div>
                            <div class="fv-row">
                                <label for="pic_names" class="col-form-label ">Contact Person</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control find-contact pe-15" data-name='contact_id[]' data-show-detail='false' placeholder="Select or add contact">
                                    <div class="find-result"></div>
                                    <div class="find-noresult"></div>
                                    <div class="find-add"></div>
                                    <button type="button" class="btn btn-primary btn-sm position-absolute end-0 top-0 btn-icon mt-1 me-1" style="display: none" data-bs-toggle="tooltip" title="Add Contact">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="fv-row">
                                <label for="latitude" class="col-form-label ">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control"  placeholder="Input Latitude">
                            </div>
                            <div class="fv-row">
                                <label for="longitude" class="col-form-label ">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control"  placeholder="Input Longitude">
                            </div>
                            <div class="fv-row">
                                <label for="radius" class="col-form-label ">Radius (meters)</label>
                                <input type="number" value="500" name="radius" id="radius" class="form-control"  placeholder="Input Radius">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            @csrf
                            <button type="button" class="btn btn-white me-5" data-bs-dismiss="modal">Back</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        {{-- <div class="fv-row">
                            <label for="tag" class="col-form-label">Tag</label>
                            <input type="text" name="tag" id="tag-company" class="form-control tag" placeholder="Masukan tag">
                        </div>
                        <div class="fv-row">
                            <label for="email" class="col-form-label required">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required placeholder="Masukan email">
                        </div>

                        <div class="fv-row">
                            <label for="jumlah_karyawan" class="col-form-label">Jumlah Karyawan</label>
                            <input type="text" name="jumlah_karyawan" id="jumlah_karyawan" class="form-control" placeholder="Masukan jumlah karyawan">
                        </div>
                        <div class="fv-row">
                            <label for="budget" class="col-form-label">Budget</label>
                            <input type="text" name="budget" id="budget" class="form-control number" placeholder="Masukan budget">
                        </div>
                        <div class="fv-row">
                            <label for="alamat" class="col-form-label required">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control" required placeholder="Masukan alamat">
                        </div>
                        <div class="fv-row" id="modal-add-prov">
                            <label for="province" class="col-form-label required">Provinsi</label>
                            <select name="province" class="form-select" data-dropdown-parent="#modal-add-prov" required data-control="select2" data-placeholder="Pilih provinsi">
                                <option value=""></option>
                                @foreach ($province as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row" id="modal-add-city">
                            <label for="city" class="col-form-label required">Kota</label>
                            <select name="city" class="form-select" required data-dropdown-parent="#modal-add-city" data-control="select2" data-placeholder="Pilih kota">
                                <option value=""></option>
                                @foreach ($city as $item)
                                    <option value="{{ $item->id }}" data-prov="{{ $item->prov_id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row">
                            <label for="kode_pos" class="col-form-label required">Kode Pos</label>
                            <input type="text" name="kode_pos" id="kode_pos" class="form-control" required placeholder="Masukan kode pos">
                        </div>
                        <div class="separator separator-solid my-3"></div>
                        <div class="fv-row">
                            <label for="pic_names" class="col-form-label ">Kontak yang bersangkutan</label>
                            <div class="input-group mb-5">
                                <input type="text" name="pic_names" id="pic_names" class="form-control border-right-0"
                                    placeholder="Masukkan Kontak yang bersangkutan">
                                <div class="input-group-text bg-transparent">
                                    <button type="button" class="btn btn-primary btn-sm" id="btn-add-contact"
                                        style="display: none">Tambah</button>
                                </div>
                            </div>
                            <input type="hidden" name="con_id" id="con_id">
                            <div id="autocomplete-div-contact"></div>
                            <div id="div-contact">
                            </div>
                        </div> --}}
                        {{-- @if ($vars->where("var_type", "company")->count() > 0)
                        <div class="separator separator-solid mt-5"></div>
                        @endif
                        @foreach ($vars->where("var_type", "company") as $item)
                            <div class="fv-row">
                                <label for="var{{ $item->id }}" class="col-form-label">{{ ucwords($item->parameter_name) }}</label>
                                <input type="text" name="var[{{ $item->id }}]" id="var{{ $item->id }}" class="form-control {{ $item->paramter_type == "Date" ? "tempusDominus" : "" }}" value="" placeholder="{{ ucwords($item->parameter_name) }}">
                            </div>
                        @endforeach --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form action="{{ route('crm.list.add', ['type' => "contact"]) }}" method="post">
        <div class="modal fade" tabindex="-1" id="modalAddKontak">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body px-15 py-10">
                        <div class="d-flex mb-5 align-items-center">
                            <div class="symbol symbol-50px me-5">
                                <div class="symbol-label bg-light-primary"><i class="fa fa-phone text-primary fs-2"></i></div>
                            </div>
                            <div class="d-flex flex-column justify-content-end">
                                <span class="fs-3 fw-bold">Add Data Contact</span>
                                <span>Register data for List Contact</span>
                            </div>
                        </div>
                        <div class="px-10 py-5 rounded mb-5" style="background-color: rgba(247, 248, 250, 1)">
                            <div class="fv-row">
                                <label for="sku" class="col-form-label required">Full Name</label>
                                <input type="text" name="sku" id="sku" class="form-control" required placeholder="Input Full Name">
                            </div>
                            <div class="fv-row">
                                <label for="jabatan" class="col-form-label">Job Title</label>
                                <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Input Job Title">
                            </div>
                            <div class="fv-row" id="modal-kontak-type">
                                <label for="tipe" class="col-form-label">Type</label>
                                <select name="tipe" id="tipe" class="form-select" data-dropdown-parent="#modal-kontak-type" data-control="select2" data-placeholder="Lead">
                                    <option value=""></option>
                                    <option value="Competitor">Competitor</option>
                                    <option value="Customer">Customer</option>
                                    <option value="Vendor">Vendor</option>
                                    <option value="Lead">Lead</option>
                                    <option value="Ex-customer">Ex-customer</option>
                                    <option value="Partner">Partner</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="separator separator-solid my-3"></div>
                            <div class="fv-row" id="modal-kontak-perusahaan">
                                <label for="company" class="col-form-label">Company</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control find-company pe-15" data-name='company' data-multiple="true" placeholder="Select or add company">
                                    <div class="find-result">
                                    </div>
                                    <div class="find-noresult">
                                    </div>
                                    <div class="find-add">

                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm position-absolute end-0 top-0 btn-icon mt-1 me-1" style="display: none" data-bs-toggle="tooltip" title="Add Contact">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            @csrf
                            <button type="button" class="btn btn-white me-5" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        {{-- comment --}}
                            {{-- <div class="fv-row">
                                <label for="tag" class="col-form-label">Tag</label>
                                <input type="text" name="tag" id="tag-kontak" class="form-control tag" placeholder="Masukan tag">
                            </div>
                            <div class="fv-row">
                                <label for="no_telp" class="col-form-label required">Nomor Telepon</label>
                                <input type="text" name="no_telp" id="no_telp" class="form-control" required placeholder="Masukan nomor telepon">
                            </div>
                            <div class="fv-row">
                                <label for="email" class="col-form-label required">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required placeholder="Masukan email">
                            </div>
                            <div class="fv-row">
                                <label for="alamat" class="col-form-label required">Alamat</label>
                                <input type="text" name="alamat" id="alamat" class="form-control" required placeholder="Masukan alamat">
                            </div>
                            <div class="fv-row" id="modal-kontak-prov">
                                <label for="province" class="col-form-label required">Provinsi</label>
                                <select name="province" data-dropdown-parent="#modal-kontak-prov" class="form-select" required data-control="select2" data-placeholder="Pilih provinsi">
                                    <option value=""></option>
                                    @foreach ($province as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row" id="modal-kontak-city">
                                <label for="city" class="col-form-label required">Kota</label>
                                <select name="city" class="form-select" data-dropdown-parent="#modal-kontak-city" required data-control="select2" data-placeholder="Pilih kota">
                                    <option value=""></option>
                                    @foreach ($city as $item)
                                        <option value="{{ $item->id }}" data-prov="{{ $item->prov_id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="fv-row">
                                <label for="kode_pos" class="col-form-label required">Kode Pos</label>
                                <input type="text" name="kode_pos" id="kode_pos" class="form-control" required placeholder="Masukan kode pos">
                            </div>
                            @if ($vars->where("var_type", "contact")->count() > 0)
                                <div class="separator separator-solid mt-5"></div>
                            @endif
                            @foreach ($vars->where("var_type", "contact") as $item)
                                <div class="fv-row">
                                    <label for="var{{ $item->id }}" class="col-form-label">{{ ucwords($item->parameter_name) }}</label>
                                    <input type="text" name="var[{{ $item->id }}]" id="var{{ $item->id }}" class="form-control {{ $item->parameter_type == "Date" ? "tempusDominus" : "" }}" value="" placeholder="{{ ucwords($item->parameter_name) }}">
                                </div>
                            @endforeach --}}
                        {{-- comment --}}
                    </div>

                    {{-- <div class="modal-footer">
                        @csrf
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    </div> --}}
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" tabindex="-1" id="modalAddFile">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('crm.list.add', ['type' => "file"]) }}" method="post" enctype="multipart/form-data">
                    <div class="modal-body px-15 py-10">
                        <div class="d-flex mb-5 align-items-center">
                            <div class="symbol symbol-50px me-5">
                                <div class="symbol-label bg-light-primary"><i class="fa fa-file text-primary fs-2"></i></div>
                            </div>
                            <div class="d-flex flex-column justify-content-end">
                                <span class="fs-3 fw-bold">Add File</span>
                                <span>Register data for List File</span>
                            </div>
                        </div>
                        <div class="px-10 py-5 rounded mb-5" style="background-color: rgba(247, 248, 250, 1)">
                            <div class="fv-row">
                                <label class="col-form-label required">File Name</label>
                                <input type="text" name="file_name" class="form-control" required placeholder="Input File Name">
                            </div>
                            <div class="fv-row upload-file">
                                <label for="file" class="col-form-label required w-100">Upload File</label>
                                <label for="add-file" data-toggle="upload_file"
                                    class="btn btn-outline btn-outline-primary btn-sm">
                                    <i class="fa fa-file"></i>
                                    Attachment
                                </label>
                                <span class="upload-file-label">Max 25 mb</span>
                                <input id="add-file" style="display: none" data-toggle="upload_file"
                                    name="attachment" accept=".jpg, .png, .pdf" type="file" />
                            </div>
                            <div class="fv-row">
                                <label class="col-form-label">File Url</label>
                                <input type="text" name="file_url" class="form-control" placeholder="Input File URL">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            @csrf
                            <button type="button" class="btn btn-white me-5" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div
        id="kt_drawer_example_basic"

        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#kt_drawer_example_basic_button"
        data-kt-drawer-close="#kt_drawer_example_basic_close"
        data-kt-drawer-width="{default : '50%', md: '50%', sm: '500px'}">
        <div class="card rounded-0 w-100" id="drawer-content">

        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                    <span class="fw-bold fs-3">Are you sure want to archive?</span>
                    <span class="text-center">Are you sure you want to archive <span id="leads-label"></span>? You can see archived items on Archive Page.</span>
                    <div class="d-flex align-items-center mt-5">
                        <a href="" id="leads-url" class="btn btn-white">Yes</a>
                        <button class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--begin::View component-->
<div id="kt_drawer_list_extend"

    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_example_advanced_button"
    data-kt-drawer-close="#kt_drawer_example_advanced_close"
    data-kt-drawer-name="docs"
    data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default : '45%', md: '45%', sm: '500px'}"
    data-kt-drawer-direction="end">
    <div class="card rounded-0 w-100" id="drawer-advance">
    </div>
</div>
<!--end::View component-->

<div class="modal fade" tabindex="-1" id="modalHierarchy">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="d-flex flex-column">
                    <div class="align-items-center bg-secondary d-flex justify-content-between p-5 rounded-top">
                        <span class="fw-bold">Hierarchy</span>
                        <button class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div id="hierarchy-div" class="min-h-100px p-5"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route("crm.list.import", 'company') }}" method="post" enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modalCsv">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <span class="fw-bold">Add Multiple Company</span>
                        <span>Download template below and write your data transfer.</span>
                        <div class="p-3 my-5 rounded bg-secondary d-flex justify-content-between align-items-center">
                            <i class="fi fi-rr-file-excel text-primary fs-2"></i>
                            <span class="fw-bold">Template_add_multiple_company.xlsx</span>
                            <a href="{{ asset("media/Template_add_multiple_contact.xlsx") }}" download="download" class="btn btn-icon btn-sm">
                                <i class="fa fa-download text-dark"></i>
                            </a>
                        </div>
                        <div class="d-flex my-5 rounded flex-column p-5 align bg-secondary align-items-center">
                            <i class="fa fa-download text-primary fs-2"></i>
                            <span class="fw-bold">Choose the file to be Imported</span>
                            <span class="text-muted text-center">[only xls,xlsx and csv formats are supported] Maximum upload file size is 5 MB.</span>
                            <div class="fv-row p-5 upload-file">
                                <label for="upload-file-csv-company" data-toggle="upload_file"
                                    class="btn btn-primary btn-sm">
                                    <i class="fa fa-file"></i>
                                    Upload File
                                </label>
                                <span class="upload-file-label"></span>
                                <input id="upload-file-csv-company" style="display: none" data-toggle="upload_file"
                                    name="file" accept=".xls, .xlsx, .csv" type="file" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    @csrf
                    <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form action="{{ route("crm.list.import", 'contact') }}" method="post" enctype="multipart/form-data">
    <div class="modal fade" tabindex="-1" id="modalCsvContact">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <span class="fw-bold">Add Multiple Contact</span>
                        <span>Download template below and write your data transfer.</span>
                        <div class="p-3 my-5 rounded bg-secondary d-flex justify-content-between align-items-center">
                            <i class="fi fi-rr-file-excel text-primary fs-2"></i>
                            <span class="fw-bold">Template_add_multiple_contact.xlsx</span>
                            <a href="{{ asset("media/Template_add_multiple_contact.xlsx") }}" download="download" class="btn btn-icon btn-sm">
                                <i class="fa fa-download text-dark"></i>
                            </a>
                        </div>
                        <div class="d-flex my-5 rounded flex-column p-5 align bg-secondary align-items-center">
                            <i class="fa fa-download text-primary fs-2"></i>
                            <span class="fw-bold">Choose the file to be Imported</span>
                            <span class="text-muted text-center">[only xls,xlsx and csv formats are supported] Maximum upload file size is 5 MB.</span>
                            <div class="fv-row p-5 upload-file">
                                <label for="upload-file-csv-contact" data-toggle="upload_file"
                                    class="btn btn-primary btn-sm">
                                    <i class="fa fa-file"></i>
                                    Upload File
                                </label>
                                <span class="upload-file-label"></span>
                                <input id="upload-file-csv-contact" style="display: none" data-toggle="upload_file"
                                    name="file" accept=".xls, .xlsx, .csv" type="file" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    @csrf
                    <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" tabindex="-1" id="modalDeleteComment">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                        <span class="fw-bold fs-2">Delete Comment</span>
                    </div>
                    <span>Are you sure you want to delete the comment?</span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <a href="#" class="btn btn-danger">Hapus</a>
            </div>
        </div>
    </div>
</div>

@component('layouts.components.fab', [
    "fab" => [
        ["label" => "Add new Opportunity", "url" => route("crm.lead.index")."?m=modal_add"],
        ["label" => "Add new contact", 'toggle' => 'data-bs-toggle="modal" data-bs-target="#modalAddKontak"'],
        ["label" => "Add new company", 'toggle' => 'data-bs-toggle="modal" data-bs-target="#modalAdd"'],
    ]
])
@endcomponent

@component('layouts.components.modal_address')

@endcomponent

@endsection

@section('custom_script')
    {{-- <script src="{{ asset("theme/assets/plugins/custom/draggable/draggable.bundle.js") }}"></script> --}}
    <link href="{{ asset('theme/jquery-ui/jquery-ui.css') }}" rel="Stylesheet">
    <script src="{{ asset('theme/jquery-ui/jquery-ui.js') }}"></script>
    <link href="{{ asset("theme/assets/plugins/custom/jstree/jstree.bundle.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("theme/assets/plugins/custom/jstree/jstree.bundle.js") }}"></script>
    <script>

        var table_list = {}

        toggle_file()

        $("table.display-list").each(function(table_index){
            var id = $(this).attr("id")
            table_list[id] = $(this).DataTable({
                // dom : `<"d-flex align-items-center justify-content-between justify-content-md-end"f>t<"dataTable-length-info-label me-3">lip`
                dom : `<"d-flex flex-column"<"d-flex align-items-center justify-content-start"f<"d-none d-md-inline ms-5">>>t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">l><"col-sm-12 col-md-7 d-flex align-items-center"p>>`,
                "fnDrawCallback": function( oSettings ) {
                    try {
                        KTMenu.createInstances();
                        $("[data-bs-toggle=tooltip]").tooltip()
                    } catch (error) {

                    }
                },
                "oSearch": {"bSmart": false},
                "search": {
                    "regex": true
                },
                buttons : [
                    {
                        extend : 'excel',
                    }
                ],
                initComplete : function(){

                    var _selDataTable = $(".dataTables_length").find("select")
                    _selDataTable.addClass("border-0 bg-white")
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
                        _input.attr("placeholder", "Cari " + id_split2[1])
                        $(this).contents().filter(function(){ return this.nodeType != 1; }).remove();
                        $(el).insertBefore(input)
                        $(this).addClass("d-lg-block d-none mb-5 mb-lg-0 position-relative w-100")
                    })

                    var _selParent = $("#" + $(this).attr("id") + "_filter").parent()
                    var fId = "filter-by-column-"+$(this).attr("id")
                    _selParent.append("<button class='btn d-none d-md-inline btn-secondary btn-sm ms-5 collapsed' data-bs-toggle='collapse' data-bs-target='#"+fId+"'><i class='la la-filter fs-2'></i> Filter</button>")

                    var _selParentDiv = $(_selParent).parent()
                    $(_selParentDiv).attr("id", "accordion-"+$(this).attr("id"))
                    $(_selParentDiv).addClass("accordion")
                    var _filterColumn = "<div class='row align-items-center w-100 collapse' id='"+fId+"' data-bs-parent='accordion-"+$(this).attr("id")+"'></div>"
                    _selParentDiv.append($(_filterColumn))
                    _selParentDiv.addClass("mb-5")

                    var cols = $(this).data('col-filter')

                    var columns = cols.toString().split(",")

                    // let sortSelect = document.createElement('select');
                    // sortSelect.add(new Option(''));
                    // $(sortSelect).attr("data-control", "select2")
                    // $(sortSelect).attr("data-placeholder", "Sort")
                    // $(sortSelect).attr("data-allow-clear", true)
                    // $(sortSelect).addClass("form-select")
                    // // $(select).addClass("")
                    // var div = document.createElement("div")
                    // $(div).addClass('d-none d-md-flex col')
                    // $(div).html(sortSelect)
                    // $(`#${fId}`).append(div)
                    // // column.footer().replaceChildren(select);

                    // // Apply listener for user change in value

                    // sortSelect.add(new Option("Newest-oldest", 1));
                    // sortSelect.add(new Option("Oldest-newest", 2));
                    // sortSelect.add(new Option("A to Z", 3));
                    // sortSelect.add(new Option("Z to A", 4));

                    this.api()
                        .columns()
                        .every(function (index) {
                            let column = this;

                            if(columns.includes(index.toString())){
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
                                $(div).html(select)
                                $(`#${fId}`).append(div)
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
                                        var v = d
                                        let regexForHTML = /<([A-Za-z][A-Za-z0-9]*)\b[^>]*>(.*?)<\/\1>/;
                                        if(!regexForHTML.test(v)){
                                            select.add(new Option(v));
                                        }
                                        if(index == columns[0].length - 2){
                                            v = $(d).text()
                                        }
                                    });
                            }
                        });

                        let select = document.createElement('select');
                        select.add(new Option(''));
                        $(select).attr("data-control", "select2")
                        $(select).attr("data-placeholder", "Completeness of Data")
                        $(select).attr("data-allow-clear", true)
                        $(select).addClass("form-select")
                        // $(select).addClass("")
                        var div = document.createElement("div")
                        $(div).addClass('d-none d-md-flex col')
                        $(div).html(select)
                        $(`#${fId}`).append(div)
                        // column.footer().replaceChildren(select);

                        // Apply listener for user change in value

                        select.add(new Option("Complete", 1));
                        select.add(new Option("Incomplete", -1));
                        var api = this.api()
                        $(select).change(function(){
                            var val = $(this).val()
                            console.log(val)
                            var f = val
                            var df = DataTable.util.escapeRegex(f)
                            var cl = api.columns()[0].length
                            if(val != ""){
                                api.columns(cl - 1).search('^' + df + "$", true, false)
                                    .draw();
                            } else {
                                api.columns(cl - 1).search("")
                                    .draw();
                            }
                        })

                        // $(select).change(function () {
                        //     var val = DataTable.util.escapeRegex($(this).val());

                        //     column
                        //         .search(val ? '^' + val + '$' : '', true, false)
                        //         .draw();
                        // });

                    $("select[data-control=select2]").select2()
                }
            })
        })

        function deleteComment(me){
            var id = $(me).data('id');
            var view = $(me).data("view")
            $('#modalDeleteComment').modal('show');
            $('.modal-footer a').attr('href', "{{ route('crm.list.comment.delete') }}/"  + view +"/" + id);
        }

        function openReplies(me){
            var show = false;
            if($(me).find(".reply-show").is(":hidden")){
                $(me).find(".reply-show").show()
                $(me).find(".reply-close").hide()
                show = true
            } else {
                $(me).find(".reply-show").hide()
                $(me).find(".reply-close").show()
            }

            var view = $(me).data("view")
            var tp = $(me).data("type")
            var id = $(me).data("id")
            var comment = $(me).data("comment")

            var chead = $(me).parents(".comment-header")
            var csec = $(chead).parent().find(".comment-data")

            var url = "{{ route("crm.list.comment.view") }}/" + view +"/" + tp + "/" + id
            if(comment != undefined){
                url += "?comment=" + comment
            }

            csec.html("")
            csec.addClass("spinner-border")
            if(show){
                $.ajax({
                    url : url,
                    type : "get",
                    dataType : "json",
                    success : function(data){
                        console.log(data)
                        csec.removeClass("spinner-border")
                        csec.html(data.view)
                        toggle_file()
                    }
                })
            } else {
                csec.removeClass("spinner-border")
            }
        }

        function closeComment(me){
            $(me).parents(".comment-section").hide()
        }

        function openComment(me){
            var chead = $(me).parents(".comment-header")
            var csec = $(chead).next()
            csec.show()
        }

        function removeDet(me){
            $(me).parent().remove()
        }

        function add_email(){
            $("[data-toggle=email]").each(function(){
                var me = $(this)
                $(me).find("[data-button]").click(function(){
                    var email = $(me).find("input[name=email_input]")
                    if(email.val() != ""){
                        var el = "<div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>"
                            el += `<div class="d-flex align-items-center">
                                <i class="la la-mail-bulk me-1 text-primary"></i>
                                <span>${email.val()}</span>
                            </div>`
                            el += `<input type='hidden' name='email[]' value="${email.val()}">`
                            el += `<button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>`
                            el += "</div>"

                        $(me).find(".d-email-user").append($(el))
                        email.val("")
                    }
                })
            })
        }

        function add_phone(){
            $("[data-toggle=phone]").each(function(){
                var me = $(this)
                $(this).find("[data-button]").click(function(){
                    var phone = $(me).find("input[name=phone_number]")
                    var phone_type = $(me).find("select[name=phone_type]").val()
                    console.log(phone, phone_type)
                    if(phone.val() != ""){
                        if(phone_type == ""){
                            phone_type = "Work"
                        }
                        var el = "<div class='align-items-center bg-white border d-flex justify-content-between p-2 rounded mb-3'>"
                            el += `<div class="d-flex align-items-center">
                                <i class="la la-phone me-1 text-primary"></i>
                                <span>${phone_type} : ${phone.val()}</span>
                            </div>`
                            el += `<input type='hidden' name='phone[]' value="${phone.val()}">`
                            el += `<input type='hidden' name='phone_types[]' value="${phone_type}">`
                            el += `<button type="button" onclick='removeDet(this)' class="btn p-0"><i class="fs-3 la la-trash text-danger"></i></button>`
                            el += "</div>"

                        $(me).find(".d-phone-user").append($(el))
                        phone.val("")
                        $(me).find("select[name=phone_type]").val("").trigger('change')
                    }
                })
            })
        }

        function archiveItem(me){
            var _id = $(me).data("id")
            var uri = $(me).data("url")
            console.log($(me).data('name'))
            $("#leads-label").text($(me).data("name"))
            $("#leads-url").attr("href", uri)
        }

        function init_tags(tag) {
            $.ajax({
                url: encodeURI("{{ route('crm.products.index') }}?a=tags"),
                type: "get",
                dataType: "json"
            }).then(function(resp) {
                var tagify = new Tagify(document.querySelector(`#${tag}`), {
                    whitelist: resp.tags,
                    dropdown: {
                        maxItems: 20, // <- mixumum allowed rendered suggestions
                        classname: "", // <- custom classname for this dropdown, so it could be targeted
                        enabled: 0, // <- show suggestions on focus
                        closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                    },
                    callbacks: {
                        "change": function(e) {}
                    }
                })
            })
        }

        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_example_basic");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");

        var blockUI = new KTBlockUI(target);

        var dwAdvance = document.getElementById("kt_drawer_list_extend");
        var dw = KTDrawer.getInstance(dwAdvance);
        dw.on("kt.drawer.show", function() {
            $(dwAdvance).css("margin-right", drawer.lastWidth)
        });
        dw.on("kt.drawer.after.hidden", function() {
            setTimeout(function(){
                $(dwAdvance).css("margin-right", 0)
            }, 250);
        });

        var blockDrawerAdvance = new KTBlockUI(document.querySelector("#drawer-advance"));

        var targetH = document.querySelector("#hierarchy-div");

        var blockUIH = new KTBlockUI(targetH);

        function showHierarchy(id){
            $("#modalHierarchy #hierarchy-div").html("")
            $("#modalHierarchy").modal("show")

            blockUIH.block()

            $.ajax({
                url : '{{ route('crm.list.hierarchy', ['type' => "company"]) }}/' + id,
                type : "get",
            }).then(function(resp){
                blockUIH.release()
                $("#modalHierarchy #hierarchy-div").html(resp)
                $('#modalHierarchy #kt_docs_jstree_basic').jstree({
                    "core": {
                        "themes": {
                            "responsive": false,
                            "icons" : false
                        },
                        // so that create works
                        "check_callback": true,
                        'data': {
                            'url': function(node) {
                                return '{{ route('crm.list.hierarchy', ['type' => "company"]) }}/' + id; // Demo API endpoint -- Replace this URL with your set endpoint
                            },
                            'data': function(node) {
                                return {
                                    'parent': node.id
                                };
                            }
                        }
                    },
                    "state": {
                        "key": "demo3"
                    },
                    "plugins": ["dnd", "state", "types"],
                });
            })
        }

        function show_detail(target, type, id){
            $(target + "").html("")
            target == "#drawer-advance" ? blockUI.block() : blockDrawerAdvance.block();
            var f = target == "#drawer-advance" ? "?f=" + type + "&id_list=" + id : ""
            $.ajax({
                url : `{{ route("crm.list.index") }}/${type}/view/${id}` + f,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                target == "#drawer-advance" ? blockUI.release() : blockDrawerAdvance.release();
                $(target + "").html(resp.view)

                toggle_file()
                $(target + " [data-bs-toggle=collapse_title]").click(function(){
                    if($(this).find('.accordion-button').hasClass("collapsed") == false) {
                        $(target + " [data-accordion=collapsed]").prev().addClass("d-none")
                        $(target + " [data-accordion=collapsed]").show()
                    } else {
                        $(target + " [data-accordion=collapsed]").prev().removeClass("d-none")
                        $(target + " [data-accordion=collapsed]").hide()
                    }
                })

                $(target + " [data-control=select2]").select2()
                let _editor = {}
                $(target + " .ck-editor").each(function(){
                    var _id = $(this).attr("id")
                    ClassicEditor.create(this, {
                        toolbar: ['link', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', 'blockQuote' ],
                    }).then(function(editor){
                        _editor[_id] = editor
                    })
                })

                $(target + " .tempusDominus").each(function(){
                    var _id = $(this).attr("id")
                    var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                        display : {
                            viewMode: "calendar",
                            components: {
                                decades: true,
                                year: true,
                                month: true,
                                date: true,
                                hours: false,
                                minutes: false,
                                seconds: false
                            }
                        },
                        localization: {
                            locale: "id",
                            startOfTheWeek: 1,
                            format: "dd/MM/yyyy"
                        }
                    });

                    Inputmask({
                        "mask" : "99/99/9999"
                    }).mask($(this));
                })

                $(target + " #form-lead input:required, select:required, textarea:required").each(function(){
                    var requireds = $(target + " #form-lead input:required, select:required, textarea:required").length
                    var filled = []

                    $(target + " #form-lead input:required, select:required, textarea:required").each(function(){
                        if($(this).val() != ""){
                            filled.push($(this).val())
                        }
                    })

                    if(filled.length != requireds){
                        $(target + " #form-lead button[type=submit]").prop("disabled", true)
                    } else {
                        $(target + " #form-lead button[type=submit]").prop("disabled", false)
                    }
                })

                $(target + " #form-lead input:required, select:required, textarea:required").change(function(){
                    var requireds = $(target + " #form-lead input:required, select:required, textarea:required").length
                    var filled = []

                    $(target + " #form-lead input:required, select:required, textarea:required").each(function(){
                        if($(this).val() != ""){
                            filled.push($(this).val())
                        }
                    })

                    if(filled.length != requireds){
                        $(target + " #form-lead button[type=submit]").prop("disabled", true)
                    } else {
                        $(target + " #form-lead button[type=submit]").prop("disabled", false)
                    }
                })

                init_contacts(id)
                init_company(id)
                init_opporunity(id, type)
                add_phone()
                add_email()
            })
        }

        var tabState = "{{ $t ?? 'perusahaan' }}"

        function change_tab(btn){
            tabState = btn
            $("button.btn-add").hide()
            $(".input-search").hide()
            $(`#btn-add-${btn}`).show()
            $(`#search-${btn}`).show()
        }
        // table_list.buttons(0,0).trigger()
        // table_list.buttons(1,0).trigger()
        // table_list.buttons(2,0).trigger()

        function uploadCsv(){
            if(tabState == "perusahaan"){
                $("#modalCsv").modal("show")
            } else if(tabState == "kontak") {
                $("#modalCsvContact").modal("show")
            }
        }

        function exportToExcel(){
            table_list["table-"+tabState].button(0).trigger()
        }

        function getTag(){
            return $.ajax({
                url: encodeURI("{{ route('crm.list.index') }}?a=contact"),
                type: "get",
                dataType: "json"
            })
        }

        function removeCompany(me){
            $(me).parents("div.position-relative").find('input[type=text]').prop("disabled", false)
            $(me).parents("form").find("button[type=submit]").prop("disabled", true)
            $(me).parents(".company-item").remove()
        }

        function removeOpporunity(me, id){
            var multiple = $(this).data("multiple") ?? true
            $(me).parents("div.find-add").append("<input type='hidden' name='op_remove[]' value='"+id+"'>")
            $(me).parents("form").find("input.find-opportunity").prop("disabled", !multiple)
            $(me).parents(".opportunity-item").remove()
        }

        function init_tag(tag, tags) {
            var tagify = new Tagify(document.querySelector(`#${tag}`), {
                whitelist: tags,
                dropdown: {
                    maxItems: 20, // <- mixumum allowed rendered suggestions
                    classname: "", // <- custom classname for this dropdown, so it could be targeted
                    enabled: 0, // <- show suggestions on focus
                    closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
                },
                callbacks: {
                    "change": function(e) {}
                }
            })
        }

        function init_contacts(id = null){
            $("input.find-contact").each(function(){
                var pName = $(this).data("name")
                var showDetail = $(this).data("show-detail")
                var divAdd = $(this).parent().find(".find-add")
                var divResult = $(this).parent().find(".find-noresult")
                var input = $(this)
                $(this).on("keyup", function(){
                    $(divResult).html("")
                    $(input).parent().find("button").hide()
                    $(this).autocomplete({
                        source: function( request, response ) {
                                $.ajax( {
                                url: encodeURI("{{ route('crm.list.index') }}?a=get-contact&id=" + id),
                                dataType: "json",
                                data: {
                                    term: request.term
                                },
                                success: function( data ) {
                                        if(data.count == 0){
                                            $(divResult).html(`<div class="align-items-center bg-white d-flex flex-column mt-5 rounded">
                                                <span class="mt-5">"${$(input).val()}" not found</span>
                                                <button type="button" class="btn btn-white btn-add">
                                                    <i class="fa fa-plus text-primary"></i>
                                                    <span class="text-primary">Add contact</span>
                                                </button>
                                            </div>`)
                                        }
                                        response( data.data );

                                        var btn = $(divResult).find("button.btn-add")
                                        $(btn).click(function(){
                                            $.ajax({
                                                url: "{{ route('crm.contact.add') }}",
                                                type: "POST",
                                                dataType: "json",
                                                data: {
                                                    _token: "{{ csrf_token() }}",
                                                    name: input.val(),
                                                }
                                            }).then(function(resp) {
                                                $(divResult).html("")
                                                var data = resp.data
                                                var conid = `<input type="hidden" name="${pName}" value="${data.id}">`
                                                if (showDetail != false) {
                                                    var el = `<span class="fw-bold cursor-pointer" onclick="show_detail('contacts', ${data.id}, ${id})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads_extend">${conid}${data.name},</span>`
                                                } else {
                                                    var el = `<span class="fw-bold">${conid}${data.name},</span>`
                                                }
                                                $(divAdd).append($(el))
                                                $(input).val("")
                                            })
                                        })
                                    }
                                } );
                            },
                        minLength: 1,
                        appendTo: $(this).next(),
                        select: function(event, ui) {
                            if(ui.item.id != null){
                                var conid = `<input type="hidden" name="${pName}" value="${ui.item.id}">`
                                if(showDetail != false){
                                    var el = `<span class="fw-bold cursor-pointer" onclick="show_detail('contacts', ${ui.item.id}, ${id})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads_extend">${conid}${ui.item.name},</span>`
                                } else {
                                    var el = `<span class="fw-bold">${conid}${ui.item.name},</span>`
                                }
                                $(divAdd).append($(el))
                            }
                            $(input).val("")
                            return false
                        },
                    });
                })
            })
        }

        function init_opporunity(id = null, type = null){
            $("input.find-opportunity").each(function(){
                var pName = $(this).data("name")
                var multiple = $(this).data("multiple")
                var divAdd = $(this).parent().find(".find-add")
                var divResult = $(this).parent().find(".find-noresult")
                $(divResult).html("")
                var input = $(this)
                $(this).on("keyup", function(){
                    $(divResult).html("")
                    $(this).autocomplete({
                        source: function( request, response ) {
                                $.ajax( {
                                    url: encodeURI("{{ route('crm.list.index') }}?a=get-opportunity&id=" + id + "&type=" + type),
                                dataType: "json",
                                data: {
                                    term: request.term
                                },
                                success: function( data ) {
                                        if(data.count == 0){
                                            $(divResult).html(`<div class="align-items-center bg-white d-flex flex-column mt-5 rounded">
                                                <span class="mt-5">"${$(input).val()}" not found</span>
                                                <button type="button" class="btn btn-white btn-add">
                                                    <i class="fa fa-plus text-primary"></i>
                                                    <span class="text-primary">Add opportunity</span>
                                                </button>
                                            </div>`)
                                        }
                                        response( data.data );

                                        var btn = $(divResult).parent().find("button.btn-add")
                                        $(btn).click(function(){
                                            $.ajax({
                                                url: "{{ route('crm.lead.store') }}",
                                                type: "POST",
                                                dataType: "json",
                                                data: {
                                                    _token: "{{ csrf_token() }}",
                                                    leads_name: input.val(),
                                                }
                                            }).then(function(resp) {
                                                $(divResult).html("")
                                                var data = resp.data
                                                var el = `<div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 opportunity-item">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                                        <span>${data.leads_name}</span>
                                                        <input type="hidden" name="${pName}" value="${data.id}">
                                                    </div>
                                                    <button type="button" onclick="removeOpporunity(this, ${data.id})" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                                        <i class="fi fi-rr-trash"></i>
                                                    </button>
                                                </div>`
                                                $(divAdd).append($(el))
                                                $("#drawer-content button[type=submit]").prop("disabled", false)
                                                $(input).prop("disabled", !multiple)
                                                $(input).val("")
                                            })
                                        })
                                    }
                                } );
                            },
                        minLength: 1,
                        appendTo: $(this).next(),
                        select: function(event, ui) {
                            if(ui.item.id != null){
                                var el = `<div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 opportunity-item">
                                    <div class="d-flex align-items-center">
                                        <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                        <span>${ui.item.name}</span>
                                        <input type="hidden" name="${pName}" value="${ui.item.id}">
                                    </div>
                                    <button type="button" onclick="removeOpporunity(this, ${ui.item.id})" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                        <i class="fi fi-rr-trash"></i>
                                    </button>
                                </div>`
                                $(divAdd).append($(el))
                                $("#drawer-content button[type=submit]").prop("disabled", false)
                            }
                            $(input).val("")
                            $(input).prop("disabled", !multiple)
                            return false
                        },
                    });
                })
            })
        }

        init_contacts()

        init_company()

        function init_company(id = null){
            $("input.find-company").each(function(){
                var pName = $(this).data("name")
                var multiple = $(this).data("multiple")
                var divAdd = $(this).parent().find(".find-add")
                var divResult = $(this).parent().find(".find-noresult")
                $(divResult).html("")
                var input = $(this)
                $(this).on("keyup", function(){
                    $(divResult).html("")
                    $(this).autocomplete({
                        source: function( request, response ) {
                                $.ajax( {
                                    url: encodeURI("{{ route('crm.list.index') }}?a=get-company&id=" + id),
                                dataType: "json",
                                data: {
                                    term: request.term
                                },
                                success: function( data ) {
                                        if(data.count == 0){
                                            $(divResult).html(`<div class="align-items-center bg-white d-flex flex-column mt-5 rounded">
                                                <span class="mt-5">"${$(input).val()}" not found</span>
                                                <button type="button" class="btn btn-white btn-add">
                                                    <i class="fa fa-plus text-primary"></i>
                                                    <span class="text-primary">Add company</span>
                                                </button>
                                            </div>`)
                                        }
                                        response( data.data );

                                        var btn = $(divResult).parent().find("button.btn-add")
                                        $(btn).click(function(){
                                            $.ajax({
                                                url: "{{ route('crm.company.add') }}",
                                                type: "POST",
                                                dataType: "json",
                                                data: {
                                                    _token: "{{ csrf_token() }}",
                                                    name: input.val(),
                                                }
                                            }).then(function(resp) {
                                                $(divResult).html("")
                                                var data = resp.data
                                                var isDetail = id == null ? "" : `onclick="show_detail('company', ${data.id}, ${id})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads_extend"`
                                                var el = `<div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 company-item">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                                        <span ${isDetail}>${data.company_name}</span>
                                                        <input type="hidden" name="${pName}" value="${data.id}">
                                                    </div>
                                                    <button type="button" onclick="removeCompany(this)" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                                        <i class="fi fi-rr-trash"></i>
                                                    </button>
                                                </div>`
                                                $(divAdd).append($(el))
                                                $("#drawer-content button[type=submit]").prop("disabled", false)
                                                $(input).prop("disabled", !multiple)
                                                $(input).val("")
                                            })
                                        })
                                    }
                                } );
                            },
                        minLength: 1,
                        appendTo: $(this).next(),
                        select: function(event, ui) {
                            if(ui.item.id != null){
                                var isDetail = id == null ? "" : `onclick="show_detail('company', ${ui.item.id}, ${id})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads_extend"`

                                var el = `<div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 company-item">
                                    <div class="d-flex align-items-center">
                                        <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                        <span class='cursor-pointer' ${isDetail}>${ui.item.name}</span>
                                        <input type="hidden" name="${pName}" value="${ui.item.id}">
                                    </div>
                                    <button type="button" onclick="removeCompany(this)" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                        <i class="fi fi-rr-trash"></i>
                                    </button>
                                </div>`
                                $(divAdd).append($(el))
                                $("#drawer-content button[type=submit]").prop("disabled", false)
                            }
                            $(input).val("")
                            $(input).prop("disabled", !multiple)
                            return false
                        },
                    });
                })
            })
        }

        function remove_instruct(me) {
            $(me).parent().remove()
        }

        function add_contact(item) {
            var el = `<div class="d-flex flex-column mb-3">
                <span>${item.name}</span>
                <span class="text-danger cursor-pointer" onclick="remove_instruct(this)">Hapus</span>
                <input type="hidden" name="contacts[]" value="${item.id}">
            </div>`

            $("#div-contact").append(el)
            $("#pic_names").val("")
        }

        function init_contact() {
            console.log("init_contact")
            $("#pic_names").autocomplete({
                source: function( request, response ) {
                    $.ajax( {
                    url: encodeURI("{{ route('crm.list.index') }}?a=get-contact"),
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                            if(data.count == 0){
                                $(input).parent().find("button").show()
                            }
                            response( data.data );
                        }
                    } );
                },
                minLength: 1,
                appendTo: "#autocomplete-div-contact",
                select: function(event, ui) {
                    $("#con_id").val(ui.item.id)
                }
            });
        }

        $(document).ready(function(){

            init_contact()

            getTag().then(function(resp){
                $(".tag").each(function(){
                    var _id = $(this).attr("id")
                    init_tag(_id, resp.tags)
                })
            })

            $("#pic_names").on("keyup change", function() {
                // if ($(this).val() != "") {
                //     $("#btn-add-contact").show()
                // } else {
                //     $("#btn-add-contact").hide()
                // }
            })

            $("select[name=province]").change(function(){
                var val = $(this).val()
                if(val != ""){
                    var selCity = $(this).parents("form").find("select[name=city]")
                    selCity.find("option").prop("disabled", true)
                    selCity.find("option[data-prov='"+val+"']").prop("disabled", false)
                    var opt = selCity.find("option:selected")
                    var prov_id = opt.data("prov")
                    console.log(prov_id)
                    if(prov_id != undefined && val != prov_id){
                        selCity.val("").trigger("change")
                    }
                }
            })

            $("select[name=city]").change(function(){
                if($(this).val() != ""){
                    var opt = $(this).find("option:selected")
                    var prov_id = opt.data("prov")
                    if(prov_id != undefined){
                        var selProv = $(this).parents("form").find("select[name=province]")
                        selProv.val(prov_id).trigger('change')
                    }
                }
            })

            $("#btn-add-contact").click(function() {
                $.ajax({
                    url: "{{ route('crm.contact.add') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: $("#pic_names").val(),
                        con_id: $("#con_id").val()
                    }
                }).then(function(resp) {
                    add_contact(resp)
                    $("#btn-add-contact").hide()
                    $("#pic_names").prop("required", false)
                })
            })

            $("input.input-search").on("keyup", function(){
                var target = $(this).data("target")
                var _table = `#table-${target}`
                var table_target = table_display.table(_table)
                table_target.search($(this).val()).draw()
            })

            @if(isset($_GET['m']))
                $("#{{ $_GET['m'] }}").modal("show")
            @endif

            Inputmask({
                "mask" : "99/99/9999"
            }).mask(".tempusDominus");

            @if (\Session::has("list_id"))
                drawer.show()
                show_detail('#drawer-content', '{{ \Session::get("list_type") }}', {{ \Session::get("list_id") }})
            @endif
        })
    </script>
@endsection
