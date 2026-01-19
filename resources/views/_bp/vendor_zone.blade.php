<form action="{{ route('be.vendors_post_zone') }}" id="form-post" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="vendor" value="{{$vendor->id}}">
    <div class="modal-body">
        <div class="d-flex flex-column gap-3">
            <span class="fw-bold fs-3 mb-5">Vendor Zone</span>
            <hr class="w-100">
            <div class="d-flex flex-column gap-2">
                <div class="fv-row" id="zone-parent">
                    <label class="col-form-label">Assign Zone</label>
                    <select name="zone_id" class="form-select" data-control="select2" data-placeholder="Select Zone" data-dropdown-parent="#zone-parent">
                        <option value=""></option>
                        @foreach($allZone as $item)
                            <option value="{{$item->id}}">{{$item->zone}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" name="submit" value="assign">Assign</button>
                </div>
            </div>
            <hr class="w-100">
            <div class="d-flex flex-column gap-2">
                <div class="fv-row">
                    <label class="col-form-label">New Zone</label>
                    <input type="text" name="zone_code" class="form-control" placeholder="Input Zone">
                </div>
                <div class="fv-row">
                    <label class="col-form-label">Zone Name</label>
                    <input type="text" name="zone_name" class="form-control" placeholder="Input Zone Name">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" name="submit" value="new">Tambah</button>
                </div>
            </div>
            <hr class="w-100">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Zone Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataZone as $item)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->zone}}</td>
                            <td>
                                <button onclick="removeZone({{$item->id}}, {{$vendor->id}})" type="button" class="btn btn-icon btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn text-primary" data-bs-dismiss="modal">Tutup</button>
    </div>
</form>