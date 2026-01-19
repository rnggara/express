<script>
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

    function bookmark(me, id, callback){
            $.ajax({
                url : "{{ route('applicant.job.bookmark') }}",
                type : "post",
                dataType : "json",
                data : {
                    _token : "{{ csrf_token() }}",
                    id : id
                }
            }).then(callback)
        }
</script>
