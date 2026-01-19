@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-body">
            {!! $data->content ?? "" !!}
        </div>
    </div>
@endsection