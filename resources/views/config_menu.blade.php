@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title">Config Menu</h3>
            <div class="card-toolbar">
                <div class="btn-group">

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered display table-responsive">
                        <thead>
                            <tr class="bg-dark">
                                <th class="text-center text-white">#</th>
                                <th class="text-center text-white">Label</th>
                                <th class="text-center text-white">Role Control</th>
                                <th class="text-center text-white">Route</th>
                                @foreach ($actions as $act)
                                    <th class="text-center text-white">{{ $act->name }}</th>
                                @endforeach
                                <th class="text-center text-white">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($list_menu as $i => $item)
                                @php
                                    $rc_item = json_decode($item->rc, true);
                                @endphp
                                <tr class="bg-light-primary">
                                    <td class="font-weight-bold" align="center">{{ $i + 1 }}</td>
                                    <td class="font-weight-bold">{{ $item->label }}</td>
                                    <td class="font-weight-bold">{{ $item->rc_name ?? "-" }}</td>
                                    <td class="font-weight-bold">{{ $item->route ?? "-" }}</td>
                                    @foreach ($actions as $act)
                                        <td align="center">
                                            <div class="checkbox-inline justify-content-center">
                                                <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                    <input type="checkbox" name="Checkboxes16" onclick="_toggle_rc(this, {{ $item->id }}, '{{ $act->name }}')" {{ (isset($rc_item[$act->name]) && $rc_item[$act->name] == 1) ? "CHECKED" : "" }} />
                                                    <span></span>
                                                </label>
                                            </div>
                                        </td>
                                    @endforeach
                                    <td class="font-weight-bold" align="center">
                                        <button type="button" onclick="_toggle_menu({{ $item->id }}, '{{ ($item->show_menu) ? 'Hide' : 'Show' }}', '{{ ucwords($item->label) }}')" class="btn btn-{{ ($item->show_menu) ? "primary" : "danger" }} btn-xs">
                                            <i class="fa fa-{{ ($item->show_menu) ? "eye" : "eye-slash" }}"></i>  {{ ($item->show_menu) ? "enabled" : "disabled" }}
                                        </button>
                                    </td>
                                </tr>
                                @php
                                    $menu_sub_headers = $item->sub_headers;
                                    $menu_links = $item->links;
                                    $num = 1;
                                @endphp
                                @if (count($menu_sub_headers) > 0)
                                    @foreach ($menu_sub_headers as $msub)
                                        @php
                                            $isub = $num++;
                                            $rc_sub = json_decode($msub->rc, true);
                                        @endphp
                                        <tr class="bg-light-secondary">
                                            <td align="center">{{ ($i + 1).".$isub" }}</td>
                                            <td>
                                                <i class="flaticon2-right-arrow"></i>
                                                {{ $msub->label }}
                                            </td>
                                            <td>{{ $msub->rc_name ?? "-" }}</td>
                                            <td>{{ $msub->route ?? "-" }}</td>
                                            @foreach ($actions as $act)
                                                <td align="center">
                                                    <div class="checkbox-inline justify-content-center">
                                                    <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                        <input type="checkbox" name="Checkboxes16" onclick="_toggle_rc(this, {{ $msub->id }}, '{{ $act->name }}')" {{ (isset($rc_sub[$act->name]) && $rc_sub[$act->name] == 1) ? "CHECKED" : "" }}/>
                                                        <span></span>
                                                    </label>
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td align="center">
                                                <button type="button" onclick="_toggle_menu({{ $msub->id }}, '{{ ($msub->show_menu) ? 'Hide' : 'Show' }}', '{{ ucwords($msub->label) }}')" class="btn btn-{{ ($msub->show_menu) ? "primary" : "danger" }} btn-xs">
                                                    <i class="fa fa-{{ ($msub->show_menu) ? "eye" : "eye-slash" }}"></i> {{ ($msub->show_menu) ? "enabled" : "disabled" }}
                                                </button>
                                            </td>
                                        </tr>
                                        @php
                                            $sub_links = $msub->links;
                                            $numlink = 1;
                                        @endphp
                                        @foreach ($sub_links as $slink)
                                            @php
                                                $ilink = $numlink++;
                                                $slink_label = $slink->label;
                                                $rc_link = json_decode($slink->rc, true);
                                            @endphp
                                            <tr>
                                                <td align="center">{{ ($i + 1).".$isub.$ilink" }}</td>
                                                <td>
                                                    <i class="flaticon2-right-arrow ml-5"></i>
                                                    {{ ($slink_label[0] == "{") ? eval("echo ".str_replace("{", "", $slink_label).";") : $slink_label  }}
                                                </td>
                                                <td>{{ $slink->rc_name ?? "-" }}</td>
                                                <td>{{ $slink->route ?? "-" }}</td>
                                                @foreach ($actions as $act)
                                                    <td align="center">
                                                        <div class="checkbox-inline justify-content-center">
                                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                            <input type="checkbox" name="Checkboxes16" onclick="_toggle_rc(this, {{ $slink->id }}, '{{ $act->name }}')" {{ (isset($rc_link[$act->name]) && $rc_link[$act->name] == 1) ? "CHECKED" : "" }}/>
                                                            <span></span>
                                                        </label>
                                                        </div>
                                                    </td>
                                                @endforeach
                                                <td align="center">
                                                    <button type="button" onclick="_toggle_menu({{ $slink->id }}, '{{ ($slink->show_menu) ? 'Hide' : 'Show' }}', '{{ ($slink_label[0] == "{") ? eval("echo ".str_replace("{", "", $slink_label).";") : $slink_label  }}')" class="btn btn-{{ ($slink->show_menu) ? "primary" : "danger" }} btn-xs">
                                                        <i class="fa fa-{{ ($slink->show_menu) ? "eye" : "eye-slash" }}"></i> {{ ($slink->show_menu) ? "enabled" : "disabled" }}
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @else
                                    @foreach ($menu_links as $slink)
                                        @php
                                            $ilink = $num++;
                                            $slink_label = $slink->label;
                                            $rc_link = json_decode($slink->rc, true);
                                        @endphp
                                        <tr>
                                            <td align="center">{{ ($i + 1).".$ilink" }}</td>
                                            <td>
                                                <i class="flaticon2-right-arrow"></i>
                                                {{ ($slink_label[0] == "{") ? eval("echo ".str_replace("{", "", $slink_label).";") : $slink_label  }}
                                            </td>
                                            <td>{{ $slink->rc_name ?? "-" }}</td>
                                            <td>{{ $slink->route ?? "-" }}</td>
                                            @foreach ($actions as $act)
                                                    <td align="center">
                                                        <div class="checkbox-inline justify-content-center">
                                                        <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                            <input type="checkbox" name="Checkboxes16" onclick="_toggle_rc(this, {{ $slink->id }}, '{{ $act->name }}')" {{ (isset($rc_link[$act->name]) && $rc_link[$act->name] == 1) ? "CHECKED" : "" }}/>
                                                            <span></span>
                                                        </label>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            <td align="center">
                                                <button type="button" onclick="_toggle_menu({{ $slink->id }}, '{{ ($slink->show_menu) ? 'Hide' : 'Show' }}', '{{ ($slink_label[0] == "{") ? eval("echo ".str_replace("{", "", $slink_label).";") : $slink_label  }}')" class="btn btn-{{ ($slink->show_menu) ? "primary" : "danger" }} btn-xs">
                                                    <i class="fa fa-{{ ($slink->show_menu) ? "eye" : "eye-slash" }}"></i> {{ ($slink->show_menu) ? "enabled" : "disabled" }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        function _toggle_menu(id, label, name){
            Swal.fire({
                title: "Are you sure?",
                text: `${label} menu ${name}`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(function(result) {
                if (result.value) {
                    window.location = "{{ route("config.menu.toggle") }}/"+id
                }
            });
        }

        function _toggle_rc(me, id, rc){
            var _label = "Uncheck"
            if(me.checked !== false){
                _label = "Check"
            }

            Swal.fire({
                title: "Are you sure?",
                text: `${_label} ${rc}`,
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes"
            }).then(function(result) {
                if (result.value) {
                    // window.location = "{{ route("config.menu.toggle") }}/"+id
                    $.ajax({
                        url : "{{ route("config.menu.rc_update") }}",
                        type : "post",
                        dataType : "json",
                        beforeSend : function(){
                            Swal.fire({
                                title: "Loading...",
                                text: "",
                                onOpen: function() {
                                    Swal.showLoading()
                                }
                            })
                        },
                        data : {
                            id : id,
                            rc : rc,
                            _token : "{{ csrf_token() }}"
                        },
                        success : function(response){
                            swal.close()
                            if(response.rc){
                                me.checked = true
                            } else {
                                me.checked = false
                            }
                        }

                    })
                } else {
                    me.checked = !me.checked
                }
            });
        }

        $(document).ready(function(){
            $("table.display").DataTable({
                sorting : false,
                pageLength : 50,
            })
        })
    </script>
@endsection
