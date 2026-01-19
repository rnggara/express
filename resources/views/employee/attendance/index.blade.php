@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Mobile Attendance</h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <a href="{{ route('emp.mt.report') }}" class="btn btn-primary">
                        Attendance Report
                    </a>
                    <a href="{{ route('emp.mt.report_personal') }}" class="btn btn-light-primary">
                        Personal Attendance Report
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12 mx-auto text-center mb-5">
                    <div class="d-flex justify-content-center align-items-center position-relative">
                        <span class="fs-3 me-5">
                            Attendance {{ $data->periode_from == $data->periode_to ? $data->periode_from : "$data->periode_from - $data->periode_to" }}
                        </span>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalExport">
                            <i class="la la-filter"></i>
                            Filter
                        </button>
                        <button type="button" id="btn-export" class="btn btn-success position-absolute end-0">
                            <i class="la la-file-excel"></i>
                            Export
                        </button>
                    </div>
                </div>
                <div class="col-12">
                    <table class="table table-bordered table-responsive-xl w-100 display" data-page-length="100">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Date</th>
                                @foreach ($data->columns as $item)
                                    <th class="text-center">{{ ucwords($item) }}</th>
                                @endforeach
                                <th class="text-center">Departement</th>
                                <th class="text-center">User</th>
                                <th class="text-center">Clock In</th>
                                <th class="text-center">Break Out</th>
                                <th class="text-center">Break In</th>
                                <th class="text-center">Clock Out</th>
                                <th class="text-center">Map</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $_loc = [
                                    1 => "Office",
                                    "Customer",
                                    "Home",
                                    "Anywhere"
                                ];
                                $num = 1;
                            @endphp
                            @foreach ($emp as $i => $item)
                                @php
                                    $uemp = $user->where('company_id', $item->company_id)->where("emp_id", $item->id)->first();
                                    $eclockin = null;
                                    $eclockout = null;
                                    $breakout = null;
                                    $breakin = null;
                                    $_att = [];

                                    if(!empty($uemp)){
                                        $_att = $dataAttendance[$uemp->id] ?? [];
                                    }
                                @endphp
                                @foreach ($_att as $attDate => $att)
                                    @php
                                        $eclockin = $att['clock_in'][0] ?? [];

                                        $clockin_time = "";

                                        $tooltip = "";

                                        if(!empty($eclockin)){
                                            $ctime = date("H:i:s", strtotime($eclockin->clock_time));
                                            $stime = date("H:i:s", strtotime($eclockin->created_at));

                                            $cexp = explode(":", $ctime);
                                            $sexp = explode(":", $stime);

                                            $clockin_time = $ctime;

                                            if($cexp[0] != $sexp[0]){
                                                // dd($diff);
                                                $msg = "";
                                                if($cexp[1] == $sexp[1]){
                                                    $msg = "Outside Timezone";
                                                } else {
                                                    $msg = "Supicious attendance";
                                                }
                                                $tooltip = "<div class='d-flex flex-column'><span>Server : $stime</span><span>Mobile : $ctime</span><span>$msg</span></div>";
                                            } else {
                                                if($cexp[1] != $sexp[1]){
                                                    $clockin_time = $stime;
                                                    $tooltip = "<div class='d-flex flex-column'><span>Server : $stime</span><span>Mobile : $ctime</span><span>Minute missmatch</span></div>";
                                                }
                                            }
                                        }


                                        $breakout = $att['break_out'][0] ?? [];
                                        $breakin = !empty($att['break_in']) ? end($att['break_in']) : [];
                                        $eclockout = !empty($att['clock_out']) ? end($att['clock_out']) : [];
                                    @endphp
                                <tr>
                                    <td align="center">{{ $num++ }}</td>
                                    <td>{{ date("d-m-Y", strtotime($attDate)) }}</td>
                                    @foreach ($data->columns as $attCols)
                                        <td>{{ $attCols == "name" ? $item->emp_name : $item->email }}</td>
                                    @endforeach
                                    <td align="center">{{ $item->div->name ?? "-" }}</td>
                                    <td align="center">
                                        @if (!empty($uemp))
                                            {{ $uemp->name }}
                                        @else
                                            <button type="button" onclick="user_assign({{ $item->id }})" data-toggle="modal" data-target="#assignUser" class="btn btn-light-danger btn-sm">No user assigned.<br>Click here to assign user.</button>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if (empty($eclockin))
                                            <button type="button" class="btn btn-light-danger btn-sm">
                                                <span class="font-weight-boldest">-</span>
                                            </button>
                                        @else
                                            <button type="button" onclick="view('clock_in', {{ $eclockin->id }})" class="btn btn-{{$eclockin->btn_class ?? "primary"}} btn-sm">
                                                <i class="fa fa-eye"></i>
                                                {{ $clockin_time }} {{$_loc[$eclockin->lokasi] ?? "Anywhere"}}
                                            </button>
                                            @if (!empty($tooltip))
                                                <button type="button" data-bs-toggle="tooltip" data-bs-html="true" title="{{ $tooltip }}" class="btn btn-warning btn-sm btn-icon mt-2">
                                                    <i class="fi fi-rr-info"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if (empty($breakout))
                                            <button type="button" class="btn btn-light-danger btn-sm">
                                                <span class="font-weight-boldest">-</span>
                                            </button>
                                        @else
                                            <button type="button" onclick="view('clock_in', {{ $breakout->id }})" class="btn btn-{{$breakout->btn_class ?? "primary"}} btn-sm">
                                                <i class="fa fa-eye"></i>
                                                {{ date("H:i:s", strtotime($breakout->clock_time)) }} {{$_loc[$breakout->lokasi] ?? "Anywhere"}}
                                            </button>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if (empty($breakin))
                                            <button type="button" class="btn btn-light-danger btn-sm">
                                                <span class="font-weight-boldest">-</span>
                                            </button>
                                        @else
                                            <button type="button" onclick="view('clock_in', {{ $breakin->id }})" class="btn btn-{{$breakin->btn_class ?? "primary"}} btn-sm">
                                                <i class="fa fa-eye"></i>
                                                {{ date("H:i:s", strtotime($breakin->clock_time)) }} {{$_loc[$breakin->lokasi] ?? "Anywhere"}}
                                            </button>
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if (empty($eclockout))
                                            <button type="button" class="btn btn-light-danger btn-sm">
                                                <span class="font-weight-boldest">-</span>
                                            </button>
                                        @else
                                            <button type="button" onclick="view('clock_out', {{ $eclockout->id }})" class="btn btn-{{$eclockout->btn_class ?? "primary"}} btn-sm">
                                                <i class="fa fa-eye"></i>
                                                {{ date("H:i:s", strtotime($eclockout->clock_time)) }} {{$_loc[$eclockout->lokasi] ?? "Anywhere"}}
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route("attendance.map", $item->id) }}" class="btn btn-icon btn-sm btn-primary">
                                            <i class="fa fa-map-location-dot"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalView" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">View Attendance</h1>
                    <button type="button" class="btn btn-icon text-hover-primary" data-bs-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="fa fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignUser" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Assign User</h1>
                    <button type="button" class="btn btn-icon text-hover-primary" data-bs-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('emp.mt.assign_user') }}" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user" class="col-form-label">User</label>
                            <select name="user" class="form-control select2" id="user" required data-placeholder="Select User">
                                <option value=""></option>
                                @foreach ($userAssign as $val)
                                    <option value="{{ $val->id }}">{{ "$val->name [$val->username]" }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <input type="hidden" name="id" id="id-emp">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalExport" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title">Filter</h1>
                    <button type="button" class="btn btn-icon text-hover-primary" data-bs-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="fa fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('emp.mt.index') }}" method="post">
                    <div class="modal-body">
                        <div class="fv-row">
                            <label for="" class="col-form-label">Periode</label>
                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <input type="text" id="periode-from" class="form-control tempusDominus" data-mask="99/99/9999" data-type="calendar" name="periode_from" value="{{ $data->periode_from }}">
                                <span class="mx-0 mx-md-3">Sampai</span>
                                <input type="text" id="periode-to" class="form-control tempusDominus" data-mask="99/99/9999" data-type="calendar" name="periode_to" value="{{ $data->periode_to }}">
                            </div>
                        </div>
                        <div class="fv-row">
                            <label for="" class="col-form-label">Clock In</label>
                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <input type="text" id="clockin-from" value="{{ $data->clockin_from ?? "" }}" class="form-control tempusDominus" data-mask="99:99" data-type="clock" name="clockin_from">
                                <span class="mx-0 mx-md-3">Sampai</span>
                                <input type="text" id="clockin-to" value="{{ $data->clockin_to ?? "" }}" class="form-control tempusDominus" data-mask="99:99" data-type="clock" name="clockin_to">
                            </div>
                        </div>
                        <div class="fv-row">
                            <label for="" class="col-form-label">Break Out</label>
                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <input type="text" id="breakout-from" value="{{ $data->breakout_from ?? "" }}" class="form-control tempusDominus" data-mask="99:99" data-type="clock" name="breakout_from">
                                <span class="mx-0 mx-md-3">Sampai</span>
                                <input type="text" id="breakout-to" value="{{ $data->breakout_to ?? "" }}" class="form-control tempusDominus" data-mask="99:99" data-type="clock" name="breakout_to">
                            </div>
                        </div>
                        <div class="fv-row">
                            <label for="" class="col-form-label">Break In</label>
                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <input type="text" id="breakin-from" value="{{ $data->breakin_from ?? "" }}" class="form-control tempusDominus" data-mask="99:99" data-type="clock" name="breakin_from">
                                <span class="mx-0 mx-md-3">Sampai</span>
                                <input type="text" id="breakin-to" value="{{ $data->breakin_to ?? "" }}" class="form-control tempusDominus" data-mask="99:99" data-type="clock" name="breakin_to">
                            </div>
                        </div>
                        <div class="fv-row">
                            <label for="" class="col-form-label">Clock Out</label>
                            <div class="d-flex flex-column flex-md-row align-items-md-center">
                                <input type="text" id="clockout-from" value="{{ $data->clockout_from ?? "" }}" class="form-control tempusDominus" data-mask="99:99" data-type="clock" name="clockout_from">
                                <span class="mx-0 mx-md-3">Sampai</span>
                                <input type="text" id="clockout-to" value="{{ $data->clockout_to ?? "" }}" class="form-control tempusDominus" data-mask="99:99" data-type="clock" name="clockout_to">
                            </div>
                        </div>
                        <div class="fv-row">
                            <label for="" class="col-form-label">Nama</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="columns[]" {{ in_array("name", $data->columns) ? "checked" : "" }} value="name" id="ckName" />
                                <label class="form-check-label" for="ckName">
                                    Show
                                </label>
                            </div>
                        </div>
                        <div class="fv-row">
                            <label for="" class="col-form-label">Email</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="columns[]" {{ in_array("email", $data->columns) ? "checked" : "" }} value="email" id="ckEmail" />
                                <label class="form-check-label" for="ckEmail">
                                    Show
                                </label>
                            </div>
                        </div>
                        <div class="fv-row" id="dep-export">
                            <label for="" class="col-form-label">Departemen</label>
                            <select name="departements" class="form-select" data-control="select2" data-allow-clear="true" data-dropdown-parent="#dep-export" data-placeholder="Semua">
                                <option value=""></option>
                                @foreach ($divisions as $idDiv => $name)
                                    <option value="{{ $idDiv }}" {{ $data->departements == $idDiv ? "SELECTED" : "" }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="fv-row" id="loc-export">
                            <label for="" class="col-form-label">Lokasi</label>
                            <select name="locations" class="form-select" data-control="select2" data-allow-clear="true" data-dropdown-parent="#loc-export" data-placeholder="Semua">
                                <option value=""></option>
                                @foreach ($locs as $item)
                                    <option value="off-{{ $item->id }}" {{ $data->locations == "off-$item->id" ? "SELECTED" : "" }}>{{ $item->name }}</option>
                                @endforeach
                                <option value="tp-2" {{ $data->locations == "tp-2" ? "SELECTED" : "" }}>Customer</option>
                                <option value="tp-3" {{ $data->locations == "tp-3" ? "SELECTED" : "" }}>Home</option>
                                <option value="tp-4" {{ $data->locations == "tp-4" ? "SELECTED" : "" }}>Anywhere</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        function view(type, id){
            $("#modalView").modal("show")
            $("#modalView .modal-body").html(`<div class="spinner spinner-primary spinner-lg mr-15 spinner-center"></div>`)
            const url = `{{ route("emp.mt.view") }}/${type}/${id}`;
            console.log(url)
            $.ajax({
                url : url,
                type : "get",
            }).then(function(resp){
                $("#modalView .modal-body").html(resp)
            })
        }

        function user_assign(id){
            $("#id-emp").val(id)
        }

        $(document).ready(function(){
            var table = $("table.display").DataTable({
                "search": true,
                dom: '<"d-flex align-items-center justify-content-md-end"f>rt<"d-flex align-items-center justify-content-md-between"ip>',
                buttons: [
                    {
                        extend: 'excel',
                        text: "<i class='fi fi-rr-file-excel fs-3'></i>",
                        titleAttr: 'Export to Excel',
                        className : "btn-success btn-icon btn-sm"
                    },
                ]
            })

            $("#btn-export").click(function(){
                table.button(0).trigger()
            })

            $("select.select2").select2({
                width : "100%"
            })

            $("#btn-go").click(function(){
                const d = $("#d").val()
                const t = $("#t").val()
                location.href = `{{ route("emp.mt.index") }}?d=${d}&t=${t}`
            })

            $(".tempusDominus").each(function(){
                var mask = $(this).data("mask")
                var type = $(this).data("type")

                Inputmask({
                    "mask" : mask
                }).mask($(this))

                $(this).each(function(){
                    var _id = $(this).attr("id")
                    var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                        display : {
                            viewMode: type,
                            components: {
                                decades: type == "calendar" ? true : false,
                                year: type == "calendar" ? true : false,
                                month: type == "calendar" ? true : false,
                                date: type == "calendar" ? true : false,
                                hours: type == "calendar" ? false : true,
                                minutes: type == "calendar" ? false : true,
                                seconds: type == false
                            }
                        },
                        localization: {
                            locale: "id",
                            startOfTheWeek: 1,
                            format: type == "calendar" ? "dd/MM/yyyy" : "HH:mm"
                        }
                    });
                })
            })
        })
    </script>
@endsection
