@extends('layouts.template')

@section('aside')
<div class="card card-custom min-w-300px w-300px card-stretch">
    <div class="card-body">
        <div class="menu menu-column menu-gray-600 menu-rounded menu-sub-indention fw-semibold fs-6">
            @foreach ($list_menu as $item)
                <div class="menu-item my-2">
                    <a href="{{ route("cms.employer.index")."?v=$item" }}" class="menu-link {{ $v == $item ? "active bg-active-secondary" : "" }}">
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
                        <span class="menu-title">{{ ucwords(str_replace("_", " ", $item)) }}</span>
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
            <h3 class="card-title">{{ ucwords(str_replace("_", " ", $v)) }}</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body bg-white rounded">
            @include("cms.employer._$v")
        </div>
    </div>
</div>
@endsection

@section('custom_script')
    <script>
        function init_validation(form){
            const t = $(`#${form}`);
            var fields = {}
            t.find(".form-input").each(function(){
                var _id = $(this).attr("id")
                var _attr = $(this).prev().text()
                var validator = {
                    validators: {
                        notEmpty : {
                            message: `${_attr} harus diisi`
                        }
                    }
                }
                fields[_id] = validator
            })
            var validator = FormValidation.formValidation(
                document.getElementById(`${form}`),
                {
                    fields: fields,

                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            );

            var submitButton = t.find("button[type=submit]")
            submitButton.click(function(e){
                e.preventDefault()
                if (validator) {
                    validator.validate().then(function (status) {
                        console.log('validated!');

                        if (status == 'Valid') {
                            // Show loading indication
                            submitButton.attr('data-kt-indicator', 'on');

                            // Disable button to avoid multiple click
                            submitButton.prop("disabled", true)

                            // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            setTimeout(function () {
                                // Remove loading indication
                                submitButton.removeAttr('data-kt-indicator');

                                // Enable button
                                submitButton.prop("disabled", false)

                                // Show popup confirmation

                                t.submit()
                            }, 2000);
                        }
                    });
                }
            })
        }
    </script>
    @yield('scripts')
    <script>

        $(document).ready(function(){
            $("#table-artikel").DataTable({
                "columnDefs": [
                    { "orderable": false, "targets": 6 }
                ]
            })
        })
    </script>
@endsection
