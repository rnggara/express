@extends('layouts.templateCrm', ['withoutFooter' => true])

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">SSO Client</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add">
                    Add Client
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table display" id="table-client">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client ID</th>
                        <th>Client Secret</th>
                        <th>Client Name</th>
                        <th>Redirect URI</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i => $item)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $item['id'] }}</td>
                            <td>{{ $item['secret'] }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['redirect'] }}</td>
                            <td>
                                <button type="button" class="btn btn-icon btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-offset="0px, 0px">
                                    <i class="fa fa-ellipsis-vertical text-dark"></i>
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 py-2 menu-state-bg-light-primary fw-semibold w-auto min-w-200 mw-300px" data-kt-menu="true">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_delete_{{ $item['id'] }}" class="menu-link px-3 text-danger">
                                            Delete Data
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#modal_edit_{{ $item['id'] }}" class="menu-link px-3">
                                            Edit Data
                                        </a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>

                                <form action="{{ route("sso_client.store", "delete") }}" method="post">
                                    <div class="modal fade" tabindex="-1" id="modal_delete_{{ $item['id'] }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <img src="{{ asset("images/archive.png") }}" class="w-150px mb-5" alt="">
                                                        <span class="fw-bold fs-3">Are you sure want to delete?</span>
                                                        <div class="d-flex align-items-center mt-5">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                                                            <button type="submit" name="submit" value="delete" class="btn btn-white">Yes</button>
                                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <form action="{{ route("sso_client.store", "store") }}" method="post">
                                    <div class="modal fade" tabindex="-1" id="modal_edit_{{ $item['id'] }}">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h3 class="modal-title">Update Client</h3>
                                    
                                                    <!--begin::Close-->
                                                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                                    </div>
                                                    <!--end::Close-->
                                                </div>
                                    
                                                <div class="modal-body">
                                                    <div class="fv-row">
                                                        <label for="" class="col-form-label required">Client Name</label>
                                                        <input type="text" name="client_name" value="{{ old("client_name") ?? $item['name'] }}" class="form-control" placeholder="Input Client Name">
                                                        @error('client_name')
                                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="fv-row">
                                                        <label for="" class="col-form-label required">Client Redirect URL</label>
                                                        <input type="text" name="client_redirect_url" value="{{ old("client_redirect_url") ?? $item['redirect'] }}" class="form-control" placeholder="Example: https://your-domain.com/callback">
                                                        @error('client_name')
                                                            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                    
                                                <div class="modal-footer">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item['id'] }}">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <form action="{{ route("sso_client.store", "store") }}" method="post">
        <div class="modal fade" tabindex="-1" id="modal_add">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Add Client</h3>
        
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>
        
                    <div class="modal-body">
                        <div class="fv-row">
                            <label for="" class="col-form-label required">Client Name</label>
                            <input type="text" name="client_name" value="{{ old("client_name") }}" class="form-control" placeholder="Input Client Name">
                            @error('client_name')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="fv-row">
                            <label for="" class="col-form-label required">Client Redirect URL</label>
                            <input type="text" name="client_redirect_url" value="{{ old("client_redirect_url") }}" class="form-control" placeholder="Example: https://your-domain.com/callback">
                            @error('client_redirect_url')
                                <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
        
                    <div class="modal-footer">
                        @csrf
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom_script')
<script>

    const isValidUrl = urlString=> {
	  	var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
	    '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
	    '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
	    '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
	    '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
	    '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
        return !!urlPattern.test(urlString);
	}

    $(document).ready(function(){
        @if(Session::has("modal"))
            $("#{{ Session::get("modal") }}").modal("show")
        @endif
    })
</script>
@endsection