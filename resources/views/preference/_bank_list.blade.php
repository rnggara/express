@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Bank List</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route("preference", base64_encode($company->id)) }}" class="btn btn-icon btn-sm btn-success">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="modal fade" id="modalAddBank" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">Add Bank</h1>
                            <button class="close" data-dismiss="modal" type="button">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                        <form action="{{ route('pref.bank.add') }}" method="post">
                            <div class="modal-body">
                                <div class="row form-group">
                                    <label class="col-form-label col-md-3 col-sm-12">
                                        Bank Name
                                    </label>
                                    <div class="col-md-9 col-sm-12">
                                        <input type="text" name="bank_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-form-label col-md-3 col-sm-12">
                                        Bank Code
                                    </label>
                                    <div class="col-md-9 col-sm-12">
                                        <input type="text" name="bank_code" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                @csrf
                                <input type="hidden" name="id_comp" value="{{ $company->id }}">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered display">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Bank Name</th>
                                <th class="text-center">Bank Code</th>
                                {{-- <th class="text-center">Action</th> --}}
                                <th class="text-center">
                                    <button type="button" data-toggle="modal" data-target="#modalAddBank" class="btn btn-primary btn-icon btn-xs">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bank as $i => $item)
                                {{-- @php
                                    $type = json_decode($item->email_type, true);
                                @endphp --}}
                                <tr>
                                    <td align="center">{{ $i+1 }}</td>
                                    <td>{{ $item->bank_name }}</td>
                                    <td>{{ $item->bank_code }}</td>
                                    {{-- <td align="center">
                                        @if ($item->priority > 1)
                                            <a href="{{ route("pref.bank.priority", ['type' => "up", "id" => $item->id]) }}" onclick="return confirm('Change Priority {{ $item->bank_name }}?')" class="btn btn-icon btn-xs btn-success">
                                                <i class="fa fa-chevron-up"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route("pref.bank.priority", ['type' => "down", "id" => $item->id]) }}" onclick="return confirm('Change Priority {{ $item->bank_name }}?')" class="btn btn-icon btn-xs btn-danger">
                                            <i class="fa fa-chevron-down"></i>
                                        </a>
                                    </td> --}}
                                    <td align="center">
                                        <button type="button" data-toggle="modal" data-target="#modalEditBank{{ $item->id }}" class="btn btn-icon btn-xs btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <a href="{{ route("pref.bank.delete", $item->id) }}" onclick="return confirm('Delete {{ $item->bank_name }}?')" class="btn btn-icon btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalEditBank{{ $item->id }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title">Edit Bank</h1>
                                                <button class="close" data-dismiss="modal" type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('pref.bank.add') }}" method="post">
                                                <div class="modal-body">
                                                    <div class="row form-group">
                                                        <label class="col-form-label col-md-3 col-sm-12">
                                                            Bank Name
                                                        </label>
                                                        <div class="col-md-9 col-sm-12">
                                                            <input type="text" name="bank_name" class="form-control" value="{{ $item->bank_name }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="row form-group">
                                                        <label class="col-form-label col-md-3 col-sm-12">
                                                            Bank Code
                                                        </label>
                                                        <div class="col-md-9 col-sm-12">
                                                            <input type="text" name="bank_code" class="form-control" value="{{ $item->bank_code }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    @csrf
                                                    <input type="hidden" name="id_bank" value="{{ $item->id }}">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Edit</button>
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
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable()
        })
    </script>
@endsection
