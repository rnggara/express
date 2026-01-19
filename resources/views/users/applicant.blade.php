@extends('layouts.template')

@section('content')
    <div class="card card-custom bg-transparent d-md-inline d-none">
        <div class="card-header border-0">
            <h3 class="card-title">Lamaran Saya</h3>
        </div>
        <div class="card-body">
            <div class="d-flex flex-column">
                <form action="" id="form-filter">
                    <div class="d-flex align-items-center mb-10">
                        <!--begin::Menu wrapper-->
                        <div class="me-5">
                            <!--begin::Toggle-->
                            <button type="button" class="btn btn btn-outline btn-outline-dark bg-white rotate" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                <i class="fa fa-filter me-3"></i>
                                Filters
                                <span class="fa fa-caret-right fs-3 rotate-90 ms-3 me-0"></span>
                            </button>
                            <!--end::Toggle-->

                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">Status Lamaran</div>
                                </div>
                                <!--end::Menu item-->

                                <!--begin::Menu item-->
                                <div class="menu-item px-3 mb-3">
                                    <div class="form-check mx-3">
                                        <input class="form-check-input" type="checkbox" name="filter[]" value="0" id="ckFTerkirim" />
                                        <label class="form-check-label" for="ckFTerkirim">
                                            Terkirim
                                        </label>
                                    </div>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 mb-3">
                                    <div class="form-check mx-3">
                                        <input class="form-check-input" type="checkbox" name="filter[]" value="1" id="ckFReview" />
                                        <label class="form-check-label" for="ckFReview">
                                            Review
                                        </label>
                                    </div>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 mb-3">
                                    <div class="form-check mx-3">
                                        <input class="form-check-input" type="checkbox" name="filter[]" value="3" id="ckFInterview" />
                                        <label class="form-check-label" for="ckFInterview">
                                            Interview
                                        </label>
                                    </div>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 mb-3">
                                    <div class="form-check mx-3">
                                        <input class="form-check-input" type="checkbox" name="filter[]" value="4" id="ckFLolos" />
                                        <label class="form-check-label" for="ckFLolos">
                                            Lolos
                                        </label>
                                    </div>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 mb-3">
                                    <div class="form-check mx-3">
                                        <input class="form-check-input" type="checkbox" name="filter[]" value="5" id="ckFGagal" />
                                        <label class="form-check-label" for="ckFGagal">
                                            Gagal
                                        </label>
                                    </div>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3 mb-3">
                                    <div class="form-check mx-3">
                                        <input class="form-check-input" type="checkbox" name="filter[]" value="-1" id="ckFBatal" />
                                        <label class="form-check-label" for="ckFBatal">
                                            Batal
                                        </label>
                                    </div>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                        </div>
                        <!--end::Dropdown wrapper-->
                        <span class="me-3">Sort By :</span>
                        <div>
                            <select name="sort_by" id="sort_by" class="form-select" data-control="select2">
                                <option value="">None</option>
                                <option value="asc">Newest to oldest</option>
                                <option value="desc">Oldest to newest</option>
                            </select>
                        </div>
                    </div>
                </form>
                <table class="table display bg-white table-row-bordered" id="table-list">
                    <thead>
                        <tr>
                            <th>Posisi</th>
                            <th>Perusahaan</th>
                            <th>Alamat</th>
                            <th>Status Lamaran</th>
                            {{-- <th>Recruitmen Officer</th> --}}
                            <th>Tanggal Apply</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column d-inline d-md-none mt-5">
        <div class="d-flex align-items-center mb-5">
            <span class="me-5 text-nowrap">Sort By</span>
            <select name="sort_by" id="sort_by_mobile" class="form-select" data-control="select2">
                <option value="">None</option>
                <option value="asc">Newest to oldest</option>
                <option value="desc">Oldest to newest</option>
            </select>
        </div>
        <ul class="pagination mb-5">
            <li class="page-item previous"><a href="javascript:;" onclick="goToPage(this)" class="page-link"><i class="previous"></i></a></li>
            @for ($i = 1; $i <= $job_list->lastPage(); $i++)
                <li class="page-item page"><a href="javascript:;" onclick="goToPage(this)" class="page-link">{{ $i }}</a></li>
            @endfor
            <li class="page-item next"><a href="javascript:;" onclick="goToPage(this)"  class="page-link"><i class="next"></i></a></li>
        </ul>
        <div id="my-applicant-list"></div>
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
                bookmark(this, _id, function(resp){
                    if(resp.booked){
                        if($(_i).hasClass("fa")){
                            $(_i).addClass("far")
                            $(_i).removeClass("fa")
                        } else {
                            $(_i).addClass("fa")
                            $(_i).removeClass("far")
                        }

                        show_toast("Ditambahkan ke bookmark")
                    } else {
                        if($(_i).hasClass("fa")){
                            $(_i).addClass("far")
                            $(_i).removeClass("fa")
                        } else {
                            $(_i).addClass("fa")
                            $(_i).removeClass("far")
                        }

                        show_toast("Dihapus dari bookmark")
                    }
                })
            })

        var table = $("#table-list").DataTable({
            ordering : false,
            ajax : {
                url : "{{ route("account.my_applicant") }}?a=table",
                type : "get",
                dataType : "json"
            },
            "processing": true,
            columns : [
                {data : "posisi"},
                {data : "company"},
                {data : "address"},
                {data : "status"},
                // {data : "officer"},
                {data : "apply_date"},
                {data : "action"},
            ],
            initComplete : function(){
                KTMenu.createInstances();
            }
        })

        function sort(){
            var filter = [];
            $("input[name='filter[]']").each(function(){
                if(this.checked){
                    filter.push($(this).val())
                }
            })

            var route = "{{ route("account.my_applicant") }}?a=table&"+$("#form-filter").serialize()
            table.ajax.url(route).load()
        }

        $("input[name='filter[]']").click(function(){
            sort()
        })

        $("#sort_by").change(function(){
            sort()
        })

        function goToPage(me){
            var url = $(me).data("url")
            pageList(url)
        }

        function pageList(url = null){
            var route = url == null ? "{{ route("account.my_applicant") }}?page=1" : url
            $.ajax({
                url : route,
                type : "get",
                data : {
                    sort_by : $("#sort_by_mobile").val()
                },
                dataType : "json"
            }).then(function(resp){
                $("#my-applicant-list").html(resp.data)

                // $(".pagination li.page-item.page").remove()

                var page_item = ""
                // for (let i = 1; i <= resp.lastPage; i++) {
                //     page_item += `<li class="page-item page"><a href="javascript:;" onclick="pageList(this)" class="page-link">${i}</a></li>`
                // }

                $(page_item).insertAfter($(".pagination li.page-item.previous"))

                $(".pagination li.page-item.page").removeClass("active")
                $(".pagination li.page-item.page").eq(resp.currentPage - 1).addClass("active")
                $(".pagination li.page-item.page").each(function(index){
                    console.log($(this))
                    var url = "{{ route("account.my_applicant") }}?page=" + (index + 1)
                    $(this).find('a').attr("data-url",  url)
                })

                $(".pagination li.page-item.previous").find("a").attr("data-url", resp.prevUrl)
                $(".pagination li.page-item.next").find("a").attr("data-url", resp.nextUrl)

                if(resp.currentPage == 1){
                    $(".pagination li.page-item.previous").addClass("disabled")
                } else {
                    $(".pagination li.page-item.previous").removeClass("disabled")
                }

                if(resp.currentPage == resp.lastPage){
                    $(".pagination li.page-item.next").addClass("disabled")
                } else {
                    $(".pagination li.page-item.next").removeClass("disabled")
                }
            })
        }

        $(document).ready(function(){
            pageList()
        })
    </script>
@endsection
