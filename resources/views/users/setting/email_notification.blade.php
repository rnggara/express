<form action="" method="post">
    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header py-3 border-0">
            <div class="card-title align-items-start flex-column">
                <h3 class="card-label font-weight-bolder text-dark">{{ __("Email Notifikasi") }}</h3>
                <span class="text-muted font-weight-bold font-size-sm mt-1"></span>
            </div>
        </div>
        <!--end::Header-->
        <div class="card-body bg-white rounded-top">
            <div class="d-flex flex-column">
                <span class="fw-bold mb-5">Alert & Notifikasi</span>
                <span class="mb-5">Kirim Aku :</span>
                <div class="form-check form-check-sm form-check-custom form-check-info mb-5">
                    <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="ckKom" id="ckKom" />
                    <label class="cursor-pointer form-check-label text-dark" for="ckKom">
                        Komunikasi {{ \Config::get("constants.APP_LABEL") }} Portal
                    </label>
                </div>
                <span class="mb-5">Dapatkan berita dari {{ \Config::get("constants.APP_LABEL") }} Portal, pengumuman, dan pembaruan apply</span>
                <div class="form-check form-check-sm form-check-custom form-check-info mb-5">
                    <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="ckKom" id="ckKom" />
                    <label class="cursor-pointer form-check-label text-dark" for="ckKom">
                        Aktifasi Akun
                    </label>
                </div>
                <span class="mb-5">Dapatkan pemberitahuan penting tentang kamu atau aktivitas yang kamu lewatkan</span>
                <div class="form-check form-check-sm form-check-custom form-check-info mb-5">
                    <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="ckKom" id="ckKom" />
                    <label class="cursor-pointer form-check-label text-dark" for="ckKom">
                        Draf
                    </label>
                </div>
                <span class="mb-5">Setelah Anda menerima undangan, Anda bisa mendapatkan email dari employer</span>
                <div class="form-check form-check-sm form-check-custom form-check-info mb-5">
                    <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="ckKom" id="ckKom" />
                    <label class="cursor-pointer form-check-label text-dark" for="ckKom">
                        Info Lowongan Sekitar kamu
                    </label>
                </div>
                <span class="mb-5">Dapatkan email saat Lowongan di kerjaku diposting di dekat lokasi saya</span>
                <span class="fw-bold mb-5">Alert & Notifikasi</span>
                <span class="mb-5">Email Saya Ketika :</span>
                <div class="form-check form-check-sm form-check-custom form-check-info mb-5">
                    <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="ckKom" id="ckKom" />
                    <label class="cursor-pointer form-check-label text-dark" for="ckKom">
                        Employer  mengundang saya untuk melamar di salah job yg dia posting
                    </label>
                </div>
                <div class="form-check form-check-sm form-check-custom form-check-info mb-5">
                    <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="ckKom" id="ckKom" />
                    <label class="cursor-pointer form-check-label text-dark" for="ckKom">
                        Lamaran saya terpilih
                    </label>
                </div>
                <div class="form-check form-check-sm form-check-custom form-check-info mb-5">
                    <input class="cursor-pointer form-check-input ck-filter" type="checkbox" value="ckKom" id="ckKom" />
                    <label class="cursor-pointer form-check-label text-dark" for="ckKom">
                        Employer  mengundang saya untuk Interview & Test
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>
