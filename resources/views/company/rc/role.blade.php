<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Portal Level</h3>
        </div>
        <div class="card-toolbar">
            <a href="#addRole" class="btn btn-primary font-weight-bolder" data-bs-toggle="modal">
                <span class="svg-icon svg-icon-md">
                    <i class="fa fa-plus"></i>
                </span>New Record
            </a>
        </div>
    </div>
</div>
<div class="modal fade" id="addRole" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form class="form" action="{{URL::route('role.store')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Role</h5>
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
                    <div class="form-group">
                        <div class="col-9 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="no_probation"/>
                                    <span></span>
                                    No Probation
                                </label>
                            </div>
                        </div>
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

    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;" data-page-length="100">
        <thead>
        <tr>
            <th>#</th>
            <th nowrap="nowrap" style="width: 40%">Name</th>
            <th nowrap="nowrap" class="text-center">Description</th>
            <th nowrap="nowrap" class="text-center">Career Path</th>
            <th nowrap="nowrap" data-priority=1></th>
        </tr>
        </thead>
        <tbody>
        @foreach($roles as $role)
            <tr>
                <td>{{$numberRole++}}</td>
                <td>{{$role->name}}</td>
                <td>{!!$role->desc!!}</td>
                <td align="center">
                    <button type="button" class="btn btn-sm btn-{{ $role->show_career == 1 ? "success" : "danger" }} btn-icon btn-icon-md" data-bs-toggle="modal" data-target="#editCareer{{ $role->id }}">
                        <i class="fa fa-{{ $role->show_career == 1 ? "check" : "times" }}"></i>
                    </button>
                </td>
                <td>
                    {{-- @actionStart('position', 'edit') --}}
                    <a href="#editRole{{$role->id}}" data-bs-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                    {{-- @actionEnd --}}

                    {{-- @actionStart('position', 'delete') --}}
                    <a href="#deleteRole{{$role->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-bs-toggle="modal">
                        <i class="la la-trash-o"></i>
                    </a>
                    {{-- @actionEnd --}}
                </td>
            </tr>

            <div class="modal fade" id="editCareer{{$role->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['role.update', $role->id], 'method' => 'POST'))!!}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Career Path</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="col-9 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" {{ ($role->show_career == 1) ? "checked" : "" }} value="1" name="career"/>
                                            <span></span>
                                            Show at Career Path?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" value="career_path" name="submit">Save</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            {{-- BEGIN MODAL EDIT --}}
            <div class="modal fade" id="editRole{{$role->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['role.update', $role->id], 'method' => 'POST'))!!}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{$role->name}}">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="desc" class="form-control">{!!$role->desc!!}</textarea>
                            </div>
                            <div class="form-group">
                                <div class="col-9 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" {{ ($role->no_probation == 1) ? "checked" : "" }} name="no_probation"/>
                                            <span></span>
                                            No Probation
                                        </label>
                                    </div>
                                </div>
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
            <div class="modal fade" id="deleteRole{{$role->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['role.delete', $role->id], 'method' => 'delete'))!!}
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
