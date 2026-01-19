@extends('layouts.templateCrm', ["menuCrm" => "menu_crm", 'withoutFooter' => true, "bgWrapper" => "bg-white", "style" => ["border" => "border-bottom", "box-shadow" => "none"]])

@section('fixaside')
    @include('_crm.leads._aside')
@endsection

@section('content')
<div class="card card-custom not-rounded h-100">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-50px me-5">
                    <div class="symbol-label bg-primary">
                        <i class="fa fa-building fs-2 text-white"></i>
                    </div>
                </div>
                <div class="d-flex flex-column justify-content-between">
                    <span class="fw-bold fs-2">Products</span>
                    <span>Silahkan Cek Produk List Disini</span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-outline btn-outline-primary me-5">
                    <i class="fa fa-download"></i>
                    Download
                </button>
                <button type="button" class="btn btn-primary me-5">
                    <i class="fa fa-upload"></i>
                    Upload CSV
                </button>
                <button type="button" class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#modalAdd">
                    <i class="fa fa-file-medical"></i>
                    Tambah Produk
                </button>
            </div>
        </div>
    </div>
    <div class="card-body h-100 rounded" style="background-color: rgba(247, 248, 250, 1)">
        <table class="table bg-white" id="table-produk" data-col-filter="1,2">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Brand</th>
                    <th>Kategori</th>
                    <th>Total Deal</th>
                    <th>Update terakhir</th>
                    <th></th>
                    <th class="d-none"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $item)
                    @php
                        $completeData = "<a href='javascript:;' data-kt-drawer-show=true data-kt-drawer-target=#kt_drawer_example_basic onclick=\"show_product('#drawer-content', $item->id)\"><u>Incomplete data</u></a>";
                    @endphp
                    <tr>
                        <td>
                            <span class="text-dark fw-bold cursor-pointer" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic" onclick="show_product('#drawer-content',{{ $item->id }})">{{ $item->label }}</span>
                        </td>
                        <td>{!! $item->brand ?? $completeData !!}</td>
                        <td>{!! $item->kategori ?? $completeData !!}</td>
                        <td>{{ isset($pl[$item->id]) ? count($pl[$item->id]) : 0 }}</td>
                        <td>{{ date("d-m-Y", strtotime($item->updated_at)) }}</td>
                        <td align="center">
                            <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                <i class="fa fa-ellipsis-vertical text-dark"></i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="javascript:;" data-bs-toggle="modal" onclick="archiveItem(this)" data-bs-target="#modalDelete" data-url="{{ route('crm.products.archive', $item->id) }}" data-id="{{ $item->id }}" data-name="{{ $item->label }}" class="menu-link px-3 text-danger">
                                        Archive
                                    </a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                        </td>
                        <td class="d-none">{{ $item->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" tabindex="-1" id="modalAdd">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('crm.products.add') }}" enctype="multipart/form-data" method="post">
                    <div class="modal-body px-15 py-17">
                        <div class="d-flex mb-5 align-items-center">
                            <div class="symbol symbol-50px me-5">
                                <div class="symbol-label bg-light-primary"><i class="fa fa-phone text-primary fs-2"></i></div>
                            </div>
                            <div class="d-flex flex-column justify-content-end">
                                <span class="fs-3 fw-bold">Tambah Produk</span>
                                <span>Isi form dan tambah produk baru disini</span>
                            </div>
                        </div>
                        <div class="px-10 py-5 rounded mb-5" style="background-color: rgba(247, 248, 250, 1)">
                            <div class="fv-row">
                                <label for="sku" class="col-form-label required">Nama Produk</label>
                                <input type="text" name="sku" id="sku" class="form-control" required placeholder="Masukan Nama Produk">
                            </div>
                            <div class="fv-row">
                                <label for="brand" class="col-form-label">Brand</label>
                                <input type="text" name="brand" id="brand" class="form-control" placeholder="Masukan Brand">
                            </div>
                            <div class="fv-row">
                                <label for="harga" class="col-form-label">Harga</label>
                                <input type="text" name="harga" id="harga" class="form-control number" placeholder="IDR 0">
                            </div>
                            <div class="fv-row">
                                <label for="deskripsi" class="col-form-label">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" class="form-control" cols="30" placeholder="Masukan Deskripsi" rows="10"></textarea>
                            </div>
                            <div class="fv-row upload-file mt-5">
                                <label for="attachment" data-toggle="upload_file"
                                    class="btn btn-secondary btn-sm">
                                    Attachment
                                    <i class="fa fa-paperclip"></i>
                                </label>
                                <span class="upload-file-label">Max 1 mb</span>
                                <input id="attachment" style="display: none" data-toggle="upload_file"
                                    name="attachment_notes" accept=".jpg, .png, .pdf" type="file" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            @csrf
                            <button type="button" class="btn btn-white me-5" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        {{-- <div class="fv-row">
                            <label for="sku" class="col-form-label required">SKU</label>
                            <input type="text" name="sku" id="sku" class="form-control" required placeholder="Masukan nama SKU">
                        </div>
                        <div class="fv-row">
                            <label for="tag" class="col-form-label">Tag</label>
                            <input type="text" name="tag" id="tag" class="form-control tag" placeholder="Masukan tipe SKU">
                        </div>

                        <div class="fv-row">
                            <label for="deskripsi" class="col-form-label">Deskripsi SKU</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control" placeholder="Masukan deskripsi SKU" cols="30" rows="10"></textarea>
                        </div>
                        <div class="fv-row">
                            <label for="uom" class="col-form-label">Satuan</label>
                            <input type="text" name="uom" id="uom" class="form-control" placeholder="Masukan satuan SKU">
                        </div>
                        <div class="fv-row p-5 upload-file">
                            <label for="task-file" data-toggle="upload_file"
                                class="btn btn-outline btn-outline-primary btn-sm">
                                <i class="fa fa-file"></i>
                                Add File
                            </label>
                            <span class="upload-file-label">Max 25 mb</span>
                            <input id="task-file" style="display: none" data-toggle="upload_file"
                                name="attachment" accept=".jpg, .png, .pdf" type="file" />
                        </div> --}}
                    </div>

                    {{-- <div class="modal-footer">
                        @csrf
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
    <form id="cari-form" name="cari-form" enctype="multipart/form-data" method="post" >
        {{ csrf_field() }}
        <div class="modal fade" tabindex="-1" id="modalMenu">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body row">
                        <p><button type="button" class="btn" stlye="float:right;" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">Close</span>
                        </button>
                        <button type="button" class="btn btn-success" stlye="float:right;" id="cari-leads">
                            <span aria-hidden="true">Cari</span>
                        </button></p>
                        <p><label>Sort BY: <select id="sorts" name="sorts">
                            <option value="leads_name">Leads Name</option>
                            <option value="client_id">Client Name</option>
                            </select>
                            </label>
                        </p>
                        <hr />
                        <p id="cl_product">PRODUCT</p>
                        <div id="menu_product">
                            @foreach($m_menu['PRODUCT'] as $m_menus)
                            <p>
                                <input type="checkbox" id="product_{{$m_menus['id']}}" name="products[]" value="{{$m_menus['id']}}">
                                <label for="product_{{$m_menus['id']}}">{{$m_menus['label']}}</label>
                            </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

    <div class="modal fade" tabindex="-1" id="modalImg">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title"></div>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <img src="#" id="preview-img" class="w-100" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@component('layouts.components.fab', [
    "fab" => [
        ["label" => "Add new Opportunity", "url" => route("crm.lead.index")."?m=modal_add"],
        ["label" => "Add new contact", "url" => route('crm.list.index')."?t=kontak&m=modalAddKontak"],
        ["label" => "Add new company", "url" => route('crm.list.index')."?t=perusahaan&m=modalAdd"],
    ]
])
@endcomponent
@endsection

@section('custom_script')
    <script src="{{ asset("theme/assets/plugins/custom/draggable/draggable.bundle.js") }}"></script>
    <link href="{{ asset('theme/jquery-ui/jquery-ui.css') }}" rel="Stylesheet">
    <script src="{{ asset('theme/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset("theme/assets/plugins/custom/fslightbox/fslightbox.bundle.js") }}"></script>
    <script>

        function preview_img(url){
            $("#preview-img").attr("src", url)
            $("#modalImg").modal("show")
        }

        $("#table-produk").DataTable({
            // dom : `<"d-flex align-items-center justify-content-between justify-content-md-end"f>t<"dataTable-length-info-label me-3">lip`
            dom : `<"d-flex flex-column"<"d-flex align-items-center justify-content-start"f<"d-none d-md-inline ms-5">>>t<"row"<"col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start"<"dataTable-length-info-label me-3">l><"col-sm-12 col-md-7 d-flex align-items-center"p>>`,
            "fnDrawCallback": function( oSettings ) {
                try {
                    KTMenu.createInstances();
                    $("[data-bs-toggle=tooltip]").tooltip()
                } catch (error) {

                }
            },
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

                let sortSelect = document.createElement('select');
                sortSelect.add(new Option(''));
                $(sortSelect).attr("data-control", "select2")
                $(sortSelect).attr("data-placeholder", "Sort")
                $(sortSelect).attr("data-allow-clear", true)
                $(sortSelect).addClass("form-select")
                // $(select).addClass("")
                var div = document.createElement("div")
                $(div).addClass('d-none d-md-flex col')
                $(div).html(sortSelect)
                $(`#${fId}`).append(div)
                // column.footer().replaceChildren(select);

                // Apply listener for user change in value

                sortSelect.add(new Option("Newest-oldest", 1));
                sortSelect.add(new Option("Oldest-newest", 2));
                sortSelect.add(new Option("A to Z", 3));
                sortSelect.add(new Option("Z to A", 4));

                var api = this.api()

                $(sortSelect).change(function(){
                    var val = $(this).val()
                    var cols = api.columns()[0].length
                    if(val == 1){
                        api.column(cols-1).order( 'asc' ).draw();
                    }

                    if(val == 2){
                        api.column(cols-1).order( 'desc' ).draw();
                    }

                    if(val == 3){
                        api.column(0).order( 'asc' ).draw();
                    }

                    if(val == 4){
                        api.column(0).order( 'desc' ).draw();
                    }

                    if(val == ""){
                        api.column(0).order( 'asc' ).draw();
                    }
                })

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

                    // $(select).change(function () {
                    //     var val = DataTable.util.escapeRegex($(this).val());

                    //     column
                    //         .search(val ? '^' + val + '$' : '', true, false)
                    //         .draw();
                    // });

                $("select[data-control=select2]").select2()
                }
        })

        function archiveItem(me){
            var _id = $(me).data("id")
            var uri = $(me).data("url")
            console.log($(me).data('name'))
            $("#leads-label").text($(me).data("name"))
            $("#leads-url").attr("href", uri)
        }

        function init_tag(tag) {
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

        function removeCompany(me){
            $(me).parents("div.position-relative").find('input[type=text]').prop("disabled", false)
            $(me).parents("form").find("button[type=submit]").prop("disabled", true)
            $(me).parents(".company-item").remove()
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

                toggle_file()

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

                console.log($("#drawer-advance #form-lead input:required, select:required, textarea:required"))

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
            })
        }

        function show_product(target, id, id_par){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : `{{ route("crm.products.detail") }}/${id}`,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release()
                $(target).html(resp.view)
                $(target + " #kt_accordion_1_header_1").click(function(){
                    if($(this).find('.accordion-button').hasClass("collapsed") == false) {
                        $(target + " [data-accordion=collapsed]").prev().hide()
                        $(target + " [data-accordion=collapsed]").show()
                    } else {
                        $(target + " [data-accordion=collapsed]").prev().show()
                        $(target + " [data-accordion=collapsed]").hide()
                    }
                })

                toggle_file()

                $(target + " input.number").number(true, 2, ",", ".")

                $(target + " [data-control=select2]").select2()
                let _editor = {}
                $(".ck-editor").each(function(){
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

                init_company(id)
            })
        }

        init_company()

        function init_company(id = null){
            $("input.find-company").each(function(){
                var pName = $(this).data("name")
                var multiple = $(this).data("multiple")
                var divAdd = $(this).parent().find(".find-add")
                var divResult = $(this).parent().find(".find-noresult")
                console.log(multiple)
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

        $(document).ready(function(){
            // slide menu search bar
            $(".menu_dicky").click(function(){
               $("#modalMenu").modal("show");
            });
            $("#cl_product").click(function(){
                if ($('#menu_product').is(':visible')){
                    $('#menu_product').slideUp();
                } else {
                    $('#menu_product').slideDown();
                }
            });

            $(".tag").each(function(){
                var _id = $(this).attr("id")
                init_tag(_id)
            })

            @if (\Session::has("list_id"))
                drawer.show()
                show_product('#drawer-content',{{ \Session::get("list_id") }})
            @endif
        })
    </script>
@endsection
