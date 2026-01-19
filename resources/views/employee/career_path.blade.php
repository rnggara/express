@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Promote/Demote</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered display table-responsive-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Request date</th>
                                <th>Requestor name</th>
                                <th>Effective Date</th>
                                <th>For Payroll</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($s as $i => $item)
                                @php
                                    $e = $emp->where("id", $item->emp_id)->first();
                                @endphp
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $e->emp_name }}</td>
                                    <td>{{ date("d F Y", strtotime($item->created_at)) }}</td>
                                    <td>{{ $item->created_by }}</td>
                                    <td>{{ empty($item->effective_date) ? "-" : date("d F Y", strtotime($item->effective_date)) }}</td>
                                    <td>{{ empty($item->effective_date) ? "-" : date("d F Y", strtotime($item->effective_date)) }}</td>
                                    <td align="center">
                                        @if (empty($item->approved_at))
                                            @if(date("Y-m") > date("Y-m", strtotime("$item->created_at + 3 months")))
                                            <button type="button" class="btn btn-danger btn-sm">Expired</button>
                                            @else
                                            @actionStart("employee", "approvedir")
                                                @actionStart("employee", "approvediv2")
                                                    <a href="{{ route('employee.career.detail', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>Approve</a>
                                                @actionEnd
                                            @actionEnd
                                            @endif
                                            <a href="{{ route('employee.career.delete', $item->id) }}" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>Delete</a>
                                        @else
                                            <a href="{{ route('employee.career.detail', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>
                                                Approved at {{ date("d F Y", strtotime($item->approved_at)) }} by {{ $item->approved_by }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1>Add Item</h1>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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
