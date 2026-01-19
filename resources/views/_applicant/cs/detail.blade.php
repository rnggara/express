@extends('layouts.template')

@section('content')
    <div class="d-flex flex-column">
        <div class="mb-8 rounded d-flex flex-column min-h-150px mh-300px rounded bgi-position-center bgi-size-cover bgi-no-repeat justify-content-end" style="background-image: url({{ asset($company->banner ?? "images/default-frame.png") }});">
            <div class="mx-auto mx-md-0 align-items-center justify-content-center bg-white border d-flex flex-column h-150px h-250px p-5 position-relative rounded w-150px w-250px" style="left: 10px;top: 50px;">
                <div class="symbol mb-5">
                    <img src="{{ asset($company->icon) }}" class="w-100px w-md-50px h-auto">
                </div>
                <span class="fw-bold mb-5">{{$company->company_name}}</span>
                <span class="mb-5">
                    <i class="fa fa-map-marker-alt text-dark"></i>
                    {{ucwords(strtolower($prov->name ?? "-"))}}, Indonesia
                </span>
                <span class="text-warning fw-bold">
                    {{number_format($overall_avg, 1)}}
                    <i class="bi bi-star-fill text-warning"></i>
                </span>
            </div>
            <div class="bg-white d-flex flex-column flex-md-row justify-content-between p-7 rounded-bottom">
                <div class="align-items-center d-flex mt-15 mt-md-0 ms-0 ms-md-auto mb-5 mb-md-0" style="margin-left: 20rem">
                    <a href="@auth{{ route("app.cs.detail", $company->id)."?v=tentang" }}@else{{ route("app.cs_guest.detail", $company->id)."?v=tentang" }}@endauth" class="{{$menu_view == "tentang" ? "active" : ""}} me-5 text-dark text-active-primary text-hover-primary">Tentang Perusahaan</a>
                    <a href="@auth{{ route("app.cs.detail", $company->id)."?v=review" }}@else{{ route("app.cs_guest.detail", $company->id)."?v=review" }}@endauth" class="{{$menu_view == "review" ? "active" : ""}} me-5 text-dark text-active-primary text-hover-primary">Review</a>
                    <a href="@auth{{ route("app.cs.detail", $company->id)."?v=job_ad" }}@else{{ route("app.cs_guest.detail", $company->id)."?v=job_ad" }}@endauth" class="{{$menu_view == "job_ad" ? "active" : ""}} me-5 text-dark text-active-primary text-hover-primary">Lowongan</a>
                </div>
                @auth
                <a href="{{ route('app.cs.review', $company->id) }}" class="btn btn-primary">Tulis Review</a>
                @endauth
            </div>
        </div>
        @include("_applicant.cs.detail_$menu_view")
    </div>
@endsection

@section('custom_script')
    <script>
        function circle_chart() {
            var e = document.querySelectorAll(".circle-chart");
            [].slice.call(e).map((function (e) {
                var t = parseInt(KTUtil.css(e, "height"));
                if (e) {
                    var a = e.getAttribute("data-kt-chart-color"),
                        v = e.getAttribute("data-kt-value"),
                        f = e.getAttribute("data-formatter"),
                        o = KTUtil.getCssVariableValue("--bs-" + a),
                        r = KTUtil.getCssVariableValue("--bs-" + a + "-light"),
                        s = KTUtil.getCssVariableValue("--bs-dark");
                    new ApexCharts(e, {
                        series: [v],
                        chart: {
                            fontFamily: "inherit",
                            height: 110,
                            type: "radialBar"
                        },
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    margin: 0,
                                    size: "55%",
                                    // background : "#D8D8D8"
                                },
                                dataLabels: {
                                    showOn: "always",
                                    name: {
                                        show: !1,
                                        fontWeight: "700"
                                    },
                                    value: {
                                        color: s,
                                        fontSize: "16px",
                                        fontWeight: "700",
                                        offsetY: 7,
                                        show: !0,
                                        formatter: function (e) {
                                            return e + f
                                        }
                                    }
                                },
                                track: {
                                    background: r,
                                    strokeWidth: "170%",
                                    opacity: .5
                                }
                            }
                        },
                        colors: [o],
                        stroke: {
                            lineCap: "round",
                            width : 1
                        },
                        labels: ["Progress"]
                    }).render()
                }
            }))
        }

        function goToPage(me){
            var url = $(me).data("url")
            list_review(url)
        }

        @if($menu_view == "job_ad")
            function list_job(url = null){
                var route = url == null ? "@auth{{route("app.cs.job_ads", $company->id)}}@else{{route("app.cs_guest.job_ads", $company->id)}}@endauth" : url

                $("#job-ad-list").html("")
                $.ajax({
                    url : route,
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{csrf_token()}}",
                    }
                }).then(function(resp){
                    $("#job-ad-list").html(resp.view)

                    $(".pagination li.page-item.page").remove()

                    var page_item = ""
                    for (let i = 1; i <= resp.lastPage; i++) {
                        page_item += `<li class="page-item page"><a href="javascript:;" onclick="goToPage(this)" class="page-link">${i}</a></li>`
                    }

                    $(page_item).insertAfter($(".pagination li.page-item.previous"))

                    $("#job-ad-list .rating .rating-label.me-3.checked").each(function(){
                        $(this).find("i").addClass("text-success")
                    })

                    $(".pagination li.page-item.page").find("a").removeClass("active")
                    $(".pagination li.page-item.page").eq(resp.currentPage - 1).find("a").addClass("active")
                    $(".pagination li.page-item.page").each(function(index){
                        var url = "@auth{{route("app.cs.review_list", $company->id)}}@else{{route("app.cs_guest.review_list", $company->id)}}@endauth?page=" + (index + 1)
                        console.log(url)
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
        @endif

        function list_review(url = null){
            var filter = $("#by_rating").val()
            var kategori = $("#by_kategori").val()

            var route = url == null ? "@auth{{route("app.cs.review_list", $company->id)}}@else{{route("app.cs_guest.review_list", $company->id)}}@endauth" : url

            $("#review-list").html("")
            $.ajax({
                url : route,
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{csrf_token()}}",
                    filter : filter,
                    kategori : kategori
                }
            }).then(function(resp){
                $("#review-list").html(resp.view)

                $(".pagination li.page-item.page").remove()

                var page_item = ""
                for (let i = 1; i <= resp.lastPage; i++) {
                    page_item += `<li class="page-item page"><a href="javascript:;" onclick="goToPage(this)" class="page-link">${i}</a></li>`
                }

                $(page_item).insertAfter($(".pagination li.page-item.previous"))

                $("#review-list .rating .rating-label.me-3.checked").each(function(){
                    $(this).find("i").addClass("text-success")
                })

                $(".pagination li.page-item.page").find("a").removeClass("active")
                $(".pagination li.page-item.page").eq(resp.currentPage - 1).find("a").addClass("active")
                $(".pagination li.page-item.page").each(function(index){
                    var url = "@auth{{route("app.cs.review_list", $company->id)}}@else{{route("app.cs_guest.review_list", $company->id)}}@endauth?page=" + (index + 1)
                    console.log(url)
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
            circle_chart()
            @if($menu_view == "review")
                list_review()
            @elseif($menu_view == "job_ad")
                list_job()
            @endif
        })
    </script>
@endsection

