<!--begin::Footer-->
<div class="d-flex flex-column bg-white flex-column-fluid">
    <div class="px-md-20">
        <footer class="row py-5 my-5">
            <div class="col-12 col-md-2 mb-5 mb-md-0 ms-n7">
                <div class="d-flex flex-column align-items-start align-items-center">
                    <a href="/" class="d-flex align-items-center mb-3 link-dark text-decoration-none">
                        <div class="symbol symbol-100px">
                            <img src="{{ asset(\Config::get('constants.APP_LOGO')) }}" alt="">
                        </div>
                    </a>
                    <span class="text-muted text-nowrap px-7">{{ \Config::get("constants.COMPANY_NAME") }}</span>
                </div>
            </div>

            <div class="col-6 col-md-2 mb-5 mb-md-0 py-5">
                <h5>Links</h5>
                <ul class="nav flex-column">
                    @foreach ($service_group as $key => $item)
                        <li class="nav-item mb-2">
                            <a href="{{$item}}" target="_blank" class="nav-link p-0 text-muted">Kirim menggunakan {{$key}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-6 col-md-2 mb-5 mb-md-0 py-5">
                <h5>Social Media</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="{{env("INSTAGRAM")}}" target="_blank" class="nav-link p-0 text-muted">Instagram</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{env("FACEBOOK")}}" target="_blank" class="nav-link p-0 text-muted">Facebook</a>
                    </li>
                    {{-- <li class="nav-item mb-2"><a href="javascript:;" onclick="underConstructions()" class="nav-link p-0 text-muted">Artikel</a></li> --}}
                </ul>
            </div>

            <div class="col-6 col-md-2 mb-5 mb-md-0 py-5">
                <h5>Help</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="{{route('faq.page')}}" class="nav-link p-0 text-muted">FAQ</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{route('about.page')}}" class="nav-link p-0 text-muted">About Us</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{route('review.page')}}" class="nav-link p-0 text-muted">Review</a>
                    </li>
                    {{-- <li class="nav-item mb-2"><a href="javascript:;" onclick="underConstructions()" class="nav-link p-0 text-muted">Artikel</a></li> --}}
                </ul>
            </div>

            {{-- <div class="col mb-5 mb-md-0">
                <h5>Untuk Recruiter</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="javascript:;" onclick="underConstructions()" class="nav-link p-0 text-muted">Berpartner dengan kita</a>
                    </li>
                    <li class="nav-item mb-2"><a href="javascript:;" onclick="underConstructions()" class="nav-link p-0 text-muted">Produk & Servis</a></li>
                    <li class="nav-item mb-2"><a href="javascript:;" onclick="underConstructions()" class="nav-link p-0 text-muted">Harga & Paket</a></li>
                    <li class="nav-item mb-2"><a href="javascript:;" onclick="underConstructions()" class="nav-link p-0 text-muted">Untuk Pelamar Kerja</a></li>
                </ul>
            </div> --}}

            {{-- <div class="col mb-5 mb-md-0">
                <h5>Resource</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="javascript:;" onclick="underConstructions()" class="nav-link p-0 text-muted">Insight</a></li>
                    <li class="nav-item mb-2"><a href="javascript:;" onclick="underConstructions()" class="nav-link p-0 text-muted">User Experience</a></li>
                </ul>
            </div> --}}
        </footer>
    </div>
    <div class="footer py-4 d-flex flex-column flex-md-row flex-stack px-20 align-items-end" id="kt_footer">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">2024&copy;</span>
            <a href="javascript:;" onclick="underConstructions()" target="_blank" class="text-gray-800 text-hover-primary">{{ \Config::get("constants.APP_NAME") }}</a>
        </div>
        <!--end::Copyright-->
        <div class="order-1 order-md-2 d-flex gap-2 align-items-end">
            <div class="d-flex flex-column gap-2 order-2 mw-500px">
                <span>{{ env("ADDRESS") }}</span>
                <div class="d-flex gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fab fa-whatsapp text-success fs-2"></i>
                        <a href="{{ env("WA_LINK") }}" target="_blank">
                            {{ env("WA_PHONE_NUMBER") }}
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-phone text-success fs-2"></i>
                        <span class="text-primary">{{ env("HOTLINE_NUMBER") }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-envelope text-success fs-2"></i>
                        <a href="mailto:{{ env("EMAIL_ADDRESS") }}" target="_blank">
                            {{ env("EMAIL_ADDRESS") }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Footer-->
