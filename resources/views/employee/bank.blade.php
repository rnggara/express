@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Bank Account of - {{ $emp->emp_name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" data-toggle="modal" data-target="#modalAddBank" class="btn btn-primary btn-sm btn-icon">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Bank</th>
                                <th class="text-center">Bank Number</th>
                                <th class="text-center">Default Bank</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($banks as $i => $item)
                                @php
                                    $_bank = $pref_bank->where("id", $item->bank_id)->first();
                                @endphp
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>{{ $_bank->bank_name }}</td>
                                    <td>{{ "[$_bank->bank_code] $item->bank_number" }}</td>
                                    <td align="center">
                                        @if($item->override)
                                            <i class="fa fa-check text-primary"></i>
                                        @else
                                            <i class="fa fa-times text-danger"></i>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <button type="button" class="btn btn-xs btn-icon btn-primary" data-toggle="modal" data-target="#editModal{{ $item->id }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="{{ route('employee.bank.delete', $item->id) }}" onclick="return confirm('Delete?')" class="btn btn-xs btn-icon btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editModal{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title">Edit Bank Account</h1>
                                                <button class="close" data-dismiss="modal" type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('employee.bank.add') }}" method="post">
                                                <div class="modal-body">
                                                    <div class="row form-group">
                                                        <label class="col-form-label col-md-3 col-sm-12">
                                                            Bank
                                                        </label>
                                                        <div class="col-md-9 col-sm-12">
                                                            <select name="bank_id" id="" class="form-control select2" required data-placeholder="Select Bank">
                                                                <option value=""></option>
                                                                @foreach ($pref_bank as $_bank)
                                                                    <option value="{{ $_bank->id }}" {{ ($_bank->id == $item->bank_id) ? "SELECTED" : "" }}>{{ "[$_bank->bank_code] $_bank->bank_name" }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row form-group">
                                                        <label class="col-form-label col-md-3 col-sm-12">
                                                            Bank Account Number
                                                        </label>
                                                        <div class="col-md-9 col-sm-12">
                                                            <input type="text" name="bank_number" class="form-control" required value="{{ $item->bank_number }}">
                                                        </div>
                                                    </div>
                                                    <div class="row form-group">
                                                        <label class="col-form-label col-md-3 col-sm-12">
                                                            Bank Default
                                                        </label>
                                                        <div class="col-md-9 col-sm-12">
                                                            <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                                <input type="checkbox" {{($item->override) ? "checked" : ""}} value="1" name="override"/>
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
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
        </div>
    </div>

    <div class="modal fade" id="modalAddBank" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Add Bank Account</h1>
                    <button class="close" data-dismiss="modal" type="button">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('employee.bank.add') }}" method="post">
                    <div class="modal-body">
                        <div class="row form-group">
                            <label class="col-form-label col-md-3 col-sm-12">
                                Bank
                            </label>
                            <div class="col-md-9 col-sm-12">
                                <select name="bank_id" id="" class="form-control select2" required data-placeholder="Select Bank">
                                    <option value=""></option>
                                    @foreach ($pref_bank as $item)
                                        <option value="{{ $item->id }}">{{ "[$item->bank_code] $item->bank_name" }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-form-label col-md-3 col-sm-12">
                                Bank Account Number
                            </label>
                            <div class="col-md-9 col-sm-12">
                                <input type="text" name="bank_number" class="form-control" required>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-form-label col-md-3 col-sm-12">
                                Bank Default
                            </label>
                            <div class="col-md-9 col-sm-12">
                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                    <input type="checkbox" value="1" name="override"/>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="emp_id" value="{{ $emp->id }}">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("select.select2").select2({
                width : "100%"
            })

            $("table.display").DataTable()
        })
    </script>
@endsection
