@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">{{ ucwords($type) }} Employee</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route("employee.index") }}" class="btn btn-sm btn-success btn-icon">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @actionStart('employee', 'approvedir')
            <div class="row">
                <div class="col-12">
                    <table>
                        <tr>
                            <td>Employee Name</td>
                            <td>: {{ $emp->emp_name }}</td>
                        </tr>
                        <tr>
                            <td>Employee No.</td>
                            <td>: {{ $emp->emp_id }}</td>
                        </tr>
                        <tr>
                            <td>Position</td>
                            <td>: {{ $pos->name }}</td>
                        </tr>
                        <tr>
                            <td>Division</td>
                            <td>: {{ $div->name }}</td>
                        </tr>
                        <tr>
                            <td>Grade</td>
                            <td>: {{ $grade->grade ?? "-" }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
            <form action="{{ route('employee.grade.submit') }}" method="post">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="grades" class="col-form-label">Grade</label>
                        <select name="grade" class="form-control select2" data-placeholder="Select Grade" id="grade">
                            <option value=""></option>
                            @foreach ($grades as $item)
                                <option value="{{ $item->id }}" {{ !empty($grade) ? ($grade->id == $item->id ? "SELECTED" : "") : "" }}>{{ "$item->grade ($item->min_edu - $item->yos years)" }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12 divpar">
                    <h3>Wages</h3>
                    <div class="form-group">
                        <label for="inputEmail3" class="control-label">Basic Salary</label>
                        <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="salary" id="salary" placeholder="" required value="{{ base64_decode($emp->n_basic_salary) }}">
                        <span class="text-center text-danger tx-label" id="salary-label"></span>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="control-label">House Allowance</label>
                        <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="house" id="house" placeholder="" required value="{{ base64_decode($emp->n_house_allow) }}">
                        <span class="text-center text-danger tx-label" id="house-label"></span>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="control-label">Health Allowance</label>
                        <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="health" id="health" placeholder="" required value="{{ base64_decode($emp->n_health_allow) }}">
                        <span class="text-center text-danger tx-label" id="health-label"></span>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="control-label">Position ALlowance</label>
                        <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="position" id="position" placeholder="" required value="{{ base64_decode($emp->n_position_allow) }}">
                        <span class="text-center text-danger tx-label" id="position-label"></span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 divpar">
                    <h3>Non Wages</h3>
                    <div class="form-group">
                        <label for="inputEmail3" class="control-label">Transport Allowance</label>
                        <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="transport" id="transport" placeholder="" required value="{{ base64_decode($emp->n_transport_allow) }}">
                        <span class="text-center text-danger tx-label" id="transport-label"></span>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="control-label">Meal Allowance</label>
                        <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="meal" id="meal" placeholder="" required value="{{ base64_decode($emp->n_meal_allow) }}">
                        <span class="text-center text-danger tx-label" id="meal-label"></span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="inputEmail3" class="control-label">Performance Bonus</label>
                        <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="performance_bonus" id="performance_bonus" placeholder="" required value="{{ base64_decode($emp->n_performance_bonus) }}">
                        <span class="text-center text-danger tx-label" id="performance_bonus-label"></span>
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
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    @csrf
                    <input type="hidden" name="emp_id" value="{{ $emp->id }}">
                    <input type="hidden" name="status" value="{{ $type }}">
                    <button type="submit" class="btn btn-primary">
                        Submit
                    </button>
                </div>
            </div>
            </form>
            @actionElse
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-custom alert-danger">
                        <span class="alert-text">You have no authorities to this page.</span>
                    </div>
                </div>
            </div>
            @actionEnd
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
    <script src="{{ asset('assets/jquery-number/jquery.number.js') }}"></script>
    <script>
        function new_calc_thp(){
            var divs = $(`div.divpar`)
            var maxthp = 0
            divs.each(function(){
                $(this).find("input:required").each(function(){
                    var tot = $(this).val() * 1
                    maxthp += tot
                })
            })
            var pb = $(`#performance_bonus`).val() * 1
            var pbval = 0
            var mthp = $.number(maxthp + pb)
            $(`#maxthp`).val(mthp)
        }
        async function getGrade(id){
            return $.ajax({
                url : "{{ route('cp.get') }}/"+id,
                type : "get",
                dataType : "json"
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
            $("#grade").change(async function(){
                var gr = $(this).val()
                var grdet = await getGrade(gr)
                console.log(grdet)
                $("span.tx-label").each(function(){
                    var id = $(this).attr("id")
                    var key = id.split("-")
                    var lb = $(this).parent().find("label.control-label").text()
                    var am = $.number(grdet[key[0]])
                    if(key[0] == "performance_bonus"){
                        console.log(key[0])
                        console.log(grdet[key[0]])
                        if(grdet[key[0]] == 0){
                            am = "unlimited"
                        } else if(grdet[key[0]] < 0){
                            am = $.number(0)
                        }
                    }
                    var txt = `${lb} for <strong>${grdet['grade']}<strong> is <strong>${am}</strong>`
                    $(this).html(txt)
                })
            })
            $("select.select2").select2({
                width : "100%"
            })
        })
    </script>
@endsection
s