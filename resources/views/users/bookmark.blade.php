@extends('layouts.template')

@section('content')
    <div class="d-flex flex-column">
        <h3 class="card-title mb-5">Bookmark</h3>
        <div class="row">
            @foreach ($job_list as $i => $item)
                @php
                    $jtype = $job_type->where("id", $item->job_type)->first();
                    $d1 = date_create(date("Y-m-d"));
                    $d2 = date_create(date("Y-m-d", strtotime($item->created_at)));
                    $d = date_diff($d1, $d2);
                    $days = $d->format("%a");
                    $months = $d->format("%m");
                    $years = $d->format("%y");
                    $nlabel = "Today";
                    if($days > 0){
                        $nlabel = "$days days ago";
                    }
                    if($months > 0){
                        $nlabel = "$months months ago";
                    }
                    if($years > 0){
                        $nlabel = "$years years ago";
                    }
                    $com = $companies->where("id", $item->company_id)->first();
                    $pr = $province->where("id", $com->prov_id ?? null)->first();
                    $ct = $city->where("id", $com->city_id ?? null)->first();
                    $booked = $bookmarked->where("job_id", $item->id)->first();
                @endphp
                <div class="col-md-6 col-sm-12 mb-5 mb-md-0">
                    <div class="card card-custom gutter-b card-stretch">
                        <div class="card-body">
                            <div class="d-flex justify-content-end position-absolute" style="right: 0px">
                                <button type="button" class="btn btn-icon btn-sm me-5 btn-bookmark" data-bookmark="{{ empty($booked) ? "false" : "true" }}" data-id="{{ $item->id }}">
                                    <i class="{{ empty($booked) ? "far" : "fa" }} fa-bookmark text-primary bookmark"></i>
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-11">
                                    <div class="d-flex justify-content-between cursor-pointer rounded" onclick="javascript:;location.href='{{ route('applicant.job.detail', $item->id) }}'">
                                        <div class="d-flex">
                                            <div class="symbol me-5">
                                                <img src="{{ asset($com->icon ?? "") }}" alt="" class="w-50px h-auto">
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="font-size-h3 fw-bold">{{ $item->position }}</span>
                                                <span class="fw-semibold">{{ $com->company_name ?? "-" }}</span>
                                                <span class="fw-semibold mb-3">{{ $ct->name ?? $item->placement }} - {{ $pr->name ?? "" }}</span>
                                                <span class="mb-3">
                                                    <i class="text-dark me-3 fa fa-clock"></i>
                                                    {{ $jtype->name ?? "Fulltime" }}
                                                </span>
                                                <span class="mb-3">
                                                    <i class="text-dark me-3 fa fa-suitcase"></i>
                                                    {{ $item->yoe }} years
                                                </span>
                                                <span class="mb-3">
                                                    <i class="text-dark me-3 fas fa-dollar-sign"></i>
                                                    {{ $item->salary_min }}{{ !empty($item->salary_max) ? "- $item->salary_max" : "" }} /month
                                                </span>
                                            </div>
                                        </div>
                                        <span class="text-primary">{{ $nlabel }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @if ($job_list->count() == 0)
            <div class="col-12 bg-white rounded p-5">
                <div class="d-flex justify-content-center">
                    Tidak ada Job yang di bookmark
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('custom_script')
    @include('job._bookmark')
    <script>
        function change_bg(_i){
            if($(_i).hasClass("fa")){
                $(_i).addClass("far")
                $(_i).removeClass("fa")
            } else {
                $(_i).addClass("fa")
                $(_i).removeClass("far")
            }
        }

        $(".btn-bookmark").on( "mouseenter", function(){
                var _i = $(this).find("i")
                change_bg(_i)
            }).on( "mouseleave", function(){
                var _i = $(this).find("i")
                change_bg(_i)
            })

            $(".btn-bookmark").click(function(){
                var _i = $(this).find("i")
                var _id = $(this).data("id")
                var _bookmark = $(this).data('bookmark')
                if(_bookmark){
                    Swal.fire({
                        html: `Hapus dari bookmark?`,
                        icon: "question",
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: "Ya",
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: 'btn btn-danger'
                        }
                    }).then((resp) => {
                        if(resp.value){
                            bookmark(this, _id, function(resp){
                                if(!resp.booked){
                                    location.reload()
                                }
                            })
                        }
                    });
                }
                // bookmark(this, _id, function(resp){
                //     if(resp.booked){
                //         if($(_i).hasClass("fa")){
                //             $(_i).addClass("far")
                //             $(_i).removeClass("fa")
                //         } else {
                //             $(_i).addClass("fa")
                //             $(_i).removeClass("far")
                //         }

                //         show_toast("Ditambahkan ke bookmark")
                //     } else {
                //         if($(_i).hasClass("fa")){
                //             $(_i).addClass("far")
                //             $(_i).removeClass("fa")
                //         } else {
                //             $(_i).addClass("fa")
                //             $(_i).removeClass("far")
                //         }

                //         show_toast("Dihapus dari bookmark")
                //     }
                // })
            })
    </script>
@endsection
