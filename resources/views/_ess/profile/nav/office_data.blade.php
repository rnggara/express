<div class="d-flex flex-column gap-5">
    <div class="card bg-secondary-crm shadow-none">
        <div class="card-body p-5">
            <div class="d-flex flex-column gap-5">
                <div class="d-flex gap-5">
                    <div class="fv-row">
                        <label class="col-form-label">Departement</label>
                        <input type="text" value="{{ $user->uacdepartement->name ?? "-" }}" disabled class="form-control">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Last Update</label>
                        <input type="text" value="{{ empty($user->uac_departement_mutation_date) ? "-" : date("d-m-Y", strtotime($user->uac_departement_mutation_date)) }}" disabled class="form-control">
                    </div>
                </div>
                <div class="d-flex gap-5">
                    <div class="fv-row">
                        <label class="col-form-label">Position</label>
                        <input type="text" value="{{ $personel->position->name ?? "-" }}" disabled class="form-control">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Last Update</label>
                        <input type="text" value="{{ empty($personel->position_mutation_date ?? null) ? "-" : date("d-m-Y", strtotime($personel->position_mutation_date)) }}" disabled class="form-control">
                    </div>
                </div>
                <div class="d-flex gap-5">
                    <div class="fv-row">
                        <label class="col-form-label">Employee Status</label>
                        <input type="text" value="{{ $personel->employee_status->label ?? "-" }}" disabled class="form-control">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Last Update</label>
                        <input type="text" value="{{ empty($personel->employee_status_mutation_start ?? null) ? "-" : date("d-m-Y", strtotime($personel->employee_status_mutation_start)) }}" disabled class="form-control">
                    </div>
                </div>
                <div class="d-flex gap-5">
                    <div class="fv-row">
                        <label class="col-form-label">Job Grade</label>
                        <input type="text" value="{{ $personel->job_grade->name ?? "-" }}" disabled class="form-control">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Last Update</label>
                        <input type="text" value="{{ empty($personel->job_grade_mutation_date ?? null) ? "-" : date("d-m-Y", strtotime($personel->job_grade_mutation_date)) }}" disabled class="form-control">
                    </div>
                </div>
                <div class="d-flex gap-5">
                    <div class="fv-row">
                        <label class="col-form-label">Class</label>
                        <input type="text" value="{{ $personel->class->name ?? "-" }}" disabled class="form-control">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Last Update</label>
                        <input type="text" value="{{ empty($personel->class_mutation_date ?? null) ? "-" : date("d-m-Y", strtotime($personel->class_mutation_date)) }}" disabled class="form-control">
                    </div>
                </div>
                <div class="d-flex gap-5">
                    <div class="fv-row">
                        <label class="col-form-label">Location</label>
                        <input type="text" value="{{ $user->uaclocation->name ?? "-" }}" disabled class="form-control">
                    </div>
                    <div class="fv-row">
                        <label class="col-form-label">Last Update</label>
                        <input type="text" value="{{ empty($user->uac_location_mutation_date ?? null) ? "-" : date("d-m-Y", strtotime($user->uac_location_mutation_date)) }}" disabled class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
