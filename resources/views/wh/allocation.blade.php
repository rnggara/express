@extends('layouts.template')

@section('content')
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3>Items need rack assignment  - <span class="text-primary"><strong>{{$wh->name}}</strong></span></h3><br>
            </div>
            <div class="card-toolbar">
                <div class="btn-group" role="group" aria-label="Basic example">
                </div>
                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <table class="table display mb-10">
                <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-left">Item Name</th>
                    <th class="text-center">Item Code</th>
                    <th class="text-center">Stock in Storage</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                    @php
                        $num = 1;
                    @endphp
                    @for($i=0;$i<count($itemsId); $i++)
                        @php
                            $balance = (isset($itemInSlot[$itemsId[$i]])) ? array_sum($itemInSlot[$itemsId[$i]]) : 0;
                            $qty = (isset($itemsQty[$itemsId[$i]]))?$itemsQty[$itemsId[$i]]['qty'] : 0;
                            $qty -= $balance;
                        @endphp
                        @if ($qty > 0 && (isset($item_code[$itemsId[$i]])))
                            <tr>
                                <td align="center">
                                    <div class="d-flex justify-content-center">
                                        {{$num++}}
                                        <div class="checkbox-inline">
                                            <label class="checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                                <input type="checkbox" onclick="_cb_clicked(this)" data-id="{{ $itemsId[$i] }}" data-qty="{{ $qty }}" class="cb-check"/>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td>{{(isset($item_name[$itemsId[$i]]))?$item_name[$itemsId[$i]]['name'] : $itemsId[$i]}}</td>
                                <td align="center">{{(isset($item_code[$itemsId[$i]])) ? $item_code[$itemsId[$i]]['code'] : ""}}</td>
                                <td align="center">{{$qty}}&nbsp;{{(isset($item_uom[$itemsId[$i]]))?$item_uom[$itemsId[$i]]['uom'] : ""}}</td>
                                <td align="center">
                                    <form action="{{ route('rak.transfer', ['act' => 'allocate']) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="data[{{ $itemsId[$i] }}]" value="{{ $qty }}">
                                        <input type="hidden" name="storage" value="{{ $wh->id }}">
                                        <input type="hidden" name="rak" value="">
                                        <input type="hidden" name="slot" value="">
                                        <button type="submit" class="btn btn-primary btn-sm">Allocate</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endfor
                </tbody>
            </table>
            <form action="{{ route('rak.transfer', ['act' => 'allocate']) }}" method="post">
                @csrf
                <div class="d-flex mt-10">
                    <div id="form-sb"></div>
                    <input type="hidden" name="storage" value="{{ $wh->id }}">
                    <input type="hidden" name="rak" value="">
                    <input type="hidden" name="slot" value="">
                    <button type="submit" id="btn-batch" class="btn btn-primary">Batch Allocation</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_script')
    <script>

        function _cb_clicked(me){
            var id = $(me).data("id")
            var qty = $(me).data("qty")
            if($(me).prop("checked")){
                pushtojs(id, qty)
            } else {
                popfromjs(id)
            }
        }

        function pushtojs(id, qty){
            var inp = "<input type='hidden' name='data["+id+"]' value='"+qty+"' class='cb_id' id='item-"+id+"'>"
            console.log(inp)
            $("#form-sb").append(inp)
        }

        function popfromjs(id){
            $("#item-"+id).remove()
        }

        $(document).ready(function() {
            $("table.display").DataTable({
                fixedHeader: true,
                fixedHeader: {
                    headerOffset: 90
                },
            })

            $(".cb-check:checked").each(function(){
                this.checked = false
            })

            $("#btn-batch").click(function(e){
                var cb_id = $(".cb_id")
                if(cb_id.length == 0){
                    e.preventDefault()
                    Swal.fire("Need items", 'at least select 1 item to batch allocation', 'warning')
                    $(".cb-check:checked").each(function(){
                        this.checked = false
                    })
                }
            })


        })
    </script>
@endsection
