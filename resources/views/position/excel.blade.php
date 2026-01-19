<table border="1">
    <thead>
    <tr>
        <th>Module Name</th>
        @foreach ($actionList as $item)
            <th>{{ strtoupper($item->name) }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach ($moduleList as $module)
            <tr>
                <td>{{ $module->name }}</td>
                @foreach ($actionList as $action)
                    @php
                        $hasPriv = $privs->where("id_rms_modules", $module->id)->where("id_rms_actions", $action->id)->first();
                    @endphp
                    <th>{{ !empty($hasPriv) ? "v" : "-" }}</th>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
