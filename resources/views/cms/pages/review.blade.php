@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="tns" style="direction: ltr">
                <div data-tns="true" data-tns-nav-position="bottom" data-tns-items="3" data-tns-mouse-drag="true" data-tns-controls="false" class="gap-5">
                    <!--begin::Item-->
                    @foreach (\App\Models\Express_review::get() as $item)
                        <div class="card border border-secondary-subtle">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <div class="symbol symbol-100px symbol-circle mb-3">
                                        @if (empty($item->avatars))
                                            <span class="symbol-label bg-light-primary text-primary fs-3x fw-bold">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                                        @else
                                            <img alt="Pic" src="{{ asset($item->avatars ?? "images/blank.png") }}">
                                        @endif
                                    </div>
                                    <div class="fs-3 text-dark fw-bold">{{ $item->name }}</div>
                                    <div class="fs-5 text-muted">{{ $item->occupation }}</div>
                                    <div class="d-flex">
                                        @for ($i = 0; $i < $item->rating; $i++)
                                            <i class="fa fa-star text-warning"></i>
                                        @endfor
                                    </div>
                                    <div>
                                        {!! $item->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection