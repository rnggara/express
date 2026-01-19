<div class="modal-body">
    <div class="d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between mb-5">
            <span class="fw-semibold">Request Jadwal Ulang</span>
            <div class="cursor-pointer" data-bs-dismiss="modal">
                <i class="fa fa-times text-dark"></i>
            </div>
        </div>
        <div class="border p-5 mb-5 rounded">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex">
                    <div class="symbol symbol-60px symbol-circle me-5">
                        <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($user->user_img ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ $user->name }}</span>
                        <span class="text-muted mb-3">{{ $job_ads->position }}</span>
                    </div>
                </div>
                <a href="{{ route("calendar.applicant", $user->id) }}" class="btn btn-primary">Lihat detail profile</a>
            </div>
        </div>
        <span class="mb-3">Keterangan :</span>
        <div class="border rounded p-5">
            <p>{!! $interview->reschedule_reasons !!}</p>
        </div>
    </div>
</div>

<div class="modal-footer">
    @csrf
    <input type="hidden" name="id" value="{{ $job_app->id }}">
    <button type="submit" name="submit" value="0" class="btn btn-danger">Tolak Request</button>
    <button type="submit" name="submit" value="1" class="btn btn-primary">Terima Request</button>
</div>
