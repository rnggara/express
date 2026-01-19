@extends('layouts.template', ['withoutFooter' => true])

@section('content')
    <div class="d-flex flex-column">
        <div class="bgi-no-repeat bgi-position-center bgi-size-cover min-h-500px mb-5 w-100 rounded" style="background-image: url('{{ asset($artikel->drawing) }}')"></div>
        <span class="fs-2 fw-bold">{{$artikel->subject}}</span>
        <ol class="breadcrumb breadcrumb-dot text-muted fs-6 fw-semibold mb-5">
            <li class="breadcrumb-item">John Doe</li>
            <li class="breadcrumb-item">@dateId($artikel->created_at)</li>
            <li class="breadcrumb-item text-muted">5 mins read</li>
        </ol>
        <div class="desc">
            {!! $artikel->description !!}
        </div>
    </div>
@endsection
