<!--begin::Nav-->
                            <ul class="menu-nav">
                                <li class="menu-item" aria-haspopup="true">
                                    <form action='{{ route('menu.direct') }}' method="post">
                                        @csrf
                                        <div class="typeahead input-group input-group-sm">
                                            <input type="text" id="search-menu" dir="ltr" name="menu" class="form-control" placeholder="Search for..." />
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </form>
                                </li>

                                @foreach ($menu_view as $item)
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                                        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="menu-text">{{ $item->label }}</span>
                                            <span class="menu-desc"></span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                    </li>
                                @endforeach

                                @actionStart('general', 'access')
                                <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">General</span>
                                        <span class="menu-desc"></span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu menu-submenu-fixed">
                                        <div class="menu-subnav">
                                            <ul class="menu-content">
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Requests</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        @actionStart('fr', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('fr.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">[IR] Item Request</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('so', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.so')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">[SO] Service Order</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('do', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('do.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">[DO] Delivery Order</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('leave_request', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{URL::route('leave.request')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Leave Request</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Administrations</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        @actionStart('cashbond', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('cashbond.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">{{($accounting_mode == 1) ? "Petty Cash" : "Cashbond"}}</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('reimburse', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('reimburse.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Reimburse</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('meeting_scheduler', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('ms.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Meeting Scheduler</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('mom', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('mom.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">[MoM] Minutes of Meeting </span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @if(!\Illuminate\Support\Facades\Session::get('is_mobile'))
                                                        <li class="menu-item is-covid menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.covid.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Covid News </span>
                                                            </a>
                                                        </li>
                                                        @endif
                                                        @actionStart("asset", 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('ag.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Asset Gallery </span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Operations</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        @actionStart('to', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('to.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Travel Order</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('crewloc', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('crewloc.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Crew Location</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('forum.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Forum</span>
                                                            </a>
                                                        </li>
                                                        @actionStart("do", "access")
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.dr')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Daily Report </span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.operation.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Operation Report </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                @actionStart('performa', 'access')
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Others</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        @actionStart('performa', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.pr.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Performa Review</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('performa', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.doc.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Documents </span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('performa', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('general.maps.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">{{ \Session::get('company_tag') }} Map</span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                        @actionStart('performa', 'access')
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('oletter.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Berita Acara </span>
                                                            </a>
                                                        </li>
                                                        @actionEnd
                                                    </ul>
                                                </li>
                                                @actionEnd
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('asset', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Asset</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">

                                             <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('categoryinventory.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Inventory</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('items.wh')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Inventory Storages</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('category.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Item Database</span>


                                                </a>
                                            </li>
                                            @actionStart('item_approval', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('items.approval')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Item Approval</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('item_assembly', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('items.assembly.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Item Assembly</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('wh.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                    </span>
                                                    <span class="menu-text">Storages</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('general.driver.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Forwarder</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('general.driver.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Mobilization</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('gr.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">[GR] Good Receive</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('asset.legal.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Legal Document</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('items.last.input')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Last Input Item</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('procurement', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Procurement</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('vendor.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Vendor</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('pricelist.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Price List</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('po_wo', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">PO & WO</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('pr.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[PR] Purchase Request</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('pe.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[PE] Purchase Evaluation</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('po.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[PO] Purchase Order</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('sr.index')}}" class="menu-link ">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[SR] Service Request</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('se.index')}}" class="menu-link ">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[SE] Service Evaluation</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('general.wo')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[WO] Work Order</span>


                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('marketing', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Marketing</span>
                                        <span class="menu-desc"></span>
                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            @if($accounting_mode == 1)
                                                @actionStart('leads', 'access')
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('leads.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Leads</span>


                                                    </a>
                                                </li>
                                                @actionEnd
                                                @actionStart('leadsmanagement', 'access')
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('leads.index_management')}}" class="menu-link">
                                                <span class="svg-icon menu-icon">
                                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                    <!--end::Svg Icon-->
                                                    </span>
                                                        <span class="menu-text">Leads Management</span>


                                                    </a>
                                                </li>
                                                @actionEnd
                                            @endif
                                            @actionStart('project', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('marketing.project')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Projects</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('client', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('marketing.client.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Clients</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('project', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('marketing.quotation.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Quotation</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('subcost', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('subcost.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Sub Cost</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('b_p', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('bp.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Bid & Performance</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('marketing.doc.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Documents </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('hrd', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">HRD</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            @actionStart('announcement', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('announcement.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Announcement</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('employee', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('employee.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Employee</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('employee', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('hrd.crewnotif.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Crew Operation Notificaton</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('employee', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('hrd.crewhr.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Crew Location HR</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('overtime', 'access')
                                            <li class="menu-item menu-item-submenu">
                                                <a href="{{route('overtime.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Overtime</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('emp_loan', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('employee.loan')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Employee Loan</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('payroll', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('payroll.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Payroll</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('leave_approval', 'access')
                                            <li class="menu-item menu-item-submenu">
                                                <a href="{{URL::route('leave.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Leave Approval</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('bonus', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('subsidies.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Bonus</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('deduction', 'access')
                                             <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('sanction.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Deduction</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('point', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('point.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Point</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('event.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Event</span>


                                                </a>
                                            </li>
                                            @actionStart('severance', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('severance.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Severance</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('training', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('training.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Training</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('official_letter', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('decree.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Official Letter</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('policy', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('policy.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Policy</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('csr', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('csr.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[CSR] Corporate Social Responsibility</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @actionStart('employee', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('hrd.contract.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Contract Template</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('finance', 'access')
                                <li class="menu-item menu-item-submenu" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Finance</span>
                                        <span class="menu-desc"></span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu menu-submenu-fixed">
                                        <div class="menu-subnav">
                                            <ul class="menu-content">
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Treasury</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('treasury.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Treasury</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('inv_in.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">{{ ($accounting_mode == 1) ? "Account Payable" : "Invoice In" }}</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('ar.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">{{ ($accounting_mode == 1) ? "Account Receivable" : "Invoice Out" }}</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('sp.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Schedule Payment</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Utilization</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('util.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Utilization</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('loan.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Loan</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('leasing.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Leasing</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('bp.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Bid & Performance</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Salary</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('salfin.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Salary Financing</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.bpjs')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">BPJS Kesehatan</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.bpjs_tk')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">BPJS TK</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{URL::route('payroll.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Payroll</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{URL::route('coa.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Transaction Codes</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{URL::route('coa.source.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">{{ !empty(\Session::get('company_tc_initial')) ? \Session::get('company_tc_initial') : "TC" }} Assignment</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="menu-item">
                                                    <h3 class="menu-heading menu-toggle">
                                                        <span class="menu-text">Others</span>
                                                        <i class="menu-arrow"></i>
                                                    </h3>
                                                    <ul class="menu-inner">
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('gj.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Input Journal</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('fts.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Transaction Summary</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('business.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                    <span class="menu-text">Business</span>
                                                                </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.fd.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Filling Document</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.br.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Budget Request</span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item" aria-haspopup="true">
                                                            <a href="{{route('finance.dp.index')}}" class="menu-link">
                                                                <i class="menu-bullet menu-bullet-line">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">Depreciation </span>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                            <a href="{{route('catering.index')}}" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                <span class="menu-text">Catering Order</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Reports</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('report.ar.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Account Receivable</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('report.ap.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Account Payable</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('tax.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
														</span>
                                                    <span class="menu-text">Tax Report</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('report.journal.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Sales & Receivables Journal</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('report.journal_general.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">General Journal</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('gl.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">General Ledger</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('report.tb.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Trial Balance</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('bs.list')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Balance Sheet</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('pl.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Profit & Loss</span>
                                                </a>
                                            </li>
                                            <li class="menu-item" aria-haspopup="true">
                                                <a href="{{route('finance.cf.list')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Cash Flow</span>
                                                </a>
                                            </li>
                                            @actionStart('charts', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('chart.custom.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Custom Charts</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                            @if (\Auth::id() == 1)
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('report.er.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon"></span>
                                                    <span class="menu-text">Exchange Rate</span>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @if($accounting_mode == 1)
                                    @actionStart('trading', 'access')
                                    <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="menu-text">Trading</span>
                                            <span class="menu-desc"></span>
                                        </a>
                                        <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                            <ul class="menu-subnav">
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('trading.orders.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Orders</span>
                                                        <!--client-->

                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('trading.products.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Products</span>
                                                        <!--client-->

                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('trading.supplier.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Suppliers</span>
                                                        <!--vendor-->

                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('trading.market.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Markets</span>
                                                        <!--client-->

                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    @actionEnd
                                @endif

                                @if($accounting_mode != 1)
                                    @actionStart('technical_engineering', 'access')
                                    <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                        <a href="javascript:;" class="menu-link menu-toggle">
                                            <span class="menu-text">Technical Engineering</span>
                                            <span class="menu-desc"></span>


                                        </a>
                                        <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                            <ul class="menu-subnav">
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.el.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Equipment List</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.pd.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Project Design</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.swt.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Surface Welltesting</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.subwt.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Subsurface Welltesting</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.slickline.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                        <span class="menu-text">Slickline</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.instrument.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                        <span class="menu-text">Instrumentation</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.testeq.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                        <span class="menu-text">Test Equipment</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                    <a href="{{route('te.pv.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                        <span class="menu-text">Pressure Vessel</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    @actionEnd
                                @endif
                                @actionStart('h_a', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Higher Authority</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('ha.powoval.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">PO/WO Validation</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('ha.powotypes.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">PO/WO Types</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('salarylist.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Salary List</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('bankceo.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">CEO Bank</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('insceo.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Insurance CEO</span>


                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('ha.password.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Passwords</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('ha.ve.index')}}" class="menu-link">
                                                        <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Asset Vehicles</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @actionStart('qhse', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">QHSE</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">

                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('mcu.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">[MCU] Medical Check Up</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('csr.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">[CSR] Corporate Social Responsibility</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('nearmiss.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Near Miss</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('miss.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Miss</span>
                                                </a>
                                            </li>
                                            @actionStart('policy', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{URL::route('policy.hse.index')}}" class="menu-link">
														<span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                            <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Policy</span>


                                                </a>
                                            </li>
                                            @actionEnd
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('sm.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Safety Meeting</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('mv.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">Management Visit</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('sop.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">[SOP] Standard Operating Procedure</span>
                                                </a>
                                            </li>
                                            @actionStart('employee', 'access')
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('qhse.csms.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
                                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
                                                        </span>
                                                    <span class="menu-text">CSMS</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('qhse.tr.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Training Record</span>
                                                </a>
                                            </li>
                                            @actionEnd
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd
                                @if($accounting_mode != 1)
                                {{-- @actionStart('charts', 'access')
                                <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                    <a href="javascript:;" class="menu-link menu-toggle">
                                        <span class="menu-text">Charts</span>
                                        <span class="menu-desc"></span>


                                    </a>
                                    <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                        <ul class="menu-subnav">

                                            <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                                <a href="{{route('chart.custom.index')}}" class="menu-link">
                                                    <span class="svg-icon menu-icon">
															<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->

                                                        <!--end::Svg Icon-->
														</span>
                                                    <span class="menu-text">Custom Charts</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                @actionEnd --}}
                                @endif
                            </ul>
                            <!--end::Nav-->
