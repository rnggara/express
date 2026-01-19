@extends('_crm.preferences.index')

@section('view_content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="fw-bold fs-3">Opportunity</span>
                    <a href="{{ route('crm.pref.crm.opportunity.detail') }}" class="btn btn-primary">
                        <i class="la la-plus"></i>
                        Add new pipeline
                    </a>
                </div>
            </div>
            <div class="d-flex align-items-center mb-5">
                <span class="text-muted">Preference <i class="fa fa-chevron-right mx-3 text-dark-75"></i></span>
                <span class="fw-semibold">Opportunity</span>
            </div>
            <div class="card-body bg-secondary-crm rounded">
                <table class="table display bg-white" id="table-pipeline">
                    <thead>
                        <tr>
                            <th class="text-nowrap">Pipeline Name</th>
                            <th class="text-nowrap text-center">Funnel</th>
                            <th class="text-nowrap text-center">Opportunity</th>
                            <th class="text-nowrap text-center">Created Date</th>
                            <th class="text-nowrap text-center">Last Update</th>
                            <th class="text-nowrap"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pipelines as $i => $item)
                            <tr>
                                <td class="text-{{$item->status == 1 ? "dark" : "muted"}}">{{ $item->label }}</td>
                                <td align="center" class="text-{{$item->status == 1 ? "dark" : "muted"}}">{{ $item->funnel->count() }}</td>
                                <td align="center" class="text-{{$item->status == 1 ? "dark" : "muted"}}">{{ $item->opportunity->count() }}</td>
                                <td align="center" class="text-{{$item->status == 1 ? "dark" : "muted"}}">{{ date("d-m-Y", strtotime($item->created_at)) }}</td>
                                <td align="center" class="text-{{$item->status == 1 ? "dark" : "muted"}}">{{ date("d-m-Y", strtotime($item->updated_at)) }}</td>
                                <td align="center">
                                    <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 0px">
                                        <i class="fa fa-ellipsis-vertical text-dark"></i>
                                    </button>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('crm.pref.crm.opportunity.change_status', $item->id) }}" class="menu-link px-3">
                                                {{ $item->status == 1 ? "Hide" : "Show" }}
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="{{ route('crm.pref.crm.opportunity.detail', $item->id) }}" class="menu-link px-3">
                                                Edit
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="javascript:;" onclick="archive(this)" data-url="{{route('crm.pref.crm.opportunity.archive', $item->id)}}" data-label="{{ $item->label }}" class="menu-link px-3 text-danger">
                                                Archive
                                            </a>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if (\Session::has("toast"))
        @php
            $toast = \Session::get("toast");
        @endphp
        <!--begin::Toast-->
        <div class="position-fixed end-0 p-3 z-index-3" style="top: 10%">
            <div id="kt_docs_toast_toggle" class="toast {{ $toast['status'] }} text-white" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                    {{ $toast['message'] }}
                </div>
            </div>
        </div>
        <!--end::Toast-->
    @endif

    <div class="modal fade" tabindex="-1" id="modalDelete">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column align-items-center">
                        <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                        <span class="fw-bold fs-3">Are you sure want to archive?</span>
                        <span class="text-center">Are you sure you want to archive <span id="archive-label"></span>?</span>
                        <div class="d-flex align-items-center mt-5">
                            <a href="" id="archive-url" class="btn btn-white">Yes</a>
                            <button class="btn btn-primary" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('view_script')
    <script>
        function archive(me){
            $("#modalDelete").modal("show")
            $("#modalDelete #archive-url").attr("href", $(me).data("url"))
            $("#modalDelete #archive-label").text($(me).data("label"))
        }
    </script>
@endsection
