<div class="card card-custom bg-transparent">
    <div class="card-header  border-0">
        <h3 class="card-title">User Colaborator</h3>
    </div>
</div>

<div class="row g-0">
    @actionStart('employer_account', "create")
    <div class="col-5 me-5 mb-5">
        <div class="card border border-dashed border-primary card-stretch cursor-pointer hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                    <span class="fa fa-plus-circle text-primary"></span>
                    <span class="text-primary">Tambah User Kolaborator</span>
                </div>
            </div>
        </div>
    </div>
    @actionEnd
    <div class="col-5 me-5 mb-5">
        <div class="card bg-secondary card-stretch">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <span class="mb-3 fa fa-crown text-warning fs-2"></span>
                    <span class="fw-bold">{{ ucwords(strtolower($compOwner->name)) }}</span>
                    <span class="mb-5">Pemilik Akun - HR Recruitmen</span>
                    <span>{{ $compOwner->email }}</span>
                </div>
            </div>
        </div>
    </div>
    @foreach ($collaborators as $item)
    <div class="col-5 me-5 mb-5">
        <div class="card card-stretch cursor-pointer hover-elevate-up" data-bs-toggle="modal" data-bs-target="#modalCollab{{ $item->id }}">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <span class="mb-3 fa fa-user fs-2"></span>
                    <span class="fw-bold">{{ ucwords(strtolower($item->name)) }}</span>
                    <span class="mb-5">{{ "$item->dept $item->do_code" }}</span>
                    <span>{{ $item->email }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalCollab{{ $item->id }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <span class="mb-3 fa fa-user fs-2"></span>
                        <span class="fw-bold">{{ ucwords(strtolower($item->name)) }}</span>
                        <span class="mb-5">{{ "$item->dept $item->do_code" }}</span>
                        <span class="mb-5">{{ $item->email }}</span>
                        <div class="d-flex">
                            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Tutup</button>
                            @actionStart("employer_account", "delete")
                            <button type="button" class="btn text-primary" data-id="{{ $item->id }}" data-name="{{ ucwords(strtolower($item->name)) }}" data-act="modal" data-bs-stacked-modal="#modalNonaktif" >Nonaktifkan</button>
                            <button type="button" class="btn text-primary" data-id="{{ $item->id }}" data-name="{{ ucwords(strtolower($item->name)) }}" data-act="modal" data-bs-stacked-modal="#modalDelete">Hapus Kolaborator</button>
                            @actionEnd
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade" tabindex="-1" id="modalAdd">
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
                    <input type="hidden" name="id" value="{{ $company->id ?? null }}">
                    <input type="hidden" name="type" value="additional">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalNonaktif">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('account.setting.uc_delete', "non") }}" method="post">
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                            <span class="fw-bold fs-2">Nonaktifkan User</span>
                        </div>
                        <span>Apakah anda yakin ingin menonaktifkan user Kolaborator <span class="lbl-name"></span></span>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="id">
                    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</a>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('account.setting.uc_delete', "delete") }}" method="post">
                <div class="modal-body">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-info-circle text-danger fs-2 me-3"></i>
                            <span class="fw-bold fs-2">Hapus Kolaborator</span>
                        </div>
                        <span>Apakah anda yakin ingin menghapus user Kolaborator <span class="lbl-name"></span></span>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="id">
                    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</a>
                </div>
            </form>
        </div>
    </div>
</div>
