@extends('_attendance.layout')

@section('view_content')
    <div class="card bg-secondary-crm">
        <div class="card-body">
            <div class="d-flex flex-column">
                <div class="d-flex flex-column mb-5 ms-10">
                    <span class="fw-bold">{{ $reg->emp->emp_name }}</span>
                    <span>{{ $reg->emp->emp_id }}</span>
                </div>
                <div class="row mb-5">
                    <div class="col-6">
                        <div class="card bg-white shadow-none">
                            <div class="card-body">
                                <div class="row">
                                    <div class="border border-bottom-0 border-left-0 border-top-0 col-4">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold mb-3">Total Kehadiran</span>
                                            <span class="text-primary fs-1 fw-bold">{{ $total['hadir'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-4 border-left">
                                        <div class="d-flex flex-column">
                                            <span class="mb-3">Total Terlambat</span>
                                            <span class="text-primary fs-1 fw-bold">{{ $total['terlambat'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex flex-column">
                                            <span class="mb-3">Total Overtime</span>
                                            <span class="text-primary fs-1 fw-bold">{{ $total['overtime'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card bg-white shadow-none">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold mb-3">Total Mangkir</span>
                                            <span class="text-primary fs-1 fw-bold">{{ $total['mangkir'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-4 border-left">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold mb-3">Total Cuti</span>
                                            <span class="text-primary fs-1 fw-bold">{{ $total['cuti'] }}</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold mb-3">Total Sakit</span>
                                            <span class="text-primary fs-1 fw-bold">{{ $total['sakit'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-display-2 bg-white table-row-bordered" data-paging="false" data-ordering="false">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Schedule</th>
                            <th class="border-right-2">Main Reason</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Break Start</th>
                            <th>Break End</th>
                            <th>Ovt In</th>
                            <th>Ovt Out</th>
                            <th>Reason</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schedules as $item)
                            @php
                                $_data = $att_data[$item['date']] ?? [];
                                $lblIn = null;
                                $lblOut = null;
                                $tlIn = "";
                                $tlOut = "";
                                $inH = 0;
                                $inM = 0;
                                $outH = 0;
                                $outM = 0;
                                if(!empty($_data->ovthoursin)){
                                    $inMinute = $_data->ovthoursin;
                                    $inH += floor($_data->ovthoursin / 60);
                                    $inM += $inMinute - ($inH * 60);
                                    $startIn = date("H:i", strtotime($_data->ovtstartin));
                                    $endIn = date("H:i", strtotime($_data->ovtendin));
                                    $tlIn .= "<span>OVT : $startIn - $endIn</span>";
                                }

                                if(!empty($_data->autoOvtIn) && !empty($_data->autoOvtInDetail)){
                                    $inMinute = $_data->autoOvtIn;
                                    $inH += floor($_data->autoOvtIn / 60);
                                    $inM += $inMinute - ($inH * 60);
                                    $startIn = date("H:i", strtotime($_data->autoOvtInDetail['start']));
                                    $endIn = date("H:i", strtotime($_data->autoOvtInDetail['end']));
                                    $tlIn .= "<span>AUTO : $startIn - $endIn</span>";
                                }

                                if(!empty($_data->ovthours)){
                                    $outMinute = $_data->ovthours;
                                    $outH += floor($_data->ovthours / 60);
                                    $outM += $outMinute - ($outH * 60);
                                    $startIn = date("H:i", strtotime($_data->ovtstart));
                                    $endIn = date("H:i", strtotime($_data->ovtend));
                                    $tlOut .= "<span>OVT : $startIn - $endIn</span>";
                                }

                                if(!empty($_data->autoOvtOut) & !empty($_data->autoOvtOutDetail)){
                                    $outMinute = $_data->autoOvtOut;
                                    $outH += floor($_data->autoOvtOut / 60);
                                    $outM += $outMinute - ($outH * 60);
                                    $startIn = date("H:i", strtotime($_data->autoOvtOutDetail['start']));
                                    $endIn = date("H:i", strtotime($_data->autoOvtOutDetail['end']));
                                    $tlOut .= "<span>AUTO : $startIn - $endIn</span>";
                                }

                                if(!empty($inH) || !empty($inM)){
                                    if(!empty($inH)){
                                        $lblIn = "$inH Hours ";
                                    }

                                    if(!empty($inM)){
                                        $lblIn .= "$inM Minutes";
                                    }
                                }

                                if(!empty($outH) || !empty($outM)){
                                    if($outH > 0){
                                        $lblOut = "$outH Hours ";
                                    }

                                    if($outM > 0){
                                        $lblOut .= "$outM Minutes";
                                    }
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $hariId[date("N", strtotime($item['date']))] }}</span>
                                        <span>{{ date("d F Y", strtotime($item['date'])) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $day_name[$_data->day_code ?? ""] ?? "-" }}</span>
                                        <span>{{ $shift_code[$item['shift']] ?? "-" }}</span>
                                    </div>
                                </td>
                                <td class="border border-left-0 border-right border-top-0 border-bottom-dark">
                                    @if (empty($_data))
                                        -
                                    @endif
                                    @foreach ($_data['reasons'] ?? [] as $rp)
                                        @if (isset($rname[$rp['id']]))
                                            <span class="badge badge-outline text-white" style="background-color: {{ $rcolor[$rp['id']] }}">{{ $rname[$rp['id']] }}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ (empty($_data) || (!empty($_data) && $_data->timin == "0000-00-00 00:00:00") || empty($_data->timin)) ? "-" : date("H:i", strtotime($_data->timin))  }}</td>
                                <td>{{ (empty($_data) || (!empty($_data) && $_data->timout == "0000-00-00 00:00:00") || empty($_data->timout)) ? "-" : date("H:i", strtotime($_data->timout))  }}</td>
                                <td>{{ (empty($_data) || (!empty($_data) && $_data->break_start == "0000-00-00 00:00:00") || empty($_data->break_start)) ? "-" : date("H:i", strtotime($_data->break_start))  }}</td>
                                <td>{{ (empty($_data) || (!empty($_data) && $_data->break_end == "0000-00-00 00:00:00") || empty($_data->break_end)) ? "-" : date("H:i", strtotime($_data->break_end))  }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <span>{{ $lblIn ?? "-" }}</span>
                                        @if (!empty($lblIn))
                                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='d-flex flex-column align-items-center'>{!! $tlIn !!}</div>"></span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <span>{{ $lblOut ?? "-" }}</span>
                                        @if (!empty($lblOut))
                                            <span class="fi fi-rr-info" data-bs-toggle="tooltip" data-bs-html="true" title="<div class='d-flex flex-column align-items-center'>{!! $tlOut !!}</div>"></span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if (isset($ovt_tp[$item['date']]))
                                        @foreach ($ovt_tp[$item['date']] as $otp)
                                            <span class="badge badge-primary">Overtime {{ $otp->overtime_type }}</span>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-icon btn-sm" onclick="show_detail('{{ $item['date'] }}')" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_detail">
                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div
        id="kt_drawer_detail"

        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#kt_drawer_detail_button"
        data-kt-drawer-close="#kt_drawer_detail_close"
        data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default : '50%', md: '50%', sm: '500px'}">
        <div class="card rounded-0 w-100" id="drawer-content">

        </div>
    </div>

    <div class="modal fade" tabindex="1" id="modalCancelOvt">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                        <span class="fw-bold fs-3 text-center">Apakah Anda yakin ingin membatalkan pengajuan Overtime ini?</span>
                        <span class="text-center">Melakukan ini dapat mempengaruhi data kehadiran dari employee</span>
                        <form action="{{ route('attendance.approval.approve') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="type" value="overtime">
                            <div class="d-flex align-items-center mt-5">
                                <button type="submit" name="submit" value="cancel" id="delete-url" class="btn btn-white">Lanjutkan</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batalkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="1" id="modalCancelLeave">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                        <span class="fw-bold fs-3 text-center">Apakah Anda yakin ingin membatalkan pengajuan Cuti ini?</span>
                        <span class="text-center">Melakukan ini dapat mempengaruhi data kehadiran dari employee</span>
                        <form action="{{ route('attendance.approval.approve') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="type" value="leave">
                            <div class="d-flex align-items-center mt-5">
                                <button type="submit" name="submit" value="cancel" id="delete-url" class="btn btn-white">Lanjutkan</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Batalkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
    <script src="{{ asset("theme/assets/plugins/custom/formrepeater/formrepeater.bundle.js") }}"></script>
    <script>
        KTDrawer.createInstances();
        var drawerElement = document.querySelector("#kt_drawer_detail");
        var drawer = KTDrawer.getInstance(drawerElement);
        var target = document.querySelector("#drawer-content");
        var blockUI = new KTBlockUI(target);

        function repeater_form(head = null){
            var target = ".repeater"
            if(head != null){
                target = head + " .repeater"
            }
            $(target).each(function(){
                var me = $(this)
                $(this).repeater({
                    initEmpty : false,
                    defaultValues : {
                        'text-input' : ""
                    },
                    show : function(){
                        $(this).slideDown();
                        $(this).find('[data-kt-repeater="select2"]').select2()
                        $(me).find('[data-repeater-item]').each(function(i){
                            $(this).find(".break-num").text(i + 1)
                        })
                        $(this).find('[data-repeater-shift]').last().each(function(){
                            var items = $(this).parents("[data-repeater-item]")
                            $(items).find('[data-repeater-shift]').each(function(){
                                $(this).html("<i class='fa fa-plus text-primary'></i>")
                                $(this).addClass("bg-white")
                                $(this).addClass("btn-lg")
                                $(this).css("backgroun-color", "")
                            })
                        })
                    },
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                        $(me).find('[data-repeater-item]').each(function(i){
                            $(this).find(".break-num").text(i + 1)
                        })
                    },
                    ready: function(){
                        $(me).find('[data-kt-repeater="select2"]').select2()
                        $(me).find('[data-repeater-item]').each(function(i){
                            $(this).find(".break-num").text(i + 1)
                        })

                        $(me).find("[data-repeater-shift]").each(function(){

                        })
                    }
                })
            })
        }

        function batalkanOvt(id, type){
            $("#modalCancelOvt input[name=id]").val(id)
            $("#modalCancelOvt button[name=submit]").val(type)
            $("#modalCancelOvt").modal("show")
        }

        function batalkanLeave(id, type){
            $("#modalCancelLeave input[name=id]").val(id)
            $("#modalCancelLeave button[name=submit]").val(type)
            $("#modalCancelLeave").modal("show")
        }

        function add_bs(me){
            var ck = $(me).prop("checked")
            if(ck){
                $(me).parents("form").find(".break-shift").removeClass("d-none")
            } else {
                $(me).parents("form").find(".break-shift").addClass("d-none")
            }
        }

        function show_detail(date){
            $(target).html("")
            blockUI.block();
            $.ajax({
                url : "{{ route('attendance.correction.detail_edit', $reg->id) }}?date=" + date,
                type : "get",
                dataType : "json"
            }).then(function(resp){
                blockUI.release();
                $(target).html(resp.view)

                repeater_form("#drawer-content")

                $(target).find('select[data-control=select2]').select2()

                $(target).find("input[name=file]").change(function(){
                    var val = $(this).val().split("\\")

                    $(this).parents("div.fv-row").find('[data-file]').text(val[val.length - 1])
                })

                $(target).find("select[name=overtime_type]").change(function(){
                    var me = $(this)
                    if($(this).val() != ""){
                        $.ajax({
                            url : "{{ route("attendance.overtime.index") }}?a=shift_date",
                            type : "get",
                            data : {
                                id : {{ $reg->emp_id }},
                                date : date
                            },
                            dataType : "json",
                        }).then(function(resp){
                            var timin = resp.time_in
                            var timout = resp.time_out
                            $(target).find(" input[name=start_date]").val("")
                            $(target).find(" input[name=end_date]").val("")
                            $(target).find(" input[name=start_date]").prop("disabled", false)
                            $(target).find(" input[name=end_date]").prop("disabled", false)
                            $(target).find(" input[name=start_date]").prop("readonly", false)
                            $(target).find(" input[name=end_date]").prop("readonly", false)
                            if($(me).val() == "in"){
                                $(target).find(" input[name=end_date]").val(timin)
                                $(target).find(" input[name=end_date]").prop("readonly", true)
                            } else if($(me).val() == "out"){
                                $(target).find(" input[name=start_date]").val(timout)
                                $(target).find(" input[name=start_date]").prop("readonly", true)
                            }
                        })
                    }
                })

                $(target).find("select[name='leave[reason]']").change(function(){
                    var id = $(this).val()
                    $(target).find("select[name='leave[leave_used]']").val("").trigger("change")
                    $(target).find("select[name='leave[leave_used]'] option[data-tp]").prop("disabled", true)
                    $(target).find("select[name='leave[leave_used]'] option[data-tp='"+id+"']").prop("disabled", false)
                })

                // $(target).find("select[name=overtime_type]").change(function(){
                //     $(target).find("input[name=start_date]").prop("disabled", false)
                //     $(target).find("input[name=end_date]").prop("disabled", false)

                //     $(target).find("input[name=start_date]").val("")
                //     $(target).find("input[name=end_date]").val("")
                // })

                $(target).find("input[name=start_date]").change(function(){
                    $(target).find("select[name=paid_type]").prop('disabled', false)
                    $(target).find("select[name=departement]").prop('disabled', false)
                    $(target).find("input[name=break_overtime]").prop('disabled', false)
                })
                $(target).find("input[name=end_date]").change(function(){
                    $(target).find("select[name=paid_type]").prop('disabled', false)
                    $(target).find("select[name=departement]").prop('disabled', false)
                    $(target).find("input[name=break_overtime]").prop('disabled', false)
                })

                $(target).find("select[name=paid_type]").change(function(){
                    var row = $(this).parents("div.row")
                    $(target).find("input[name=reference]").prop("disabled", false)
                    if($(this).val() == "money"){
                        $(row).find(".fv-row").removeClass("col-4")
                        $(row).find(".fv-row").addClass("col-6")
                        $(row).find("input[name=day]").prop("disabled", true).parents("div.fv-row").addClass("d-none")
                    } else {
                        $(row).find(".fv-row").removeClass("col-6")
                        $(row).find(".fv-row").addClass("col-4")
                        $(row).find("input[name=day]").prop("disabled", false).parents("div.fv-row").removeClass("d-none")
                    }
                })

                $(target).find(`input[drpicker]`).daterangepicker({
                    locale: {
                        format: "DD/MM/YYYY"
                    }
                });
            })
        }

        $(document).ready(function(){
            @if(\Session::has("drawer"))
                @if(Session::get("drawer") != null)
                    drawer.show()
                    show_detail('{{Session::get("drawer")}}')
                @endif
            @endif
        })
    </script>
@endsection
