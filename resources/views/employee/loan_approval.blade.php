@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="card card-custom gutter-b col-md-12">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold text-dark">Employee Loan - <span class="text-primary">{{$loan->loan_id}}</span></h3>
                <div class="card-toolbar">
                    <a href="{{ route('employee.loan') }}" class="btn btn-sm btn-success">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
            <div class="card-body pt-2">
                <!--begin::Form-->
                <div class="row">
                    <div class="col-md-5 col-sm-12 bg-secondary pt-5 rounded">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0" name="name" value="Name" disabled/>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0" name="name" value="{{$emp->emp_name}}" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0" name="name" value="Loan Amount" disabled/>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0" name="name" value="IDR {{number_format($loan->loan_amount,2)}}" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0" name="name" value="Loan Start" disabled/>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0" name="name" value="{{date('d F Y', strtotime($loan->loan_start))}}" disabled/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0" name="name" value="Loan End" disabled/>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input type="text" class="form-control border-0" name="name" value="{{date('d F Y', strtotime($loan->loan_end))}}" disabled/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="card card-custom gutter-b col-md-12">
            <!--begin::Header-->
            <div class="card-header border-0">
                <h5 class="card-title text-dark">Payment Detail</h5>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-2">
                <!--begin::Form-->
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <table class="table table-bordered table-hover display font-size-sm" style="margin-top: 13px !important; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th nowrap="nowrap" class="text-left">Payment#</th>
                                <th nowrap="nowrap" class="text-center">Payment Date</th>
                                <th nowrap="nowrap" class="text-right">Amount</th>
                                <th nowrap="nowrap" class="text-right">Balance</th>
                                <th nowrap="nowrap" class="text-center">Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $balance_num = intval($loan->loan_amount);
                        @endphp
                        @foreach($payments as $key => $value)
                            <tr>
                                <th>{{($key+1)}}</th>
                                <th>{{$value->payment_id}}</th>
                                <th class="text-center">{{date('d F Y', strtotime($value->date_of_payment))}}</th>
                                <th class="text-right">IDR {{number_format($value->amount,2)}}</th>
                                @php
                                    $balance_num -= intval($value->amount);
                                @endphp
                                <th class="text-right">IDR {{number_format($balance_num,2)}}</th>
                                <th class="text-center">{{$value->remark}}</th>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-center" colspan="5">
                                    Approval
                                </th>
                                <th class="text-center">
                                    @actionStart('emp_loan', 'approvedir')
                                        @if (empty($loan->approved_at))
                                        <form action="{{ route("employee.loan.approve_loan") }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $loan->id }}">
                                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure?')">
                                                Approve
                                            </button>
                                        </form>
                                        @endif
                                    @actionEnd
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!--end::Form-->
            </div>
            <!--end::Body-->
    </div>



@endsection
@section('custom_script')
            <script>
                $(document).ready(function () {
                    $('.display').DataTable({
                        responsive: true,
                        fixedHeader: {
                            headerOffset: 90
                        },
                        paging : false,
                        searching: false
                    })
                });
            </script>
@endsection
