@extends('layouts.templateCrm', ["menuCrm" => "menu_crm", 'withoutFooter' => true, 'bgWrapper' => 'bg-white', "style" => ["border" => "border-bottom", "box-shadow" => "none"]])

@section('content')
<div class="card shadow-none card-px-0">
    <div class="card-header border-0">
        <div class="card-title d-flex align-items-center">
            <div class="symbol symbol-50px me-5">
                <div class="symbol-label bg-primary text-white">
                    <i class="fi fi-rr-layers"></i>
                </div>
            </div>
            <div class="d-flex flex-column">
                <span class="fw-bold fs-3">Archive</span>
                <span class="text-muted fs-base">You can see archived items</span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs nav-fill border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
            <li class="nav-item">
                <a class="nav-link text-active-dark active" data-bs-toggle="tab" href="#tab_opp">
                    <span class="nav-text">Opportunity</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_comp">
                    <span class="nav-text">Company</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_cont">
                    <span class="nav-text">Contact</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_file">
                    <span class="nav-text">File</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_prod">
                    <span class="nav-text">Product</span>
                </a>
            </li>
        </ul>
        <div class="tab-content p-5 bg-secondary-crm rounded" id="myTabContent">
            <div class="tab-pane fade show active" id="tab_opp" role="tabpanel">
                <table class="table bg-white display" id="table-opportunity">
                    <thead>
                        <tr>
                            <th>Opportunity Name</th>
                            <th>Funnel</th>
                            <th>Priority</th>
                            <th>Sales Confident</th>
                            <th>Opportunity Owner</th>
                            <th>Opportunity Collaborators</th>
                            <th>Update Terakhir</th>
                            <th class="text-nowrap"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opportunities as $i => $item)
                            @php
                                $conf = "Nice to";
                                $confClass = "secondary";
                                if($item['sales_confidence'] == 1){
                                    $confClass = "secondary";
                                    $conf = "Nice to";
                                } elseif($item['sales_confidence'] == 2){
                                    $confClass = "success";
                                    $conf = "Run through";
                                } elseif($item['sales_confidence'] == 3){
                                    $confClass = "warning";
                                    $conf = "Best case";
                                } elseif($item['sales_confidence'] == 4){
                                    $confClass = "danger";
                                    $conf = "Commit";
                                }

                                $prty = "Low";
                                $ptyClass = "success";
                                if($item['level_priority'] == 1){
                                    $ptyClass = "success";
                                    $prty = "Low";
                                } elseif($item['level_priority'] == 2){
                                    $ptyClass = "warning";
                                    $prty = "Medium";
                                } elseif($item['level_priority'] == 3){
                                    $ptyClass = "danger";
                                    $prty = "High";
                                }

                                $cons = "<div class='symbol-group symbol-hover'>";
                                if(!empty($item->contributors) && $item->contributors != "null"){
                                    $con = json_decode($item->contributors ?? "[]", true);
                                    foreach($con as $ic => $cc){
                                        if(isset($user_name[$cc])){
                                            $tooltip = "<div class='d-flex flex-column align-items-center'><span>".($user_name[$cc] ?? "-")."</span>";
                                            $tooltip .= "<span class='text-primary'>".Session::get("company_name_parent")."</span>";
                                            $tooltip .= "<span>".($user_phone[$cc] ?? "-")."</span>";
                                            $tooltip .= "<span>".($user_email[$cc] ?? "-")."</span>";
                                            $tooltip .= "</div>";
                                            $cons .= "<div class='symbol symbol-40px symbol-circle' data-bs-toggle='tooltip' data-bs-html='true' title=\"$tooltip\"><img src='".asset($user_img[$cc] ?? "theme/assets/media/avatars/blank.png")."'></div>";
                                        }
                                    }
                                }
                                $cons .= "</div>";
                                $tooltip = "<div class='d-flex flex-column align-items-center'><span>".($user_name[$item->partner] ?? "-")."</span>";
                                $tooltip .= "<span class='text-primary'>".Session::get("company_name_parent")."</span>";
                                $tooltip .= "<span>".($user_phone[$item->partner] ?? "-")."</span>";
                                $tooltip .= "<span>".($user_email[$item->partner] ?? "-")."</span>";
                                $tooltip .= "</div>";
                                $owner = "<div class='symbol symbol-40px symbol-circle' data-bs-toggle='tooltip' data-bs-html='true' title=\"$tooltip\"><img src='".asset($user_img[$item->partner] ?? "theme/assets/media/avatars/blank.png")."'></div>";
                                $button = '<button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" data-bs-toggle="modal" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="lead_detail('.$item->id.')" class="menu-link px-3">
                                            Recover
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>';
                            @endphp
                            <tr>
                                <td>{{ $item->leads_name }}</td>
                                <td>{{ $item->funnel->label ?? "-" }}</td>
                                <td><span class="badge badge-{{ $ptyClass }}">{{ $prty }}</span></td>
                                <td><span class='badge badge-{{ $confClass }}'>{{ $conf }}</span></td>
                                <td>{!! $owner !!}</td>
                                <td>{!! $cons !!}</td>
                                <td>{{ date("d/m/Y", strtotime($item->updated_at)) }}</td>
                                <td>{!! $button !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="tab_comp" role="tabpanel">
                <table class="table bg-white display" id="table-company">
                    <thead>
                        <tr>
                            <th>Perusahaan</th>
                            <th>Kontak</th>
                            <th>Related To</th>
                            <th>Tipe</th>
                            <th>Pembuat</th>
                            <th>Tanggal dibuat</th>
                            <th>Update Terakhir</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $item)
                            @php
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
                            @endphp
                            <tr>
                                <td>
                                    <span class="text-dark fw-bold cursor-pointer">{{ $item->company_name }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $item->pic_number }}</span>
                                        <span>{{ $item->email }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->type ?? "-" }}</td>
                                <td>{{ $item->type ?? "-" }}</td>
                                <td class="fw-bold">{{ $user_name[$item->created_by] ?? "-" }}</td>
                                <td>{{ date("d/m/Y", strtotime($item->created_at)) }}</td>
                                <td>{{ date("d/m/Y", strtotime($item->updated_at ?? $item->created_at)) }}</td>
                                <td align="center" class="text-nowrap">
                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                    </button>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="list_detail('company', {{ $item->id }})" class="menu-link px-3">
                                                Recover
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
            <div class="tab-pane fade" id="tab_cont" role="tabpanel">
                <table class="table bg-white display" id="table-contact">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Posisi</th>
                            <th>Nomor Telepon</th>
                            <th>Email</th>
                            <th>Tipe</th>
                            <th>Tanggal dibuat</th>
                            <th>Update Terakhir</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $item)
                            @php
                                $completeData = "<a href='javascript:;' data-kt-drawer-show=true data-kt-drawer-target=#kt_drawer_example_basic onclick=\"show_detail('contacts', $item->id)\"><u>Lengkapi Data</u></a>";
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="text-dark fw-bold cursor-pointer mb-3" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="show_detail('contacts', {{ $item->id }})">{{ $item->name }}</span>
                                        <span class="text-primary cursor-pointer" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="show_detail('company', {{ $item->comp_id }})">{{ $comPluck[$item->comp_id] ?? "-" }}</span>
                                    </div>
                                </td>
                                <td>
                                    {!! $item->position ?? "-" !!}
                                </td>
                                <td>{!! $item->no_telp ?? "-" !!}</td>
                                <td>{!! $item->email ?? "-" !!}</td>
                                <td>{!! $item->type ?? "-" !!}</td>
                                <td>{{ date("d/m/Y", strtotime($item->created_at)) }}</td>
                                <td>{{ date("d/m/Y", strtotime($item->updated_at)) }}</td>
                                <td align="center">
                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                    </button>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="list_detail('contacts', {{ $item->id }})" class="menu-link px-3">
                                                Recover
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
            <div class="tab-pane fade" id="tab_file" role="tabpanel">
                <table class="table bg-white display" id="table-file">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Tipe</th>
                            <th>Related to</th>
                            <th>Pembuat</th>
                            <th>Tanggal dibuat</th>
                            <th>Update terakhir</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="tab_prod" role="tabpanel">
                <table class="table bg-white display" id="table-product">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Brand</th>
                            <th>Kategori</th>
                            <th>Total Deal</th>
                            <th>Sales Target</th>
                            <th>Update terakhir</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $item)
                            <tr>
                                <td>
                                    <span class="text-dark fw-bold cursor-pointer">{{ $item->label }}</span>
                                </td>
                                <td>{{ $item->brand ?? "-" }}</td>
                                <td>{{ $item->kategori ?? "-" }}</td>
                                <td>{{ isset($pl[$item->id]) ? count($pl[$item->id]) : 0 }}</td>
                                <td><span class="text-danger">100</span></td>
                                <td>{{ date("d-m-Y", strtotime($item->updated_at)) }}</td>
                                <td align="center">
                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                    </button>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="product_detail({{ $item->id }})" class="menu-link px-3">
                                                Recover
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

