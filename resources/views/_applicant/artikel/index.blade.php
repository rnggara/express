@extends('layouts.template', ["withoutFooter" => true])

@section('content')
<div class="d-flex flex-column">
    {{-- <div class="card card-custom mb-8" style="background-color: #EAE3FB">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between p-10">
                <div class="d-flex flex-column">
                    <div class="d-flex align-items-center me-5">
                        <!--begin::Logo-->
                        <a href="/">
                            <img alt="Logo"
                                src="{{ asset('theme/assets/media/logos/icon-sm.png') }}"
                                class="h-25px h-lg-30px" />
                        </a>
                        <span class="fs-3 fw-bold text-dark">{{ env('APP_LABEL') }}</span>
                        <!--end::Logo-->
                    </div>
                    <div class="mt-3">
                        <span class="fs-3tx">Tips Karir yang mudah</span>
                    </div>
                    <span class="mt-3">Lorem ipsum dolor sit amet consectetur. Nibh interdum non tincidunt fames tortor sed pharetra. Euismod aenean posuere risus convallis non ut habitasse a. Vel habitasse at euismod ut.....</span>
                    <div class="d-flex mt-5">
                        <a href="javascript:;"
                            class="btn btn-primary me-5">Baca Selengkapnya</a>
                    </div>
                </div>
                <div class="flex-fill h-300px rounded w-50" style="background-color: #7340E5; opacity: .1">
                </div>
            </div>
        </div>
    </div> --}}
    <span class="fw-bold fs-2 mb-5">Artikel Populer Minggu Ini</span>
    <div class="row mt-5 mb-18">
        @if (!empty($hot_artikel))
        <div class="col-12 col-md-7 d-flex flex-column">
            <img src="{{ asset($hot_artikel->thumbnail ?? "images/article.png") }}" class="mb-5 rounded w-100" alt="">
            <a href="{{ route("artikel.detail", $hot_artikel->id) }}" class="fw-bold fs-2">{{ $hot_artikel->subject ?? "Lorem ipsum dolor sit amet consectetur. At enim amet eros tellus non scelerisque mollis." }}</a>
            <span>{!! strlen($hot_artikel->description) > 100 ? substr($hot_artikel->description, 0, 100) : $hot_artikel->description !!}</span>
            <div class="d-flex text-muted">
                <p>{{$hot_artikel->created_by}}</p>
                <p>- @dateId(date("Y-m-d", strtotime($hot_artikel->created_at ?? date("Y-m-d"))))</p>
                <p></p>
            </div>
        </div>
        @endif
        <div class="col-12 col-md-5 d-flex flex-column">
            @if ($artikel->count() > 0)
                @foreach ($artikel as $i => $item)
                    <div class="d-flex flex-column flex-md-row">
                        <div class="bgi-no-repeat h-350px bgi-position-center bgi-size-cover h-md-200px me-5 rounded w-100 min-w-300px" style="background-image: url({{ asset($item->thumbnail) }})"></div>
                        <div class="d-flex flex-column">
                            <a href="{{ route("artikel.detail", $item->id) }}" class="fw-bold fs-2">{{ $item->subject }}</a>
                            <span>{!! strlen($item->description) > 100 ? substr($item->description, 0, 100) : $item->description !!}</span>
                            <div class="d-flex text-muted">
                                <p>{{$item->created_by}}</p>
                                <p>- @dateId(date("Y-m-d", strtotime($item->created_at)))</p>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="separator separator-dotted my-5"></div>
                @endforeach
            @endif
        </div>
    </div>
    <span class="fw-bold fs-2 mb-5">Artikel Baru</span>
    <div class="row">
        @if ($newArtikel->count() > 0)
            @foreach ($newArtikel as $item)
                <div class="col-12 col-md-4">
                    <div class="d-flex flex-column" style="width: 100%">
                        <img src="{{ asset($item->thumbnail) }}" class="mb-5 rounded w-100" alt="">
                        <a href="{{ route("artikel.detail", $item->id) }}" class="fw-bold fs-2">{{ $item->subject }}</a>
                        <span>{!! strlen($item->description) > 100 ? substr($item->description, 0, 100) : $item->description !!}</span>
                        <div class="d-flex text-muted">
                            <p>{{$item->created_by}}</p>
                            <p>- @dateId(date("Y-m-d", strtotime($item->created_at)))</p>
                            <p></p>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
        <div class="d-flex flex-column align-items-center">
            <img src="{{ asset("theme/assets/media/icons/empty.png") }}" alt="">
            <span class="mt-3 fw-semibold">Tidak ada artikel</span>
        </div>
        @endif
    </div>
</div>
@endsection
