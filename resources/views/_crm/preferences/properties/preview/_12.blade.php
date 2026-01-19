<div class="fv-row mb-5">
    <label for="" class="col-form-label">{{ ucwords($name ?? "Property name") }}</label>
    @for ($i = 0; $i < 5; $i++)
        @php
            $color = $additional['color'] ?? [];
            $_val = $color[$i - 1] ?? "#EBEBEB";
        @endphp
        <div class="row row-cols-4 bg-secondary-crm rounded border mb-5 h-30px">
            @for ($j = 0; $j < 4; $j ++)
                @php
                    $k = 5-$i;
                    if($j >= $k){
                        $_val = "#EBEBEB";
                    }
                @endphp
                <div class="border border-bottom-0 border-top-0 col {{ $j == 0 ? "rounded-start border-right border-left-0" : (($j == 3) ? "rounded-end border-right-0" : "border-right") }}" style="background-color: {{ $_val }}">&nbsp;</div>
            @endfor

            {{-- <div class="border border-bottom-0 border-left-0 border-right border-top-0 col rounded-left" style="background-color: {{ $_val }}">&nbsp;</div>
            <div class="border border-bottom-0 border-left-0 border-right border-top-0 col rounded-left" style="background-color: {{ $_val }}">&nbsp;</div>
            <div class="col rounded-end" style="background-color: {{ $_val }}">&nbsp;</div> --}}
        </div>
    @endfor
</div>
