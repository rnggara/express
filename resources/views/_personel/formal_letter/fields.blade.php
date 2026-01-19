@foreach ($flds as $item)
    @php
        $value = "";
        if(!empty($item->field_emp)){
            $kk = $item->field_emp;
            $value = $emp->$kk ?? $kk;
        }
    @endphp
    <div class="fv-row">
        <h3 class="col-form-label">{{ ucwords(str_replace("_", " ", $item->name)) }}</h3>
        @if ($item->type_data == "int")
        <div class="">
            <input type="number" value="{{ $value }}" placeholder="Input {{ $item->description }}" required class="form-control {{ (!empty($item->field_emp)) ? "field_emp" : "" }}" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : ((str_replace(" ", "_", strtolower($item->name)) == "periode_kontrak") ? "id=periode_kontrak" : "") }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
        </div>
        @elseif ($item->type_data == "text")
        <div class="">
            <input type="text" value="{{ $value }}" placeholder="Input {{ $item->description }}" required class="form-control {{ (!empty($item->field_emp)) ? "field_emp" : "" }}" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : "" }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
        </div>
        @elseif ($item->type_data == "time")
        <div class="">
            <input type="time" value="{{ $value }}" required class="form-control {{ (!empty($item->field_emp)) ? "field_emp" : "" }}" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : "" }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
        </div>
        @elseif ($item->type_data == "date")
        <div class="">
            <input type="text" placeholder="Input {{ $item->description }}" value="{{ $value }}" required class="form-control flatpicker {{ (!empty($item->field_emp)) ? "field_emp" : "" }}" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : "" }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
        </div>
        @elseif ($item->type_data == "currency")
        <div class="">
            <input type="text" value="{{ $value }}" placeholder="Input {{ $item->description }}" required class="form-control {{ (!empty($item->field_emp)) ? "field_emp" : "" }} number" {{ (!empty($item->field_emp)) ? "id=$item->field_emp" : "" }} name="fld[{{ str_replace(" ", "_", strtolower($item->name)) }}]">
        </div>
        @endif
    </div>
@endforeach