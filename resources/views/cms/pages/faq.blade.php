@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column align-items-center justify-content-center gap-5">
                <span class="fs-2 fw-bold">FAQ</span>
                <div class="accordion w-100" id="kt_accordion_1">
                    @foreach ($data as $item)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="kt_accordion_1_header_{{$loop->iteration}}">
                                <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_{{$loop->iteration}}" aria-expanded="true" aria-controls="kt_accordion_1_body_{{$loop->iteration}}">
                                    {{$item->title}}
                                </button>
                            </h2>
                            <div id="kt_accordion_1_body_{{$loop->iteration}}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_{{$loop->iteration}}" data-bs-parent="#kt_accordion_1">
                                <div class="accordion-body">
                                    {!! $item->description !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection