@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">You are firing {{ $employee->emp_name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('employee.expel', $employee->id) }}" method="post">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered table-light-primary">
                            <thead>
                                <tr>
                                    <th>Documents</th>
                                    <th>Details</th>
                                    <th style="width: 20%">Check</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Employee Loan</td>
                                    <td>
                                        @if (count($loans) > 0)
                                            <div class="d-flex justify-content-between">
                                                <span>Amount left to pay : </span>
                                                <div class="row text-right">
                                                    @php
                                                        $total_loan = 0;
                                                    @endphp
                                                    @foreach ($loans as $item)
                                                        @if ($item->amount_left > 0)
                                                            <div class="col-12">
                                                                Rp. {{ number_format($item->amount_left, 2) }}
                                                            </div>
                                                            @php
                                                                $total_loan += $item->amount_left;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    <div class="col-12">
                                                        Total : Rp. {{ number_format($total_loan, 2) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            No outstanding loan
                                        @endif
                                    </td>
                                    <td>
                                        @if (count($loans) > 0)
                                        <div class="row">
                                            @foreach ($loans as $item)
                                                @if ($item->amount_left > 0)
                                                    <div class="col-12">
                                                        <a href="{{ route('employee.loan.detail', $item->id) }}">Link</a>
                                                    </div>
                                                @endif
                                            @endforeach
                                            <input type="hidden" id="loan" name="loan" value="{{ ($item->amount_left <= 0) ? 1 : 0 }}">
                                        </div>
                                        @else
                                            <i class="fa fa-check text-primary font-weight-boldest"></i>
                                            <input type="hidden" id="loan" name="loan" value="1">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Assets</td>
                                    <td>
                                        @if (count($assets) > 0)
                                            <div class="row">
                                                <div class="col-12">
                                                    <ul>
                                                        @foreach ($assets as $item)
                                                            @if (!empty($item->item_name) && $item->qty > 0)
                                                                <li>[{{ $item->item_code }}] {{ $item->item_name }} {{ $item->qty }}{{ $item->uom }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="col-12">
                                                    <span class="text-danger">
                                                        (You need to create and approve Delivery Order for the transfer of these assets)
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                        No assets held by {{ $employee->emp_name }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (count($assets) > 0)
                                            <a href="{{ route("items.warehouses", $wh->id) }}">Link</a>
                                            <input type="hidden" id="asset" name="asset" value="0">
                                        @else
                                            <i class="fa fa-check text-primary font-weight-boldest"></i>
                                            <input type="hidden" id="asset" name="asset" value="1">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email Account</td>
                                    <td>
                                        Is the Email account of {{ $employee->emp_name }} is locked?
                                    </td>
                                    <td>
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-lg checkbox-primary">
                                                <input type="checkbox" name="email" onchange="_check()" id="email" value="1"/>
                                                <span></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cypher Account</td>
                                    <td>
                                        @if (!empty($account))
                                            {{ $account->name }}
                                        @else
                                            Please pick a Cypher Account of this user
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($account))
                                        This account will be de-activated
                                        <input type="hidden" name="account" value="{{ $account->id }}">
                                        @else
                                            <select name="account" class="form-control select2" data-placeholder="No Cypher account for this employee">
                                                <option value=""></option>
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Payroll</td>
                                    <td>
                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-lg checkbox-primary">
                                            <input type="checkbox" name="payroll" value="1"/>
                                            <span></span>
                                            Yes
                                        </label>
                                        Show Payroll for current period
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" align="right">
                                        @csrf
                                        <button type="submit" name="submit" value="confirm" class="btn btn-primary" id="btn-confirm" onclick="return confirm('Are you sure?')" disabled>
                                            Confirm firing <br> {{ $employee->emp_name }}
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row mt-10">
                    <div class="col-md-4 col-sm-12 mx-auto">
                        <button type="submit" name="submit" value="cancel" class="btn btn-warning btn-block" onclick="return confirm('Are you sure?')">
                            Cancel firing {{ $employee->emp_name }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        function _check(){
            var loan = $("#loan")
            var asset = $("#asset")
            var email = document.getElementById("email")
            console.log(email.checked)
            if (loan.val() == 1 && asset.val() == 1 && email.checked) {
                $("#btn-confirm").attr("disabled", false)
            } else {
                $("#btn-confirm").attr("disabled", true)
            }
        }

        $(document).ready(function(){
            $("select.select2").select2({
                width : "100%",
                allowClear : true
            })
        })
    </script>
@endsection
