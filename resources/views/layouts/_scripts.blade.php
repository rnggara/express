
<div class="modal fade" tabindex="-1" id="modalUnderConstructions">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset("images/construct.jpg") }}" alt="" class="w-100 mb-5">
                    <span class="fw-semibold mb-5 fs-2">Dalam Pengembangan</span>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function underConstructions(){
        $("#modalUnderConstructions").modal("show")
    }

    @auth

    function login_portal(){
        window.open("{{ \Config::get("constants.PORTAL_HOST").'/login-portal?id='.base64_encode(Auth::user()->id) }}", '_blank').focus();
    }

    function login_lms(){
        $.ajax({
            url : "{{ route("login_lms") }}",
            type : "get",
            dataType : "json"
        }).then(function(resp){
            var x = window.open("https://lms-kerjaku.databiota.com/login", "_blank").focus()
            console.log(x)
        })
    }

    @endauth
</script>
