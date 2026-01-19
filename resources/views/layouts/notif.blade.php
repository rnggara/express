@if(count($notif) > 0)
    @foreach($notif as $item)
    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <div class="menu-content fs-6 text-dark px-3 py-4">
            <!--end::Menu item-->
            <div class="d-flex py-3 notif-div align-items-center">
                <i class="fa fa-dot-circle me-3"></i>
                <span onclick="notification_click({{ $item['id'] }}, this)" class="text-dark-75 font-weight-bold text-hover-primary cursor-pointer font-size-lg mb-1 me-3">
                    {{$item['text']}}
                </span>
                <a href="{{ route('notif.clear', ["type" => base64_encode('clear'), 'id' => base64_encode($item['id'])]) }}" onclick="return confirm('are you sure?')" class="mb-1 ml-5 text-hover-danger">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
    </div>
    @endforeach
@else
<!--begin::Menu item-->
<div class="menu-item px-3">
    <div class="menu-content fs-6 text-dark px-3 py-4">No Notifications</div>
</div>
<!--end::Menu item-->
@endif
