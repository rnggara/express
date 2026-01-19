<!--begin::Form-->
<div class="card card-custom card-stretch">
    <form class="form" action="{{route('account.delete')}}" id="kt_form" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <!--begin::Header-->
        <div class="card-header">
            <h3 class="card-title">{{ __("user.delete_account") }}</h3>
        </div>
        <!--end::Header-->
        <div class="card-body">
            <!--begin::Form Group-->
            <div class="d-flex flex-column">
                <span class="fw-bold mb-5">Apakah Anda ingin menghapus akun Kerjaku Portal Anda: {{ $user->email }}?</span>
                <span>Akun ini berisi profile dan ada 5 lamaran yang sedang dalam proses dan 10 draf. Menghapus akun Anda akan menghapus semua konten dan data Anda yang terkait dengannya.</span>
                <div>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalDelete" class="btn btn-link text-primary">Saya ingin menghapus akun saya</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!--end::Form-->

<div class="modal fade" tabindex="-1" id="modalDelete">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">
                <form class="form" action="{{route('account.delete')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="align-items-center d-flex flex-column p-5">
                        <img src="{{ asset("images/delete-confirmation.png") }}" class="w-50 mb-5">
                        <span class="fw-bold fs-2 mb-3">Apakah anda yakin ingin menghapus akun?</span>
                        <span class="mb-5">Anda dapat mengubah kata sandi lagi setelah 15 hari</span>
                        <div>
                            <button type="submit" class="btn btn-primary">Yakin, Hapus</button>
                        </div>
                        <div>
                            <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
