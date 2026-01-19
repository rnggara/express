<table class="table table-bordered display">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __("view.district") }}</th>
            <th>{{ __("view.subdistrict") }}</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['list'] as $i => $item)
            @php
                $item->prov_id = $data['city']->prov_id;
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <a href="{{ "?v=$v&p=$item->prov_id&c=$item->city_id" }}">{{ $item->dis_name }}</a>
                </td>
                <td>{{ $item->subdist }}</td>
                <td>{{ $item->subdist }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
