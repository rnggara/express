@extends('_express.layout')

@section('view_content')
<div class="card card-custom bg-primary text-white" style="background-image: url({{ asset("images/dashboard-banner.png") }}); background-size: cover; background-blend-mode: screen;">
    <div class="card-body">
        <div class="d-flex align-items-center flex-column flex-md-column flex-lg-row">
            <div class="d-flex flex-column w-100">
                <span class="fs-2tx">Halo! {{ Auth::user()->name }}</span>
                <span class="fw-semibold mb-5">Lacak Pesananmu dengan Nomor Resi</span>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-5 px-20">
                                <div class="d-flex align-items-center gap-5">
                                    <input type="text" class="form-control" id="inputCekResi" placeholder="Masukkan kode tracking">
                                    <button class="btn btn-primary" id="btnCekResi">Lacak</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('view_script')
    <script>
        $("#btnCekResi").click(function(){
            var resi = $("#inputCekResi").val()

            window.location.href = "{{ route("cek.resi") }}/" + resi
        })
    </script>
@endsection