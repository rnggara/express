<table class="table table-bordered display">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __("view.city") }}</th>
            <th>{{ __("view.district") }}</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['list'] as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <a href="{{ "?v=$v&p=$item->prov_id&c=$item->city_id" }}">{{ $item->city_name }}</a>
                </td>
                <td>{{ $item->districts }}</td>
                <td>{{ $item->districts }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
