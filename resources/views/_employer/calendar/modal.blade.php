<div class="modal-header">
    <h3 class="modal-title">{{ $type == "n" ? $user_name[$mKandidat[0]->user_id] : "Interview Group" }}</h3>

    <!--begin::Close-->
    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
    </div>
    <!--end::Close-->
</div>

<div class="modal-body">
    <div class="fv-row mb-3" id="f1">
        <div class="d-flex align-items-center">
            <span class="fa fs-2 fa-clock me-3"></span>
            <input type="text" class="form-control tempusDominus me-3" id="int-date" name="date">
            <select name="start_time" id="start_time" class="form-select me-3" data-control="select2" data-dropdown-parent="#f1">
                @php
                    $itime = 1;
                @endphp
                @foreach ($hours as  $h)
                    @php
                        $_h = explode(":", $h);
                    @endphp
                    @for ($i = 0; $i < 60; $i+=15)
                        @php
                            $ht = $_h[0].":".sprintf("%02d", $i);
                        @endphp
                        <option value="{{ $ht }}" data-index="{{ $itime++ }}" {{ $start_time == $ht ? "SELECTED" : "" }} >{{ $ht }}</option>
                    @endfor
                @endforeach
            </select>
            <span class="mx-3">-</span>
            <select name="end_time" id="end_time" class="form-select" data-control="select2" data-dropdown-parent="#f1">
                @php
                    $itime = 1;
                @endphp
                @foreach ($hours as  $h)
                    @php
                        $_h = explode(":", $h);
                    @endphp
                    @for ($i = 0; $i < 60; $i+=15)
                        @php
                            $ht = $_h[0].":".sprintf("%02d", $i);
                        @endphp
                        <option value="{{ $ht }}" data-index="{{ $itime++ }}" {{ $end_time == $ht ? "SELECTED" : "" }}>{{ $ht }}</option>
                    @endfor
                @endforeach
            </select>
        </div>
    </div>
    <div class="fv-row mb-3" id="f2">
        <div class="d-flex align-items-center">
            <span class="fa fs-2 fa-network-wired me-3"></span>
            <select name="int_type" id="int_type" class="form-select me-3" required data-control="select2" data-dropdown-parent="#f2" data-placeholder="Tipe Interview">
                <option value=""></option>
                <option value="1">Online</option>
                <option value="2" selected>Offline</option>
            </select>
        </div>
    </div>
    <div class="fv-row mb-3" style="display: none;">
        <div class="d-flex align-items-center">
            <span class="fa fs-2 fa-video me-3"></span>
            <input type="text" name="int_link" class="form-control" placeholder="Link meeting interview">
        </div>
    </div>
    <div class="fv-row mb-3">
        <div class="d-flex align-items-center">
            <span class="fa fs-2 fa-map-location-dot me-3"></span>
            <input type="text" name="int_lokasi" class="form-control" placeholder="Tambah ruang atau lokasi interview">
        </div>
    </div>
    <div class="fv-row mb-3">
        <div class="d-flex align-items-start">
            <span class="fa fs-2 fa-list me-3"></span>
            <textarea name="int_deskripsi" class="form-control ck-editor" placeholder="Silahkan input deskripsi lamaran" rows="10"></textarea>
        </div>
    </div>
    <div class="fv-row mb-3">
        <div class="d-flex align-items-start">
            <span class="fa fs-2 fa-list me-3"></span>
            <textarea name="int_test" class="form-control ck-editor" placeholder="Silahkan input test lamaran" rows="10"></textarea>
        </div>
    </div>
    <div class="fv-row mb-3" id="f3">
        <div class="d-flex align-items-center">
            <span class="fa fs-2 fa-link me-3"></span>
            <select name="int_assign_to" id="int_assign_to" required class="form-select me-3" data-control="select2" data-dropdown-parent="#f3" data-placeholder="Assign To">
                <option value=""></option>
                @foreach ($_users as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="fv-row mb-3">
        <div class="d-flex align-items-start">
            <span class="fa fs-2 fa-calendar me-3"></span>
            <div class="d-flex flex-column">
                <span>{{ Auth::user()->name }}</span>
                <span class="text-muted">Organizer</span>
            </div>

        </div>
    </div>
    <div class="fv-row mb-3">
        <div class="d-flex align-items-start">
            <span class="fa fs-2 fa-users me-3"></span>
            <span>Kandidat yang akan di interview</span>
        </div>
    </div>
    @foreach ($mKandidat as $item)
        <div class="fv-row mb-3">
            <div class="d-flex align-items-start">
                <div class="symbol symbol-25px symbol-circle me-3">
                    <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($user_img[$item->user_id] ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                </div>
                <span class="text-muted">{{ $user_name[$item->user_id] }}</span>
            </div>
        </div>
    @endforeach
</div>
<div class="modal-footer">
    @csrf
    <input type="hidden" name="id" value="{{ $id }}">
    <input type="hidden" name="type" value="{{ $type }}">
    <input type="hidden" name="e" value="{{ $e }}">
    <button type="button" class="btn text-primary" data-bs-dismiss="modal">Batal</button>
    <button type="submit" class="btn btn-primary">Kirim Undangan</button>
</div>
