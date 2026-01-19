@extends('layouts.templateCrm', ['withoutFooter' => true])

@section('css')
    <link href="{{ asset('assets/plugins/custom/leaflet/leaflet.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <!--begin::Subheader-->
    <!--end::Subheader-->
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                Personal
            </div>
            <div class="card-toolbar">
                <div class="" role="group" aria-label="Basic example">
                    {{-- <a href="{{ route('employee.user.outstanding') }}" class="btn btn-warning mr-5 d-inline-flex align-items-center">
                        <span class="font-size-base mr-3">Outstanding Signatures</span>
                        <span class="symbol symbol-20px symbol-circle symbol-light-{{ $userEmp->count() > 0 ? "danger" : "success" }}">
                            <span class="symbol-label font-size-base">{{ $userEmp->count() }}</span>
                        </span>
                    </a> --}}
                    @actionStart("employee", "approvedir")
                    <a href="{{ route('employee.salary.approval') }}" class="btn btn-facebook mr-5">Upgrade Approval</a>
                    @actionEnd
                    @actionStart("employee", "approvedir")
                    <a href="{{ route('employee.career.index') }}" class="btn btn-facebook mr-5">Promote/Demote</a>
                    @actionEnd
                    @actionStart('employee', 'create')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployee"><i class="fa fa-plus"></i>New Record</button>
                    @actionEnd
                </div>
                <!--end::Button-->
            </div>
        </div>
        {{-- <div class="card-body">
            <button type="button" onclick="getData(0)" class="btn btn-light-info font-weight-bold type btn-type" id="type0" name="type" style="width: 190px" value="0">
                All
            </button>
            <button type="button" onclick="getData(-1)" class="btn btn-light-info font-weight-bold type btn-type" id="typebank" name="type" style="width: 190px" value="-1">
                Expeled
            </button>
            <br><br>
            <div class="row">
            @foreach($emptypes as $key => $value)
                <div class="col-md-1 col-sm-12">
                    <button type="button" class="btn btn-block btn-light-info font-weight-bold type btn-type" id="type{{$value->id}}" name="type" value="{{$value->id}}">
                        {{$value->name}}
                    </button>
                </div>
            @endforeach
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                    <a href="{{ route('employee.report.view') }}" class="btn btn-light-info font-weight-bold">
                        Report
                    </a>
                </div>
            </div>

        </div> --}}
        <div class="card-body">
            <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table class="table table-bordered table-hover font-size-sm data_emp" id="table-employee" style="margin-top: 13px !important; width: 100%;">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th nowrap="nowrap" style="width: 25%">Name</th>
                        <th nowrap="nowrap" class="text-center">Job Title</th>
                        <th nowrap="nowrap" class="text-center">Email</th>
                        <th nowrap="nowrap" class="text-center">NIK</th>
                        <th nowrap="nowrap" class="text-center">Last Login</th>
                        {{-- <th nowrap="nowrap" class="text-center">Branch</th> --}}
                        <th nowrap="nowrap" class="text-center">Absen Anywhere</th>
                        @actionStart('employee', 'delete')
                        <th nowrap="nowrap" class="text-center">#</th>
                        @actionEnd
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Employee</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="fa fa-times"></i>
                    </button>
                </div>
                <form method="post" action="{{URL::route('employee.add')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <br>
                        <h4>Setup Profile</h4><hr>
                        <div class="row">
                            <div class="form col-md-6">
                                <div class="fv-row mb-5">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" value="{{ old("full_name") }}" name="full_name" placeholder="Full Name" required />
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Email</label>
                                    <input type="email" class="form-control" value="{{ old("email") }}" name="email" placeholder="Email" required />
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Employee Status</label>
                                    <select class="form-control" data-control="select2" data-hide-search="true" id="emp_status" name="emp_status" data-placeholder="Contract">
                                        <option value="kontrak">Contract</option>
                                        <option value="konsultan">Consultant</option>
                                        <option value="tetap">Permanent</option>

                                    </select>
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Religion</label>
                                    <select class="form-control" data-control="select2" data-hide-search="true" id="kt_select2_religion" name="religion" data-placeholder="Select Religion">
                                        @foreach ($religion as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                        {{-- <option value="islam">Islam</option>
                                        <option value="kristen_protestan">Kristen Protestan</option>
                                        <option value="kristen_katholik">Kristen Katholik</option>
                                        <option value="hindu">Hindu</option>
                                        <option value="buddha">Buddha</option>
                                        <option value="konghuchu">Kong Hu Chu</option>
                                        <option value="lain">Lain-lain</option> --}}
                                    </select>
                                </div>
                                {{-- <div class="fv-row mb-5">
                                    <label>Phone 1</label>
                                    <input type="text" class="form-control" name="phone_1" placeholder="Phone 1" required/>
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Phone 2</label>
                                    <input type="text" class="form-control" name="phone_2" placeholder="Phone 2" />
                                </div> --}}
                                {{-- <div class="fv-row mb-5">
                                    <label>Latitude</label>
                                    <input type="text" class="form-control" name="latitude" placeholder="Latitude"/>
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Longitude</label>
                                    <input type="text" class="form-control" name="longitude" placeholder="Longitude" />
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Address</label>
                                    <textarea class="form-control" name="address" required></textarea>
                                </div> --}}
                                <div class="fv-row mb-5">
                                    <label>Phone</label>
                                    <input type="text" value="{{ old("phone") }}" class="form-control" name="phone" placeholder="Phone" required/>
                                </div>
                                <div class="fv-row mb-5">
                                            <label>Date Birth</label>
                                    <input type="date" class="form-control" value="{{ old("date_birth") }}" name="date_birth" placeholder="Date Birth" required />
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Education</label>
                                    <select class="form-control" data-control="select2" data-hide-search="true" id="kt_select2_edu" name="edu" required data-placeholder="Select Education">
                                        <option value=""></option>
                                        @foreach ($edu as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form col-md-6">
                                <div class="fv-row mb-5">
                                    <label>Employee ID (NIK)</label>
                                    <input type="text" class="form-control" name="emp_id" id="emp_id" placeholder="Employee ID (NIK)"/>
                                    @error('emp_id')
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Employee Type</label>
                                    <select class="form-control" data-control="select2" data-hide-search="true" id="emp_type" onchange="genPos()" name="emp_type" required data-placeholder="Select Type">
                                        <option value=""></option>
                                        @foreach($emptypes as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Division</label>
                                    <select class="form-control" data-control="select2" data-hide-search="true" id="division" name="division" required data-placeholder="Select Division">
                                        <option value=""></option>
                                        @foreach($divisions as $key => $val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Position</label>
                                    <input type="text" class="form-control" id="emp-position" name="position" placeholder="Position"/>
                                </div>
                                <div class="fv-row mb-5" id="modalAddBank">
                                    <label>Bank</label>
                                    <select class="form-control" data-control="select2" data-dropdown-parent="#modalAddBank" id="bankCode" name="bankCode" data-placeholder="Select Bank" required>
                                        <option value=""></option>
                                        @foreach ($master_banks as $kode_bank => $nama_bank)
                                            <option value="{{ $kode_bank }}">[{{ $kode_bank }}] {{ $nama_bank }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fv-row mb-5">
                                    <label>Account Number</label>
                                    <input type="text" class="form-control" value="{{ old("account") }}" name="account" placeholder="Account Number" required/>
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <br>
                                <h4>Detail Information</h4><hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="fv-row mb-5">
                                            <label>Profile photo <span class="text-danger">* Image File</span></label>
                                            <img src="" id="prev_eq1" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                            <input type="file" class="form-control" name="picture" id="picture1" accept='image/*' placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fv-row mb-5">
                                            <label>Identity Card <span class="text-danger">* Image File</span></label>
                                            <img src="" id="prev_eq2" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                            <input type="file" class="form-control" name="ktp" id="picture2" accept='image/*' placeholder="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fv-row mb-5">
                                            <label>Certificate <span class="text-danger">* Image File</span></label>
                                            <img src="" id="prev_eq3" alt="Photo" class="img-thumbnail img-responsive center-block" >
                                            <input type="file" class="form-control" name="serti1" id="picture3" accept='image/*' placeholder="" required>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-12">
                                <br>
                                <h4>Salary</h4><hr>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Salary</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" onkeyup="calc()" name="SAL" id="salary" placeholder="" required>
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Transport</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" onkeyup="calc()" name="TRANSPORT" id="transport" placeholder="" required>
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Meal</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" onkeyup="calc()" name="MEAL" id="meal" placeholder="" required>
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">House</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" onkeyup="calc()" name="HOUSE" id="house" placeholder="" required>
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Health</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" onkeyup="calc()" name="HEALTH" id="health" placeholder="" required>
                                    </div>
                                </div>
                                <hr>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Take Home Pay</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" name="thp" id="thp" disabled placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Position Allowance</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" name="pa" id="pa" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Health Insurance</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" name="hi" id="hi" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Jamsostek</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" name="jam" id="jam" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Pension</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" name="pensi" id="pensi" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Perfomance Bonus</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" name="yb" id="yb" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Over Time</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" name="overtime" id="overtime" placeholder="" value="0">
                                    </div>
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Voucher</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control number" name="voucher" id="voucher" placeholder="" value="0">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <div class="fv-row mb-5">
                                    <label for="grades" class="col-form-label">Grade</label>
                                    <select name="grade" class="form-control select2" data-placeholder="Select Grade" id="grade">
                                        <option value=""></option>
                                        @foreach ($grades as $item)
                                            <option value="{{ $item->id }}">{{ "$item->grade ($item->min_edu - $item->yos years)" }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 divpar">
                                        <h3>Wages</h3>
                                        <div class="fv-row mb-5">
                                            <label for="inputEmail3" class="control-label">Basic Salary</label>
                                            <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="n_basic_salary" id="salary" placeholder="" required value="0">
                                            <div class="col-sm-12">
                                            </div>
                                        </div>
                                        <div class="fv-row mb-5">
                                            <label for="inputEmail3" class="control-label">House Allowance</label>
                                            <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="n_house_allow" id="house" placeholder="" required value="0">
                                            <div class="col-sm-12">
                                            </div>
                                        </div>
                                        <div class="fv-row mb-5">
                                            <label for="inputEmail3" class="control-label">Health Allowance</label>
                                            <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="n_health_allow" id="health" placeholder="" required value="0">
                                            <div class="col-sm-12">
                                            </div>
                                        </div>
                                        <div class="fv-row mb-5">
                                            <label for="inputEmail3" class="control-label">Position ALlowance</label>
                                            <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="n_position_allow" id="position" placeholder="" required value="0">
                                            <div class="col-sm-12">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 divpar">
                                        <h3>Non Wages</h3>
                                        <div class="fv-row mb-5">
                                            <label for="inputEmail3" class="control-label">Transport Allowance</label>
                                            <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="n_transport_allow" id="transport" placeholder="" required value="0">
                                            <div class="col-sm-12">
                                            </div>
                                        </div>
                                        <div class="fv-row mb-5">
                                            <label for="inputEmail3" class="control-label">Meal Allowance</label>
                                            <input type="text" class="form-control number" onkeyup="new_calc_thp()" name="n_meal_allow" id="meal" placeholder="" required value="0">
                                            <div class="col-sm-12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="fv-row mb-5">
                                            <label for="inputEmail3" class="control-label">Performance Bonus</label>
                                            <input type="text" class="form-control" onkeyup="new_calc_thp()" name="n_performance_bonus" id="performance_bonus" placeholder="" required value="-">
                                            <span class="text-center text-danger" id="pb-label"></span>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="fv-row mb-5">
                                            <label for="inputEmail3" class="control-label">Max. Take Home Pay</label>
                                            <input type="text" class="form-control" name="maxthp" id="maxthp" disabled value="0">
                                            <div class="col-sm-12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div> --}}
                        {{-- <div class="row">
                            <div class="col-md-12">
                                <br>
                                <h4>Field, Warehouse, ODO Rate</h4>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="fv-row mb-5">
                                            <label for="" class="col-form-label">Field</label>
                                            <input type="text" class="form-control number" min="0" name="fld_bonus" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fv-row mb-5">
                                            <label for="" class="col-form-label">Warehouse</label>
                                            <input type="text" class="form-control number" min="0" name="wh_bonus" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fv-row mb-5">
                                            <label for="" class="col-form-label">ODO</label>
                                            <input type="text" class="form-control number" min="0" name="odo_bonus" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">
                            <i class="fa fa-check"></i>
                            Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGenerate" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="modal-content-fld">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script src="{{ asset("assets/jquery-number/jquery.number.js") }}"></script>
    <script src="{{asset('theme/assets/js/signature_pad.js')}}"></script>
    <script>

        var max_perf = null

        function clear_calc(modal_name = "#addEmployee"){
            var divs = $(`${modal_name} div.divpar`)
            var maxthp = 0
            divs.each(function(){
                $(this).find("input:required").each(function(){
                    $(this).val("")
                })
            })
            $(`${modal_name} #performance_bonus`).val("-")
            $(`${modal_name} #maxthp`).val("")
        }

        function new_calc_thp(modal_name = "#addEmployee"){
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

        function pad(num, size) {
            num = num.toString();
            while (num.length < size) num = "0" + num;
            return num;
        }

        function _new_date(date, periode){
            var _date = date.split("-")
            var year = parseInt(_date[0])
            var month = parseInt(_date[1])
            var date = parseInt(_date[2])

            for (let i = 0; i < periode; i++) {
                month++
                if(month > 12){
                    month = 1
                    year++
                }
            }

            var _lastdate = new Date(`${year}-${pad(month, 2)}-01`)
            var lastDay = new Date(_lastdate.getFullYear(), _lastdate.getMonth() + 1, 0);

            if(date > lastDay.getDate()){
                date = lastDay.getDate()
            } else {
                date -= 1
            }

            return `${year}-${pad(month, 2)}-${pad(date, 2)}`
        }

        function _contract(x){
            $("#modal-content-fld").html("")
            $.ajax({
                url : "{{ route('hrd.contract.indexPost') }}",
                type : "post",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : x,
                    type : "modal"
                },
                success : function(response){
                    $("#modal-content-fld").html(response)
                    $(".number").number(true, 2)
                    $("select.select2").select2({
                        width : "100%"
                    })

                    $("#btn-generate").prop("disabled", true)

                    $("#emp-name").change(function(){
                        $.ajax({
                            url : "{{ route('hrd.contract.indexPost') }}",
                            type : "post",
                            dataType : "json",
                            data : {
                                _token : "{{ csrf_token() }}",
                                id : $(this).val(),
                                type : "emp"
                            },
                            success : function(data){
                                $("#emp-name").prop('disabled', true)
                                var nik = $("#modal-content-fld").find("input[name=nik]")
                                var address = $("#modal-content-fld").find("textarea[name=address]")
                                nik.val(data.nik)
                                $("#jk").val(data.gender).trigger('change')
                                $("#tmpt").val(data.emp_tmpt_lahir)
                                $("#tgl").val(data.emp_lahir)
                                address.val(data.address)
                                $("#template-id").change(function(){
                                    $.ajax({
                                        url : "{{ route('hrd.contract.indexPost') }}",
                                        type : "post",
                                        data : {
                                            _token : "{{ csrf_token() }}",
                                            id_tp : $(this).val(),
                                            id : $("#emp-name").val(),
                                            type : "modal-content"
                                        },
                                        success : function(response){
                                            $("#mdl-content").html(response)
                                            $(".number").number(true, 2)
                                            $("select.select2").select2({
                                                width : "100%"
                                            })
                                            $("#btn-generate").prop("disabled", false)

                                            $("#join_date").change(function(){
                                                var _periode = $("#periode_kontrak").val()
                                                var due_date_val = _new_date($(this).val(), _periode)
                                                $("#expire").val(due_date_val)
                                            })

                                            $("#periode_kontrak").change(function(){
                                                var _periode = $(this).val()
                                                var due_date_val = _new_date($("#join_date").val(), _periode)
                                                $("#expire").val(due_date_val)
                                            })

                                            // $(".field_emp").each(function(){
                                            //     var id = $(this).attr('id')
                                            //     var _val = data[id]
                                            //     if(id == "salary"){
                                            //         _val = parseFloat(atob(data[id])) + parseFloat(atob(data['transport'])) + parseFloat(atob(data['meal'])) + parseFloat(atob(data['house'])) + parseFloat(atob(data['transport']))
                                            //     }
                                            //     console.log(_val)
                                            //     $(this).val(_val)
                                            //     $("#emp-type").val(data.emp_type).trigger('change')
                                            //     $("#emp-div").val(data.division).trigger('change')
                                            // })

                                            var wrapper     = document.getElementById("form-sign"),
                                                saveButton  = wrapper.querySelector("[name=submit_sign]"),
                                                canvas      = wrapper.querySelector("canvas"),
                                                signaturePad;

                                            signaturePad    = new SignaturePad(canvas);


                                            $('#btn-sign-clear').click(function() {
                                                signaturePad.clear();
                                            });

                                            $("#btn-generate").click(function(e){
                                                var isEmpty = signaturePad.isEmpty()
                                                var signUrl = signaturePad.toDataURL();
                                                $("#sign-url").val(signUrl)
                                                if(isEmpty){
                                                    e.preventDefault()
                                                    return Swal.fire("Signature Required", "Please draw your signature", 'warning')
                                                }
                                            })
                                        }
                                    })
                                })
                            }
                        })
                    })

                    $("#emp-name").val(x).trigger('change')
                    $("#emp-id").val(x)
                }
            })
        }

        function freeze_status(id, status){
            Swal.fire({
                title: "Are you sure?",
                text: status + " this employee!",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route('employee.freeze') }}/"+id
                }
            });
        }

        function _link(x){
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-bottom-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            var link = $(x).data('link')

            var $tempElement = $("<input>");
            $("body").append($tempElement);
            $tempElement.val($(x).data('link')).select();
            document.execCommand("Copy");
            $tempElement.remove();


            toastr.success(link + " copied to the clipboard");
        }

        function _expel(id){
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(function(result) {
                if (result.value) {
                    window.location.href = "{{ route("employee.expel.get") }}/" + id
                }
            });
        }

        function calc(){
            var salary = $("#salary").val()
            var transport = $("#transport").val()
            var meal = $("#meal").val()
            var house = $("#house").val()
            var health = $("#health").val()

            var thp = (salary * 1) + (transport * 1) + (meal * 1) + (house * 1) + (health * 1)
            console.log(thp)
            $("#thp").val(thp)
        }

        async function getGrade(id){
            return $.ajax({
                url : "{{ route('cp.get') }}/"+id,
                type : "get",
                dataType : "json"
            })
        }

        function genPos(){
            var tp = $("#emp_type option:selected" ).text()
            $('#emp-position').val(`${tp}`);
        }

        @if (\Session::has("career"))
            Swal.fire("Employee Update", "{{ \Session::get("career") }}", "success")
        @endif

        function nik_generate(status){
            $.ajax({
                url: "{{ route('employee.nik') }}",
                type: 'GET',
                data: {
                    emp_status: status,
                },
                success: function(response){
                    var res = JSON.parse(response);
                    $("#emp_id").val(res.data);
                }
            });
        }

        function toggleAnywhere(me){
            var id = $(me).data("id")
            var checked = me.checked
            console.log(checked)
            $.ajax({
                url : "{{ route("employee.index") }}?t=toggle&a=anywhere&i="+id,
                type : "get",
                dataType: "json",
                data : {
                    enable : checked
                }
            })
        }

        $(document).ready(function () {

            @if(Session::has("modal"))
                $("#{{ Session::get("modal") }}").modal("show")
            @endif

            $("#grade").change(async function(){
                var gr = $(this).val()
                var grdet = await getGrade(gr)
                var divs = $(`#addEmployee div.divpar`)
                divs.each(function(){
                    $(this).find("input:required").each(function(){
                        var k = $(this).attr("id")
                        $(this).val(grdet[k])
                    })
                })
                var pb = 0
                var pb_label = ""
                if(grdet.performance_bonus < 0){
                    max_perf = 0
                    pb_label = "Max. Perf. Bonus is 0"
                } else if(grdet.performance_bonus == 0){
                    pb_label = "Max. Perf. Bonus is Unlimited"
                } else {
                    pb = grdet.performance_bonus
                    max_perf = pb
                    pb_label = "Max. Perf. Bonus is " + $.number(grdet.performance_bonus)
                }
                $(`#addEmployee #performance_bonus`).val($.number(pb))
                $(`#addEmployee #pb-label`).text(pb_label)
                new_calc_thp()
            })

            $("#performance_bonus").on("change onpaste", function(){
                var v = $(this).val() * 1
                if(!isNaN(v)){
                    if(v < 0){
                        $(this).val($.number(0))
                    } else {
                        $(this).val($.number(v))
                        if(max_perf != null){
                            if(v > max_perf){
                                Swal.fire("Max. Limit", `Max. Perf. Bonus is ${$.number(max_perf)}`, 'error')
                                if(max_perf == 0){
                                    $(this).val("-")
                                } else {
                                    $(this).val($.number(max_perf))
                                }
                            }
                        }
                    }
                    new_calc_thp()
                }
            })

            $(".number").number(true)

            @if (\Session::has('link'))
                @if (\Session::get('link') == "error")
                    Swal.fire("Error", "", "error")
                @else
                    Swal.fire("{{ \Session::get('link') }}", "Share the link above to the Employee for signing", "success")
                @endif
            @endif

            var _table = $("#table-employee").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
                dom : `<"d-flex align-items-center border justify-content-end justify-content-md-end"<"dataTable-length-info-label me-3">lfip>`,
                pageLength: 10,
                @actionStart('employee', 'read')
                ajax : {
                    url: "{{route('employee.getdata_post')}}?type=0",
                    type: "post",
                    data: {
                        _token: "{{csrf_token()}}",
                    },
                    error: function (xhr, error, thrown) {
                        // Swal.fire("Page need to be reload", "Please reload this page", "error")
                    }
                },
                columns : [
                    { "data": "no" },
                    { "data": "emp_name" },
                    { "data": "emp_type" },
                    { "data": "email" },
                    { "data": "emp_id" },
                    { "data": "last_login" },
                    { "data": "anywhere", 'className' : "" },
                    @actionStart('employee', 'delete'){ "data": "action" },@actionEnd
                ],
                columnDefs: [
                    {
                        "targets": [0,2,3,4,5,6,7],
                        "className": "text-center",
                    },
                    {
                        "targets": [1],
                        "className": "text-left",
                    },
                ],
                createdRow: function (row, data, index) {
                    var is_expel = $(row).find(".is-expel")
                    if(is_expel.length > 0){
                        $(row).addClass("table-warning")
                    }

                    var freeze = $(row).find(".unfreeze")
                    if(freeze.length > 0){
                        $(row).addClass("table-primary")
                    }
                }
                @actionEnd
            })

            $(".dataTable-length-info-label").text("Rows per page:")

            var _selDataTable = $(".dataTables_length").find("select")
            _selDataTable.addClass("border-0")
            _selDataTable.removeClass("form-select-solid")
            _selDataTable.parent().addClass("border-bottom border-dark")

            $(".btn-type").click(function(){
                _table.clear().draw()
                _table.ajax.url("{{route('employee.getdata_post')}}?type="+$(this).val()).load()
            })

            $("select.select2").select2({
                width : "100%"
            })

            // getData(0)




            function myNewFunction(sel) {
                $('#emp-position').val(sel.options[sel.selectedIndex].text)
            }

            $("#addEmployee").on("shown.bs.modal", function(){
                var status = $("#emp_status").val();
                // console.log(status);
                nik_generate(status)
            })

            $("#emp_status").change(function(){
                var status = $(this).val();
                // console.log(status);
                nik_generate(status)
            });
            // $('#thp').bind('keypress keyup', function() {
            //     var nilai = $(this).val();
            //     $.ajax({
            //         url: "{{ route('employee.thp') }}",
            //         type: 'GET',
            //         data: {
            //             thp: nilai,
            //         },
            //         success: function(response){
            //             var res = JSON.parse(response);

            //             $("#breakdown").html(res.data);
            //         }
            //     });
            // });
            $('#emp_type').change(function () {
                var position = $("#emp_type option:selected").html();

                $('#position').val(position)
            })
            $("#fld_bonus").change(function(){
                var nilaiODO = $("#fld_bonus").val();
                var rateODO  = $("#odo_rate").val();
                var rateWH  = 0.33;

                var odo_bonus_calc  = nilaiODO * rateODO;
                var wh_bonus_calc  = nilaiODO * rateWH;

                $("#odo_bonus").val(odo_bonus_calc);
                $("#wh_bonus").val(wh_bonus_calc);
            });
            $("#emp_type").change(function(){
                var typer = $("#emp_type").val();
                var a     = [];
                switch(typer){
                    case "whbin":
                    case "whcil":
                    case "staff":
                        a[0]  = 40000; // meal
                        a[1]  = 75000; // spending
                        a[2]  = 250000; // overnight
                        a[3]  = 10; // ovs_meal
                        a[4]  = 10; // ovs_spending
                        a[5]  = 50; // ovs_overnight
                        a[6]  = 200000; // dom_airport
                        a[7]  = 200000; // dom_bus
                        a[8]  = 200000; // dom_train
                        a[9]  = 100000; // dom_cileungsi
                        a[10] = 15; // ovs_airport
                        a[11] = 15; // ovs_bus
                        a[12] = 15; // ovs_train
                        a[13] = 10; // ovs_cileungsi
                        break;
                    case "manager":
                        a[0]  = 55000; // meal
                        a[1]  = 100000; // spending
                        a[2]  = 400000; // overnight
                        a[3]  = 15; // ovs_meal
                        a[4]  = 15; // ovs_spending
                        a[5]  = 100; // ovs_overnight
                        a[6]  = 200000; // dom_airport
                        a[7]  = 200000; // dom_bus
                        a[8]  = 200000; // dom_train
                        a[9]  = 100000; // dom_cileungsi
                        a[10] = 15; // ovs_airport
                        a[11] = 15; // ovs_bus
                        a[12] = 15; // ovs_train
                        a[13] = 10; // ovs_cileungsi
                        break;
                    case "bod":
                        a[0]  = 75000; // meal
                        a[1]  = 150000; // spending
                        a[2]  = 500000; // overnight
                        a[3]  = 20; // ovs_meal
                        a[4]  = 20; // ovs_spending
                        a[5]  = 130; // ovs_overnight
                        a[6]  = 200000; // dom_airport
                        a[7]  = 200000; // dom_bus
                        a[8]  = 200000; // dom_train
                        a[9]  = 100000; // dom_cileungsi
                        a[10] = 15; // ovs_airport
                        a[11] = 15; // ovs_bus
                        a[12] = 15; // ovs_train
                        a[13] = 10; // ovs_cileungsi
                        break;
                    case "field":
                    case "konsultan":
                        a[0]  = 0; // meal
                        a[1]  = 0; // spending
                        a[2]  = 0; // overnight
                        a[3]  = 0; // ovs_meal
                        a[4]  = 0; // ovs_spending
                        a[5]  = 0; // ovs_overnight
                        a[6]  = 0; // dom_airport
                        a[7]  = 0; // dom_bus
                        a[8]  = 0; // dom_train
                        a[9]  = 0; // dom_cileungsi
                        a[10] = 0; // ovs_airport
                        a[11] = 0; // ovs_bus
                        a[12] = 0; // ovs_train
                        a[13] = 0; // ovs_cileungsi
                        break;
                }
                $("#dom_meal").val(a[0]); $("#dom_spending").val(a[1]); $("#dom_overnight").val(a[2]);
                $("#ovs_meal").val(a[3]); $("#ovs_spending").val(a[4]); $("#ovs_overnight").val(a[5]);
                $("#dom_transport_airport").val(a[6]); $("#dom_transport_bus").val(a[7]); $("#dom_transport_train").val(a[8]);  $("#dom_transport_cil").val(a[9]);
                $("#ovs_transport_airport").val(a[10]); $("#ovs_transport_bus").val(a[11]); $("#ovs_transport_train").val(a[12]); $("#ovs_transport_cil").val(a[13]);
            });
            $("#prev_eq1").hide();
            $("#prev_eq2").hide();
            $("#prev_eq3").hide();

            $("#picture1").change(function(){
                console.log($(this).val());
                if ($(this).val()) {
                    readURL(this, 1);
                    $("#prev_eq1").show();
                } else {
                    $("#prev_eq1").hide();
                }
            });

            $("#picture2").change(function(){
                if ($(this).val()) {
                    readURL(this, 2);
                    $("#prev_eq2").show();
                } else {
                    $("#prev_eq2").hide();
                }
            });

            $("#picture3").change(function(){
                if ($(this).val()) {
                    readURL(this, 3);
                    $("#prev_eq3").show();
                } else {
                    $("#prev_eq3").hide();
                }
            });
            function readURL(input, sec) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#prev_eq' + sec).attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#addEmployee input[name=email]").change(function(){
                var em = $(this).val()
                var form = $(this).parents("form")
                $.ajax({
                    url : "{{ route("employee.index") }}?act=email&v=" + em,
                    type : "get",
                    dataType : "json"
                }).then(function(resp){
                    console.log($(form).find("button[type=submit]"))
                    var btn = $(form).find("button[type=submit]")
                    $(btn).prop("disabled", !resp.success)
                    if(!resp.success){
                        Swal.fire("Pemberitahuan", resp.message, "warning")
                    }
                })
            })
        });
    </script>
@endsection
