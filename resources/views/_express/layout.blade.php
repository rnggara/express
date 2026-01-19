@extends("layouts.template", [
    "withoutFooter" => true
])

@section('css')
    @yield('view_css')
@endsection

@section('content')
    @yield('view_content')
@endsection

@section('custom_script')
    <script src="{{ asset('assets/jquery-number/jquery.number.min.js') }}"></script>
    @yield('view_script')
@endsection

