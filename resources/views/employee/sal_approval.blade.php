@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Salary Upgrade</h3>
            <div class="card-toolbar">
                <div class="btn-group">

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
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Request Date</th>
                                <th class="text-center">Requestor Name</th>
                                <th class="text-center">Effective Date</th>
                                <th class="text-center">For Payroll</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $num = 1;
                            @endphp
                            @foreach ($list as $item)
                                <tr class="{{ (!empty($item->approved_at)) ? "table-success" : "" }}">
                                    <td align="center">{{ $num++ }}</td>
                                    <td align="center">{{ $item->emp_name }}</td>
                                    <td align="center">{{ date("d F Y", strtotime($item->created_at)) }}</td>
                                    <td align="center">{{ $item->created_by }}</td>
                                    <td align="center">{{ (empty($item->implement_date)) ? "-" : date("d F Y", strtotime($item->implement_date)) }}</td>
                                    <td align="center">{{ date("F Y", strtotime($item->implement_date." +1 month")) }}</td>
                                    <td align="center">
                                        @if (empty($item->approved_at))
                                            @if(date("Y-m") > date("Y-m", strtotime("$item->created_at + 3 months")))
                                            <button type="button" class="btn btn-danger btn-sm">Expired</button>
                                            @else
                                            @actionStart("employee", "approvedir")
                                                @actionStart("employee", "approvediv2")
                                                    <a href="{{ route('employee.salary.approval.approve', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>Approve</a>
                                                @actionEnd
                                            @actionEnd
                                            @endif
                                        <a href="{{ route('employee.salary.approval.delete', $item->id) }}" onclick="return confirm('Delete?')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>Delete</a>
                                        @else
                                            <a href="{{ route('employee.salary.approval.approve', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i>
                                                Approved at {{ date("d F Y", strtotime($item->approved_at)) }} by {{ $item->approved_by }}
                                            </a>
                                            @actionStart('employee', 'approvedir')
                                            <br>
                                            <br>
                                            @actionStart("employee", "approvediv2")
                                            <a href="{{ route("employee.salary.approval.email", $item->id) }}" class="btn btn-sm btn-info">Send Email</a>
                                            @actionEnd
                                            @actionEnd
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
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable()
        })
    </script>
@endsection
