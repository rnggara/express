<div id="kt_quick_notifications" class="offcanvas offcanvas-left p-10">
    <!--begin::Header-->
    <div class="offcanvas-header d-flex align-items-center justify-content-between mb-10">
        <h3 class="font-weight-bold m-0">Company Selector</h3>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_notifications_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>
    <!--end::Header-->
    <!--begin::Content-->
    <div class="offcanvas-content pr-5 mr-n5">
        <!--begin::Nav-->
        <div class="navi navi-icon-circle navi-spacer-x-0">
            @if(Auth::user()->username != 'cypher')<!--c4only-->
                @foreach(Session::get('comp_user') as $k => $valK)
                    @foreach($comp as $value)
                        @if($value->id == $valK)
                            <a href="{{URL::route('company.switch')."?id=".$value->id}}" class="navi-item">
                                <div class="navi-link rounded">
                                    <div class="symbol symbol-50 symbol-circle mr-5">
                                        <div class="symbol-label">
                                            <img src='{{str_replace("public", "public_html", asset('images/'.$value->app_logo))}}' height='30px' alt="Company Logo"/>
                                        </div>
                                    </div>
                                    <div class="navi-text">
                                        <div class="font-weight-bold font-size-lg">{{$value->company_name}}</div>
                                    </div>
                                </div>
                            </a>
                        @endif
                    @endforeach
                @endforeach
            @else
                @foreach($comp as $value)
                    <a href="{{URL::route('company.switch')."?id=".$value->id}}" class="navi-item">
                        <div class="navi-link rounded">
                            <div class="symbol symbol-50 symbol-circle mr-5">
                                <div class="symbol-label">
                                    <img src='{{str_replace("public", "public_html", asset('images/'.$value->app_logo))}}' height='30px' alt="Company Logo"/>
                                </div>
                            </div>
                            <div class="navi-text">
                                <div class="font-weight-bold font-size-lg">{{$value->company_name}}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
        <!--end::Nav-->
    </div>
    <!--end::Content-->
</div>
