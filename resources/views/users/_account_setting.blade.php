<!--begin::Form-->
<form class="form" action="{{route('account.update.info')}}" id="kt_form" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$user->id}}">
    @csrf
    <input type="hidden" name="act" value="account_update">
    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header py-3">
            <div class="card-title align-items-start flex-column">
                <h3 class="card-label font-weight-bolder text-dark">{{ __("user.account_information") }}</h3>
                <span class="text-muted font-weight-bold font-size-sm mt-1">Change your account settings</span>
            </div>
            <div class="card-toolbar">
                <button type="submit" id="submit_update_account" name="submit_update_account" class="btn btn-success mr-2">Save Changes</button>
                {{--                                    <button type="reset" class="btn btn-secondary">Cancel</button>--}}
            </div>
        </div>
        <!--end::Header-->
        <div class="card-body">
            <!--begin::Heading-->
            <div class="row">
                <label class="col-xl-3"></label>
                <div class="col-lg-9 col-xl-6">
                    <h5 class="font-weight-bold mb-6">Account:</h5>
                </div>
            </div>
            <!--begin::Form Group-->
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Username</label>
                <div class="col-lg-9">
                    <input type="text" class="form-control form-control-lg" disabled id="username" name="username" value="{{$user->username}}">
                </div>
            </div>
            <!--begin::Form Group-->
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Position</label>
                <div class="col-lg-9">
                    <input class="form-control form-control-lg" type="text" id="position" name="position" value="{{($user->position != null)?$user->position:'SYSTEM'}}" disabled>
                </div>
            </div>
            <!--begin::Form Group-->
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">My Profile Picture</label>
                <div class="col-lg-9 col-xl-6">
                    <div class="image-input image-input-outline" id="printed_logo">
                        <div class="image-input-wrapper" style='background-image: url("@if($user->user_img == null) {{asset('theme/assets/media/users')}}/default.jpg @else {{asset('theme/assets/media/users')}}/{{$user->user_img}} @endif");'></div>
                        <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change">
                            <i class="fa fa-pen icon-sm text-muted"></i>
                            <input type="file" name="user_img" id="p_logo" accept=".png, .jpg, .jpeg" />
                            <input type="hidden" name="p_logo_remove" />
                        </label>
                        <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                    </div>
                    <span class="form-text text-muted">
                    Allowed file types: png, jpg, jpeg.</span>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label">Attendance Password</label>
                @if (!empty($userComp->emp_id))
                <div class="col-lg-6">
                    <input class="form-control form-control-lg" type="text" data-id="{{ $userComp->id }}" id="attend_code" name="attend_code" value="{{ $userComp->attend_code }}" disabled>
                </div>
                <div class="col-lg-3">
                    <button type="button" id="btn-random" class="btn btn-primary">
                        <i class="fa fa-random"></i>
                        Randomize
                    </button>
                </div>
                @else
                <div class="col-lg-9">
                    <input class="form-control form-control-lg" type="text" id="attend_code" name="attend_code" value="Please contact HR Department to connect your account to employee database" disabled>
                </div>
                @endif
            </div>
            @if (!empty($userComp->emp_id))
                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9">
                        <table class="table table-bordered">
                            <tr>
                                <th class="text-center">Session</th>
                                <th class="text-center">Time</th>
                            </tr>
                            <tr>
                                <th class="text-center">Clock In</th>
                                <td class="text-center">
                                    {{ (empty($clockin)) ? "N/A" : date("d/m/Y H:i", strtotime($clockin)) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center">Clock Out</th>
                                <td class="text-center">
                                    {{ (empty($clockout)) ? "N/A" : date("d/m/Y H:i", strtotime($clockout)) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif
            <div class="form-grou"></div>
        </div>
    </div>
</form>
<!--end::Form-->
<!--end::Card-->
<br />
<form class="form" action="{{route('account.update.password')}}" id="kt_form" method="post">
    <input type="hidden" name="id" value="{{$user->id}}">
    @csrf
    {{--                        <input type="hidden" name="act" value="password_update">--}}
    <div class="card card-custom">
        <!--begin::Header-->
        <div class="card-header py-3">
            <div class="card-title align-items-start flex-column">
                <h3 class="card-label font-weight-bolder text-dark">Change Password</h3>
                <span class="text-muted font-weight-bold font-size-sm mt-1">Change your account password</span>
            </div>
            <div class="card-toolbar">
                <input type="submit" name="submit_update_password" id="submit_update_password" value="Save Changes" class="btn btn-success mr-2">
                {{--                                    <button type="reset" class="btn btn-secondary">Cancel</button>--}}
            </div>
        </div>
        <!--end::Header-->
        <!--begin::Form-->
        <div class="card-body">
            <!--begin::Alert-->
            <div class="alert alert-custom alert-light-danger fade show mb-10" role="alert">
                <div class="alert-icon">
                    <span class="svg-icon svg-icon-3x svg-icon-danger">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Code/Info-circle.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"></circle>
                                <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1"></rect>
                                <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1"></rect>
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                </div>
                <div class="alert-text font-weight-bold">Configure user passwords to expire periodically. Users will need warning that their passwords are going to expire,
                <br>or they might inadvertently get locked out of the system!</div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">
                        <i class="ki ki-close"></i>
                    </span>
                    </button>
                </div>
            </div>
            <!--end::Alert-->
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label text-alert">New Password</label>
                <div class="col-lg-9 col-xl-6">
                    <input type="password" id="password" name="password" class="form-control form-control-lg form-control-solid" required placeholder="New password">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-xl-3 col-lg-3 col-form-label text-alert">Verify Password</label>
                <div class="col-lg-9 col-xl-6">
                    <input type="password" id="confirm_password" class="form-control form-control-lg form-control-solid" required placeholder="Verify password">
                </div>
            </div>
        </div>
    </div>
</form>
@if (!empty($emp))
<div class="card card-custom gutter-b mt-5">
    <div class="card-header">
        <div class="card-title">My Slip</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered table-hover display table-responsive-xs">
                    <thead>
                        <tr>
                            <th class="text-center">Month</th>
                            <th class="text-center">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mnths as $i => $record)
                        <tr>
                            <td align="center">{{ $record." ".date('Y') }}</td>
                            <td align="center">
                                @if (isset($arch[$i."-".date('Y')]) && ($arch[$i."-".date('Y')][0] + 1) == ($i + 1))
                                    <a target="_blank" href="{{ route('payroll.slip.print', ['id' => $emp->id, 'period' => date('Y')."-".$i]) }}" class="btn btn-sm btn-icon btn-primary"><i class="fa fa-print"></i></a>
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif


<div class="row">
    <div class="col-6">
        <div class="card card-custom gutter-b mt-5">
            <div class="card-header py-3">
                <div class="card-title align-items-start flex-column">
                    <h3 class="card-label font-weight-bolder text-dark">Your Signature</h3>
                    <span class="text-muted font-weight-bold font-size-sm mt-1">Please sign for the system to sample your signature.</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="symbol symbol-150 mr-3">
                            @php
                                $file_sign = (!empty($user->file_signature)) ? str_replace("public", "public_html", asset('media/user/signature/'.$user->file_signature)) : asset('theme/assets/media/users/1-profile.jpg');
                            @endphp
                            <img alt="Pic" src="{{ $file_sign }}"/>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="button" onclick="modal_sign('Signature')" data-toggle="modal" data-target="#modalSign" class="btn btn-success">Create/Upload your signature</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card card-custom gutter-b mt-5">
            <div class="card-header py-3">
                <div class="card-title align-items-start flex-column">
                    <h3 class="card-label font-weight-bolder text-dark">Your Paraf</h3>
                    <span class="text-muted font-weight-bold font-size-sm mt-1">Please sign for the system to sample your paraf.</span>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="symbol symbol-150 mr-3">
                            @php
                                $file_par = (!empty($user->file_paraf)) ? str_replace("public", "public_html", asset('media/user/paraf/'.$user->file_paraf)) : asset('theme/assets/media/users/1-profile.jpg');
                            @endphp
                            <img alt="Pic" src="{{ $file_par }}"/>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="button" onclick="modal_sign('Paraf')" data-toggle="modal" data-target="#modalSign" class="btn btn-success">Create/Upload your paraf</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Form-->
