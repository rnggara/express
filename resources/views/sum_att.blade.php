<table border="1">
    <thead>
        <tr>
            <th>company_id</th>
            <th>employee_id</th>
            <th>salary_group_id</th>
            <th>period_id</th>
            <th>working_hours</th>
            <th>effective_working_hours</th>
            <th>break_time</th>
            <th>late</th>
            <th>home_early</th>
            <th>overtime</th>
            <th>overtime_index</th>
            <th>work_day</th>
            <th>off_day</th>
            <th>national_holiday</th>
            <th>salary_cut_off_day</th>
            <th>shift_id</th>
            <th>working_group_id</th>
            <th>working_group_date</th>
            <th>leave_group_id</th>
            <th>leave_group_date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            @if (!empty($item['salary_group_id']) && !empty($item['period_id']))
            <tr>
                <td>{{ $item['company_id'] }}</td>
                <td>{{ $item['employee_id'] }}</td>
                <td>{{ $item['salary_group_id'] }}</td>
                <td></td>
                <td>{{ $item['working_hours'] }}</td>
                <td>{{ $item['effective_working_hours'] }}</td>
                <td>{{ $item['break_time'] }}</td>
                <td>{{ $item['late'] }}</td>
                <td>{{ $item['home_early'] }}</td>
                <td>{{ $item['overtime'] }}</td>
                <td>1</td>
                <td>{{ $item['workday'] }}</td>
                <td>{{ $item['off_day'] }}</td>
                <td>{{ $item['national_day'] }}</td>
                <td>{{ $item['salary_cut_off_day'] }}</td>
                <td>{{ $item['shift_id'] ?? "" }}</td>
                <td>{{ $item['working_group_code'] }}</td>
                <td>{{ $item['working_group_date'] }}</td>
                <td>{{ $item['leave_group_code'] }}</td>
                <td>{{ $item['leave_group_date'] }}</td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table>