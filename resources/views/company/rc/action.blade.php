<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Actions</h3>
        </div>
        <div class="card-toolbar">
            <a href="#addAction" class="btn btn-primary font-weight-bolder" data-bs-toggle="modal">
                <span class="svg-icon svg-icon-md">
                    <i class="fa fa-plus"></i>
                </span>New Record
            </a>
        </div>
    </div>
</div>
<div class="modal fade" id="addAction" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form class="form" action="{{URL::route('action.store')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Action</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="desc" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card-body">

    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;">
        <thead>
        <tr>
            <th>#</th>
            <th nowrap="nowrap" style="width: 40%">Name</th>
            <th nowrap="nowrap" class="text-center">Description</th>
            <th nowrap="nowrap" data-priority=1></th>
        </tr>
        </thead>
        <tbody>
        @foreach($actions as $action)
            <tr>
                <td>{{$numberAction++}}</td>
                <td>{{$action->name}}</td>
                <td>{!!$action->desc!!}</td>
                <td>
                    {{-- @actionStart('position', 'edit') --}}
                    <a href="#editAction{{$action->id}}" data-bs-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                    {{-- @actionEnd --}}

                    {{-- @actionStart('position', 'delete') --}}
                    <a href="#deleteAction{{$action->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-bs-toggle="modal">
                        <i class="la la-trash-o"></i>
                    </a>
                    {{-- @actionEnd --}}
                </td>
            </tr>

            {{-- BEGIN MODAL EDIT --}}
            <div class="modal fade" id="editAction{{$action->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['action.update', $action->id], 'method' => 'POST'))!!}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Action</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{$action->name}}">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="desc" class="form-control">{!!$action->desc!!}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            {{-- END MODAL EDIT --}}

            {{-- BEGIN MODAL DELETE --}}
            <div class="modal fade" id="deleteAction{{$action->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['action.delete', $action->id], 'method' => 'delete'))!!}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure want to delete this data?
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <button type="submit" class="btn btn-primary">Yes</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            {{-- END MODAL DELETE --}}
        @endforeach
        </tbody>
    </table>
</div>
