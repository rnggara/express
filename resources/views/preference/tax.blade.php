<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Tax</h3>
        </div>
        <div class="card-toolbar text-right">

        </div>
    </div>

</div>
<div class="row col-md-12 mx-auto m-5">
    <div class="col-md-12 mb-10">
        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalAddTax"><i class="fa fa-plus"></i> Add Tax</button>
        <div class="modal fade" id="modalAddTax" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Tax</h1>
                    </div>
                    <form action="{{ route('pref.tax.submit') }}" method="post">
                        <div class="modal-body">
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-12">Tax Name</label>
                                <div class="col-md-9 col-sm-12">
                                    <input type="text" class="form-control" name="tax_name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-12">Formula</label>
                                <div class="col-md-9 col-sm-12">
                                    <input type="text" class="form-control" placeholder="$sum * 0.1" name="formula" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-12">Is WAPU?</label>
                                <div class="col-md-9 col-sm-12 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" name="wapu" value="1"/>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-12">Is Print</label>
                                <div class="col-md-9 col-sm-12 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" name="print" value="1"/>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-12">Conflict With</label>
                                <div class="col-md-9 col-sm-12">
                                    <select name="conflict[]" class="form-control select2" multiple data-placeholder="Select Tax conflict">
                                        <option value=""></option>
                                        @foreach ($taxes as $item)
                                            <option value="{{ $item->id }}">{{ $item->tax_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            @csrf
                            <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <table class="table table-responsive-xl display">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Tax Name</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                @foreach ($taxes as $i => $item)
                    <tr>
                        <td align="center">{{ $i+1 }}</td>
                        <td>{{ $item->tax_name }}</td>
                        <td align="center">
                            <div class="d-flex justify-content-center">
                                <button type="button" data-toggle="modal" data-target="#modalEditTax{{ $item->id }}" class="btn btn-sm btn-icon btn-primary">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <form action="{{ route('pref.tax.submit', 'delete') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button type="submit" onclick="return confirm('Delete {{ $item->tax_name }}'?)" data-toggle="modal" class="btn btn-sm btn-icon btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="modal fade" id="modalEditTax{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title">Edit Tax</h1>
                                        </div>
                                        <form action="{{ route('pref.tax.submit', 'edit') }}" method="post">
                                            <div class="modal-body">
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-3 col-sm-12">Tax Name</label>
                                                    <div class="col-md-9 col-sm-12">
                                                        <input type="text" value="{{ $item->tax_name }}" class="form-control" name="tax_name" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-3 col-sm-12">Formula</label>
                                                    <div class="col-md-9 col-sm-12">
                                                        <input type="text" value="{{ $item->formula }}" placeholder="$sum * 0.1" class="form-control" name="formula" required>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-3 col-sm-12">Is WAPU?</label>
                                                    <div class="col-md-9 col-sm-12 col-form-label">
                                                        <div class="checkbox-inline">
                                                            <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                <input type="checkbox" {{ ($item->is_wapu == 1) ? "checked" : "" }} name="wapu" value="1"/>
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-3 col-sm-12">Is Print</label>
                                                    <div class="col-md-9 col-sm-12 col-form-label">
                                                        <div class="checkbox-inline">
                                                            <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                <input type="checkbox" {{ ($item->is_print == 1) ? "checked" : "" }} name="print" value="1"/>
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-form-label col-md-3 col-sm-12">Conflict With</label>
                                                    <div class="col-md-9 col-sm-12">
                                                        <select name="conflict[]" class="form-control select2" multiple data-placeholder="Select Tax conflict">
                                                            <option value=""></option>
                                                            @foreach ($taxes->where('id', "!=", $item->id) as $va)
                                                                <option value="{{ $va->id }}" {{ (!empty($item->conflict_with) && in_array($va->id, json_decode($item->conflict_with, true))) ? "selected" : "" }}>{{ $va->tax_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->id }}">
                                                <button type="button" class="btn btn-light-primary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Add</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
