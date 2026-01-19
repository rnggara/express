@extends('layouts.template')
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{$role->name}} - Privilege</h3>
            </div>
            <div class="card-toolbar">

                <a href="{{ route("company.role_controll", base64_encode($companyId)) }}?v=custom-roles" class="btn btn-secondary font-weight-bolder me-3">
                    <span class="svg-icon svg-icon-md">
                        <i class="la la-angle-double-left"></i>
                    </span>Back
                </a>
                <button class="btn btn-primary font-weight-bolder me-3" id="selectButton">
				<span class="svg-icon svg-icon-md">
				</span>Select All / Deselect All
                </button>
                <button class="btn btn-info font-weight-bolder" id="saveUserPrivelege">
                    <i class="fa fa-check"></i>Save
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="userPrivelegeUpdate" action="{{route('pref.cr.update_privilege')}}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{$role->id}}">
                <table class="table table-bordered table-hover">
                    <thead>
                    <th></th>
                    @foreach($actions as $key => $action)
                        <th style="text-align: center; max-width: 30px;">
							<span>
								{{$action}}
							</span>
                        </th>
                    @endforeach

                    </thead>
                    <tbody>
                    @foreach($modules as $moduleKey => $module)
                        <tr>
                            <td style="text-align: right; max-width: 100px;">
								<a href="#" onclick="get_module({{ $moduleKey }})" data-container="body" data-toggle="kt-tooltip" data-placement="left">
									{{ucwords(str_replace("_", " ", $module))}}
                                </a>
                            </td>
                            @foreach($actions as $actionKey => $action)
                                @php
                                    $checked = "";
                                    if(isset($rolePriv[$moduleKey])){
                                        $rModule = $rolePriv[$moduleKey];
                                        if(isset($rModule[$actionKey])){
                                            $checked = "checked";
                                        }
                                    }
                                @endphp
                                <td align="center" class="text-center" onclick="td_click(this)">
                                    <div class="d-flex justify-content-center">
                                        <div class="form-check">
                                            <input class="form-check-input" {{$checked}} type="checkbox" name="privilege[{{$moduleKey}}][{{$actionKey}}]" id="privilege_{{$moduleKey}}_{{$actionKey}}" value="1" />
                                            <label class="form-check-label" for="privilege_{{$moduleKey}}_{{$actionKey}}">
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <div class="modal fade" id="moduleModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="module-content">

            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    {{-- @if(isset($role))
        <script>
        jQuery.each({!! $role->privilege !!}, function(key, value)
        {
            // console.log(value['id_rms_modules'], value['id_rms_actions'])
            $('#privilege_'+value['id_rms_modules']+'_'+value['id_rms_actions']).attr('checked', true);
        });
        </script>
    @endif --}}
    <script type="text/javascript">
        function td_click(x){
            var td = $(x)
            var input = td.find('input')
            input.prop('checked', !input.prop('checked'))
        }
        $(document).ready(function() {
            var clicked = false;
            $("#selectButton").on("click", function() {
                $(":checkbox").prop("checked", !clicked);
                clicked = !clicked;
            });

            $("#saveUserPrivelege").click(function(){
            	$('#userPrivelegeUpdate').submit();
            });
        });
    </script>
@endsection
