@extends('layouts.template')

@section('aside')
<div class="card card-custom min-w-300px w-300px card-stretch">
    <div class="card-body">
        <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6">
            @foreach ($list_menu as $item)
                @php
                    $key = strtolower(str_replace(" ", "_", $item));
                @endphp
                <div class="menu-item my-2">
                    <a href="{{ route("cms.pages.index")."?v=$key" }}" class="menu-link {{ $v == $key ? "active bg-active-secondary" : "" }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-double-right fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                                <span class="path6"></span>
                            </i>
                        </span>
                        <span class="menu-title">{{ $item }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card card-custom shadow-none gutter-b card-stretch bg-transparent">
        <div class="card-header border-0">
            <h3 class="card-title">{{ strtoupper(str_replace("_", " ", $v)) }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded">
            @include("cms.pages._$v")
        </div>
    </div>
</div>
@endsection

@section('custom_script')
    <script src="{{ asset('theme/assets/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
    <script src="{{asset("assets/jquery-number/jquery.number.min.js")}}"></script>
    <script>
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | table code',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });
        $(document).ready(function(){
            $("table.table-display").DataTable()
            $("input.number").number(true, 0)
        })
    </script>
    @yield('custom_script_inner')
@endsection
