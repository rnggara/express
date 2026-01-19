@extends('layouts.template', ['bgWrapper' => "bg-white"])

@section('content')

<div class="container">
    <div class="d-flex flex-column">
        <a href="{{ route("calendar.index") }}" class="d-flex align-items-center mb-3">
            <i class="fa fa-arrow-left me-3 text-primary"></i>
            Kembali ke interview
        </a>
        <div class="card border mb-8">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="symbol symbol-30px symbol-circle symbol-md-70px me-5">
                        <img src="{{ asset($applicant->user_img ?? 'theme/assets/media/avatars/blank.png') }}" alt="image">
                    </div>
                    <div class="d-flex flex-fill flex-column">
                        <span class="fs-3 mb-3 fw-bold">{{ $applicant->name }}</span>
                        <span class="mb-1">{{ $exp->position ?? "" }} {{ "(".($exp->yoe ?? 0)." Tahun)" }}</span>
                        <span class="mb-1">{{ $exp->company ?? "" }}</span>
                        <span class="mb-1">Gaji yang diharapkan Rp. {{ number_format($exp->salary ?? 0, 2, ",", ".") }}</span>
                    </div>
                    <span class="badge badge-yellow">Interview</span>
                </div>
            </div>
        </div>
        <span class="fw-bold mb-8 fs-2">Proses Interview</span>
        @foreach ($interview as $i => $item)
            <div class="accordion accordion-icon-toggle border rounded mb-10" id="kt_accordion_profile{{ $item->id }}">
                <!--begin::Header-->
                <div class="accordion-header align-items-center d-flex justify-content-between p-5" data-bs-toggle="collapse" data-bs-target="#kt_accordion_profile{{ $item->id }}_item_1">
                    <h3 class="fs-4 fw-semibold">
                        <i class="fa fa-video text-dark me-3">
                        </i>
                        Interview {{  $i+1 }}
                    </h3>
                    {{-- <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-primary me-3">Jadwal Ulang</button>
                        <span class="accordion-icon">
                            <i class="fa fa-caret-right text-dark fs-4"></i>
                        </span>
                    </div> --}}
                </div>
                <!--end::Header-->

                <!--begin::Body-->
                <div id="kt_accordion_profile{{ $item->id }}_item_1" class="fs-6 p-5 ps-10 show border-top" data-bs-parent="#kt_accordion_profile{{ $item->id }}">
                    <div class="d-flex flex-column">
                        <span class="fs-3 fw-bold mb-5">Jadwal Interview</span>
                        @php
                            $t1 = date_create(date("Y-m-d")." ".$item->int_start);
                            $t2 = date_create(date("Y-m-d")." ".$item->int_end);

                            $tdiff = date_diff($t1, $t2);
                            $duration = $tdiff->format("%h jam %i menit");
                            $uAssign = $user_assign->where("id", $item->int_officer)->first();
                        @endphp
                        <div class="border rounded mb-5 p-5 d-flex flex-column">
                            <div class="d-flex align-items-center mb-5">
                                <div class="symbol symbol-50px me-5">
                                    <div class="symbol-label bg-light-primary" style="background-image:url('{{ asset($uAssign->user_img ?? "theme/assets/media/avatars/blank.png") }}')"></div>
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $uAssign->name ?? "-" }}</span>
                                    <span>{{ $mComp->company_name }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-computer text-dark fs-3 me-5"></i>
                                <span>{{ $item->int_type == 1 ? "Interview Online" : "Interview Offline" }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="far fa-calendar text-dark fs-3 me-5"></i>
                                <span>@dateId($item->int_date)</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="far fa-clock text-dark fs-3 me-5"></i>
                                <span>{{ date("H:i", strtotime($item->int_start)) }} - {{ date("H:i", strtotime($item->int_end)) }} {{ "($duration)" }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fa fa-link text-dark fs-3 me-5"></i>
                                <span>Link room meeting : <a href="#">{{ $item->int_link }}</a></span>
                            </div>
                            <div class="d-flex mb-3 flex-column">
                                <span>Deskripsi :</span>
                                {!! $item->int_descriptions !!}
                            </div>
                            <div class="d-flex mb-3 flex-column">
                                <span>Lampiran :</span>
                                -
                            </div>
                        </div>
                        <form action="{{ route('calendar.review') }}" method="post" enctype="multipart/form-data">
                            <div class="d-flex flex-column">
                                <span class="fs-3 fw-bold mb-5">Catatan</span>
                                <textarea name="interview_notes" id="interview_notes{{ $item->id }}" class="form-control ck-editor" cols="30" rows="10">{!! $item->int_notes ?? "" !!}</textarea>
                                <div class="fv-row p-5 upload-file border border-top-0 mb-5">
                                    <label for="task-file{{$item->id}}" data-toggle="upload_file"
                                        class="btn btn-outline btn-outline-primary btn-sm">
                                        <i class="fa fa-file"></i>
                                        Add File
                                    </label>
                                    <span class="upload-file-label {{ !empty($item->int_file_name) ? "text-primary" : "" }}">{{ $item->int_file_name ?? "Max 25 mb" }}</span>
                                    <input id="task-file{{$item->id}}" style="display: none" data-toggle="upload_file"
                                        name="attachment_task" type="file" />
                                </div>
                                <div class="d-flex justify-content-end align-items-center">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                    <button type="button" class="btn btn-primary btn-submit">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end::Body-->
            </div>
        @endforeach
        <button type="button" class="btn btn-outline btn-outline-primary" onclick="addSchedule()">
            <i class="fa fa-plus-circle"></i>
            Tambah Jadwal Interview
        </button>
    </div>
</div>

<form action="{{ route("calendar.add") }}" method="post">
    <div class="modal fade" tabindex="-1" data-backdrop="static" role="dialog" id="modalAddInterview">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

            </div>
        </div>
    </div>
</form>

@endsection

@section('custom_script')
    <script>

        function updateStartTime(ind){
            $("#modalAddInterview [name=end_time]").find("option").each(function(){
                var $ind = $(this).data("index")
                if($ind <= ind){
                    $(this).prop('disabled', true)
                } else {
                    $(this).prop('disabled', false)
                }
            })
        }

        function addSchedule(){

            var date = new Date()
            var type = "n"
            var id = "{{ $job_applicant->id }}"
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

            $.ajax({
                url : "{{ route("calendar.index")."?a=modal" }}",
                type : "get",
                dataType : "json",
                data : {
                    id : id,
                    type : type,
                    e : "new",
                    date : date.toLocaleString("en-US")
                }
            }).then(function (resp) {
                $("#modalAddInterview .modal-content").html(resp.view)
                $("#modalAddInterview").modal("show")
                $("#modalAddInterview [data-control=select2]").select2()
                $("#modalAddInterview .ck-editor").each(function(){
                    var _id = $(this).attr("id")
                    ClassicEditor.create(this).then(function(editor){
                        _editor[_id] = editor
                    })
                })

                $("#modalAddInterview .tempusDominus").each(function(){
                    var _id = $(this).attr("id")
                    var _dt = new tempusDominus.TempusDominus(document.getElementById(_id), {
                        useCurrent: false,
                        display : {
                            viewMode: "calendar",
                            components: {
                                decades: true,
                                year: true,
                                month: true,
                                date: true,
                                hours: false,
                                minutes: false,
                                seconds: false
                            }
                        },
                        localization: {
                            locale: "id",
                            startOfTheWeek: 1,
                            format: "dddd, d MMMM yyyy"
                        }
                    });
                })

                $("#modalAddInterview [name=date]").val(date.toLocaleString("id-ID", options))

                $("#modalAddInterview [name=start_time]").on("change", function(){
                    var opt = $(this).find("option:selected")
                    var ind = $(opt).data("index")
                    updateStartTime(ind)
                })

                var opt = $("#modalAddInterview [name=start_time]").find("option:selected")
                var ind = $(opt).data("index")
                updateStartTime(ind)
            })
        }

        function show_toast(msg){
            toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toastr-top-center",
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

            toastr.success(msg);
        }

        $(document).ready(function(){
            $("[data-toggle=bookmark]").click(function(){
                var $i = $(this).find("i")
                var id = $(this).data("id")
                console.log("hi")
                $.ajax({
                    url : "{{ route("search_talent.bookmark") }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id
                    }
                }).then(function(resp){
                    if(resp.bookmark){
                        $i.removeClass("far")
                        $i.addClass("fa")
                        $i.next().text("Bookmarked")
                        show_toast("Ditambahkan ke bookmark")
                    } else {
                        $i.removeClass("fa")
                        $i.addClass("far")
                        $i.next().text("Bookmark")
                        show_toast("Dihapus dari bookmark")
                    }
                })
            })

            $(".btn-submit").each(function(){
                $(this).click(function(){
                    var textarea = $(this).parents("form").find("textarea")
                    var id = $(textarea).attr("id")
                    var notes = _editor[id].getData()
                    if(notes == ""){
                        Swal.fire("Catatan kosong", "Harap mengisi catatan", "error")
                    } else {
                        $(this).parents("form").submit()
                    }
                })
            })

        })
    </script>
@endsection
