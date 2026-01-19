<div class="card">
    <div class="card-header border-0 px-0">
        <h3 class="card-title">
            <div class="d-flex flex-column">
                <span class="fs-1 fw-bold">{{ ucwords($key) }} Custom Permission</span>
                <span class="fs-base fw-normal mt-2">Setup permission for {{ ucwords($key) }} module</span>
            </div>
        </h3>
    </div>
    <div class="card-body rounded bg-white px-0 ">
        <input type="hidden" name="name" value="{{ $key }}">
        <div class="d-flex flex-column">
            <table class="table gy-7 gs-7 border table-rounded" id="table-perm" data-bInfo="false" data-ordering="false">
                <thead>
                    <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                        <th>
                            Feature
                        </th>
                        @foreach ($actions as $item)
                            <th>{{ ucwords($item) }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perm_data as $val)
                        @php
                            $p = [];
                            $rp = [];
                            if(!empty($view)){
                                $p = $rperm['permissions'] ?? [];
                                $rp = $p[$val] ?? [];
                            }
                        @endphp
                        <tr>
                            <td>
                                {{ ucwords(str_replace("_", " ", $val)) }}
                            </td>
                            @foreach ($actions as $item)
                                @php
                                    $checked = "";
                                    if(!empty($rp)){
                                        if(in_array($item, $rp)) $checked = "checked";
                                    }
                                @endphp
                                <td>
                                    <div class="form-check {{ $item != "view" && $checked == "" ? "form-check-solid" : "" }}">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" {{ $checked }} data-action="{{ $item }}" name="permission[{{ $val }}][{{ $item }}]" {{ $item != "view" && $checked == "" ? "disabled" : "" }} value="1" />
                                        </label>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>