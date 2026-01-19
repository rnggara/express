<div class="aside aside-left d-flex flex-column" id="kt_aside" style="background-color: {{Session::get('company_bgcolor')}}">
    <!--begin::Brand-->
    <div class="aside-brand d-flex flex-column align-items-center flex-column-auto py-9">
        <!--begin::Logo-->
        <div class="btn p-0 symbol symbol-40 symbol-primary" href="#" id="kt_quick_user_toggle">
            <div class="symbol-label">
                {{-- <img alt="Logo" src="{{asset('theme/assets/media/logos/default-c.png')}}" class="h-75" /> --}}
                <img alt="Logo" src="{{ asset("theme/assets/media/svg/avatars/007-boy-2.svg") }}" class="h-75 align-self-end" />
            </div>
        </div>
        <!--end::Logo-->
    </div>
    <!--end::Brand-->
    <!--begin::Nav Wrapper-->
    <div class="aside-nav d-flex flex-column align-items-center flex-column-fluid pb-10">
        <!--begin::Nav-->
        <ul class="nav flex-column">
            <!--begin::Item-->
            <li class="nav-item mb-2" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Switch Company">
                <a href="#" class="btn btn-icon btn-lg btn-borderless mb-1 position-relative" id="kt_quick_notifications_toggle">
                    <span class="symbol symbol-35">
                        <span class="symbol-label font-size-h5 font-weight-bold text-white bg-white">
                            <img src='{{str_replace("public", "public_html", asset('images/'.Session::get('company_app_logo')))}}' height='30px' alt="Company Logo"/>
                        </span>
                    </span>
                </a>
            </li>
            <!--end::Item-->
        </ul>
        <!--end::Nav-->
    </div>
    <!--end::Nav Wrapper-->
    <!--begin::Footer-->
    <div class="aside-footer d-flex flex-column align-items-center flex-column-auto py-8">
        <!--begin::Notifications-->
        {{-- <a href="#" class="btn btn-icon btn-lg btn-borderless mb-1 position-relative" id="kt_quick_notifications_toggle" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Notifications">
            <span class="svg-icon svg-icon-xxl">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
                        <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
            <span class="label label-sm label-light-danger label-rounded font-weight-bolder position-absolute top-0 right-0 mt-1 mr-1">3</span>
        </a> --}}
        <!--end::Notifications-->
        <!--begin::Quick Actions-->
        {{-- <a href="#" class="btn btn-icon btn-lg btn-borderless mb-1" id="kt_quick_actions_toggle" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Quick Actions">
            <span class="svg-icon svg-icon-xxl">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5" />
                        <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5" />
                        <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5" />
                        <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
        </a> --}}
        <!--end::Quick Actions-->
        <!--begin::Preferences-->
        @actionStart('preferences', 'access')
        <a href="{{route('company.index')}}" class="btn btn-icon btn-lg btn-borderless mb-1">
            <span class="svg-icon svg-icon-xxl">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
                <i class="fa fa-cog text-white"></i>
                <!--end::Svg Icon-->
            </span>
        </a>
        @actionEnd
        <!--end::Preferences-->
        <!--begin::Languages-->
        <a href="{{ route("home") }}" class="btn btn-icon btn-lg btn-borderless">
            <img alt="Logo" src="{{asset('theme/assets/media/logos/default-c.png')}}" class="w-20px h-20px rounded" />
        </a>
        <!--end::Languages-->
    </div>
    <!--end::Footer-->
</div>
