@if ($leave_request->count() == 0)
    <div class="d-flex align-items-center flex-column h-100 justify-content-center">
        <span class="fi fi-rr-document fs-3tx text-muted"></span>
        <span class="text-muted">No Data Available</span>
    </div>
@else
    <div class="d-flex flex-column scroll h-175px">
        @foreach ($leave_request as $item)
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-40px">
                        <div class="symbol-label" style="background-image : url({{ asset($item->emp->user->user_img ?? "images/image_placeholder.png") }})"></div>
                    </div>
                    <div class="mx-2"></div>
                    <div class="d-flex flex-column">
                        <span class="fw-bold">{{ $item->emp->emp_name }}</span>
                        <span>{{ $item->emp->user->uacdepartement->name ?? "-" }}</span>
                    </div>
                </div>
                @php
                    $days = 0;
                    $d1 = date_create(date("Y-m-d"));
                    $d2 = date_create($item->created_at);
                    $d3 = date_diff($d1, $d2);
                    $days = $d3->format("%a");

                    $cls = "warning";
                    if($days > 7){
                        $cls = "danger";
                    }

                    $label = "$days Days Ago";

                    if(date("Y-m-d", strtotime($item->created_at)) == date("Y-m-d")){
                        $label = "Today";
                    }

                @endphp
                <div class="d-flex flex-column">
                    <span class="opacity-75">Request</span>
                    <span class="text-{{ $cls }}">{{ $label }}</span>
                </div>
            </div>
            <div class="my-3 border"></div>
        @endforeach
    </div>
@endif
