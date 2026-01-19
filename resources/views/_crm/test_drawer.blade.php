@extends('layouts.templateCrm', ['withoutFooter' => true])

{{-- @section('fixaside')
    @include('_crm.leads._aside')
@endsection --}}

@section('content')
<div class="card card-custom not-rounded h-100">
    <div class="card-body border-bottom">
        <div class="row">
            <div class="col-12">
                <button class="btn btn-primary me-3" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_basic">Toggle basic drawer</button>
            </div>
            <div class="col-12">
                @if (session('request'))
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (session('request') as $key => $item)
                                @if ($key != "_token")
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $item }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <div
        id="kt_drawer_example_basic"

        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#kt_drawer_example_basic_button"
        data-kt-drawer-close="#kt_drawer_example_basic_close"
        data-kt-drawer-width="{default : '45%', md: '45%', sm: '500px'}">
        <div class="card rounded-0 w-100">
            <div class="card-header">
                <h3 class="card-title">Form 1</h3>
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_example_basic_close">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <div class="card-body hover-scroll-overlay-y">
                <button class="btn btn-primary" data-kt-drawer-show="true" data-kt-drawer-target="#kt_drawer_example_advanced">Toggle advanced drawer</button>
                <form action="" method="post">
                    @csrf
                    <input type="hidden" name="type" value="form1">
                    @for ($i = 1; $i <= 10; $i++)
                        <div class="fv-row">
                            <label for="input_form_1_{{ $i }}" class="col-form-label">Input {{ $i }}</label>
                            <input type="text" name="input_form_1_{{ $i }}" class="form-control" id="input_form_1_{{ $i }}" value="{{ $i }}">
                        </div>
                    @endfor
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!--begin::View component-->
    <div
        id="kt_drawer_example_advanced"

        class="bg-white"
        data-kt-drawer="true"
        data-kt-drawer-activate="true"
        data-kt-drawer-toggle="#kt_drawer_example_advanced_button"
        data-kt-drawer-close="#kt_drawer_example_advanced_close"
        data-kt-drawer-name="docs"
        data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default : '45%', md: '45%', sm: '500px'}"
        data-kt-drawer-direction="end"
    >
        <div class="card rounded-0 w-100">
            <!--begin::Card header-->
            <div class="card-header pe-5">
                <!--begin::Title-->
                <div class="card-title">
                    <!--begin::User-->
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 lh-1">Example Advanced</a>
                    </div>
                    <!--end::User-->
                </div>
                <!--end::Title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Close-->
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_example_advanced_close">
                        <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body hover-scroll-overlay-y">
                <form action="" method="post">
                    @csrf
                    <input type="hidden" name="type" value="form2">
                    @for ($i = 1; $i <= 10; $i++)
                        <div class="fv-row">
                            <label for="input_form_2_{{ $i }}" class="col-form-label">Input {{ $i }}</label>
                            <input type="text" name="input_form_2_{{ $i }}" class="form-control" id="input_form_2_{{ $i }}" value="{{ $i }}">
                        </div>
                    @endfor
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <!--end::Card body-->
        </div>
    </div>
    <!--end::View component-->

@endsection

@section('custom_script')
    <script>
    $(document).ready(function(){
        var drawerElement1 = document.querySelector("#kt_drawer_example_basic");
        var drawer1 = KTDrawer.getInstance(drawerElement1);
        var drawerElement = document.querySelector("#kt_drawer_example_advanced");
        var drawer = KTDrawer.getInstance(drawerElement);
        console.log(drawer)
        drawer.on("kt.drawer.show", function() {

            $(drawerElement).css("margin-right", drawer1.lastWidth)
        });
        drawer.on("kt.drawer.after.hidden", function() {
            setTimeout(function(){
                $(drawerElement).css("margin-right", 0)
            }, 250);
            // console.log("kt.drawer.hide event is fired");
        });
    });
    </script>
@endsection
