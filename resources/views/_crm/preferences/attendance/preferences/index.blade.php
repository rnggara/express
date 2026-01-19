@extends('_crm.preferences.index')

@section('view_content')
    <div class="d-flex flex-column">
        <div class="card shadow-none">
            <div class="card-header border-bottom-0 px-0">
                <div class="d-flex flex-column">
                    <h3 class="card-title">Preferences Management</h3>
                    <span>More details and settings</span>
                </div>
            </div>
            <div class="card-header border-0 px-0">
                <ul class="nav nav-tabs border nav-line-tabs mb-5 fs-6 border-0 pt-5">
                    <li class="nav-item">
                        <a class="nav-link active text-active-dark" data-bs-toggle="tab" href="#tab_date_management">
                            <span class="nav-text">Date Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-dark" data-bs-toggle="tab" href="#tab_attendance_management">
                            <span class="nav-text">Attendance Management</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="myTabContent" style="padding: 0">
                <div class="tab-pane fade show active" id="tab_date_management" role="tabpanel">
                    <div class="card-body rounded p-0">
                        <form action="{{ route("crm.pref.attendance.preferences.store") }}" method="post" id="form-date">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex flex-column bg-secondary-crm p-10 rounded">
                                        <div class="fv-row">
                                            <label class="col-form-label">Date on Leave Process</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" name="dolp" value="{{ $prefs->dolp ?? "" }}" data-reset="{{ $prefs->dolp ?? "" }}" placeholder="25">
                                                <div class="position-absolute top-25 end-0 me-5">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <label class="col-form-label">Date Attendance Closing Period</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control" name="dacp" value="{{ $prefs->dacp ?? "" }}" data-reset="{{ $prefs->dacp ?? "" }}" placeholder="25">
                                                <div class="position-absolute top-25 end-0 me-5">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Cut Off Process</label>
                                                <input type="number" class="form-control" name="cop" value="{{ $prefs->cop ?? "" }}" data-reset="{{ $prefs->cop ?? "" }}" placeholder="-2">
                                            </div>
                                            <div class="fv-row col-6">
                                                <label class="col-form-label">Cut Off Report</label>
                                                <input type="number" class="form-control" name="cor" value="{{ $prefs->cor ?? "" }}" data-reset="{{ $prefs->cor ?? "" }}" placeholder="-2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column bg-secondary-crm p-10 rounded">
                                        <div class="fv-row">
                                            <label class="col-form-label">Working Schedule Process</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control tempusDominus" readonly id="td1" name="wsp" value="{{ date("d/m/Y", strtotime($prefs->wsp ?? "2023-01-25")) }}" data-reset="{{ date("d/m/Y", strtotime($prefs->wsp ?? "2023-01-25")) }}">
                                                <div class="position-absolute top-25 end-0 me-5">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <label class="col-form-label">Time & Attendance Process</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control tempusDominus" readonly id="td2" name="tap" value="{{ date("d/m/Y", strtotime($prefs->tap ?? "2023-01-25")) }}" data-reset="{{ date("d/m/Y", strtotime($prefs->tap ?? "2023-01-25")) }}">
                                                <div class="position-absolute top-25 end-0 me-5">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <label class="col-form-label">Collect Data From Attendance Machine</label>
                                            <div class="position-relative">
                                                <input type="text" class="form-control tempusDominus" readonly id="td3" name="cdm" value="{{ date("d/m/Y", strtotime($prefs->cdm ?? "2023-01-25")) }}" data-reset="{{ date("d/m/Y", strtotime($prefs->cdm ?? "2023-01-25")) }}">
                                                <div class="position-absolute top-25 end-0 me-5">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end d-none btn-toggle mt-10">
                                    @csrf
                                    <input type="hidden" name="type" value="date">
                                    <input type="hidden" name="id" value="{{ $prefs->id ?? "" }}">
                                    <button class="btn btn-sm" onclick="form_reset(this)" type="button">Reset</button>
                                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab_attendance_management" role="tabpanel">
                    <div class="card-body rounded p-0">
                        <form action="{{ route("crm.pref.attendance.preferences.store") }}" method="post" id="form-attendance">
                            <div class="row">
                                <div class="col-6">
                                    <div class="d-flex flex-column bg-secondary-crm p-10 rounded">
                                        <div class="fv-row">
                                            <label class="col-form-label">Grace In (Minutes)</label>
                                            <div class="position-relative">
                                                <input type="number" name="grace_in" class="form-control pe-20" value="{{ $prefs->grace_in ?? "" }}" data-reset="{{ $prefs->grace_in ?? "" }}" placeholder="5" id="">
                                                <span class="position-absolute text-muted me-5 top-25 end-0">Minutes</span>
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <label class="col-form-label">Early In Limit (Hours)</label>
                                            <div class="position-relative">
                                                <input type="number" name="early_in" class="form-control pe-20" value="{{ $prefs->early_in ?? "" }}" data-reset="{{ $prefs->early_in ?? "" }}" placeholder="8" id="">
                                                <span class="position-absolute text-muted me-5 top-25 end-0">Hours</span>
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <label class="col-form-label">Late in Limit (Hours)</label>
                                            <div class="position-relative">
                                                <input type="number" name="late_in" class="form-control pe-20" value="{{ $prefs->late_in ?? "" }}" data-reset="{{ $prefs->late_in ?? "" }}" placeholder="8" id="">
                                                <span class="position-absolute text-muted me-5 top-25 end-0">Hours</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column bg-secondary-crm p-10 rounded">
                                        <div class="fv-row">
                                            <label class="col-form-label">Grace Out (Minutes)</label>
                                            <div class="position-relative">
                                                <input type="number" name="grace_out" class="form-control pe-20" value="{{ $prefs->grace_out ?? "" }}" data-reset="{{ $prefs->grace_out ?? "" }}" placeholder="5" id="">
                                                <span class="position-absolute text-muted me-5 top-25 end-0">Minutes</span>
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <label class="col-form-label">Early Out Limit (Hours)</label>
                                            <div class="position-relative">
                                                <input type="number" name="early_out" class="form-control pe-20" value="{{ $prefs->early_out ?? "" }}" data-reset="{{ $prefs->early_out ?? "" }}" placeholder="8" id="">
                                                <span class="position-absolute text-muted me-5 top-25 end-0">Hours</span>
                                            </div>
                                        </div>
                                        <div class="fv-row">
                                            <label class="col-form-label">Late Out Limit (Hours)</label>
                                            <div class="position-relative">
                                                <input type="number" name="late_out" class="form-control pe-20" value="{{ $prefs->late_out ?? "" }}" data-reset="{{ $prefs->late_out ?? "" }}" placeholder="8" id="">
                                                <span class="position-absolute text-muted me-5 top-25 end-0">Hours</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end d-none btn-toggle mt-10">
                                    @csrf
                                    <input type="hidden" name="type" value="attendance">
                                    <input type="hidden" name="id" value="{{ $prefs->id ?? "" }}">
                                    <button class="btn btn-sm" onclick="form_reset(this)" type="button">Reset</button>
                                    <button class="btn btn-sm btn-primary" type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('view_script')
    <script>

        function form_reset(me){
            var form = $(me).parents("form")
            $(form).find(".btn-toggle").addClass("d-none")
            $(form).find("input").each(function(){
                if($(this).attr("name") != "_token" && $(this).attr("name") != "id" && $(this).attr("name") != "type"){
                    $(this).val($(this).data("reset"))
                }
            })
        }


        $(document).ready(function(){
            $("#form-date input").change(function(){
                $("#form-date .btn-toggle").removeClass("d-none")
            })
            $("#form-attendance input").change(function(){
                $("#form-attendance .btn-toggle").removeClass("d-none")
            })

            @if(Session::has("tab"))
                var triggerEl = $("a[data-bs-toggle='tab'][href='#tab_{{ Session::get("tab") }}_management']")
                bootstrap.Tab.getOrCreateInstance(triggerEl).show()
            @endif
        })
    </script>
@endsection
