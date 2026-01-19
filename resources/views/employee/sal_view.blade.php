@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Salary Approval - {{ $emp->emp_name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('employee.salary.approval') }}" class="btn btn-icon btn-sm btn-success">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <form action="{{ route('employee.salary.approval.post', $list->id) }}" method="post">
            <div class="card-body">
                <div class="d-flex">
                    <label class="col-form-label mr-5">This upgrade effective at?</label>
                    @if (empty($list->approved_at))
                    <select name="implement_date" class="form-control select2" id="">
                        <option value="{{ date("Y-m", strtotime(date("Y-m", strtotime($list->created_at))))."-01" }}">
                            {{ date("F", strtotime(date("Y-m", strtotime($list->created_at)))) }}, for payroll {{ date("F", strtotime(date("Y-m", strtotime($list->created_at)))) }}
                        </option>
                        <option value="{{ date("Y-m", strtotime(date("Y-m", strtotime($list->created_at))." +1month"))."-01" }}">
                            {{ date("F", strtotime(date("Y-m", strtotime($list->created_at)))) }}, for payroll {{ date("F", strtotime(date("Y-m", strtotime($list->created_at))." +1month")) }}
                        </option>
                        <option value="{{ date("Y-m", strtotime(date("Y-m", strtotime($list->created_at))." +2month"))."-01" }}">
                            {{ date("F", strtotime(date("Y-m", strtotime($list->created_at))." +1month")) }}, for payroll {{ date("F", strtotime(date("Y-m", strtotime($list->created_at))." +2month")) }}
                        </option>
                        <option value="{{ date("Y-m", strtotime(date("Y-m", strtotime($list->created_at))." +3month"))."-01" }}">
                            {{ date("F", strtotime(date("Y-m", strtotime($list->created_at))." +2month")) }}, for payroll {{ date("F", strtotime(date("Y-m", strtotime($list->created_at))." +3month")) }}
                        </option>
                    </select>
                    @else
                        <label for="" class="font-weight-bolder col-form-label">
                            {{ date("d F Y", strtotime($list->implement_date)) }}
                        </label>
                    @endif
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        @php
                            /** @var TYPE_NAME $emp */
                            $SAL       = base64_decode($emp->salary);
                            $TRANSPORT = base64_decode($emp->transport);
                            $MEAL      = base64_decode($emp->meal);
                            $HOUSE     = base64_decode($emp->house);
                            $HEALTH    = base64_decode($emp->health);

                            $new = json_decode($list->salary_json, true);
                            $newThp = $new["SAL"]+$new['TRANSPORT']+$new['MEAL']+$new['HOUSE']+$new['HEALTH'];

                         $thp = $SAL+$TRANSPORT+$MEAL+$HOUSE+$HEALTH
                        @endphp
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Take Home Pay</label>
                            <div class="col-sm-8">
                                <label class="font-weight-bolder col-form-label">{{number_format($thp, 2)}} @if($thp != $newThp)<i class="fa fa-chevron-right text-dark"></i> {{ number_format($newThp, 2) }}@endif</label>
                                <div id="breakdown"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Position Allowance</label>
                            <div class="col-sm-8">
                                <label class="font-weight-bolder col-form-label">{{number_format($emp->allowance_office, 2)}} @if((number_format($emp->allowance_office) != number_format($new['pa'])))<i class="fa fa-chevron-right text-dark"></i> {{ number_format($new['pa'], 2) }}@endif</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Health Insurance</label>
                            <div class="col-sm-8">
                                <label class="font-weight-bolder col-form-label">{{number_format($emp->health_insurance, 2)}} @if((number_format($emp->health_insurance) != number_format($new['hi'])))<i class="fa fa-chevron-right text-dark"></i> {{ number_format($new['hi'], 2) }}@endif</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Jamsostek</label>
                            <div class="col-sm-8">
                                <label class="font-weight-bolder col-form-label">{{number_format($emp->jamsostek, 2)}} @if((number_format($emp->jamsostek) != number_format($new['jam'])))<i class="fa fa-chevron-right text-dark"></i> {{ number_format($new['jam'], 2) }}@endif</label>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Pension</label>
                            <div class="col-sm-8">
                                <label class="font-weight-bolder col-form-label">{{number_format($emp->pension, 2)}} @if((number_format($emp->pension) != number_format($new['pensi'])))<i class="fa fa-chevron-right text-dark"></i> {{ number_format($new['pensi'], 2) }}@endif</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Perfomance Bonus</label>
                            <div class="col-sm-8">
                                <label class="font-weight-bolder col-form-label">{{number_format($emp->yearly_bonus, 2)}} @if((number_format($emp->yearly_bonus) != number_format($new['yb'])))<i class="fa fa-chevron-right text-dark"></i> {{ number_format($new['yb'], 2) }}@endif</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Over Time</label>
                            <div class="col-sm-8">
                                <label class="font-weight-bolder col-form-label">{{number_format($emp->overtime, 2)}} @if((number_format($emp->overtime) != number_format($new['overtime'])))<i class="fa fa-chevron-right text-dark"></i> {{ number_format($new['overtime'], 2) }}@endif</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Voucher</label>
                            <div class="col-sm-8">
                                <label class="font-weight-bolder col-form-label">{{number_format($emp->voucher, 2)}} @if((number_format($emp->voucher) != number_format($new['voucher'])))<i class="fa fa-chevron-right text-dark"></i> {{ number_format($new['voucher'], 2) }}@endif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    @empty($list->approved_at)
                    @csrf
                    @actionStart("employee", "approvedir")
                        @actionStart("employee", "approvediv2")
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle"></i> Approve
                        </button>
                        @actionEnd
                    @actionEnd
                    @endempty
                </div>
            </div>
        </form>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("table.display").DataTable()
            $("select.select2").select2({
                width : "40%"
            })
        })
    </script>
@endsection
