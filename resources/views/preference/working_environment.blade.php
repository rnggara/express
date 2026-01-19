<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Working environment</h3>
        </div>
        <div class="card-toolbar text-right">
            <button type="button" data-toggle="modal" data-target="#addModalenvironment" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> New Working environment</button>
        </div>
    </div>

</div>
<div class="row col-md-12 mx-auto m-5">
    <div class="col-md-12">
        <table class="table table-responsive-xl display">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Name</th>
                <th class="text-center">Tag</th>
                <th class="text-center">Formula</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                @foreach($we as $key => $value)
                    <tr>
                        <td align="center">{{$key + 1}}</td>
                        <td align="center">{{$value->name}}</td>
                        <td align="center">{{strtoupper($value->tag)}}</td>
                        <td align="center">{{$value->formula}}</td>
                        <td align="center">
                            <button type="button" onclick="edit_item('{{route('pref.find_we', $value->id)}}', '#editModalContent', '#editModalenvironment')" class="btn btn-xs btn-primary btn-icon"><i class="fa fa-edit"></i></button>
                            <button type="button" onclick="delete_item('{{route('pref.delete_we', $value->id)}}')" class="btn btn-xs btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Modal-->
<div class="modal fade" id="addModalenvironment" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Working environment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form action="{{route('pref.store_we')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="" class="col-form-label col-md-3">Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-form-label col-md-3">Tag</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="tag" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-form-label col-md-3">Formula</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="formula">
                            <span class="font-size-xs text-secondary">eg = $rate + 100000. [+, -, *, /]</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editModalenvironment" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" id="editModalContent">

    </div>
</div>


<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Unit of Measurements</h3>
        </div>
        <div class="card-toolbar text-right">
            <button type="button" data-toggle="modal" data-target="#addUom" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> New UoM</button>
        </div>
    </div>

</div>
<div class="row col-md-12 mx-auto m-5">
    <div class="col-md-12">
        <table class="table table-responsive-xl display">
            <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Name</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                @foreach($uoms as $key => $value)
                    <tr>
                        <td align="center">{{$key + 1}}</td>
                        <td align="center">{{$value->name}}</td>
                        <td align="center">
                            <button type="button" data-toggle="modal" data-target="#editUom{{ $value->id }}" class="btn btn-xs btn-primary btn-icon"><i class="fa fa-edit"></i></button>
                            <button type="button" onclick="delete_item('{{route('pref.uom.delete', $value->id)}}')" class="btn btn-xs btn-danger btn-icon"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <div class="modal fade" id="editUom{{ $value->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add UoM</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                </div>
                                <form action="{{route('pref.uom.store', $value->id)}}" method="post">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-group row">
                                            <label for="" class="col-form-label col-md-3">Name</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="name" value="{{ $value->name }}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="id_company" value="{{ $company->id }}">
                                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary font-weight-bold">Edit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addUom" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add UoM</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <form action="{{route('pref.uom.store')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="" class="col-form-label col-md-3">Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_company" value="{{ $company->id }}">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
