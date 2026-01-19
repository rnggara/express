@extends('layouts.template', ['bgWrapper' => "bg-white"])

@section('content')
    <div class="d-flex flex-column">
        <a href="{{ route("search_talent.index") }}" class="d-flex align-items-center mb-3">
            <i class="fa fa-arrow-left me-3 text-primary"></i>
            Kembali ke search talent
        </a>
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Bookmark Talent</h3>
            </div>
            <div class="card-body">
                @if (count($uid) == 0)
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <img src="{{ asset("theme/assets/media/icons/empty.png") }}" alt="">
                        <span class="fw-bold">Tidak ada bookmark</span>
                        <span>Cari talent untuk menemukan kandidat terbaik lorem ipsum dolor sit amet</span>
                    </div>
                @else
                    {!! $view !!}
                @endif
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>
        $(document).ready(function(){
            $("[data-toggle=bookmark]").click(function(){
                var $i = $(this).find("i")
                var id = $(this).data("id")
                $.ajax({
                    url : "{{ route("search_talent.bookmark") }}",
                    type : "post",
                    dataType : "json",
                    data : {
                        _token : "{{ csrf_token() }}",
                        id : id
                    }
                }).then(function(resp){
                    location.reload()
                })
            })
        })
    </script>
@endsection
