@extends('layouts.template')

@section('content')
    <div class="d-flex flex-column">
        <div class="card mb-8">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
                    <span class="fw-bold fs-1">Laporan Job Ad</span>
                    <div class="d-flex flex-column flex-md-row">
                        <a href="{{route("job_report.index")}}" class="btn text-active-primary {{ $a == null ? "active" : "" }}">Job Ad yang Tayang ({{ $job_list->whereNotNull("confirm_at")->count() }})</a>
                        <a href="{{ route("job_report.index")."?a=draft" }}" class="btn text-active-primary {{ $a == "draft" ? "active" : "" }}">Draf Tersimpan</a>
                        <a href="{{ route("job_report.index")."?a=applicant" }}" class="btn text-active-primary" {{ $a == "applicant" ? "active" : "" }}>Lamaran</a>
                        <a href="{{ route('job.add.view') }}" class="btn btn-primary">Buat Job Ad</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 overflow-auto">
                        <table class="table rounded" id="table-job">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">Nama Job Ad</th>
                                    <th class="text-nowrap text-md-center">Status Job Ad</th>
                                    <th class="text-nowrap">Dibuat Oleh</th>
                                    <th class="text-nowrap text-md-center">Tanggal Dibuat</th>
                                    <th class="text-nowrap text-md-center">Tanggal Tayang</th>
                                    <th class="text-nowrap text-md-center">Jumlah dilihat</th>
                                    <th class="text-nowrap text-md-center">Jumlah pelamar</th>
                                    <th class="text-nowrap text-md-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $_job_list = $job_list;
                                    if(!empty($a)){
                                        $_job_list = $job_list->whereNull("confirm_at");
                                    }
                                @endphp
                                @foreach ($_job_list as $item)
                                    @php
                                        $hasComp = false;
                                        $activateText = "Aktifkan";
                                        $status = 1;
                                        if(!empty($item->company_id)){
                                            $hasComp = true;
                                            if(!empty($item->activate_at)){
                                                $status = 0;
                                                $activateText = "Non Aktifkan";
                                            }
                                        }

                                        $statusJobAd = "Non Aktif";
                                        $titleTooltip = "";
                                        if(!empty($item->activate_at)){
                                            $statusJobAd = "Aktif";

                                            if(!empty($item->confirm_at)){
                                                $statusJobAd = "Tayang";
                                            }

                                            if(!empty($item->rejected_at)){
                                                $statusJobAd = "Tolak";
                                                $titleTooltip = $item->rejected_notes;
                                            }

                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-nowrap">{{$item->position}}</td>
                                        <td class="text-center">
                                            <span data-bs-toggle="tooltip" title="{{ $titleTooltip }}">{{ $statusJobAd }}</span>
                                        </td>
                                        <td class="text-nowrap">{{ $users[$item->user_id] ?? "-" }}</td>
                                        <td class="text-center">{{ date("d/m/Y", strtotime($item->created_at)) }}</td>
                                        <td class="text-center">{{ empty($item->confirm_at) ? "-" : date("d/m/Y", strtotime($item->confirm_at)) }}</td>
                                        <td class="text-center">{{ $views->where('job_id', $item->id)->count() }}</td>
                                        <td class="text-center">{{ $applicant->where("job_id", $item->id)->count() }}</td>
                                        <td>
                                            <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 10px">
                                                <i class="fa fa-ellipsis-vertical text-dark"></i>
                                            </button>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                                <!--begin::Menu item-->
                                                @if (!in_array($item->id, $jobCollabs->pluck("id")->toArray()))
                                                <div class="menu-item px-3">
                                                    <a href="javascript:;" onclick="assign_to({{ $item->id }})" class="menu-link px-3">
                                                        Assign To
                                                    </a>
                                                </div>
                                                @endif
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('job_report.detail', $item->id) }}" class="menu-link px-3">
                                                        Lihat Semua Kandidat
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="{{ route("job_report.view", $item->id) }}" class="menu-link px-3">
                                                        Preview Job Ad
                                                    </a>
                                                </div>
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                @if (!in_array($item->id, $jobCollabs->pluck("id")->toArray()))
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('job.add.view')."?id=$item->id" }}" class="menu-link px-3">
                                                        Edit Job Ad
                                                    </a>
                                                </div>
                                                @endif
                                                <!--end::Menu item-->

                                                <!--begin::Menu item-->
                                                @if (!in_array($item->id, $jobCollabs->pluck("id")->toArray()))
                                                <div class="menu-item px-3">
                                                    <a href="javascript:;" @if($hasComp) onclick="nonaktifjob({{ $item->id }}, {{ $status }})" @endif class="menu-link px-3 {{ !$hasComp ? "disabled text-secondary" : "" }}">
                                                        {{ $activateText }} Job Ad
                                                    </a>
                                                </div>
                                                @endif
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
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

    <div class="modal fade" tabindex="-1" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('job_report.nonaktif') }}" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-info-circle fs-2 me-3"></i>
                                <span class="fw-bold fs-2">Non Aktifkan Job Ads</span>
                            </div>
                            <span>Apakah Anda yakin untuk <span class="ff"></span> job ad?</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id" id="id-job-ad">
                        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn">Ya, Nonaktifkan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalAssign">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('job_report.collaborator') }}" method="post">
                    @csrf
                    <input type="hidden" name="job_id">
                    <div class="modal-body">
                        <div class="d-flex flex-column mb-5">
                            <div class="d-flex justify-content-between">
                                <h3 class="fw-bold">Assign To</h3>
                                <button type="button" class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <div class="fw-row mb-5" id="sel-modal-assign">
                                <div class="input-group flex-nowrap border rounded">
                                    <div class="input-group-text border-0 bg-transparent"><i class="fa fa-search"></i></div>
                                    <div class="overflow-hidden flex-grow-1">
                                        <select name="search_user" id="search_user" class="form-select rounded-start-0 border-0" data-control="select2" data-dropdown-parent="#sel-modal-assign" data-placeholder="Cari nama user yang ditugaskan">
                                            <option value=""></option>
                                            @foreach ($user_collab as $item)
                                                <option value="{{ $item->id }}" data-dept="{{ $item->dept }}" data-pos="{{ $item->do_code }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-center" id="add-collaborator">
                                <img src="{{ asset("images/collab.png") }}" class="w-75 mb-3" alt="">
                                <span class="fw-bold mb-3">Pilih User</span>
                                <span class="mb-3">Lorem Ipsum dolor sit amet consectyetru adipiscing elit</span>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-stacked-modal="#modalAddCollab">
                                    Tambah User
                                </button>
                            </div>
                            <div class="d-flex flex-column" id="assign-user">

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalAddCollab">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambahan Kolaborator</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <form action="{{ route("account.setting.uc_add") }}" id="form-uc-add" method="post">
                    <div class="modal-body">
                        <div class="d-flex flex-column">
                            <div class="fv-row">
                                <label for="name" class="col-form-label required">Nama Lengkap</label>
                                <input type="text" name="name" id="name" class="form-control" required placeholder="Masukan nama lengkap">
                            </div>
                            <div class="fv-row">
                                <label for="position" class="col-form-label">Penamaan</label>
                                <input type="text" name="position" id="position" class="form-control" placeholder="Masukan penamaan atau posisi">
                            </div>
                            <div class="fv-row">
                                <label for="departemen" class="col-form-label">Departemen</label>
                                <input type="text" name="departemen" id="departemen" class="form-control" placeholder="Masukan departemen">
                            </div>
                            <div class="fv-row">
                                <label for="email" class="col-form-label required">Departemen</label>
                                <input type="email" name="email" id="email" class="form-control" required placeholder="Masukan email">
                            </div>
                            <div class="fv-row mt-5">
                                <label class="d-flex flex-column mb-3">
                                    <span class="fw-bold">Hak Akses Lowongan</span>
                                    <span>Pengguna dengan akses lowongan tertentu dapat mengelola kandidat di iklan lowowngan yang di posting atau bekerja sama mereka </span>
                                </label>
                                <div class="d-flex">
                                    <div class="form-check form-check-custom form-check-sm me-5">
                                        <input class="form-check-input" type="radio" name="job_ads_applicant" checked value="1" id="job_ads_applicant1"/>
                                        <label class="form-check-label text-dark" for="job_ads_applicant1">
                                            Semua lowongan pekerjaan
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-sm">
                                        <input class="form-check-input" type="radio" name="job_ads_applicant" value="2" id="job_ads_applicant2"/>
                                        <label class="form-check-label text-dark" for="job_ads_applicant2">
                                            Pekerjaan tertentu
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row mt-5">
                                <label class="d-flex flex-column mb-3">
                                    <span class="fw-bold">Laporan Iklan Pekerjaan</span>
                                    <span>Laporan iklan pekerjaan akun</span>
                                </label>
                                <div class="d-flex">
                                    <div class="form-check form-check-custom form-check-sm me-5">
                                        <input class="form-check-input" type="radio" name="job_ads_report" checked value="1" id="job_ads_report1"/>
                                        <label class="form-check-label text-dark" for="job_ads_report1">
                                            Diaktifkan
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-sm">
                                        <input class="form-check-input" type="radio" name="job_ads_report" value="2" id="job_ads_report2"/>
                                        <label class="form-check-label text-dark" for="job_ads_report2">
                                            Dengan disabilitas
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row mt-5">
                                <label class="d-flex flex-column mb-3">
                                    <span class="fw-bold">Postingan pekerjaan</span>
                                    <span>Dengan mengaktifkan postingan pekerjaan maka kolaborator anda dapat menggunakan kredit posting pekerjaan pada pembuatan iklan pekerjaan</span>
                                </label>
                                <div class="d-flex">
                                    <div class="form-check form-check-custom form-check-sm me-5">
                                        <input class="form-check-input" type="radio" name="job_ads" checked value="1" id="job_ads1"/>
                                        <label class="form-check-label text-dark" for="job_ads1">
                                            Diaktifkan
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-sm">
                                        <input class="form-check-input" type="radio" name="job_ads" value="2" id="job_ads2"/>
                                        <label class="form-check-label text-dark" for="job_ads2">
                                            Dengan disabilitas
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row mt-5">
                                <label class="d-flex flex-column mb-3">
                                    <span class="fw-bold">Pemanfaatan Pencarian Bakat</span>
                                    <span>Ambil resume dengan kredit search talen</span>
                                </label>
                                <div class="d-flex">
                                    <div class="form-check form-check-custom form-check-sm me-5">
                                        <input class="form-check-input" type="radio" name="search_applicant" checked value="1" id="search_applicant1"/>
                                        <label class="form-check-label text-dark" for="search_applicant1">
                                            Diaktifkan
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-sm">
                                        <input class="form-check-input" type="radio" name="search_applicant" value="2" id="search_applicant2"/>
                                        <label class="form-check-label text-dark" for="search_applicant2">
                                            Dengan disabilitas
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row mt-5">
                                <label class="d-flex flex-column mb-3">
                                    <span class="fw-bold">Manajemen Pengguna</span>
                                    <span>Kelola akun lain dalam akun</span>
                                </label>
                                <div class="d-flex">
                                    <div class="form-check form-check-custom form-check-sm me-5">
                                        <input class="form-check-input" type="radio" name="employer_account" checked value="1" id="employer_account1"/>
                                        <label class="form-check-label text-dark" for="employer_account1">
                                            Diaktifkan
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-sm">
                                        <input class="form-check-input" type="radio" name="employer_account" value="2" id="employer_account2"/>
                                        <label class="form-check-label text-dark" for="employer_account2">
                                            Dengan disabilitas
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row mt-5">
                                <label class="d-flex flex-column mb-3">
                                    <span class="fw-bold">Manajemen Pembelian</span>
                                    <span>Mengelola pembelian dalam akun</span>
                                </label>
                                <div class="d-flex">
                                    <div class="form-check form-check-custom form-check-sm me-5">
                                        <input class="form-check-input" type="radio" name="employer_purchasing" checked value="1" id="employer_purchasing1"/>
                                        <label class="form-check-label text-dark" for="employer_purchasing1">
                                            Diaktifkan
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-sm">
                                        <input class="form-check-input" type="radio" name="employer_purchasing" value="2" id="employer_purchasing2"/>
                                        <label class="form-check-label text-dark" for="employer_purchasing2">
                                            Dengan disabilitas
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="fv-row mt-5">
                                <label class="d-flex flex-column mb-3">
                                    <span class="fw-bold">Manajemen Profile Perusahaan</span>
                                    <span>Kelola profile perusahaan dalam akun</span>
                                </label>
                                <div class="d-flex">
                                    <div class="form-check form-check-custom form-check-sm me-5">
                                        <input class="form-check-input" type="radio" name="employer_profile" checked value="1" id="employer_profile1"/>
                                        <label class="form-check-label text-dark" for="employer_profile1">
                                            Diaktifkan
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-sm">
                                        <input class="form-check-input" type="radio" name="employer_profile" value="2" id="employer_profile2"/>
                                        <label class="form-check-label text-dark" for="employer_profile2">
                                            Dengan disabilitas
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id" value="{{ Auth::user()->comp_id ?? null }}">
                        <input type="hidden" name="type" value="additional">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        var user_assign = []

        function drawUser(){
            var el = $("#assign-user")

            var div = ""

            el.html("")

            var opt = $("#modalAssign select[name=search_user]").find(`option`).prop("disabled", false)

            if(user_assign.length > 0){
                div += "<span class='fw-bold mb-5'>Pengguna</span>"
            }

            for (let i = 0; i < user_assign.length; i++) {
                const element = user_assign[i];
                $("#modalAssign select[name=search_user]").find(`option[value='${element['id']}']`).prop("disabled", true)
                div += `<div class='d-flex justify-content-between align-items-center mb-5 p-5 border rounded w-100'>
                        <div class="d-flex flex-column">
                            <span class="fw-bold mb-2">${element['name']}</span>
                            <span class="">${element['pos']}</span>
                        </div>
                        <div class="form-check form-check-sm">
                            <input class="form-check-input" checked name="user_id[]" type="checkbox" value="${element['id']}" id="ckUser${element['id']}" />
                            <label class="form-check-label" for="ckUser${element['id']}">
                            </label>
                        </div>
                    </div>`
            }

            div += "<button type='submit' class='btn btn-primary btn-sm'>Assign</button>"

            el.html(div)

            var input = el.find("input[name='user_id[]']")
            if(input.length == 0){
                el.removeClass("d-flex").hide()
                $("#add-collaborator").addClass("d-flex").show()
            } else {
                el.addClass("d-flex").show()
                $("#add-collaborator").removeClass("d-flex").hide()
            }
        }

        function assign_to(id){
            $("#modalAssign").modal("show")
            $("#modalAssign input[name=job_id]").val(id)
            $.ajax({
                url : "{{ route("job_report.get_collaborator") }}/" + id,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                var data = resp.data
                user_assign = data
                drawUser()
            })
        }

        $("#modalAssign select[name=search_user]").change(function(){
            var $val = $(this).val()
            if($val != ""){
                var opt = $(this).find("option:selected")
                var col = {}
                col['id'] = opt.val()
                col['name'] = opt.text()
                col['pos'] = (opt.data("dept") ?? "") + " " + (opt.data['do_code'] ?? "")
                user_assign.push(col)
                $(this).val("").trigger("change")
                drawUser()
            }
        })

        function nonaktifjob(id, status){
            $("#modalDelete").modal("show")
            $('#id-job-ad').val(id);
            if(status == 0){
                $("#modalDelete .fw-bold.fs-2").text("Non Aktifkan Job Ads")
                $("#modalDelete .ff").text("menonaktifkan")
                $("#modalDelete .fa.fa-info-circle").removeClass("text-primary").addClass("text-danger")
                $("#modalDelete button[type=submit]").removeClass("btn-primary").addClass("btn-danger").text("Ya, Nonaktifkan")
            } else {
                $("#modalDelete .fw-bold.fs-2").text("Aktifkan Job Ads")
                $("#modalDelete .ff").text("mengaktifkan")
                $("#modalDelete .fa.fa-info-circle").removeClass("text-danger").addClass("text-primary")
                $("#modalDelete button[type=submit]").removeClass("btn-danger").addClass("btn-primary").text("Ya, Aktifkan")
            }
        }

        $(document).ready(function(){
            $("table").addClass("gy-7 gs-7 border").removeClass("table-striped")

            $("table thead tr").addClass("fw-semibold fs-6 text-gray-800 border-bottom border-gray-200").css("background-color", "#FAFAFA")

            var table_job = $("#table-job").DataTable({
                dom : "ftip",
                bInfo : false
            })

            var _filterDataTable = $(".dataTables_filter")
            _filterDataTable.find("input[type=search]").removeClass("form-control-solid")
            var _filterLabel = _filterDataTable.find("label")
            _filterLabel.each(function(){
                $(this).contents().filter(function(){ return this.nodeType != 1; }).remove();
                var el = "<span>Cari :</span>"
                var input = $(this).find("input")
                $(el).insertBefore(input)
            })

            @if ($errors->any())
                Swal.fire("", "{{ $errors->first() }}", "error")
            @endif

            @if (\Session::has("msg"))
                Swal.fire("", "{{ \Session::get('msg') }}", "success")
            @endif
        })
    </script>
@endsection
