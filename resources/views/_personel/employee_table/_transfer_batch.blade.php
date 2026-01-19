<div class="d-flex flex-column">
    <div class="row">
        <div class="fv-row col-12">
            <label class="col-form-label">Employee*</label>
            <select name="emp" class="form-select" data-control="select2" data-placeholder="Choose Employee" id="">
                <option value=""></option>
                @foreach ($personel as $item)
                    <option value="{{ $item->id }}" data-emp-id="{{ $item->emp_id }}" {{ in_array($item->id, $personels->pluck("id")->toArray()) ? "disabled" : "" }}>{{ $item->emp_name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="my-5"></div>
    <table class="table table-display-2 bg-white" data-ordering="false" id="table-batch">
        <thead>
            <tr>
                <th>No</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($personels as $i => $item)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $item->emp_id }}</td>
                    <td>{{ $item->emp_name }}</td>
                    <td>
                        <input type="hidden" name="emp_id[]" value="{{ $item->id }}">
                        <button type="button" data-toggle="remove" class="btn btn-icon btn-sm">
                            <i class="fa fa-times text-danger"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div data-transfer class="mb-5 d-flex flex-column"></div>
    <div class="d-flex flex-column">
        <label class="col-form-label">Transfering Field*</label>
        <span class="my-3">&nbsp;&nbsp;.</span>
        <div class="row">
            <input type="hidden" name="batch" value="1">
            @foreach ($personels as $item)
                <input type="hidden" name="emp_id[]" value="{{ $item->id }}">
            @endforeach
            <div class="col-3 p-3">
                <div class="border {{ count($disabled['wg']) > 1 ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!count($disabled['wg']) <= 1) onclick="show_transfer_form_batch('{{ $disabled['wg'][0] ?? '' }}', 'workgroup')" @endif>
                    <i class="fi fi-rr-users-alt {{ count($disabled['wg']) > 1 ? "text-dark" : "text-primary" }}"></i>
                    <span >Work Group</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border {{ count($disabled['job_level']) > 1 ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} border-primary bg-light-primary d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!count($disabled['job_level']) <= 1) onclick="show_transfer_form_batch('{{ $disabled['job_level'][0] ?? '' }}', 'job_level')" @endif>
                    <i class="fi fi-rr-chevron-double-up  {{ count($disabled['job_level']) > 1 ? "text-dark" : "text-primary" }}"></i>
                    <span >Job Level</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border {{ count($disabled['job_grade']) > 1 ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} border-primary bg-light-primary d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!count($disabled['job_grade']) <= 1) onclick="show_transfer_form_batch('{{ $disabled['job_grade'][0] ?? '' }}', 'job_grade')" @endif>
                    <i class="fi fi-rr-star  {{ count($disabled['job_grade']) > 1 ? "text-dark" : "text-primary" }}"></i>
                    <span >Job Grade</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border {{ count($disabled['employee_status']) > 1 ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} border-primary bg-light-primary d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!count($disabled['employee_status']) <= 1) onclick="show_transfer_form_batch('{{ $disabled['employee_status'][0] ?? '' }}', 'employee_status')" @endif>
                    <i class="fi fi-rr-user-gear  {{ count($disabled['employee_status']) > 1 ? "text-dark" : "text-primary" }}"></i>
                    <span >Employee Status</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border {{ count($disabled['departement']) ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!count($disabled['departement']) <= 1) onclick="show_transfer_form_batch('{{ $disabled['departement'][0] ?? '' }}', 'departement')" @endif>
                    <i class="fi fi-rr-building {{ count($disabled['departement']) ? "text-dark" : "text-primary" }}"></i>
                    <span >Departement</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border {{ count($disabled['position']) > 1 ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} border-primary bg-light-primary d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!count($disabled['position']) <= 1) onclick="show_transfer_form_batch('{{ $disabled['position'][0] ?? '' }}', 'position')" @endif>
                    <i class="fi fi-rr-briefcase  {{ count($disabled['position']) > 1 ? "text-dark" : "text-primary" }}"></i>
                    <span >Position</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border {{ count($disabled['acting_position']) ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!count($disabled['acting_position']) <= 1) onclick="show_transfer_form_batch('{{ $disabled['acting_position'][0] ?? '' }}', 'acting_position')" @endif>
                    <i class="fi fi-rr-users {{ count($disabled['acting_position']) ? "text-dark" : "text-primary" }}"></i>
                    <span >Acting Position</span>
                </div>
            </div>
            <div class="col-3 p-3">
                <div class="border {{ count($disabled['location']) ? "bg-secondary border-secondary text-dark" : "bg-light-primary border-primary text-primary" }} d-flex flex-column align-items-center rounded py-3 cursor-pointer" @if(!count($disabled['location']) <= 1) onclick="show_transfer_form_batch('{{ $disabled['location'][0] ?? '' }}', 'location')" @endif>
                    <i class="fi fi-rr-marker {{ count($disabled['location']) ? "text-dark" : "text-primary" }}"></i>
                    <span >Location</span>
                </div>
            </div>
        </div>
    </div>
</div>
