<div class="d-flex flex-column w-300px" data-toggle="imageInput">
    <div class="w-300px img-wrapper h-200px rounded bgi-position-center bgi-no-repeat bgi-size-contain" style="background-image: url('{{ asset("images/image_placeholder.png") }}')"></div>
    <span class="my-3 text-muted text-center">Maximum image size is 5 MB</span>
        <label class="btn btn-primary">
            Upload Photo
            <input type="file" name="image" class="d-none">
        </label>
</div>
<div class="row">
    <div class="fv-row col-6">
        <label class="col-form-label required">Employee ID</label>
        <input type="text" name="emp_id" class="form-control" placeholder="Input Employee ID">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label required">Employee Name</label>
        <input type="text" name="comp" class="form-control" placeholder="Input Employee Name">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label required">Office Email</label>
        <input type="text" name="email" class="form-control" placeholder="Input Email">
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label required">Position</label>
        <select name="position" class="form-select" data-control="select2" data-placeholder="Select Position">
            <option value=""></option>
            @foreach ($position as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label required">Acting Position</label>
        <select name="action_position" class="form-select" data-control="select2" data-placeholder="Select Acting Position">
            <option value=""></option>
            @foreach ($position as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label required">Job level</label>
        <select name="job_level" class="form-select" data-control="select2" data-placeholder="Select Job Level">
            <option value=""></option>
            @foreach ($master['job_level'] as $id => $item)
                <option value="{{ $id }}">{{ $item }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label required">Departement</label>
        <select name="departement" class="form-select" data-control="select2" data-placeholder="Select Departement">
            <option value=""></option>
            @foreach ($departements as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label required">Location</label>
        <select name="location" class="form-select" data-control="select2" data-placeholder="Select Location">
            <option value=""></option>
            @foreach ($locations as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="fv-row col-6">
        <label class="col-form-label required">Join Date</label>
        <input type="text" name="join_date" class="form-control tempusDominus" id="join_date" placeholder="Input Join Date">
    </div>

</div>
