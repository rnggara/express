@extends('_ess.layout', [
    'contentHeight' => "h-100"
])

@section('view_content')
<div class="card shadow-none card-p-0 h-100">
    <div class="card-body">
        <div class="d-flex flex-column gap-5 h-100">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-50px">
                        <div class="symbol-label bg-primary">
                            <i class="fi fi-sr-file-invoice-dollar text-white fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold">Remunerasi</span>
                        <span>Anda dapat mendownload slip gaji dan slip pajak</span>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 px-5 pt-5">
                <li class="nav-item">
                    <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_collect">
                        <span class="nav-text">Slip Gaji</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_history">
                        <span class="nav-text">Slip Pajak</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content flex-fill" id="myTabContent">
                <div class="tab-pane fade show active h-100" id="tab_collect" role="tabpanel">
                    <div class="row h-100 gap-5 gap-md-0">
                        <div class="col-12 col-md-3">
                            <div class="p-10 rounded bg-secondary-crm">
                                <div class="d-flex flex-column" data-form>
                                    <div class="fv-row">
                                        <label class="col-form-label">Tahun</label>
                                        <select name="payroll_year" data-required class="form-select" data-control="select2" data-placeholder="Pilih Tahun" id="">
                                            <option value=""></option>
                                            @for ($i = date("Y") - 3; $i <= date("Y") ; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="fv-row">
                                        <label class="col-form-label">Bulan</label>
                                        <select name="payroll_month" data-required class="form-select" data-control="select2" data-placeholder="Pilih Bulan" id="">
                                            <option value=""></option>
                                            @for ($i = 1; $i <= 12 ; $i++)
                                                <option value="{{ $i }}">{{ date("F", strtotime(date("Y")."-".sprintf("%02d", $i))) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-5" disabled onclick="confirmPassowrd('payroll')" data-bs-toggle="modal" data-bs-target="#modalConfirm">Kirim</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 h-100">
                            <div class="p-10 h-100 rounded bg-secondary-crm" data-result="payroll">
                                <div class="h-100 justify-content-center d-flex flex-column align-items-center p-10" data-list-empty>
                                    <span class="fi fi-rr-document fs-1 text-muted"></span>
                                    <span class="text-muted">No preview</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade h-100" id="tab_history" role="tabpanel">
                    <div class="row h-100 gap-5 gap-md-0">
                        <div class="col-12 col-md-3">
                            <div class="p-10 rounded bg-secondary-crm">
                                <div class="d-flex flex-column">
                                    <div class="fv-row">
                                        <label class="col-form-label">Tahun</label>
                                        <select name="machine_name" data-required class="form-select" data-control="select2" data-placeholder="Pilih Tahun" id="">
                                            <option value=""></option>
                                            @for ($i = date("Y") - 3; $i <= date("Y") ; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-5" disabled>Kirim</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 h-100">
                            <div class="p-10 h-100 rounded bg-secondary-crm">
                                <div class="h-100 justify-content-center d-flex flex-column align-items-center p-10" data-list-empty>
                                    <span class="fi fi-rr-document fs-1 text-muted"></span>
                                    <span class="text-muted">No preview</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="modalConfirm">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-secondary-crm px-10">
            <div class="modal-body">
                <div class="card shadow-none card-p-0 bg-secondary-crm">
                    <div class="card-body rounded">
                        <div class="d-flex flex-column gap-5">
                            <div class="fv-row" data-kt-password-meter="true">
                                <label class="col-form-label">Masukan password untuk melanjutkan pengajuan</label>
                                <div class="position-relative mb-3">
                                    <input class="form-control"
                                        type="password" placeholder="" name="password" autocomplete="off" />
                        
                                    <!--begin::Visibility toggle-->
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        data-kt-password-meter-control="visibility">
                                            <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                            <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </span>
                                    <!--end::Visibility toggle-->
                                </div>
                            </div>
                            <input type="hidden" name="key">
                            <button type="button" class="btn btn-sm btn-primary" onclick="postPassword()">Kirim</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_script')
    <script>

        function printIframe(iframeName) {
            var iframe = document.getElementById(iframeName);
            iframe.focus();
            iframe.contentWindow.print();
        }

        function confirmPassowrd(key) {
            $("#modalConfirm input[name=key]").val(key)
        }

        function resizeIframe(obj) {
            obj.style.height = (obj.contentWindow.document.documentElement.scrollHeight + 50) + 'px';
            obj.style.width = "100%";
        }

        function postPassword(){
            var key = $("#modalConfirm input[name=key]").val()
            var pw = $("#modalConfirm input[name=password]").val()
            var data = {}
            if(key == "payroll"){
                data['year'] = $("select[name=payroll_year]").val()
                data['month'] = $("select[name=payroll_month]").val()
            }

            $.ajax({
                url : "{{ route("ess.benefit.index") }}",
                type : "get",
                data : {
                    a : "post",
                    key : key,
                    pw : btoa(pw),
                    data : data
                },
                dataType : "json"
            }).then(function(resp){
                if(resp.success){
                    $("div[data-result='"+key+"']").html(resp.view)
                    $("#modalConfirm").modal("hide")
                    $("#modalConfirm input[name=password]").val("")
                    $("#modalConfirm input[name=key]").val("")
                } else {
                    Swal.fire(resp.message, "", "error")
                }
            })
        }
        
        $(document).ready(function(){
            $("[data-required]").change(function(){
                var form = $(this).parents("div[data-form]").eq(0)
                var btn = $(form).find("button")
                var requireds = []
                var ff = $(form).find("[data-required]")
                $(ff).each(function(){
                    if($(this).val() != ""){
                        requireds.push($(this).val())
                    }
                })

                if(requireds.length == ff.length){
                    $(btn).prop("disabled", false)
                    $(btn).removeClass("btn-secondary").addClass("btn-primary")
                } else {
                    $(btn).prop("disabled", true)
                    $(btn).removeClass("btn-primary").addClass("btn-secondary")
                }
            })
        })
    </script>
@endsection
