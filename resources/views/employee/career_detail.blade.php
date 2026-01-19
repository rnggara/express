@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">{{ ucwords($cp->status) }} - {{ $emp->emp_name }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('employee.salary.approval') }}" class="btn btn-icon btn-sm btn-success">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <form action="{{ route('employee.career.approve') }}" method="post">
            <div class="card-body">
                <div class="d-flex">
                    <label class="col-form-label mr-5">This upgrade effective at?</label>
                    @if (empty($cp->approved_at))
                    <select name="implement_date" class="form-control select2" id="">
                        <option value="{{ date("Y-m", strtotime(date("Y-m", strtotime($cp->created_at))))."-01" }}">
                            {{ date("F", strtotime(date("Y-m", strtotime($cp->created_at)))) }}, for payroll {{ date("F", strtotime(date("Y-m", strtotime($cp->created_at)))) }}
                        </option>
                        <option value="{{ date("Y-m", strtotime(date("Y-m", strtotime($cp->created_at))." +1month"))."-01" }}">
                            {{ date("F", strtotime(date("Y-m", strtotime($cp->created_at)))) }}, for payroll {{ date("F", strtotime(date("Y-m", strtotime($cp->created_at))." +1month")) }}
                        </option>
                        <option value="{{ date("Y-m", strtotime(date("Y-m", strtotime($cp->created_at))." +2month"))."-01" }}">
                            {{ date("F", strtotime(date("Y-m", strtotime($cp->created_at))." +1month")) }}, for payroll {{ date("F", strtotime(date("Y-m", strtotime($cp->created_at))." +2month")) }}
                        </option>
                        <option value="{{ date("Y-m", strtotime(date("Y-m", strtotime($cp->created_at))." +3month"))."-01" }}">
                            {{ date("F", strtotime(date("Y-m", strtotime($cp->created_at))." +2month")) }}, for payroll {{ date("F", strtotime(date("Y-m", strtotime($cp->created_at))." +3month")) }}
                        </option>
                    </select>
                    @else
                        <label for="" class="font-weight-bolder col-form-label">
                            {{ date("d F Y", strtotime($cp->effective_date)) }}
                        </label>
                    @endif
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Grade</label>
                            <div class="col-sm-12">
                                <label class="font-weight-bolder col-form-label">
                                    {{ $old->grade ?? "-" }}
                                    @if ( !empty($old) && $new->id != $old->id)
                                        <i class="fa fa-chevron-right text-dark"></i>
                                        {{ $new->grade }}
                                    @endif
                                    @if(empty($old))
                                    <i class="fa fa-chevron-right text-dark"></i>
                                        {{ $new->grade }}
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @php
                        $total_old = 0;
                        $total_new = 0;
                    @endphp
                    @foreach ($detail_old as $key => $item)
                        <div class="col-md-6 col-sm-12">
                            @if ($key != "n_performance_bonus")
                                <h3>{{ ucwords(str_replace("_", " ", $key)) }}</h3>
                                @foreach ($item as $c => $val)
                                    @php
                                        $compare = base64_decode($detail_new[$key][$c]);
                                        $am = base64_decode($val);
                                        $total_old += $am;
                                        $total_new += $compare;
                                        $lbl = ucwords(str_replace("allow", "allowance", str_replace("_", " ", substr($c, 2))));
                                    @endphp
                                    <div class="form-group">
                                        <label for="inputEmail3" class="control-label">{{ $lbl }}</label>
                                        <div class="col-sm-12">
                                            <label class="font-weight-bolder col-form-label">
                                                {{ number_format($am) }}
                                                @if ($am != $compare)
                                                    <i class="fa fa-chevron-right text-dark"></i>
                                                    {{ number_format($compare) }}
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @php
                                    $am = base64_decode($item);
                                    $compare = base64_decode($detail_new[$key]);
                                    $total_old += $am;
                                    $total_new += $compare;
                                @endphp
                                <h3>Performance Bonus</h3>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <label class="font-weight-bolder col-form-label">
                                                    {{ number_format($am) }}
                                                    @if ($am != $compare)
                                                        <i class="fa fa-chevron-right text-dark"></i>
                                                        {{ number_format($compare) }}
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="inputEmail3" class="control-label"><h3>Max. Take Home Pay</h3></label>
                            <div class="col-sm-12">
                                <label class="font-weight-bolder col-form-label">
                                    {{ number_format($total_old) }}
                                    @if ($total_old != $total_new)
                                        <i class="fa fa-chevron-right text-dark"></i>
                                        {{ number_format($total_new) }}
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    @empty($cp->approved_at)
                    @csrf
                    <input type="hidden" name="cp" value="{{ $cp->id }}">
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
