@extends('layouts.templateCrm', ["menuCrm" => "menu_crm", 'withoutFooter' => true, "bgWrapper" => "bg-white", "style" => ["border" => "border-bottom", "box-shadow" => "none"]])

@section('css')

@endsection

@section('fixaside')
    @include('_crm.leads._aside')
@endsection

@section('content')
<div class="row h-100 w-100">
    <div class="col-12 w-100">
        <div class="card card-custom not-rounded shadow-none h-100 w-100 card-p-0">
            <div class="card-body accordion" id="kt_acc_kanban">
                <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center">
                    <div class="d-flex align-items-center">
                        <div class="d-flex flex-column justify-content-between">
                            <span class="fs-3 fw-bold"><span id="l-count">0 Opportunity</span></span>
                            <span>Here’s kanban board for list opportunities</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center ps-5">
                        <div class="position-relative">
                            <input type="text" id="search" class="form-control ps-13" placeholder="Search anything here, projects, contacts, etc...">
                            <span class="fa fa-search text-muted fs-2 ms-5 position-absolute top-25"></span>
                        </div>
                        <ul class="nav nav-tabs border mb-5 fs-6 border-0 px-5 pt-5">
                            <li class="nav-item">
                                <a class="btn bg-secondary btn-active-primary btn-icon btn-sm active me-3" data-bs-toggle="tab" href="#kanban">
                                    <span class="nav-text">
                                        <i class="fa fa-table text-active-white"></i>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="btn bg-secondary btn-active-primary btn-icon btn-sm" id="list-btn" data-bs-toggle="tab" href="#list">
                                    <span class="nav-text">
                                        <i class="fa fa-list text-active-white"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex justify-content-md-between flex-column flex-md-row">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <button type="button" class="btn btn-primary btn-icon" data-bs-toggle="collapse" data-bs-target="#kt_acc_kanban_body" style="padding: 8px">
                                <i class="fa fa-filter text-white" style="font-size: 20px"></i>
                            </button>
                        </div>
                        <select name="layout_id" id="layout_id" data-control="select2" class="form-select w-200px">
                            @foreach ($leadLayout as $item)
                                <option value="{{ $item->id }}" {{ $layoutSelected == $item->id ? "SELECTED" : "" }}>{{ $item->label }}</option>
                            @endforeach
                        </select>
                        <div class="d-flex gap-3 align-items-center">
                            <button type="button" onclick="exportOpp('business')" class="btn btn-primary btn-icon" data-bs-toggle="tooltip" title="Export Business Unit">
                                <i class="fi fi-rr-document text-white" style="font-size: 20px"></i>
                            </button>
                            <button type="button" onclick="exportOpp('outlook')" class="btn btn-primary btn-icon" data-bs-toggle="tooltip" title="Export Business Outlook">
                                <i class="fi fi-rr-document-signed text-white" style="font-size: 20px"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary text-nowrap" {{ $leadLayout->count() == 0 || $funnels->count() == 0 ? "disabled" : "" }} data-bs-toggle="modal" data-bs-target="#modal_add">
                            <i class="fa fa-plus"></i>
                            Add Opportunity
                        </button>
                    </div>
                </div>
                <div class="accordion-collapse collapse" data-bs-parent="#kt_acc_kanban" id="kt_acc_kanban_body" style="margin-top: 24px">
                    <div class="accordion-body p-0">
                        <div class="row">
                            <div class="col-3 mb-5">
                                <select name="filter[sort]" onchange="initData()" class="form-select" data-allow-clear="true" data-control="select2" data-placeholder="Sort">
                                    <option value=""></option>
                                    <option value="created_at-asc">Newest to Oldest</option>
                                    <option value="created_at-desc">Oldest to Newest</option>
                                    <option value="leads_name-asc">A to Z</option>
                                    <option value="leads_name-desc">Z to A</option>
                                </select>
                            </div>
                            <div class="col-3 mb-5">
                                <select name="filter[sales_confidence]" onchange="initData()" class="form-select" data-allow-clear="true" data-control="select2" data-placeholder="Sales Confidence">
                                    <option value=""></option>
                                    <option value="4">
                                        Commit</option>
                                    <option value="3">
                                        Best case</option>
                                    <option value="2">
                                        Run through</option>
                                    <option value="1">
                                        Nice to</option>
                                </select>
                            </div>
                            <div class="col-3 mb-5">
                                <select name="filter[priority]" onchange="initData()" class="form-select" data-allow-clear="true" data-control="select2" data-placeholder="Priority">
                                    <option value=""></option>
                                    <option value="3">
                                        High</option>
                                    <option value="2">
                                        Medium</option>
                                    <option value="1">
                                        Low</option>
                                </select>
                            </div>
                            <div class="col-3 mb-5">
                                <select name="filter[owner]" onchange="initData()" class="form-select" data-allow-clear="true" data-control="select2" data-placeholder="Opportunity Owner">
                                    <option value=""></option>
                                    @foreach ($user_name as $idUser => $userName)
                                        <option value="{{$idUser}}">{{ $userName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3 mb-5">
                                <select name="filter[company]" onchange="initData()" class="form-select" data-allow-clear="true" data-control="select2" data-placeholder="Company Name">
                                    <option value=""></option>
                                    @foreach ($comps as $idComp => $compName)
                                        <option value="{{$idComp}}">{{$compName}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body h-100 w-100 bg-secondary-crm rounded" style="margin-top: 16px; padding: 12px">
                <div class="tab-content h-100" id="myTabContent" style="padding: 0">
                    <div class="tab-pane fade show active h-100" id="kanban" role="tabpanel">
                        <div class="d-flex hover-scroll-x scroll-x h-100 min-h-500px">

                        </div>
                    </div>
                    <div class="tab-pane fade" id="list" role="tabpanel">
                        <table class="table bg-white display" id="table-opportunity">
                            <thead>
                                <tr>
                                    <th>Opportunity Name</th>
                                    {{-- <th>Tag</th> --}}
                                    <th>Funnel</th>
                                    <th>Priority</th>
                                    <th>Sales Confident</th>
                                    <th>Opportunity Owner</th>
                                    <th>Opportunity Collaborators</th>
                                    <th>Update Terakhir</th>
                                    <th class="text-nowrap"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="cari-form" name="cari-form" enctype="multipart/form-data" method="post" >
    {{ csrf_field() }}
    <div class="modal fade" tabindex="-1" id="modalMenu">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex mb-5 justify-content-end">
                        <button type="button" class="btn text-primary" stlye="float:right;" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">Close</span>
                        </button>
                        <button type="button" class="btn btn-primary" stlye="float:right;" id="cari-leads">
                            <span aria-hidden="true">Cari</span>
                        </button>
                    </div>
                    <div class="fv-row">
                        <span class="text-nowrap">Sort By:</span>
                        <select id="sorts" name="sorts" class="form-select" data-control="select2" data-hide-search="true">
                            <option value="creatd_at-asc">Newest-oldest</option>
                            <option value="creatd_at-desc">Oldest-newest</option>
                            <option value="sales_confidence-asc">Nice to - Commit</option>
                            <option value="sales_confidence-desc">Commit - Nice to</option>
                            <option value="level_priority-asc">Low - High</option>
                            <option value="level_priority-desc">High - Low</option>
                            <option value="leads_name-asc">A to Z</option>
                            <option value="leads_name-desc">Z to A</option>
                        </select>
                    </div>
                    <hr />
                    <p id="cl_funnel">FUNNEL</p>
                    <div id="menu_funnel" class="d-flex flex-column">
                        @foreach($m_menu['FUNNEL'] as $m_menus)
                        <div class="form-check mb-5">
                            <input class="form-check-input" type="checkbox"name="funnels[]" value="{{$m_menus['id']}}" id="funnel_{{$m_menus['id']}}" />
                            <label class="form-check-label text-dark" for="funnel_{{$m_menus['id']}}">
                                {{$m_menus['label']}}
                            </label>
                        </div>
                        {{-- <p>
                            <input type="checkbox" id="funnel_{{$m_menus['id']}}" name="funnels" value="{{$m_menus['id']}}">
                            <label for="funnel_{{$m_menus['id']}}">{{$m_menus['label']}}</label>
                        </p> --}}
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="kt_drawer_leads"
    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_example_basic_button"
    data-kt-drawer-close="#kt_drawer_example_basic_close"
    data-kt-drawer-width="{default : '45%', md: '45%', sm: '500px'}">
    <div class="card rounded-0 w-100" id="drawer-content">

    </div>
</div>

<!--begin::View component-->
<div id="kt_drawer_leads_extend"

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

<form action="{{ route("crm.lead.store") }}" method="post">
    @csrf
    @component('layouts._crm_modal')
        @slot('modalId')
            modal_add
        @endslot
        @slot('modalSize')
            modal-lg
        @endslot
        @slot('modalTitle')
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px">
                    <div class="symbol-label bg-primary me-5">
                        <i class="fa fa-briefcase text-white fs-2"></i>
                    </div>
                </div>
                <div class="d-flex flex-column justify-content-between">
                    <span class="fw-bold">Add Opportunity</span>
                    <span class="text-muted fw-normal fs-base">Add opportunities that are being worked on</span>
                </div>
            </div>
        @endslot
        @slot('modalContent')
            <div class="fv-row">
                <label for="" class="col-form-label required">Opportunity Name</label>
                <input type="text" class="form-control" required name="leads_name" placeholder="Input Opportunity Name">
            </div>
            <div class="fv-row" id="opp-source">
                <label for="" class="col-form-label">Opportunity Source</label>
                <select name="source" class="form-select" data-control="select2" data-dropdown-parent="#opp-source" data-placeholder="Canvasing">
                    <option value=""></option>
                    <option value="Canvasing">Canvasing</option>
                    <option value="Web">Web</option>
                    <option value="Phone Inquiry">Phone Inquiry</option>
                    <option value="Partner Referral">Partner Referral</option>
                    <option value="External Partner">External Partner</option>
                    <option value="Partner">Partner</option>
                    <option value="Public Relations">Public Relations</option>
                    <option value="Trade Show">Trade Show</option>
                    <option value="Word of mouth">Word of mouth</option>
                    <option value="Employee Referral">Employee Referral</option>
                    <option value="Purchased List">Purchased List</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="fv-row">
                <label for="" class="col-form-label">Pipeline</label>
                <select name="pipeline" data-dropdown-parent="#modal_add" data-control="select2" data-placeholder="Select Pipeline" class="form-select">
                    <option value=""></option>
                    @foreach ($leadLayout as $item)
                        <option value="{{ $item->id }}" {{ $layoutSelected == $item->id ? "SELECTED" : "" }}>{{ $item->label }}</option>
                    @endforeach
                </select>
            </div>
            <hr>
            <div class="fv-row" id="opp-comp">
                <label for="" class="col-form-label">Company</label>
                <div class="position-relative">
                    <input type="text" class="form-control find-company pe-15" data-name='id_client' data-multiple="false" placeholder="Select or add company">
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
            <hr>
            <div class="fv-row">
                <label for="" class="col-form-label">Contact Person</label>
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
            <hr>
            <div class="fv-row">
                <label for="" class="col-form-label">Products</label>
                <select name="product[]" class="form-select" data-control="select2" data-dropdown-parent="#opp-comp" data-placeholder="Select Product or Solutions">
                    <option value=""></option>
                    @foreach ($products as $item)
                        <option value="{{ $item->id }}">{{ $item->label }}</option>
                    @endforeach
                </select>
            </div>
        @endslot
        @slot('modalFooter')
            <input type="hidden" name="pipeline" value="{{ $layoutSelected }}">
            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        @endslot
    @endcomponent
</form>

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
{{--
<div class="modal fade" tabindex="-1" id="modalDeleteFunnel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('crm.funnel.delete') }}" method="post">
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                            <span class="fw-bold fs-2">Hapus Funnel</span>
                        </div>
                        <span>Apakah kamu yakin akan menghapus funnel <span id="funnel-label"></span>?</span>
                        <div class="fv-row">
                            <label for="" class="col-form-label required">Pindah Funnel</label>
                            <select name="funnel" id="funnel-select" class="form-select" data-control="select2" data-placeholder="Pilih Funnel" required>
                                <option value=""></option>
                                @foreach ($funnels as $fun)
                                    <option value="{{ $fun->id }}" class="opt-fun">{{ $fun->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="fid" id="fid-delete">
                    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</a>
                </div>
            </form>
        </div>
    </div>
</div> --}}

<div class="modal fade" tabindex="-1" id="modalAddLayout">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Lead Panels</h3>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <div class="d-flex justify-content-end mb-5">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-stacked-modal="#modalAddLayoutForm">
                            <i class="fa fa-plus"></i> Create Panels
                        </button>
                    </div>
                    <table class="table display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Sequence</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leadLayout as $i => $ll)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $ll->label }}</td>
                                    <td align="center">
                                        @if ($i > 0)
                                        <a href="{{ route('crm.layout.edit', ['id' => $ll->id, "type" => "up"]) }}" class="btn btn-icon btn-primary btn-sm">
                                            <i class="fa fa-arrow-up"></i>
                                        </a>
                                        @endif
                                        @if ($i < $leadLayout->count() - 1)
                                        <a href="{{ route('crm.layout.edit', ['id' => $ll->id, "type" => "down"]) }}" class="btn btn-icon btn-warning btn-sm">
                                            <i class="fa fa-arrow-down"></i>
                                        </a>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="deleteLayout({{ $item->id }})" data-bs-stacked-modal="#modalDeleteLayout">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn text-primary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalAddLayoutForm">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add Panels</h3>
            </div>
            <form action="{{ route('crm.layout.add') }}" method="post">
                <div class="modal-body">
                    <div class="fv-row">
                        <label for="label" class="col-form-label required">Nama Panel</label>
                        <input type="text" name="label" id="label" required class="form-control" placeholder="Masukan nama Panel">
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalDeleteLayout">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('crm.layout.delete') }}" method="post">
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                            <span class="fw-bold fs-2">Peringatan</span>
                        </div>
                        <span>Dengan menghapus layout ini, anda akan kehilangan isi beserta funnel, lead dan konten lainnya.</span>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="lid" id="lid-delete">
                    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalDeleteComment">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                        <span class="fw-bold fs-2">Hapus Komentar</span>
                    </div>
                    <span>Apakah kamu yakin akan menghapus komentar?</span>
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
        ["label" => "Add new Opportunity", "url" => "javascript:;", 'toggle' => 'data-bs-toggle="modal" data-bs-target="#modal_add"'],
        ["label" => "Add new contact", "url" => route('crm.list.index')."?t=kontak&m=modalAddKontak"],
        ["label" => "Add new company", "url" => route('crm.list.index')."?t=perusahaan&m=modalAdd"],
    ]
])
@endcomponent

@component('layouts.components.modal_address')

@endcomponent

@endsection

@section('custom_script')
    <script src="{{ asset("theme/assets/plugins/custom/draggable/draggable.bundle.js") }}"></script>
    <link href="{{ asset('theme/jquery-ui/jquery-ui.css') }}" rel="Stylesheet">
    <script src="{{ asset('theme/jquery-ui/jquery-ui.js') }}"></script>
    <script>

        function exportOpp(type){
            $.ajax({
                url : "{{ route("crm.export.opp") }}/" + type,
                data : {
                    _token : "{{ csrf_token() }}",
                    pipeline : $("#layout_id").val()
                },
                type : "post",
                xhrFields: {
                    responseType: 'blob'
                },
                success : function(result, status, xhr){
                    var pp = $("#layout_id option:selected").text()
                    var disposition = xhr.getResponseHeader('content-disposition');
                    var matches = /"([^"]*)"/.exec(disposition);
                    var filename = (type == "business" ? pp + " Business Unit.xlsx" : pp + ' Business Outlook.xlsx');

                    // The actual download
                    var blob = new Blob([result], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    document.body.appendChild(link);

                    link.click();
                    document.body.removeChild(link);
                }
            })
        }

        function topLead(id, me){
            $(me).find("i").removeClass("active")
            $.ajax({
                url : "{{ route('crm.lead.index') }}",
                type : "get",
                data : {
                    a : "top",
                    id : id
                },
                dataType : "json"
            }).then(function(resp){
                if(resp.top == 1){
                    $(me).find("i").addClass("active")
                }
            })
        }


        KTDrawer.createInstances();
        var drawerElement = document.getElementById("kt_drawer_leads");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.getElementById("drawer-content");

        var dwAdvance = document.getElementById("kt_drawer_leads_extend");
        var dw = KTDrawer.getInstance(dwAdvance);
        dw.on("kt.drawer.show", function() {
            $(dwAdvance).css("margin-right", drawer.lastWidth)
        });
        dw.on("kt.drawer.show", function() {
            $("[data-toggle=upload_file]").change(function(){
                var parent = $(this).parents("div.upload-file")
                var btn = $(parent).find("label[data-toggle=upload_file]")
                console.log(this.value)
                var _html = `<i class="fa fa-file"></i>${$(this).val().split('\\').pop()}`
                console.log(_html)
                btn.html("")
                btn.html(_html)
                btn.removeClass("btn-outline btn-outline-primary")
                btn.addClass("btn-primary")
            })
        });
        dw.on("kt.drawer.after.hidden", function() {
            setTimeout(function(){
                $(dwAdvance).css("margin-right", 0)
            }, 250);
            // console.log("kt.drawer.hide event is fired");
        });

        var blockDrawer = new KTBlockUI(target);
        var blockDrawerAdvance = new KTBlockUI(document.querySelector("#drawer-advance"));

        var blockKanban = new KTBlockUI(document.querySelector("#kanban"));

        var member = []

        function lead_detail(id){
            $("#drawer-content").html("")
            blockDrawer.block();
            $.ajax({
                url : `{{ route("crm.lead.detail") }}/${id}`,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockDrawer.release()
                $("#drawer-content").html(resp.view)

                $("#drawer-content #kt_lead_drawer_acc").click(function(){
                    if($(this).find('.accordion-button').hasClass("collapsed") == false) {
                        $("#drawer-content [data-accordion=collapsed]").prev().addClass('d-none')
                        $("#drawer-content [data-accordion=collapsed]").show()
                        $("#drawer-content [data-accordion=collapsed]").addClass("text-dark")
                    } else {
                        $("#drawer-content [data-accordion=collapsed]").prev().removeClass('d-none')
                        $("#drawer-content [data-accordion=collapsed]").hide()
                    }
                })

                toggle_file()

                $("#drawer-content select[data-control=select2]").select2()

                $("#drawer-content select[name=prod_sel]").change(function(){
                    if($(this).val() != ""){
                        var opt = $(this).find("option:selected")

                        var el = `<div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5">
                                        <div class="d-flex align-items-center">
                                            <i class="fi fi-rr-cube fw-bold text-primary me-3"></i>
                                            <span>${opt.text()}</span>
                                            <input type="hidden" name="product_id[]" value="${$(this).val()}">
                                        </div>
                                        <button type="button" onclick="removeProd(this)" data-id="${$(this).val()}" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                            <i class="fi fi-rr-trash"></i>
                                        </button>
                                    </div>`
                        $(this).next().append($(el))
                        $(opt).prop('disabled', true)
                        $(this).val("").trigger('change')
                    }
                })

                $("#drawer-content [data-kt-buttons='true']").each(function(){
                    var target = $(this).data("kt-buttons-target")
                    $(target).click(function(){
                        $(target).removeClass('active')
                        $(this).addClass("active")
                    })
                })

                // $("#drawer-content select[name=collab_sel]").change(function(){
                //     if($(this).val() != ""){
                //         var opt = $(this).find("option:selected")
                //         var el = `<span class="fw-bold cursor-pointer">
                //                         <input type="hidden" name="collaborators[]" value="${this.value}">
                //                         ${opt.text()}
                //                     </span>`
                //         $(this).parent().find(".collaborator-list").append($(el))
                //         $(opt).prop('disabled', true)
                //         $(this).val("").trigger('change')
                //     }
                // })

                selected = []
                bgSelected = []
                titleSelected = []

                $("#drawer-content .scale").each(function(scaleIndex){
                    var me = $(this)
                    var childs = $(me).find(".scale-item")
                    var clicked = false
                    selected[scaleIndex] = 0;
                    bgSelected[scaleIndex] = ""
                    titleSelected[scaleIndex] = ""
                    $(childs).each(function(){
                        var rb = $(this).find("input[type=radio]")
                        var bg = $(this).data("color")
                        var title = $(this).data("title")
                        var radio = $(this).find("input[type=radio]")
                        if(rb.prop("checked")){
                            selected[scaleIndex] = rb.val() - 1
                            bgSelected[scaleIndex] = bg
                            titleSelected[scaleIndex] = title
                        }
                    })

                    var bf = titleSelected[scaleIndex]

                    $(me).parent().find("label.col-form-label").text(titleSelected[scaleIndex])

                    for (let i = 0; i <= selected[scaleIndex]; i++) {
                        const element = childs[i];
                        $(element).addClass("bg-"+bgSelected[scaleIndex])
                    }
                    $(childs).on("mouseenter mouseleave click hover", function(e){
                        var bg = $(this).data("color")
                        var title = $(this).data("title")
                        var radio = $(this).find("input[type=radio]")
                        var rb = $(this).find("input[type=radio]")
                        if(e.type == 'mouseenter' || e.type == "click"){
                            var af = titleSelected[scaleIndex]
                            console.log(bf, title)
                            $(childs).removeClass("bg-"+bgSelected[scaleIndex])
                            if(e.type == "click"){
                                selected[scaleIndex] = rb.val() - 1
                                bgSelected[scaleIndex] = bg
                                titleSelected[scaleIndex] = title
                                $(me).parent().find("label.col-form-label").text(titleSelected[scaleIndex])
                            }
                            for (let i = 0; i <= rb.val() - 1; i++) {
                                const element = childs[i];
                                $(element).addClass("bg-"+bg)
                                $(me).parent().find("label.col-form-label").text(title)
                            }
                        } else {
                            $(childs).removeClass("bg-"+bg)
                            for (let i = 0; i <= selected[scaleIndex]; i++) {
                                const element = childs[i];
                                $(element).addClass("bg-"+bgSelected[scaleIndex])
                                $(me).parent().find("label.col-form-label").text(bf)
                            }
                        }
                    })
                })

                $("#drawer-content input.input-currency").number(true, 2, ",", ".").on("keyup", function(){
                    if($(this).val() != ""){
                        $(this).addClass("ps-13").next().show()
                    } else {
                        $(this).removeClass("ps-13").next().hide()
                    }
                })


                $("#drawer-content .tempusDominus").each(function(){
                    var _id = $(this).attr("id")
                    var viewMode = $(this).data("view")
                    var mask = $(this).data("mask")
                    Inputmask({
                        mask : mask
                    }).mask($(this))

                    var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                        display : {
                            viewMode: viewMode,
                            components: {
                                decades: viewMode == "calendar" ? true : false,
                                year: viewMode == "calendar" ? true : false,
                                month: viewMode == "calendar" ? true : false,
                                date: viewMode == "calendar" ? true : false,
                                hours: viewMode == "clock" ? true : false,
                                minutes: viewMode == "clock" ? true : false,
                                seconds: false
                            }
                        },
                        localization: {
                            locale: "id",
                            startOfTheWeek: 1,
                            format: viewMode == "calendar" ? "dd/MM/yyyy" : "HH:ss"
                        }
                    });
                })

                let _editor = {}
                $("#drawer-content .ck-editor").each(function(){
                    var _id = $(this).attr("id")
                    ClassicEditor.create(this, {
                        toolbar: ['link', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', 'blockQuote' ],
                    }).then(function(editor){
                        _editor[_id] = editor
                    })
                })

                init_contacts(id)
                init_company(id)

                selectMember("#collab_sel", "#collab_list")
                member = resp.members
                showTeamMember("#collab_list", resp.members)
            })
        }

        function removeMember(me){
            var div = $(me).parent()
            var inp = $(div).find("input[name='team_member[]']").val()

            const index = member.findIndex(x => parseInt(x.id) === parseInt(inp));

            console.log(index, member, inp)

            if (index > -1) { // only splice array when item is found
                member.splice(index, 1); // 2nd parameter means remove one item only
            }

            var form = $(me).parents("form")

            $(form).find(`select[name=team_member_sel] option[value=${inp}]`).prop("disabled", false)
            var modal = $(me).parents("div.modal")
            var id = $(modal).attr("id")
            if(id == "modal_team_add"){
                showTeamMember("#member-list")
            } else {
                showTeamMember("#member-list-edit")
            }
        }

        function showTeamMember(target, data){
            if(data.length > 0){
                var el = ""
                for (let i = 0; i < data.length; i++) {
                    const element = data[i];
                    var cm = data.length > 1 && i != data.length - 1 ? "," : ""
                    var tooltip = "<div class='d-flex flex-column align-items-center'>"
                        tooltip += `<span class='fw-semibold'>${element['name']}</span>`
                        tooltip += `<span class='fw-semibold'>${element['email']}</span>`
                        tooltip += `<span class='fw-semibold text-primary'>${element['company']}</span>`
                        tooltip += `<span class='fw-semibold'>${element['phone']}</span>`
                        tooltip += `<span class='fw-semibold'>${element['email']}</span>`
                        tooltip += "</div>"
                    el += "<div class='d-flex align-items-center me-2'>"
                    el += `<span class="btn btn-link text-dark p-0" data-bs-placement="top" data-bs-toggle="tooltip" data-bs-html="true" title="${tooltip}">${element['name']}${cm}</span>`
                    el += `<input type="hidden" name="collaborators[]" value="${element['id']}">`
                    el += "</div>"
                }
                $(target).html(`<div class='d-flex flex-wrap'>${el}</div>`)
            } else {
                $(target).html("")
            }
        }

        const userFormat = (item) => {
            if (!item.id) {
                return item.text;
            }

            var val = item.element.value

            var selected = $(item.element).prop("selected")

            var checked = selected ? "checked" : ""

            var span = document.createElement('span');
            var template = '';
            template += '<div class="d-flex align-items-center">'
            if(val != ""){
                template += '<div class="form-check">'
                template += '<input class="form-check-input" data-member-ck='+val+' type="checkbox" value="" />'
                template += '<label class="form-check-label" for=""></label>'
                template += '</div>'
            }
            template += '<div class="d-flex flex-column">'
            template += '<span class="fw-semibold">' + item.text + '</span>';
            template += '<span class="text-muted">' + item.element.getAttribute('data-job') + '</span>';
            template += '</div>';
            template += '</div>';

            span.innerHTML = template;

            return $(span);
        }


        function selectMember(me, target){
            $(me).select2({
                // minimumResultsForSearch: Infinity,
                // templateSelection: userFormat,
                templateResult: userFormat
            }).change(function(){
                if($(this).val() != ""){
                    if($(this).val() != "_all"){
                        var opt = $(this).find("option:selected")
                        var id = $(opt).val()
                        var index = member.findIndex(x => parseInt(x.id) === parseInt(id));
                        if (index > -1) { // only splice array when item is found
                            member.splice(index, 1); // 2nd parameter means remove one item only
                        } else {
                            var col = {}
                            col['id'] = $(opt).val()
                            col['name'] = $(opt).text()
                            col['email'] = $(opt).data("email")
                            col['company'] = $(opt).data("company")
                            col['phone'] = $(opt).data("phone")
                            member.push(col)
                        }
                        showTeamMember(target, member)
                        $(this).val("").trigger("change")
                    } else {
                        member = []
                        showTeamMember(target, member)
                    }
                }
            }).on("select2:open", async function (e) {
                await new Promise(function(){
                    window.setTimeout(function(){
                        var f = $(me).parents(".fv-row")
                        for (let i = 0; i < member.length; i++) {
                            const element = member[i];
                            $(f).find('input[data-member-ck='+element['id']+']').prop("checked", true)
                        }
                    }, 0)
                });
            })

            var f = $(me).parents(".fv-row")
            var $searchfield = $(f).find('.select2-search__field');
            $(f).on("keydown", ".select2-search__field", async function(){
                await new Promise(function(){
                    window.setTimeout(function(){
                        for (let i = 0; i < member.length; i++) {
                            const element = member[i];
                            $(f).find('input[data-member-ck='+element['id']+']').prop("checked", true)
                        }
                    }, 0)
                });
            })
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

        function removeOpporunity(me, id){
            var multiple = $(this).data("multiple") ?? true
            $(me).parents("div.find-add").append("<input type='hidden' name='op_remove[]' value='"+id+"'>")
            $(me).parents("form").find("input.find-opportunity").prop("disabled", !multiple)
            $(me).parents(".opportunity-item").remove()
        }

        function show_detail(type, id, id_lead){
            $("#drawer-advance").html("")
            blockDrawerAdvance.block();
            $.ajax({
                url : `{{ route("crm.list.index") }}/${type}/view/${id}?f=lead&id_lead=` + id_lead,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockDrawerAdvance.release()
                $("#drawer-advance").html(resp.view)
                $("#drawer-advance #kt_accordion_1_header_1").click(function(){
                    if($(this).find('.accordion-button').hasClass("collapsed") == false) {
                        $("#drawer-advance [data-accordion=collapsed]").prev().hide()
                        $("#drawer-advance [data-accordion=collapsed]").show()
                    } else {
                        $("#drawer-advance [data-accordion=collapsed]").prev().show()
                        $("#drawer-advance [data-accordion=collapsed]").hide()
                    }
                })

                $("#drawer-advance [data-control=select2]").select2()
                let _editor = {}
                $("#drawer-advance .ck-editor").each(function(){
                    var _id = $(this).attr("id")
                    ClassicEditor.create(this, {
                        toolbar: ['link', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', 'blockQuote' ],
                    }).then(function(editor){
                        _editor[_id] = editor
                    })
                })

                $("#drawer-advance .tempusDominus").each(function(){
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

                $("#drawer-advance #form-lead input:required, select:required, textarea:required").each(function(){
                    var requireds = $("#drawer-advance #form-lead input:required, select:required, textarea:required").length
                    var filled = []

                    $("#drawer-advance #form-lead input:required, select:required, textarea:required").each(function(){
                        if($(this).val() != ""){
                            filled.push($(this).val())
                        }
                    })

                    if(filled.length != requireds){
                        $("#drawer-advance #form-lead button[type=submit]").prop("disabled", true)
                    } else {
                        $("#drawer-advance #form-lead button[type=submit]").prop("disabled", false)
                    }
                })

                $("#drawer-advance #form-lead input:required, select:required, textarea:required").change(function(){
                    var requireds = $("#drawer-advance #form-lead input:required, select:required, textarea:required").length
                    var filled = []

                    $("#drawer-advance #form-lead input:required, select:required, textarea:required").each(function(){
                        if($(this).val() != ""){
                            filled.push($(this).val())
                        }
                    })

                    if(filled.length != requireds){
                        $("#drawer-advance #form-lead button[type=submit]").prop("disabled", true)
                    } else {
                        $("#drawer-advance #form-lead button[type=submit]").prop("disabled", false)
                    }
                })

                $("#drawer-advance .remove-opp").click(function(){
                    var id = $(this).data("id")
                    $("#drawer-content select[name=opp] option[value='"+id+"']").prop("disabled", false)
                    $(this).parents(".opp-item").remove()
                })

                $("#drawer-advance select[name=opp]").change(function(){
                    if($(this).val() != ""){
                        var opt = $(this).find("option:selected")

                        var el = `<div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5">
                                        <div class="d-flex align-items-center">
                                            <i class="fi fi-rr-cube fw-bold text-primary me-3"></i>
                                            <span>${opt.text()}</span>
                                            <input type="hidden" name="product_id[]" value="${$(this).val()}">
                                        </div>
                                        <button type="button" onclick="removeProd(this)" data-id="${$(this).val()}" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                            <i class="fi fi-rr-trash"></i>
                                        </button>
                                    </div>`
                        $(this).parent().find(".opp-list").append($(el))
                        $(opt).prop('disabled', true)
                        $(this).val("").trigger('change')
                    }
                })

                init_opporunity(id, type)

                toggle_file()

                add_phone()
                add_email()

                init_contacts(id, type)
                init_company()
            })
        }

        function deleteComment(me){
            var id = $(me).data('id');
            $('#modalDeleteComment').modal('show');
            $('.modal-footer a').attr('href', "{{ route('crm.comment.delete') }}/" + id);
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

            console.log(show)

            var tp = $(me).data("type")
            var id = $(me).data("id")
            var comment = $(me).data("comment")

            var chead = $(me).parents(".comment-header")
            var csec = $(chead).parent().find(".comment-data")

            var url = "{{ route("crm.comment.view") }}/" + tp + "/" + id
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

        function removeCompany(me){
            $(me).parents("div.position-relative").find('input[type=text]').prop("disabled", false)
            $(me).parents("form").find("button[type=submit]").prop("disabled", true)
            $(me).parents(".company-item").remove()
        }

        function removeProd(me){
            var id = $(me).data("id")
            $("#drawer-content select[name=prod_sel] option[value='"+id+"']").prop("disabled", false)
            $(me).parents(".product-item").remove()
        }

        function deleteLayout(id){
            $('#lid-delete').val(id);
        }

        // var table_display = $("#table-opportunity").DataTable({
        //     "fnDrawCallback": function( oSettings ) {
        //         KTMenu.createInstances();
        //     }
        // })

        function init_contacts(id = null, type = null){
            $("input.find-contact").each(function(){
                var pName = $(this).data("name")
                var showDetail = $(this).data("show-detail")
                var divAdd = $(this).parent().find(".find-add")
                var divResult = $(this).parent().find(".find-noresult")
                var input = $(this)
                $(this).on("keyup", function(){
                    $(divResult).html("")
                    $(input).parent().find("button").hide()
                    var form = $(this).parents("form")
                    var comp = type == null ? ($(form).find("input[name=id_client]").val() ?? "") : id;
                    $(this).autocomplete({
                        source: function( request, response ) {
                                $.ajax( {
                                url: encodeURI("{{ route('crm.lead.index') }}?a=find&b=contact&c=" + comp + "&t=" + (type ?? "")),
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

                            var inputClient = $(form).find("input.find-company").eq(0);
                            var _pName = $(inputClient).data("name")

                            if(ui.item.comp_id != null && comp == null){
                                var isDetail = id == null ? "" : `onclick="show_detail('company', ${ui.item.comp_id}, ${id})" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_leads_extend"`

                                var el = `<div class="d-flex justify-content-between border bg-white rounded p-3 align-items-center mt-5 company-item">
                                    <div class="d-flex align-items-center">
                                        <i class="fi fi-rr-building fw-bold text-primary me-3"></i>
                                        <span class='cursor-pointer' ${isDetail}>${ui.item.company_name}</span>
                                        <input type="hidden" name="${_pName}" value="${ui.item.comp_id}">
                                    </div>
                                    <button type="button" onclick="removeCompany(this)" class="btn btn-icon btn-sm p-0 btn-outline-danger btn-outline border-0">
                                        <i class="fi fi-rr-trash"></i>
                                    </button>
                                </div>`
                                var multiple = $(inputClient).data("multiple")
                                var divAddCompany = $(inputClient).parent().find(".find-add")
                                $(divAddCompany).append($(el))
                                $("#drawer-content button[type=submit]").prop("disabled", false)
                                $(inputClient).val("")
                                $(inputClient).prop("disabled", !multiple)
                            }
                            return false
                        },
                    });
                })
            })
        }

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
                                url: encodeURI("{{ route('crm.lead.index') }}?a=find&b=company"),
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

        function dfunel(me){
            var id = $(me).data("id")
            var label = $(me).data("label")
            $("#fid-delete").val(id)
            $("#funnel-label").text(label)
            $(`#funnel-select option`).prop("disabled", false)
            $(`#funnel-select option[value='${id}']`).prop("disabled", true)
        }

        function draggable(){
            var containers = document.querySelectorAll(".draggable-zone");

            if (containers.length === 0) {
                return false;
            }

            var swappable = new Sortable.default(containers, {
                draggable: ".draggable",
                handle: ".draggable .draggable-handle",
                dropzone: ".draggable-zone",
                dragging: true,
                mirror: {
                    //appendTo: selector,
                    appendTo: "body",
                    constrainDimensions: true
                },
            });

            swappable.on("drag:stopped", function(e){
                var zone = $("#dzone")
                var row = $(zone).find(".draggable")
                var order = {}
                var ind = 1
                row.each(function(){
                    var _id = $(this).attr("id").split("-")
                    order[_id[1]] = ind++
                })

                update_funnels(order)
            })
        }

        function updateCountProject(source){
            var funHead = $(source).parents("div.draggable")
            var lbl = $(funHead).find("div.card-toolbar span.badge-primary")
            var oldLeads = $(funHead).find(".draggable-leads")
            lbl.text(oldLeads.length)
            _updateCount()
        }

        function _updateCount(){
            var lcount = 0
            $("div.draggable").each(function(index){
                var funHead = this

                var tlWorth = $(funHead).find("[data-target=networth]")
                var networth = 0
                $(funHead).find(".draggable-leads").each(function(){
                    var nominal = $(this).data("networth")
                    networth += parseInt(nominal ?? 0)
                })

                lcount += $(funHead).find(".draggable-leads").length

                $(tlWorth).tooltip('dispose');

                var title = "Funnel worth : <span class='text-primary text-nowrap'>IDR " + $.number(networth, 0, ",", ".") + "</span>";
                $(tlWorth).tooltip({
                    html : true,
                    title : title
                })
                tlWorth.attr("title", title)
            })

            $("#l-count").text(lcount)
            console.log($("#l-count").parent().text())

            if(lcount <= 1){
                $("#l-count").text(lcount + " Opportunity")
            } else {
                $("#l-count").text(lcount + " Opportunities")
            }
        }

        function draggable_leads(){
            var containers = document.querySelectorAll(".draggable-zone-leads");

            if (containers.length === 0) {
                return false;
            }

            var swappable = new Sortable.default(containers, {
                draggable: ".draggable-leads",
                handle: ".draggable-leads .draggable-handle-leads",
                dragging: true,
                mirror: {
                    //appendTo: selector,
                    appendTo: "body",
                    constrainDimensions: true
                },
            });

            $(".draggable-leads").find("a").click(function(){
                console.log("hello")
            })

            swappable.on("drag:stopped", function(e){
                var newSource = e.data.originalSource
                var newFunnel = $(newSource).parents(".draggable-zone-leads")
                var _row = $(newFunnel).find("div.draggable-leads")
                var order = {}
                var ind = 1
                var oldSource = e.data.sourceContainer

                updateCountProject(oldSource)
                updateCountProject(newSource)

                $(".draggable-zone-leads").each(function(){
                    var row = $(this).find(".draggable-leads")
                    var f = $(this)
                    var _leads = {}
                    var ind = 1
                    row.each(function(){
                        var _id = $(this).attr("id").split("-")
                        _leads[_id[1]] = ind++
                    })
                    order[f.data("funnel")] = _leads
                    var _links = $(this).find(".link-leads")
                    var ind = 1
                    _links.each(function(){
                        var _uri = "{{ route("crm.lead.add")."/$layoutSelected" }}/"+$(f).data("funnel")+"/"+ind++
                        $(this).attr("href", _uri)
                    })
                })

                update_leads($(newFunnel).data("funnel"), $(newSource).data("id"), order)
            })
        }

        function update_funnels(orders){
            blockKanban.block();
            $.ajax({
                url : "{{ route("crm.funnel.update-order") }}",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    orders: orders
                },
                type : "POST"
            }).then(function(resp){
                blockKanban.release();
            })
        }

        function update_leads(funnel, id, orders){
            blockKanban.block();
            $.ajax({
                url : "{{ route("crm.lead.update-funnel") }}",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    funnel : funnel,
                    id : id,
                    orders: orders,

                },
                type : "POST"
            }).then(function(resp){
                blockKanban.release();
            })
        }

        function add_funnel(me){
            $(me).removeClass("d-flex")
            $(me).hide()
            $("#form-funnel").show()
        }

        function cancel_funnel(me){
            $("#form-funnel").prev().addClass("d-flex")
            $("#form-funnel").prev().show()
            $("#form-funnel").hide()
        }

        function filter_table(status){
            let postData = $("#cari-form").serialize();
            table_display.clear().draw();
            $.ajax({
                url : "{{ route("crm.lead.index")."/$layoutSelected" }}?a=table&status="+status,
                dataType : "json",
                type : "get",
                data: postData,
            }).then(function(resp){
                $("#modalMenu").modal("hide");
                $("#list").addClass("show active")
                $("#kanban").removeClass("show active")
                $("a[href='#list']").addClass("active")
                $("a[href='#kanban']").removeClass("active")
                table_display.rows.add(resp).draw()
            });
        }

        function archiveItem(me){
            var _id = $(me).data("id")
            var uri = $(me).data("url")
            console.log($(me).data('name'))
            $("#leads-label").text($(me).data("name"))
            $("#leads-url").attr("href", uri)
        }

        function initData(){

            var data = {
                sort : $("select[name='filter[sort]']").val(),
                sales_confidence : $("select[name='filter[sales_confidence]']").val(),
                priority : $("select[name='filter[priority]']").val(),
                owner : $("select[name='filter[owner]']").val(),
                company : $("select[name='filter[company]']").val(),
            }

            blockKanban.block()
            table_display.clear().draw()
            $.ajax({
                url : "{{ route("crm.lead.index")."/$layoutSelected" }}?a=table&tags[]=&contacts[]=&products[]=&funnels=",
                dataType : "json",
                data : {
                    filter : data
                },
                type : "get"
            }).then(function(resp){
                var table = resp.table
                table_display.rows.add(table).draw()
                KTMenu.createInstances();
                blockKanban.release()
                $("#table-opportunity [data-bs-toggle=tooltip]").tooltip()
                $("button.btn-delete").click(function(){
                    var _id = $(this).data("id")
                    var uri = "{{ route('crm.lead.delete') }}/"+ _id
                    $("#leads-label").text($(this).data("name"))
                    $("#leads-url").attr("href", uri)
                })

                $("#kanban").html(resp.kanban)
                $("#kanban [data-bs-toggle=tooltip]").tooltip()
                draggable()
                draggable_leads()
                _updateCount()
            })
        }

        $(document).ready(function(){

            _updateCount()

            initData()

            init_contacts()

            $(".dataTable-length-info-label").text("Rows per page:")

            var _selDataTable = $(".dataTables_length").find("select")
            _selDataTable.addClass("border-0")
            _selDataTable.removeClass("form-select-solid")
            _selDataTable.parent().addClass("border-bottom border-dark")
            $(".dt-buttons button").removeClass("btn-secondary")

            // cari form
            $("#cari-leads").click(function(e){
                e.preventDefault();
                let postData = $("#cari-form").serialize();
                table_display.clear().draw();
                $.ajax({
                    url : "{{ route("crm.lead.index")."/$layoutSelected" }}?a=table",
                    dataType : "json",
                    type : "get",
                    data: postData,
                }).then(function(resp){
                    $("#modalMenu").modal("hide");
                    $("#list").addClass("show active")
                    $("#kanban").removeClass("show active")
                    $("a[href='#list']").addClass("active")
                    $("a[href='#kanban']").removeClass("active")
                    table_display.rows.add(resp).draw()
                    $("button.btn-delete").click(function(){
                        var _id = $(this).data("id")
                        var uri = "{{ route('crm.lead.delete') }}/"+ _id
                        $("#leads-label").text($(this).data("name"))
                        $("#leads-url").attr("href", uri)
                    })
                });
            });

            // slide menu search bar
            $(".menu_dicky").click(function(){
                $("#modalMenu").modal("show");
            });
            $("#cl_tag").click(function(){
                if ($('#menu_tag').is(':visible')){
                    $('#menu_tag').slideUp();
                } else {
                    $('#menu_tag').slideDown();
                }
            });
            $("#cl_contact").click(function(){
                if ($('#menu_contact').is(':visible')){
                    $('#menu_contact').slideUp();
                } else {
                    $('#menu_contact').slideDown();
                }
            });
            $("#cl_product").click(function(){
                if ($('#menu_product').is(':visible')){
                    $('#menu_product').slideUp();
                } else {
                    $('#menu_product').slideDown();
                }
            });
            $("#cl_funnel").click(function(){
                if ($('#menu_funnel').is(':visible')){
                    $('#menu_funnel').slideUp();
                } else {
                    $('#menu_funnel').slideDown();
                }
            });

            draggable()
            draggable_leads()

            $("#search").on("keyup", function(){
                $("#list").addClass("show active")
                $("#kanban").removeClass("show active")
                $("a[href='#list']").addClass("active")
                $("a[href='#kanban']").removeClass("active")
                table_display.search($(this).val()).draw()
            })

            $("#layout_id").change(function(){
                location.href = "{{ route("crm.lead.index") }}/" + $(this).val()
            })

            @if (\Session::has("leads_id"))
                drawer.show()
                lead_detail({{ \Session::get("leads_id") }})
            @endif

            @if(isset($_GET['m']))
                $("#{{ $_GET['m'] }}").modal("show")
            @endif
        })
    </script>
@endsection
