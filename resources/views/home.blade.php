@extends('layouts.template', ["withoutFooter" => 1])

@section('css')
    <style>
        .w-145px {
            width: 145px!important;
        }
    </style>
@endsection

@section('content')
<div class="d-flex flex-column gap-5">
    <div class="row row-cols-md-3 row-cols-1">
        <div class="col">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <span class="fs-3">Saldo Anda</span>
                        <span class="fs-3 fw-bold">Rp. {{ number_format($saldoDeposit, 0, ",", ".") }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <span class="fs-3">Pesanan Menunggu Konfirmasi</span>
                        <span class="fs-3 fw-bold">{{ $myOrder->whereNull("nomor_resi")->count() }} Pesanan</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card shadow-none">
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <span class="fs-3">Pesanan Dikirim</span>
                        <span class="fs-3 fw-bold">{{ $myOrder->whereNotNull("nomor_resi")->count() }} Pesanan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom bg-primary text-white" style="background-image: url({{ asset("images/dashboard-banner.png") }}); background-size: cover; background-blend-mode: screen;">
        <div class="card-body">
            <div class="d-flex align-items-center flex-column flex-md-column flex-lg-row">
                <form method="post" action="{{ route('booking.cari') }}" class="w-md-100 mb-5 mb-md-0">
                    <div class="d-flex flex-column">
                        <span class="fs-2tx">Halo! {{ Auth::user()->name }}</span>
                        <span class="fw-semibold mb-5">Temukan harga terbaik untuk pengiriman mu</span>
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="d-flex flex-column gap-5">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="fv-row">
                                                <label class="col-form-label text-nowrap">Jenis produk</label>
                                                <select name="produk_id" required class="form-select" data-control="select2">
                                                    @foreach ($produk as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="fv-row">
                                                <label class="col-form-label">Dari</label>
                                                <select name="dari" required class="form-select" data-control="select2">
                                                    @foreach ($dari as $item)
                                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="fv-row">
                                                <label class="col-form-label">Negara Tujuan</label>
                                                <select name="tujuan" required class="form-select" data-control="select2" data-placeholder="- Negara Tujuan -">
                                                    <option value=""></option>
                                                    @foreach ($tujuan as $item)
                                                        <option value="{{ $item->id }}">{{ strtoupper($item->nama) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        @foreach ($produk as $item)
                                            <div class="{{ $loop->iteration == 1 ? "" : "d-none" }} d-flex flex-column gap-5" id="tab_{{ $item->id }}" data-role="tab">
                                                <div class="fv-row w-100">
                                                    <select name="kategori[{{ $item->id }}]{{ $item->tipe_kategori == "w" ? "" : "[]" }}" {{ $item->tipe_kategori == "w" ? "" : "multiple" }} {{ $loop->iteration == 1 ? "required" : "" }} class="form-select w-100" data-control="select2" data-placeholder="- {{ $item->tipe_kategori == "w" ? "Berat" : "Kategori" }} -">
                                                        <option value=""></option>
                                                        @foreach ($kategori->where("produk_id", $item->id) as $val)
                                                            <option value="{{ $val->id }}">{{ $val->nama }} {{ $item->tipe_kategori == "w" ? "kg" : "" }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!--begin::Repeater-->
                                                @if ($item->tipe_kategori != "w")
                                                    <div id="form_repeat_{{ $item->id }}" data-form="repeater">
                                                        <!--begin::Form group-->
                                                        <div class="form-group">
                                                            <div data-repeater-list="data" class="d-flex flex-column gap-5">
                                                                <div data-repeater-item class="border-top">
                                                                    <div class="form-group row pt-5">
                                                                        <div class="col-md-2">
                                                                            <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                <label class="form-label required">Jumlah Paket</label>
                                                                                <input type="number" {{ $loop->iteration == 1 ? "required" : "" }} data-input="total_paket" name="total_paket-{{ $item->id }}" value="1" min="1" class="form-control w-75px">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                <label class="form-label required">Berat (kg)</label>
                                                                                <input type="number" {{ $loop->iteration == 1 ? "required" : "" }} data-input="berat" name="berat-{{ $item->id }}" value="1" min="1" class="form-control w-75px">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                <label class="form-label required">Panjang (cm)</label>
                                                                                <input type="number" {{ $loop->iteration == 1 ? "required" : "" }} data-input="panjang" name="panjang-{{ $item->id }}" value="1" min="1" class="form-control w-75px">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                <label class="form-label required">Lebar (cm)</label>
                                                                                <input type="number" {{ $loop->iteration == 1 ? "required" : "" }} data-input="lebar" name="lebar-{{ $item->id }}" value="1" min="1" class="form-control w-75px">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                <label class="form-label required">Tinggi (cm)</label>
                                                                                <input type="number" {{ $loop->iteration == 1 ? "required" : "" }} data-input="tinggi" name="tinggi-{{ $item->id }}" value="1" min="1" class="form-control w-75px">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="d-flex align-items-center justify-content-between gap-3">
                                                                                <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger w-100">
                                                                                    <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                                                    Delete
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--end::Form group-->

                                                        <!--begin::Form group-->
                                                        <div class="form-group mt-5 d-flex justify-content-end gap-5">
                                                            <a href="javascript:;" data-repeater-create class="btn btn-warning">
                                                                Tambah Barang
                                                            </a>
                                                            <button type="submit" class="btn btn-primary">
                                                                Cari Harga
                                                            </button>
                                                        </div>
                                                        <!--end::Form group-->
                                                    </div>
                                                @else
                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary">
                                                        Cari Harga
                                                    </button>
                                                </div>
                                                @endif
                                                <!--end::Repeater-->
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="add_token" value="{{ rand(100000, 999999) }}">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_script')
<link href="{{ asset('theme/jquery-ui/jquery-ui.css') }}" rel="Stylesheet">
<script src="{{ asset('theme/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{ asset("theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js") }}"></script>
<script>
    function circle_chart() {
        var e = document.querySelectorAll(".circle-chart");
        [].slice.call(e).map((function (e) {
            var t = parseInt(KTUtil.css(e, "height"));
            if (e) {
                var a = e.getAttribute("data-kt-chart-color"),
                    v = e.getAttribute("data-kt-value"),
                    f = e.getAttribute("data-formatter"),
                    l = e.getAttribute("data-kt-label"),
                    o = KTUtil.getCssVariableValue("--bs-" + a),
                    r = KTUtil.getCssVariableValue("--bs-" + a + "-light"),
                    s = KTUtil.getCssVariableValue("--bs-white");
                new ApexCharts(e, {
                    series: [v],
                    chart: {
                        fontFamily: "inherit",
                        height: 110,
                        type: "radialBar"
                    },
                    plotOptions: {
                        radialBar: {
                            hollow: {
                                margin: 0,
                                size: "55%",
                                // background : "#D8D8D8"
                            },
                            dataLabels: {
                                showOn: "always",
                                name: {
                                    show: !1,
                                    fontWeight: "700"
                                },
                                value: {
                                    color: s,
                                    fontSize: "16px",
                                    fontWeight: "700",
                                    offsetY: 7,
                                    show: !0,
                                    formatter: function (e) {
                                        return l + f
                                    }
                                },
                            },
                            track: {
                                background: r,
                                strokeWidth: "170%",
                                opacity: .5
                            },
                        }
                    },
                    colors: [s],
                    stroke: {
                        lineCap: "round",
                        width : 1
                    },
                    labels: ["Progress"]
                }).render()
            }
        }))
    }

    $(document).ready(function(){
        circle_chart()
        $("#home-lokasi").autocomplete({
            source: encodeURI(
                "@auth{{ route("applicant.job.index") }}@else{{ route("applicant.job_guest.index") }}@endauth?a=location&t=autocomplete"
                ),
            minLength: 1,
            appendTo: "#autocomplete-div",
            response: function(event, ui) {
                console.log(event)
            },
            select: function(event, ui) {
                // $("#comp_id").val(ui.item.id)
                // $("#btn-add-company").show()
            }
        });

        $("div[data-role=tab]").each(function(){
            const tabTrigger = new bootstrap.Tab(this)
        })

        $("select[name=produk_id]").change(function(){
            const produkId = $(this).val()
            var tab = "#tab_" + produkId
            $("div[data-role=tab]").addClass("d-none")
            $("div[data-role=tab]").find("input, select").prop("required", false)
            $(tab).removeClass('d-none')
            $(tab).find("input, select").prop("required", true)
        })

        $("div[data-form=repeater]").each(function(){
            $(this).repeater({
                initEmpty: false,

                show: function () {
                    $(this).slideDown();
                    $(this).find("input").val(1)
                    // $(this).find("input[data-input]").each(function(){
                    //     var name = $(this).attr("name")
                    //     name += "["+$(this).data("input")+"]"
                    //     $(this).attr("name", name)
                    // })
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                ready: function(setIndexes){
                    setTimeout(() => {

                    }, 1000);
                },
                isFirstItemUndeletable: true
            });
        })
    })
</script>
@endsection
