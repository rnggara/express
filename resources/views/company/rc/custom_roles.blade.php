<div class="card-header py-3">
    <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Custom Roles</h3>
    </div>
    <div class="card-toolbar">
        <a href="#addPosition" class="btn btn-primary font-weight-bolder" data-bs-toggle="modal">
            <span class="svg-icon svg-icon-md">
                <i class="fa fa-plus"></i>
            </span>New Record
        </a>
    </div>
</div>
<div class="modal fade" id="addPosition" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form class="form" action="{{URL::route('pref.cr.add')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Position</h5>
                    <button type="button" class="btn btn-light btn-sm btn-icon" data-bs-dismiss="modal" aria-label="Close">
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Role Name</label>
                        <input type="text" class="form-control" name="name" required placeholder="Role Name">
                    </div>
                    <div class="form-group">
                        <label>Descriptions</label>
                        <textarea name="descriptions" class="form-control" placeholder="Descriptions" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="coid" value="{{base64_encode($company->id)}}">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="saveAdd">Save</button>
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
            <th nowrap="nowrap" class="text-center">Role Name</th>
            <th nowrap="nowrap" style="width: 40%">Descriptions</th>
            <th nowrap="nowrap" data-priority=1></th>
        </tr>
        </thead>
        <tbody>
        @foreach($custom_roles as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->name}}</td>
                <td>{{$item->descriptions}}</td>
                <td>
                    {{-- @actionStart('rprivilege', 'access') --}}
                    <a href="{{ URL::route('pref.cr.priv',$item->id) }}" class="btn btn-sm btn-warning btn-icon btn-icon-md" title="Privilege"><i class="la la-key"></i></a>
                    {{-- @actionEnd --}}

                    {{-- @actionStart('position', 'edit') --}}
                    <a href="#editPosition{{$item->id}}" data-bs-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                    {{-- @actionEnd --}}

                    {{-- @actionStart('position', 'delete') --}}
                    <a href="#deletePosition{{$item->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-bs-toggle="modal">
                        <i class="la la-trash-o"></i>
                    </a>
                    {{-- @actionEnd --}}
                </td>
            </tr>
            {{-- BEGIN MODAL EDIT --}}
            <div class="modal fade" id="editPosition{{$item->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['pref.cr.add'], 'method' => 'POST'))!!}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Position</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Role Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ $item->name }}" required placeholder="Role Name">
                                </div>
                                <div class="form-group">
                                    <label>Descriptions</label>
                                    <textarea name="descriptions" class="form-control" placeholder="Descriptions" cols="30" rows="10">{!! $item->descriptions !!}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" value="{{$item->id}}">
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
            <div class="modal fade" id="deletePosition{{$item->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['pref.cr.delete'], 'method' => 'post'))!!}
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
                            <input type="hidden" name="id" value="{{$item->id}}">
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
