@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Deleted Account') }}</div>

                <div class="card-body">
                    {{-- @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif --}}

                    {{ __('Your account will be deleted permanently at ').date("d F Y", strtotime(Auth::user()->delete_schedule)) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
