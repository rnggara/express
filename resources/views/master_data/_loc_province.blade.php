<table class="table table-bordered display">
    <thead>
        <tr>
            <th>#</th>
            <th>{{ __("view.province") }}</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['list'] as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <a href="{{ "?v=$v&p=$item->prov_id" }}">{{ $item->prov_name }}</a>
                </td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>