<div id="kt_drawer_example_basic"
    class="bg-white"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-toggle="#kt_drawer_example_basic_button"
    data-kt-drawer-close="#kt_drawer_example_basic_close"
    data-kt-drawer-width="{default : '45%', md: '45%', sm: '500px'}">
    <div class="card rounded-0 w-100" id="drawer-content">

    </div>
</div>
@endsection

@section('custom_script')
    <script>
        var target = document.getElementById("drawer-content");
        var blockUI = new KTBlockUI(target);

        function lead_detail(id){
            $("#drawer-content").html("")
            blockUI.block();
            $.ajax({
                url : `{{ route("crm.lead.detail") }}/${id}?archived=true`,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release()
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

                $("#drawer-content select[name=collab_sel]").change(function(){
                    if($(this).val() != ""){
                        var opt = $(this).find("option:selected")
                        var el = `<span class="fw-bold cursor-pointer">
                                        <input type="hidden" name="collaborators[]" value="${this.value}">
                                        ${opt.text()}
                                    </span>`
                        $(this).parent().find(".collaborator-list").append($(el))
                        $(opt).prop('disabled', true)
                        $(this).val("").trigger('change')
                    }
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

                $("#drawer-content input, textarea, select, button[type=submit]").prop("disabled", true)
                $("#drawer-content input, textarea, select, button[type=submit]").addClass("disabled")
            })
        }

        function list_detail(type, id){
            $("#drawer-content").html("")
            blockUI.block();
            $.ajax({
                url : `{{ route("crm.list.index") }}/${type}/view/${id}?archived=true`,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release()
                $("#drawer-content").html(resp.view)
                $("#drawer-content #kt_accordion_1_header_1").click(function(){
                    if($(this).find('.accordion-button').hasClass("collapsed") == false) {
                        $("#drawer-content [data-accordion=collapsed]").prev().hide()
                        $("#drawer-content [data-accordion=collapsed]").show()
                    } else {
                        $("#drawer-content [data-accordion=collapsed]").prev().show()
                        $("#drawer-content [data-accordion=collapsed]").hide()
                    }
                })

                $("#drawer-content [data-control=select2]").select2()
                let _editor = {}
                $(".ck-editor").each(function(){
                    var _id = $(this).attr("id")
                    ClassicEditor.create(this, {
                        toolbar: ['link', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', 'blockQuote' ],
                    }).then(function(editor){
                        _editor[_id] = editor
                    })
                })

                $("#drawer-content .tempusDominus").each(function(){
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

                $("#drawer-content #form-lead input:required, select:required, textarea:required").change(function(){
                    var requireds = $("#drawer-content #form-lead input:required, select:required, textarea:required").length
                    var filled = []

                    $("#drawer-content #form-lead input:required, select:required, textarea:required").each(function(){
                        if($(this).val() != ""){
                            filled.push($(this).val())
                        }
                    })

                    if(filled.length != requireds){
                        $("#drawer-content #form-lead button[type=submit]").prop("disabled", true)
                    } else {
                        $("#drawer-content #form-lead button[type=submit]").prop("disabled", false)
                    }
                })

                $("#drawer-content input, textarea, select, button[type=submit]").prop("disabled", true)
                $("#drawer-content input, textarea, select, button[type=submit]").addClass("disabled")
            })
        }

        function product_detail(id){
            $("#drawer-content").html("")
            blockUI.block();
            $.ajax({
                url : `{{ route("crm.products.detail") }}/${id}?archived=true`,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release()
                $("#drawer-content").html(resp.view)
                $("#drawer-content #kt_accordion_1_header_1").click(function(){
                    if($(this).find('.accordion-button').hasClass("collapsed") == false) {
                        $("#drawer-content [data-accordion=collapsed]").prev().hide()
                        $("#drawer-content [data-accordion=collapsed]").show()
                    } else {
                        $("#drawer-content [data-accordion=collapsed]").prev().show()
                        $("#drawer-content [data-accordion=collapsed]").hide()
                    }
                })

                $("#drawer-content input.number").number(true, 2, ",", ".")

                $("#drawer-content [data-control=select2]").select2()
                let _editor = {}
                $(".ck-editor").each(function(){
                    var _id = $(this).attr("id")
                    ClassicEditor.create(this, {
                        toolbar: ['link', '|', 'bold', 'italic', '|', 'bulletedList', 'numberedList', 'blockQuote' ],
                    }).then(function(editor){
                        _editor[_id] = editor
                    })
                })

                $("#drawer-content .tempusDominus").each(function(){
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
                            format: "dd-MM-yyyy"
                        }
                    });

                    Inputmask({
                        "mask" : "99-99-9999"
                    }).mask($(this));
                })

                $("#drawer-content form input:required, select:required, textarea:required").change(function(){
                    var requireds = $("#drawer-content form input:required, select:required, textarea:required").length
                    var filled = []

                    $("#drawer-content form input:required, select:required, textarea:required").each(function(){
                        if($(this).val() != ""){
                            filled.push($(this).val())
                        }
                    })

                    if(filled.length != requireds){
                        $("#drawer-content form button[type=submit]").prop("disabled", true)
                    } else {
                        $("#drawer-content form button[type=submit]").prop("disabled", false)
                    }
                })

                $("#drawer-content input, textarea, select, button[type=submit]").prop("disabled", true)
                $("#drawer-content input, textarea, select, button[type=submit]").addClass("disabled")
            })
        }
    </script>
@endsection
