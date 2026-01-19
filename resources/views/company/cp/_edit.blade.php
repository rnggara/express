<div class="modal-header">
    <h3>Edit Grade</h3>
    <button type="button" class="close" data-dismiss="modal">
        <i class="fa fa-times"></i>
    </button>
</div>
<form action="{{ route('cp.update') }}" method="post">
    <div class="modal-body">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="level" class="col-form-label">Level</label>
                    <select name="level" id="level" class="form-control select2" required data-placeholder="Select Level">
                        <option value=""></option>
                        @foreach ($role as $item)
                            <option value="{{ $item->id }}" {{ $cp->role_id == $item->id ? "SELECTED" : "" }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="grade" class="col-form-label">Grade Name</label>
                    <input type="text" name="grade" id="grade" class="form-control" required value="{{ $cp->grade }}">
                </div>
                <div class="form-group">
                    <label for="min_edu" class="col-form-label">Min. Education</label>
                    <select class="form-control select2" name="min_edu" id="min_edu" required data-placeholder="Select Education">
                        <option value=""></option>
                        <option value="S3" {{ $cp->min_edu == "S3" ? "SELECTED" : "" }}>S3</option>
                        <option value="S2" {{ $cp->min_edu == "S2" ? "SELECTED" : "" }}>S2</option>
                        <option value="S1" {{ $cp->min_edu == "S1" ? "SELECTED" : "" }}>S1</option>
                        <option value="D3" {{ $cp->min_edu == "D3" ? "SELECTED" : "" }}>D4</option>
                        <option value="D3" {{ $cp->min_edu == "D3" ? "SELECTED" : "" }}>D3</option>
                        <option value="D2" {{ $cp->min_edu == "D2" ? "SELECTED" : "" }}>D2</option>
                        <option value="D1" {{ $cp->min_edu == "D1" ? "SELECTED" : "" }}>D1</option>
                        <option value="SMA" {{ $cp->min_edu == "SMA" ? "SELECTED" : "" }}>SMA</option>
                        <option value="SMK" {{ $cp->min_edu == "SMK" ? "SELECTED" : "" }}>SMK</option>
                        <option value="MA" {{ $cp->min_edu == "MA" ? "SELECTED" : "" }}>MA</option>
                        <option value="SMP" {{ $cp->min_edu == "SMP" ? "SELECTED" : "" }}>SMP</option>
                        <option value="SD" {{ $cp->min_edu == "SD" ? "SELECTED" : "" }}>SD</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="yos" class="col-form-label">Year of service</label>
                    <input type="number" name="yos" id="yos" class="form-control" required value="{{ $cp->yos }}">
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 col-sm-12 divpar">
                <h3>Wages</h3>
                <div class="form-group">
                    <label for="inputEmail3" class="control-label">Basic Salary</label>
                    <input type="text" class="form-control number" onkeyup="new_calc_thp('#modalEdit')" name="salary" id="salary" placeholder="" required value="{{ $cp->salary }}">
                    <div class="col-sm-12">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="control-label">House Allowance</label>
                    <input type="text" class="form-control number" onkeyup="new_calc_thp('#modalEdit')" name="house" id="house" placeholder="" required value="{{ $cp->house }}">
                    <div class="col-sm-12">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="control-label">Health Allowance</label>
                    <input type="text" class="form-control number" onkeyup="new_calc_thp('#modalEdit')" name="health" id="health" placeholder="" required value="{{ $cp->health }}">
                    <div class="col-sm-12">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="control-label">Position ALlowance</label>
                    <input type="text" class="form-control number" onkeyup="new_calc_thp('#modalEdit')" name="position" id="position" placeholder="" required value="{{ $cp->position }}">
                    <div class="col-sm-12">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 divpar">
                <h3>Non Wages</h3>
                <div class="form-group">
                    <label for="inputEmail3" class="control-label">Transport Allowance</label>
                    <input type="text" class="form-control number" onkeyup="new_calc_thp('#modalEdit')" name="transport" id="transport" placeholder="" required value="{{ $cp->transport }}">
                    <div class="col-sm-12">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="control-label">Meal Allowance</label>
                    <input type="text" class="form-control number" onkeyup="new_calc_thp('#modalEdit')" name="meal" id="meal" placeholder="" required value="{{ $cp->meal }}">
                    <div class="col-sm-12">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="control-label">Performance Bonus</label>
                    <input type="text" class="form-control" onkeyup="new_calc_thp('#modalEdit')" name="performance_bonus" id="performance_bonus" placeholder="" required value="{{ $cp->performance_bonus < 0 ? "-" : $cp->performance_bonus }}">
                    <span class="text-center text-danger">* fill with 0 to set unlimited</span>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="inputEmail3" class="control-label">Max. Take Home Pay</label>
                    <input type="text" class="form-control" name="maxthp" id="maxthp" disabled value="0">
                    <div class="col-sm-12">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @csrf
        <input type="hidden" name="company_id" value="{{ $company->id }}">
        <input type="hidden" name="cp_id" value="{{ $cp->id }}">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
    </div>
</form>
