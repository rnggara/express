@extends('layouts.template')

@section('css')

@endsection

@section('content')
    <div class="card card-custom gutter-b card-stretch">
        <div class="card-header">
            <h3 class="card-title"></h3>
            <div class="card-toolbar">
                <div class="btn-group">
                    <input type="text" name="searc_menu" class="form-control" placeholder="Search menu..." onkeyup="_search_menu(this)" id="">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($menu_mobile as $item)
                    @actionStart("$item->rc_name", 'access')
                        <div class="col-6 mb-5 menu-view">
                            <div class="card card-custom card-stretch cursor-pointer bg-hover-dark-o-4 text-hover-white" onclick="_go_to('{{route($item->route)}}')">
                                <div class="card-body">
                                    <div class="symbol-list d-flex flex-wrap flex-column justify-content-center align-items-center">
                                        <div class="symbol symbol-lg-30 symbol-circle">
                                            <img src="{{ asset('images/menu/'.$item->img_name) }}"/>
                                        </div>
                                        <span class="text-center menu-label">{{ ($item->label[0] == "{") ? eval("echo ".str_replace("{", "", $item->label).";") : $item->label  }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @actionEnd
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        function _go_to(uri){
            window.location = uri
        }

        function _show_all(){
            $(".menu-view").show()
        }

        function _hide_all(){
            $(".menu-view").hide()
        }

        var menus = []
        var div_menu = $("div.menu-view").find("span.menu-label")
        for (let i = 0; i < div_menu.length; i++) {
            const element = div_menu[i];
            var col = {}
            col["text"] = $(element).text()
            col['index'] = i
            menus.push(col)

        }
        console.log(menus)
        console.log(div_menu[0])

        function _search_menu(params){
            var term = $(params).val(); // search term (regex pattern)
            if(term != ""){
                _hide_all()
                var search = new RegExp(term , 'i'); // prepare a regex object
                let b = menus.filter(item => item.text.toLowerCase().indexOf(term) > -1);
                for (let _i = 0; _i < b.length; _i++) {
                    const el = b[_i];
                    var _span = div_menu[el.index]
                    var parents = $(_span).parents("div.menu-view").show()
                }
            } else {
                _show_all()
            }
        }

        $(document).ready(function(){

        })
    </script>
@endsection
