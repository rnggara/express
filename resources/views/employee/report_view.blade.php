<table class="table table-bordered display">
    <thead>
        <tr>
            <th class="text-center">#</th>
            @foreach ($cols as $item)
                <th class="text-center">{{ ucwords(str_replace("_", " ", $item)) }}</th>
            @endforeach
            @foreach ($addCols as $idTp => $tp)
                <th class="text-center">{{ ucwords(str_replace("_", " ", $tp)) }}</th>
                @if ($with_expiry == 1)
                    <th class="text-center">Expired Date</th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $num = 1;
        @endphp
        @foreach ($employee as $i => $emp)
            @if (in_array($emp->emp_type, $typeshow))
                <tr>
                    <td align="center">{{ $num++ }}</td>
                    @foreach ($cols as $item)
                        @php
                            $val = $emp[$item] ?? "-";
                            if($item == "emp_name"){
                                $val = "<a href='".route('employee.detail',['id'=>$emp->id])."'>$val</a>";
                            } elseif($item == "emp_type"){
                                $val = "-";
                                $et = $etype->where("id", $emp[$item])->first();
                                if(!empty($et)){
                                    $val = $et->name;
                                }
                            } elseif($item == "division"){
                                $val = "-";
                                $et = $divs->where("id", $emp[$item])->first();
                                if(!empty($et)){
                                    $val = $et->name;
                                }
                            } if($item == "join_date"){
                                $val = "-";
                                $et = $jdate->where("emp_id", $emp->id)->first();
                                if(!empty($et)){
                                    $val = $et->act_date;
                                }
                            }
                            $align = "left";
                            if(in_array($item, ['salary', "health", "transport", "meal", "house"])){
                                $val = base64_decode($val);
                            }
                            if(is_numeric($val)){
                                $align = "right";
                                $val = number_format($val);
                            }
                        @endphp
                        <td align="{{ $align }}">{!! $val !!}</td>
                    @endforeach
                    @foreach ($addCols as $idTp => $tp)
                        @php
                            $empDocLabel = "x";
                            $expDate = "N/A";
                            $empDoc = $hasDoc->where("category_id", $idTp)->where('user_id', $emp->id)->first();
                            if(!empty($empDoc)){
                                $empDocLabel = "v";
                                $expDate = $empDoc->expiry_date;
                            }
                        @endphp
                        <td align="center">{{ $empDocLabel }}</td>
                        @if ($with_expiry == 1)
                            <td align="center">{{ $expDate }}</td>
                        @endif
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
