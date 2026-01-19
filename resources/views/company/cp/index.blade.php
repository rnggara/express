@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Career Path</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalAdd">
                        Add Grade
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-responsive-sm">
                        <thead>
                            <tr>
                                <th rowspan="2">Level</th>
                                <th rowspan="2">Grade</th>
                                <th rowspan="2">Min. Education</th>
                                <th rowspan="2">Year of Service</th>
                                <th colspan="5">Wages</th>
                                <th colspan="3">Non Wages</th>
                                <th rowspan="2">THP</th>
                                <th rowspan="2">Max. Perf. Bonus</th>
                                <th rowspan="2">THP + Max. Perf. Bonus</th>
                                <th rowspan="2">Action</th>
                            </tr>
                            <tr>
                                <th>Min. Basic salary</th>
                                <th>House Allow.</th>
                                <th>Health Allow.</th>
                                <th>Position Allow.</th>
                                <th>Total Wages</th>
                                <th>Transport Allow.</th>
                                <th>Meal Allow.</th>
                                <th>Total Non Wages</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $or = count($career_path);
                            @endphp
                            @foreach ($role as $item)
                                @php
                                    $cp = $career_path->where("role_id", $item->id);
                                    $frst = $cp->first();
                                    $rspan = count($cp);
                                    $ftw = 0;
                                    $fntw = 0;
                                    $fthp = 0;
                                    $fmthp = 0;
                                    if(!empty($frst)){
                                        $ftw = $frst->salary + $frst->house + $frst->health + $frst->position;
                                        $fntw = $frst->transport + $frst->meal;
                                        $fthp = $ftw + $fntw;
                                        $fmthp = $fthp;
                                        if($frst->performance_bonus == 0){
                                            $fmthp = number_format($fthp) . " + unlimited";
                                        } elseif($frst->performance_bonus > 0) {
                                            $fmthp = number_format($fthp + $frst->performance_bonus);
                                        }
                                        $or--;
                                    }
                                @endphp
                                <tr>
                                    <td {{ $rspan > 1 ? "rowspan=$rspan" : '' }}>{{ ucwords($item->name) }}</td>
                                    <td align="center">
                                        <div class="d-flex flex-column">
                                            <span>{{ $frst->grade ?? "-" }}</span>
                                            <div class="btn-group">
                                                @if (!empty($frst) && $frst->order_num != 1)
                                                    <a href="{{ route("cp.order", ['type' => "up", "id" => $frst->id]) }}" onclick="return confirm('Are you sure?')" class="btn btn-icon btn-xs btn-success">
                                                        <i class="fa fa-arrow-up"></i>
                                                    </a>
                                                @endif
                                                @if ($or != 0)
                                                    <a href="{{ route("cp.order", ['type' => "down", "id" => $frst->id]) }}" onclick="return confirm('Are you sure?')" class="btn btn-icon btn-xs btn-danger">
                                                        <i class="fa fa-arrow-down"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td align="center">{{ $frst->min_edu ?? "-" }}</td>
                                    <td align="center">{{ $frst->yos ?? "-" }}</td>
                                    <td align="center">{{ number_format($frst->salary ?? 0) }}</td>
                                    <td align="center">{{ number_format($frst->house ?? 0) }}</td>
                                    <td align="center">{{ number_format($frst->health ?? 0) }}</td>
                                    <td align="center">{{ number_format($frst->position ?? 0) }}</td>
                                    <td align="center">{{ number_format($ftw) }}</td>
                                    <td align="center">{{ number_format($frst->transport ?? 0) }}</td>
                                    <td align="center">{{ number_format($frst->meal ?? 0) }}</td>
                                    <td align="center">{{ number_format($fntw) }}</td>
                                    <td align="center">{{ number_format($fthp) }}</td>
                                    <td align="center">{{ empty($frst) ? "-" : ($frst->performance_bonus > 0 ? number_format($frst->performance_bonus) : ($frst->performance_bonus < 0 ? "-" : "unlimited")) }}</td>
                                    <td align="center">{{ $fmthp }}</td>
                                    <td align="center">
                                        @if (!empty($frst))
                                            <button type="button" class="btn btn-primary btn-icon btn-xs" onclick="edit({{ $frst->id }})">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href="{{ route("cp.delete", $frst->id) }}" onclick="return confirm('Delete?')" class="btn btn-danger btn-icon btn-xs">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @if ($rspan > 1)
                                    @foreach ($cp->where("id", "!=", $frst->id) as $cps)
                                        @php
                                            $ftw = 0;
                                            $fntw = 0;
                                            $fthp = 0;
                                            $fmthp = 0;
                                            $ftw = $cps->salary + $cps->house + $cps->health + $cps->position;
                                            $fntw = $cps->transport + $cps->meal;
                                            $fthp = $ftw + $fntw;
                                            $fmthp = number_format($fthp);
                                            if($cps->performance_bonus == 0){
                                                $fmthp = number_format($fthp) . " + unlimited";
                                            } elseif($cps->performance_bonus > 0) {
                                                $fmthp = number_format($fthp + $cps->performance_bonus);
                                            }
                                            $or--;
                                        @endphp
                                        <tr>
                                            <td align="center">
                                                <div class="d-flex flex-column">
                                                    <span>{{ $cps->grade ?? "-" }}</span>
                                                    <div class="btn-group">
                                                        @if ($cps->order_num != 1)
                                                            <a href="{{ route("cp.order", ['type' => "up", "id" => $cps->id]) }}" onclick="return confirm('Are you sure?')" class="btn btn-icon btn-xs btn-success">
                                                                <i class="fa fa-arrow-up"></i>
                                                            </a>
                                                        @endif
                                                        @if ($or != 0)
                                                            <a href="{{ route("cp.order", ['type' => "down", "id" => $cps->id]) }}" onclick="return confirm('Are you sure?')" class="btn btn-icon btn-xs btn-danger">
                                                                <i class="fa fa-arrow-down"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td align="center">{{ $cps->min_edu ?? "-" }}</td>
                                            <td align="center">{{ $cps->yos ?? "-" }}</td>
                                            <td align="center">{{ number_format($cps->salary ?? 0) }}</td>
                                            <td align="center">{{ number_format($cps->house ?? 0) }}</td>
                                            <td align="center">{{ number_format($cps->health ?? 0) }}</td>
                                            <td align="center">{{ number_format($cps->position ?? 0) }}</td>
                                            <td align="center">{{ number_format($ftw) }}</td>
                                            <td align="center">{{ number_format($cps->transport ?? 0) }}</td>
                                            <td align="center">{{ number_format($cps->meal ?? 0) }}</td>
                                            <td align="center">{{ number_format($fntw) }}</td>
                                            <td align="center">{{ number_format($fthp) }}</td>
                                            <td align="center">{{ ($cps->performance_bonus > 0 ? number_format($cps->performance_bonus) : ($cps->performance_bonus < 0 ? "-" : "unlimited")) }}</td>
                                            <td align="center">{{ $fmthp }}</td>
                                            <td align="center">
                                                <button type="button" class="btn btn-primary btn-icon btn-xs" onclick="edit({{ $cps->id }})">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="{{ route("cp.delete", $cps->id) }}" onclick="return confirm('Delete?')" class="btn btn-danger btn-icon btn-xs">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalEdit" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAdd" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Add Grade</h3>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('cp.add') }}" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="level" class="col-form-label">Level</label>
                                    <select name="level" id="level" class="form-control select2" required data-placeholder="Select Level">
                                        <option value=""></option>
                                        @foreach ($role as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="grade" class="col-form-label">Grade Name</label>
                                    <input type="text" name="grade" id="grade" class="form-control" required value="">
                                </div>
                                <div class="form-group">
                                    <label for="min_edu" class="col-form-label">Min. Education</label>
                                    <select class="form-control select2" name="min_edu" id="min_edu" required data-placeholder="Select Education">
                                        <option value=""></option>
                                        <option value="S3">S3</option>
                                        <option value="S2">S2</option>
                                        <option value="S1">S1</option>
                                        <option value="D3">D4</option>
                                        <option value="D3">D3</option>
                                        <option value="D2">D2</option>
                                        <option value="D1">D1</option>
                                        <option value="SMA">SMA</option>
                                        <option value="SMK">SMK</option>
                                        <option value="MA">MA</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SD">SD</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="yos" class="col-form-label">Year of service</label>
                                    <input type="number" name="yos" id="yos" class="form-control" required value="0">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-sm-12 divpar">
                                <h3>Wages</h3>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Basic Salary</label>
                                    <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="salary" id="salary" placeholder="" required value="0">
                                    <div class="col-sm-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">House Allowance</label>
                                    <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="house" id="house" placeholder="" required value="0">
                                    <div class="col-sm-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Health Allowance</label>
                                    <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="health" id="health" placeholder="" required value="0">
                                    <div class="col-sm-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Position ALlowance</label>
                                    <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="position" id="position" placeholder="" required value="0">
                                    <div class="col-sm-12">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 divpar">
                                <h3>Non Wages</h3>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Transport Allowance</label>
                                    <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="transport" id="transport" placeholder="" required value="0">
                                    <div class="col-sm-12">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Meal Allowance</label>
                                    <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="meal" id="meal" placeholder="" required value="0">
                                    <div class="col-sm-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Performance Bonus</label>
                                    <input type="text" class="form-control" onkeyup="new_calc_thp()" name="performance_bonus" id="performance_bonus" placeholder="" required value="-">
                                    <span class="text-center text-danger">* fill with 0 to set unlimited</span>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="inputEmail3" class="control-label">Max. Take Home Pay</label>
                                    <input type="text" class="form-control" name="maxthp" id="maxthp" disabled value="0">
                                    <div class="col-sm-12">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        function new_calc_thp(modal_name = "#modalAdd"){
            var divs = $(`${modal_name} div.divpar`)
            var maxthp = 0
            divs.each(function(){
                $(this).find("input:required").each(function(){
                    var tot = $(this).val() * 1
                    maxthp += tot
                })
            })
            var pb = $(`${modal_name} #performance_bonus`).val().replaceAll(",", "")
            var pbval = 0
            console.log(pb)
            var mthp = $.number(maxthp)
            if(!isNaN(pb * 1)){
                if(pb != ""){
                    if((pb * 1) <= 0){
                        mthp = $.number(maxthp) + " + unlimited"
                    } else {
                        mthp = $.number(maxthp + (pb * 1))
                    }
                }
            }
            $(`${modal_name} #maxthp`).val(mthp)
        }

        function edit(id){
            $("#modalEdit .modal-content").html('')
            $.ajax({
                url : "{{ route("cp.edit") }}/" + id,
                type : "get",
                success : function(resp){
                    $("#modalEdit .modal-content").html(resp)
                    $("#modalEdit").modal("show")
                    $("#modalEdit select.select2").select2({
                        width : "100%"
                    })
                    new_calc_thp("#modalEdit")

                    $("#modalEdit .number").number(true)
                    $("#modalEdit #performance_bonus").on("change onpaste", function(){
                        var v = $(this).val() * 1
                        if(!isNaN(v)){
                            $(this).val($.number(v))
                            new_calc_thp("#modalEdit")
                        }
                    })
                }
            })
        }

        $(document).ready(function(){
            new_calc_thp()
            $(".number").number(true)
            $("#performance_bonus").on("change onpaste", function(){
                var v = $(this).val() * 1
                if(!isNaN(v)){
                    $(this).val($.number(v))
                    new_calc_thp()
                }
            })
            $("select.select2").select2({
                width : "100%"
            })
            // $("table.display").DataTable()
        })
    </script>
@endsection
