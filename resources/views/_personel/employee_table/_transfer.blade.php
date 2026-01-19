<div class="d-flex flex-column">
    <div class="row">
        <div class="fv-row col-6">
            <label class="col-form-label">ID Pegawai</label>
            <input type="hidden" name="id" value="{{ $pr->id }}">
            <input type="text" name="emp_id" readonly class="form-control" value="{{ $pr->emp_id }}" id="">
        </div>
        <div class="fv-row col-6">
            <label class="col-form-label">Nama Pegawai</label>
            <input type="text" name="emp_name" readonly class="form-control" value="{{ $pr->emp_name }}" id="">
        </div>
    </div>
    <div class="my-5"></div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-start">
                <div class="symbol symbol-100px symbol-circle me-15">
                    <div class="symbol-label" style="background-image: url({{ asset($pr->user->user_img ?? "images/image_placeholder.png") }})"></div>
                </div>
                <div class="flex-fill">
                    <div class="row">
                        <div class="fv-row col-6">
                            <label class="col-form-label w-100 fw-bold">Nama Lengkap</label>
                            <label class="fw-normal mb-3">{{ $pr->emp_name }}</label>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label w-100 fw-bold">Tanggal Bergabung</label>
                            <label class="fw-normal mb-3">{{ date("d-m-Y", strtotime($pr->join_date ?? $pr->created_at)) }}</label>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label w-100 fw-bold">Status Kepegawaian</label>
                            <label class="fw-normal mb-3">{{ $pr->employee_status->label ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label w-100 fw-bold">Grup Kerja</label>
                            <label class="fw-normal mb-3">{{ $pr->reg->wg->workgroup_name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label w-100 fw-bold">Tingkat</label>
                            <label class="fw-normal mb-3">{{ $pr->job_grade->name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label w-100 fw-bold">Level</label>
                            <label class="fw-normal mb-3">{{ $pr->job_level->name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label w-100 fw-bold">Posisi</label>
                            <label class="fw-normal mb-3">{{ $pr->position->name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label w-100 fw-bold">Departemen</label>
                            <label class="fw-normal mb-3">{{ $pr->user->uac_departement->name ?? "-" }}</label>
                        </div>
                        <div class="fv-row col-6">
                            <label class="col-form-label w-100 fw-bold">Lokasi</label>
                            <label class="fw-normal mb-3">{{ $pr->user->uac_location->name ?? "-" }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-transfer class="mb-5 d-flex flex-column"></div>
    <div class="d-flex flex-column">
        <label class="col-form-label">Perpindahan*</label>
        <span class="my-3">&nbsp;&nbsp;</span>
        <div class="row">
            <div class="col-3 p-3">
                <div @if(empty($reg)) data-bs-toggle="tooltip" title="Employee is not registered in the attendance" @endif class="border {{ empty($reg) ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!empty($reg)) onclick="show_transfer_form({{ $pr->id }}, 'workgroup')" @endif>
                    <i class="fi fi-rr-users-alt {{ empty($reg) ? "text-dark" : "text-primary" }}"></i>
                    <span >Grup Kerja</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border border-primary bg-light-primary d-flex flex-column align-items-center rounded py-3 cursor-pointer text-primary" onclick="show_transfer_form({{ $pr->id }}, 'job_level')">
                    <i class="fi fi-rr-chevron-double-up text-primary"></i>
                    <span >Level</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border border-primary bg-light-primary d-flex flex-column align-items-center rounded py-3 cursor-pointer text-primary" onclick="show_transfer_form({{ $pr->id }}, 'job_grade')">
                    <i class="fi fi-rr-star text-primary"></i>
                    <span >Tingkat</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border border-primary bg-light-primary d-flex flex-column align-items-center rounded py-3 cursor-pointer text-primary" onclick="show_transfer_form({{ $pr->id }}, 'employee_status')">
                    <i class="fi fi-rr-user-gear text-primary"></i>
                    <span >Status Kepegawaian</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div @if(empty($user)) data-bs-toggle="tooltip" title="Employee does not have a user account" @endif class="border {{ empty($user) ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!empty($user)) onclick="show_transfer_form({{ $pr->id }}, 'departement')" @endif>
                    <i class="fi fi-rr-building {{ empty($user) ? "text-dark" : "text-primary" }}"></i>
                    <span >Departemen</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border border-primary bg-light-primary d-flex flex-column align-items-center rounded py-3 cursor-pointer text-primary" onclick="show_transfer_form({{ $pr->id }}, 'position')">
                    <i class="fi fi-rr-briefcase text-primary"></i>
                    <span >Posisi</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border bg-light-primary border-primary text-primary d-flex flex-column align-items-center rounded py-3 cursor-pointer" onclick="show_transfer_form({{ $pr->id }}, 'acting_position')">
                    <i class="fi fi-rr-users text-primary"></i>
                    <span >Posisi Sementara</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div @if(empty($user)) data-bs-toggle="tooltip" title="Employee does not have a user account" @endif class="border {{ empty($user) ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!empty($user)) onclick="show_transfer_form({{ $pr->id }}, 'location')" @endif>
                    <i class="fi fi-rr-marker {{ empty($user) ? "text-dark" : "text-primary" }}"></i>
                    <span >Lokasi</span>
                </div>
            </div>
        </div>
    </div>
</div>
