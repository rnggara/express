<div class="card-header py-3">
    <div class="row">
        <div class="card-title align-items-start flex-column col-md-10">
            <h3 class="card-label font-weight-bolder text-dark">Position</h3>
        </div>
        <div class="card-toolbar">
            <a href="#addEmpType" class="btn btn-primary font-weight-bolder" data-bs-toggle="modal">
                <span class="svg-icon svg-icon-md">
                    <i class="fa fa-plus"></i>
                </span>New Record
            </a>
        </div>
    </div>
</div>
<div class="modal fade" id="addEmpType" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <form class="form" action="{{URL::route('rc.emp.store')}}" method="POST">
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
                    {{--  @for ($i = 1; $i <= 3; $i++)
                        <div class="form-group">
                            <label>Signature {{ $i }}</label>
                            <select name="sign_{{ $i }}" class="form-control select2" id="">
                                <option value="">Select Signature</option>
                                @foreach($roleDivsList as $key => $value)
                                    <option value="{{ $value->id }}">{{ $value->roleName." ".$value->divName }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endfor  --}}
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
                    <div class="form-group">
                        <div class="col-9 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="with_voucher"/>
                                    <span></span>
                                    With Voucher
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-9 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="with_bonus"/>
                                    <span></span>
                                    With Bonus
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-9 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="disable_thr"/>
                                    <span></span>
                                    Disable THR
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-9 col-form-label">
                            <div class="checkbox-inline">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" name="is_print"/>
                                    <span></span>
                                    Automatic Print
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                        <select name="tc_id" id="" class="form-control select2" aria-placeholder="Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}">
                            <option value="">Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</option>
                            @foreach ($coa as $item)
                                <option value="{{ $item->id }}">[{{ $item->code }}] {{ $item->name }}</option>
                            @endforeach
                        </select>
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
            <th nowrap="nowrap" class="text-center">No Probation</th>
            <th nowrap="nowrap" class="text-center">With Voucher</th>
            <th nowrap="nowrap" class="text-center">With Bonus</th>
            <th nowrap="nowrap" class="text-center">Disable THR</th>
            <th nowrap="nowrap" class="text-center">Automatic Print</th>
            <th nowrap="nowrap" class="text-center">{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</th>
            <th nowrap="nowrap" data-priority=1></th>
        </tr>
        </thead>
        <tbody>

        @foreach($emp_type as $i => $role)
            @php
                $probation = json_decode($role->no_probation, true);
                $voucher = json_decode($role->with_voucher, true);
                $bonus = json_decode($role->with_bonus, true);
                $thr = json_decode($role->disable_thr, true);
                $tc = json_decode($role->tc_id, true);
                $print = json_decode($role->is_print, true);
                $no_probation = (isset($probation[$company->id])) ? $probation[$company->id] : ((isset($probation[$company->id_parent])) ? $probation[$company->id_parent] : 0);
                $with_voucher = (isset($voucher[$company->id])) ? $voucher[$company->id] : ((isset($voucher[$company->id_parent])) ? $voucher[$company->id_parent] : 0);
                $with_bonus = (isset($bonus[$company->id])) ? $bonus[$company->id] : ((isset($bonus[$company->id_parent])) ? $bonus[$company->id_parent] : 0);
                $disable_thr = (isset($thr[$company->id])) ? $thr[$company->id] : ((isset($thr[$company->id_parent])) ? $thr[$company->id_parent] : 0);
                $tc_id = (isset($tc[$company->id])) ? $tc[$company->id] : ((isset($tc[$company->id_parent])) ? $tc[$company->id_parent] : 0);
                $is_print = (isset($print[$company->id])) ? $print[$company->id] : ((isset($print[$company->id_parent])) ? $print[$company->id_parent] : 0);
            @endphp
            <tr>
                <td>{{$i+1}}</td>
                <td>{{$role->name}}</td>
                <td class="text-center"><label for="" class="label label-inline label-{{ ($no_probation == 1) ? "success" : "danger" }}"><i class="fa fa-{{ ($no_probation == 1) ? "check" : "times" }} text-white"></i></label></td>
                <td class="text-center"><label for="" class="label label-inline label-{{ ($with_voucher == 1) ? "success" : "danger" }}"><i class="fa fa-{{ ($with_voucher == 1) ? "check" : "times" }} text-white"></i></label></td>
                <td class="text-center"><label for="" class="label label-inline label-{{ ($with_bonus == 1) ? "success" : "danger" }}"><i class="fa fa-{{ ($with_bonus == 1) ? "check" : "times" }} text-white"></i></label></td>
                <td class="text-center"><label for="" class="label label-inline label-{{ ($disable_thr == 1) ? "success" : "danger" }}"><i class="fa fa-{{ ($disable_thr == 1) ? "check" : "times" }} text-white"></i></label></td>
                <td class="text-center"><label for="" class="label label-inline label-{{ ($is_print == 1) ? "success" : "danger" }}"><i class="fa fa-{{ ($is_print == 1) ? "check" : "times" }} text-white"></i></label></td>
                <td class="text-center">
                    @if (isset($coa_detail['code'][$tc_id]))
                        [{{ $coa_detail['code'][$tc_id] }}] {{ $coa_detail['name'][$tc_id] }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    {{-- @actionStart('position', 'edit') --}}
                    <a href="#editEmpType{{$role->id}}" data-bs-toggle="modal" class="btn btn-sm btn-primary btn-icon btn-icon-md" title="Edit"><i class="la la-edit"></i></a>
                    {{-- @actionEnd --}}

                    {{-- @actionStart('position', 'delete') --}}
                    <a href="#deleteEmpType{{$role->id}}" class="btn btn-sm btn-google btn-icon btn-icon-md" title="Delete" data-bs-toggle="modal">
                        <i class="la la-trash-o"></i>
                    </a>
                    {{-- @actionEnd --}}
                </td>
            </tr>

            {{-- BEGIN MODAL EDIT --}}
            <div class="modal fade" id="editEmpType{{$role->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['rc.emp.update', $role->id], 'method' => 'POST'))!!}
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Employee Type</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control"  value="{{$role->name}}" {{ ($company->id != $role->company_id) ? "readonly" : "" }}>
                            </div>
                            <div class="form-group">
                                <div class="col-9 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" {{ ($no_probation == 1) ? "checked" : "" }} name="no_probation"/>
                                            <span></span>
                                            No Probation
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-9 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" {{ ($with_voucher == 1) ? "checked" : "" }} name="with_voucher"/>
                                            <span></span>
                                            With Voucher
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-9 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" {{ ($with_bonus == 1) ? "checked" : "" }} name="with_bonus"/>
                                            <span></span>
                                            With Bonus
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-9 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" {{ ($disable_thr == 1) ? "checked" : "" }} name="disable_thr"/>
                                            <span></span>
                                            Disable THR
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-9 col-form-label">
                                    <div class="checkbox-inline">
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                            <input type="checkbox" {{ ($is_print == 1) ? "checked" : "" }} name="is_print"/>
                                            <span></span>
                                            Automatic Print
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</label>
                                <select name="tc_id" id="" class="form-control select2" aria-placeholder="Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}">
                                    <option value="">Select {{ !empty(\Session::get('company_tc_name')) ? \Session::get('company_tc_name') : "Transaction Code" }}</option>
                                    @foreach ($coa as $item)
                                        <option value="{{ $item->id }}" {{ ($item->id == $tc_id) ? "SELECTED" : "" }}>[{{ $item->code }}] {{ $item->name }}</option>
                                    @endforeach
                                </select>
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
            <div class="modal fade" id="deleteEmpType{{$role->id}}" tabindex="-1" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    {!! Form::open(array('route' => ['rc.emp.delete', $role->id], 'method' => 'delete'))!!}
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
