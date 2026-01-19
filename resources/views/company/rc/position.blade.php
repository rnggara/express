<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Portal Roles</h3>
        </div>
        <div class="card-toolbar">
            @if(\Auth::id() == 1)
            <a href="{{ route("rprivilege.excel") }}" target="_blank" class="btn btn-success font-weight-bolder">
                <span class="svg-icon svg-icon-md">
                    <i class="fa fa-download"></i>
                </span>Download
            </a>
            @endif
            <a href="#addPosition" class="btn btn-primary font-weight-bolder" data-bs-toggle="modal">
                <span class="svg-icon svg-icon-md">
                    <i class="fa fa-plus"></i>
                </span>New Record
            </a>
        </div>
    </div>
</div>
<div class="modal fade" id="addPosition" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form class="form" action="{{URL::route('rolediv.store')}}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Position</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        X
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Parent Position</label>
                        <select name="id_rms_roles_divisions_parent" class="form-control">
                            <option value=""></option>
                            @foreach($parentLists as $keyParentLists => $parentList)
                                <option value="{{$parentList->id}}">{{$parentList->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        {{Form::select('id_rms_roles', $roleList, null, array('class' => 'form-control'))}}
                    </div>

                    <div class="form-group">
                        <label>Division</label>
                        {{ Form::select('id_rms_divisions', $divList, null, array('class' => 'form-control')) }}
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
    <div class="mb-5">
        <a href="{{ route('company.st', $company->id) }}" class="btn btn-primary font-weight-bolder">
            <span class="svg-icon svg-icon-md">
                <i class="fa fa-plus"></i>
            </span>Structure Oganization
        </a>
    </div>
    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 50%;" data-page-length="100">
        <thead>
        <tr>
            <th>#</th>
            <th nowrap="nowrap" class="text-center">Position</th>
            <th nowrap="nowrap" style="width: 40%">Parent</th>
            <th nowrap="nowrap" data-priority=1></th>
        </tr>
        </thead>
        <tbody>
        @foreach($roleDivsList as $key => $roleDivList)
            @if($level_role[$roleDivList->id] == 1)
                <tr>
                    <td>{{$numberPosition++}}</td>
                    <td>{{$roleDivList->roleName}} {{$roleDivList->divName}}</td>
                    <td>{{($parentPosition[$roleDivList->id])? $parentPosition[$roleDivList->id]['name']:''}}</td>
                    <td>
                        {{-- @actionStart('rprivilege', 'access') --}}
                        <a href="{{ URL::route('rprivilege.edit',$roleDivList->id) }}" class="btn btn-sm btn-warning btn-icon btn-icon-md" title="Privilege"><i class="la la-key"></i></a>
                        {{-- @actionEnd --}}

                        {{-- @actionStart('position', 'edit') --}}
                        <a href="#editPosition{{$roleDivList->id}}" data-bs-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                        {{-- @actionEnd --}}

                        {{-- @actionStart('position', 'delete') --}}
                        <a href="#deletePosition{{$roleDivList->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-bs-toggle="modal">
                            <i class="la la-trash-o"></i>
                        </a>
                        {{-- @actionEnd --}}
                    </td>
                </tr>
            @endif
            @foreach($roleDivsList as $h => $n)
                @if($level_role[$n->id] == 2 && $n->id_rms_roles_divisions_parent == $roleDivList->id)
                    <tr>
                        <td>{{$numberPosition++}}</td>
                        <td><i class="fa fa-reply fa-rotate-180"></i> {{$n->roleName}} {{$n->divName}}</td>
                        <td>{{($parentPosition[$n->id])? $parentPosition[$n->id]['name']:''}}</td>
                        <td>
                            {{-- @actionStart('rprivilege', 'access') --}}
                            <a href="{{ URL::route('rprivilege.edit',$n->id) }}" class="btn btn-sm btn-warning btn-icon btn-icon-md" title="Privilege"><i class="la la-key"></i></a>
                            {{-- @actionEnd --}}

                            {{-- @actionStart('position', 'edit') --}}
                            <a href="#editPosition{{$n->id}}" data-bs-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                            {{-- @actionEnd --}}

                            {{-- @actionStart('position', 'delete') --}}
                            <a href="#deletePosition{{$n->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-bs-toggle="modal">
                                <i class="la la-trash-o"></i>
                            </a>
                            {{-- @actionEnd --}}
                        </td>
                    </tr>
                @endif
                @foreach($roleDivsList as $k => $m)
                    @if($level_role[$m->id] == 3 && $m->id_rms_roles_divisions_parent == $n->id && $n->id_rms_roles_divisions_parent == $roleDivList->id)
                        <tr>
                            <td>{{$numberPosition++}}</td>
                            <td><i class="fa fa-reply fa-rotate-180 ml-3"></i> {{$m->roleName}} {{$m->divName}}</td>
                            <td>{{($parentPosition[$m->id])? $parentPosition[$m->id]['name']:''}}</td>
                            <td>
                                {{-- @actionStart('rprivilege', 'access') --}}
                                <a href="{{ URL::route('rprivilege.edit',$m->id) }}" class="btn btn-sm btn-warning btn-icon btn-icon-md" title="Privilege"><i class="la la-key"></i></a>
                                {{-- @actionEnd --}}

                                {{-- @actionStart('position', 'edit') --}}
                                <a href="#editPosition{{$m->id}}" data-bs-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                                {{-- @actionEnd --}}

                                {{-- @actionStart('position', 'delete') --}}
                                <a href="#deletePosition{{$m->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-bs-toggle="modal">
                                    <i class="la la-trash-o"></i>
                                </a>
                                {{-- @actionEnd --}}
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
            {{-- BEGIN MODAL EDIT --}}
            <div class="modal fade" id="editPosition{{$roleDivList->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['rolediv.update', $roleDivList->id], 'method' => 'POST'))!!}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Position</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Parent Position</label>
                                <select name="id_rms_roles_divisions_parent" class="form-control">
                                    <option value=""></option>
                                    @foreach($parentLists as $keyParentLists => $parentList)
                                        @if($parentList->id == $roleDivList->id_rms_roles_divisions_parent)
                                            <option value="{{$parentList->id}}" selected="selected">{{$parentList->name}}</option>
                                        @else
                                            <option value="{{$parentList->id}}">{{$parentList->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Role</label>
                                {{Form::select('id_rms_roles', $roleList, $roleDivList->roleId, array('class' => 'form-control'))}}
                            </div>

                            <div class="form-group">
                                <label>Division</label>
                                {{ Form::select('id_rms_divisions', $divList, $roleDivList->divId, array('class' => 'form-control')) }}
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
            <div class="modal fade" id="deletePosition{{$roleDivList->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['rolediv.delete', $roleDivList->id], 'method' => 'delete'))!!}
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
