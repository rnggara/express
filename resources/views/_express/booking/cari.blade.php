@extends("_express.layout")

@section('view_content')
    <div class="align-items-center d-flex flex-column">
        <div class="card w-100">
            <div class="card-body">
                <div class="d-flex flex-column gap-5">
                    <span class="fs-1">Daftar Harga {{ $total_berat }} Kg (Volume : {{ $total_volume }} Kg)</span>
                    <label>
                        <span class="fw-bold">Pengiriman: </span>
                        @php
                            $cat = [];
                            if ($produk->tipe_kategori == "w") {
                                $cat[] = $kategori->nama;
                            } else {
                                foreach($kategori as $item){
                                    $cat[] = $item->nama;
                                }
                            }
                        @endphp
                        <span>{{ $total_paket }} Paket {{ $produk->nama }} ({{ implode(", ", $cat) }})</span>
                    </label>
                    <div class="mt-3 d-flex gap-3 align-items-center">
                        <span class="fs-3">{{ $dari->nama }}</span>
                        <i class="fa fa-plane text-dark fs-3"></i>
                        <span class="fs-3">{{ $tujuan->nama }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="align-items-center col-md-4 col-12 d-flex flex-column gap-5 mt-10">
            <span>Biaya pengiriman {{ \Config::get("constants.APP_NAME") }} tidak menggunakan harga kelipatan per kg. Semakin berat, maka harga per kg-nya semakin hemat.</span>
            @foreach ($vendor->where("show", true) as $item)
                <div class="bg-secondary p-5 w-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex flex-column gap-3 align-items-center">
                            <div class="w-200px h-100px bgi-no-repeat bgi-size-contain bgi-position-center" style="background-image: url('{{ asset($item->logo_path) }}')"></div>
                            <span>{{ $item->nama }}</span>
                        </div>
                        <div class="d-flex flex-column gap-3 align-items-end">
                            <span class="fs-3">IDR {{ number_format($item->pr + $item->fuel_charge + $item->gtp, 0, ",", ".") }}</span>
                            <span>{{ ($tujuan->day_min) }} - {{ $tujuan->day_max }} Hari</span>
                            <i>Tidak termasuk hari libur/minggu di negara tujuan</i>
                            <div class="mt-3">
                                @auth
                                    <a href="{{ $item->url }}" class="btn btn-success">
                                        Booking Sekarang
                                    </a>
                                @endauth
                                @guest
                                    <button type="button" onclick="login_modal('{{ $item->url }}')" href="{{ $item->url }}" class="btn btn-success">
                                        Booking Sekarang
                                    </button>
                                @endguest
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="bg-info p-5 w-100">
                <span class="text-white">
                    Harga di atas di luar biaya bea cukai, permit fee , fumigasi maupun biaya remote area untuk kota-kota yang terletak di wilayah pelosok/pedalaman ataupun biaya lain yang terjadi akibat jenis barang yang di kirimkan. DHL dan TNT Fedex akan ada biaya tambahan khusus tujuan yang berada di remote area. Untuk list lengkap kode pos yang termasuk Remote Area, silakan cek di sini: DHL, TNT, Fedex.
                </span>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalLogin">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <div class="d-flex align-items-center modal-title">
                        <img alt="Logo" src="{{ asset(\Config::get('constants.APP_ICON')) }}" class="h-25px h-lg-50px" />
                    </div>
                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm ms-2" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fi fi-ss-circle-xmark fs-1"></i>
                    </div>
                    <!--end::Close-->
                </div>
                <div class="modal-body">
                    <div class="d-flex flex-column gap-3">
                        <span class="fw-bold fs-1">Untuk melanjutkan pesanan</span>
                        <span class="fs-3">Silahkan Masuk terlebih dahulu</span>
                        <form action="{{ str_replace(URL::to('/'), \Config::get("constants.PORTAL_HOST"), route('login')) }}" data-kt-redirect-url="" id="kt_sign_in_form" method="post" data-form="login">
                            <div class="d-flex flex-column gap-3">
                                <div class="fv-row">
                                    <label for="" class="col-form-label required">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old("email") }}" class="form-control" required placeholder="Masukan Email Anda">
                                    @error('email')
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="email" data-validator="notEmpty">{{$message}}</div>
                                        </div>
                                    @enderror
                                </div>
                                <div class="fv-row mb-5">
                                    <label for="" class="col-form-label required">Kata Sandi</label>
                                    <input type="password" name="password" id="password" class="form-control" required placeholder="Masukan Kata Sandi">
                                    @error('password')
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="password" data-validator="notEmpty">{{$message}}</div>
                                        </div>
                                    @enderror
                                </div>
                                @csrf
                                <input type="hidden" name="locale" id="locale" value="{{ $locale ?? "id" }}">
                                <input type="hidden" name="role" value="applicant">
                                <input type="hidden" name="intended">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Masuk</span>
                                    <!--end::Indicator label-->
                                    <!--begin::Indicator progress-->
                                    <span class="indicator-progress">Mohon tunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    <!--end::Indicator progress-->
                                </button>
                                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold">
                                    <div></div>
                                    <!--begin::Link-->
                                    <a href="{{ str_replace(URL::to('/'), \Config::get("constants.PORTAL_HOST"), route('password.request')) }}" class="fw-bold">Lupa Kata Sandi?</a>
                                    <!--end::Link-->
                                </div>
                                <div class="d-flex align-items-center justify-content-center gap-5">
                                    <div class="separator separator-solid flex-fill"></div>
                                    <span>Tidak Punya Akun?</span>
                                    <div class="separator separator-solid flex-fill"></div>
                                </div>
                                <button type="button" onclick="toggleView('register')" class="btn btn-outline-primary btn-outline">
                                    Buat Akun
                                </button>
                            </div>
                        </form>
                        <form action="{{ str_replace(URL::to('/'), \Config::get("constants.PORTAL_HOST"), route('register')) }}" id="kt_sign_up_form" data-kt-redirect-url="" method="post" style="display: none!important" data-form="register">
                            <div class="d-flex flex-column gap-3">
                                <div class="fv-row">
                                    <!--begin::Nama Lengkap-->
                                    <label for="fullname" class="col-form-label">Nama Lengkap</label>
                                    <input type="text" placeholder="Input Nama Lengkap Anda" name="fullname" autocomplete="off" class="form-control bg-transparent border-primary @error('fullname') is-invalid @enderror" />
                                    @error('fullname')
                                        <div class="text-danger">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <!--end::Nama Lengkap-->
                                </div>
                                <div class="fv-row">
                                    <label for="" class="col-form-label required">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old("email") }}" class="form-control" required placeholder="Masukan Email Anda">
                                    @error('email')
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="email" data-validator="notEmpty">{{$message}}</div>
                                        </div>
                                    @enderror
                                </div>
                                <div class="fv-row" data-kt-password-meter="true">
                                    <label for="" class="col-form-label required">Kata Sandi</label>
                                    <div class="position-relative mb-3">
                                        <input type="password" placeholder="Masukkan Kata Sandi" name="password" autocomplete="off" class="form-control bg-transparent border-primary @error('password') is-invalid @enderror" />
                                        <!--begin::Visibility toggle-->
                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        data-kt-password-meter-control="visibility">
                                                <i class="fa fa-eye-slash fs-3"></i>
                                                <i class="fa fa-eye d-none fs-3"></i>
                                        </span>
                                        <!--end::Visibility toggle-->
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <!--end::Input wrapper-->
                                    <!--begin::Meter-->
                                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                    </div>
                                    @error('password')
                                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            <div data-field="password" data-validator="notEmpty">{{$message}}</div>
                                        </div>
                                    @enderror
                                    <div class="text-muted">Gunakan 8 atau lebih karakter dengan menggabungkan huruf, angka & simbol.</div>
                                </div>
                                <div class="fv-row mb-5" data-kt-password-meter="true">
                                    <!--begin::Password-->
                                    <label for="password_confirmation" class="col-form-label">Konfirmasi Kata Sandi</label>
                                    <div class="position-relative mb-3">
                                        <input type="password" placeholder="Kata Sandi" name="password_confirmation" autocomplete="off" class="form-control bg-transparent" />
                                        <!--begin::Visibility toggle-->
                                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        data-kt-password-meter-control="visibility">
                                            <i class="fa fa-eye-slash fs-3"></i>
                                            <i class="fa fa-eye d-none fs-3"></i>
                                        </span>
                                        <!--end::Visibility toggle-->
                                    </div>
                                    <!--end::Password-->
                                </div>
                                <div id="recaptcha_reg"></div>
                                @csrf
                                @if(!empty($who))
                                <input type="hidden" name="company_id" value="{{$who->id}}">
                                <input type="hidden" name="tag" value="{{ $who->tag }}">
                                @else
                                <input type="hidden" name="id_company" id="id_company">
                                @endif
                                <input type="hidden" name="locale" id="locale">
                                @csrf
                                <input type="hidden" name="register_as" value="44">
                                <input type="hidden" name="intended">
                                <input type="hidden" name="role" id="applicant">
                                <button type="button" data-sitekey="{{ config("services.recaptcha_v3.siteKey") }}" id="kt_sign_up_submit" class="btn btn-primary g-recaptcha d-none">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Daftar</span>
                                    <!--end::Indicator label-->
                                    <!--begin::Indicator progress-->
                                    <span class="indicator-progress">Mohon Tunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    <!--end::Indicator progress-->
                                </button>
                                <div class="d-flex align-items-center justify-content-center gap-5">
                                    <div class="separator separator-solid flex-fill"></div>
                                    <span>Sudah Punya Akun?</span>
                                    <div class="separator separator-solid flex-fill"></div>
                                </div>
                                <button type="button" onclick="toggleView('login')" class="btn btn-outline-primary btn-outline">
                                    Masuk
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
    <script src="{{ asset("theme/assets/js/custom/authentication/sign-up/general.js") }}?v={{ time() }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script src="{{ asset("theme/assets/js/custom/authentication/sign-in/general.js")."?v=".date("Ymdhis") }}"></script>
    <script>

        $("input.is-invalid").on("keyup", function(){
            $(this).removeClass("is-invalid")
        })

        var widget1;
        var widget2;

        var verifyCallback = function(response) {
            var res = grecaptcha.getResponse(widget1)
            if(res.length == 0){
                Swal.fire("reCaptcha", "Failed to verify", 'warning')
            } {
                $("#kt_sign_up_submit").prop("type", "submit")
                $("#kt_sign_up_submit").removeClass("d-none")
            }
        };

        var onloadCallback = function() {
            widget1 = grecaptcha.render('recaptcha_reg', {
                'sitekey' : '{{ config("services.recaptcha_v3.siteKey") }}',
                'callback' : verifyCallback,
            });
        };

        function toggleView(type){

            var target = $("#modalLogin [data-form='"+type+"']");
            $("#modalLogin [data-form]").not("[data-form='"+type+"']").attr("display", "none!important").fadeOut(300, function() {
                $(target).fadeIn(300);
            });
        }

        function login_modal(url){
            $("#modalLogin").modal("show")
            $("#modalLogin input[name=intended]").val(url)
            $("#modalLogin form").attr("data-kt-redirect-url", url)
        }

        $(document).ready(function(){
            // alert("hello")

            $("input[name=password]").on("keyup", function(){
                var val = $(this).val()
                var i = $(this).next().find("i")
                if(val != ""){
                    i.show()
                } else {
                    i.hide()
                }
            })

            $("input[name=password]").next().click(function(){
                const type = $("input[name=password]").attr("type") === 'password' ? 'text' : 'password';
                $("input[name=password]").attr("type", type)
                if(type == "text"){
                    $(this).find("i").removeClass("fa-eye")
                    $(this).find("i").addClass("fa-eye-slash")
                } else {
                    $(this).find("i").addClass("fa-eye")
                    $(this).find("i").removeClass("fa-eye-slash")
                }
            })
        })
    </script>
@endsection

