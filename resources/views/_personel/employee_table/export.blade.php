<table>
    <thead>
        <tr>
            <th>Company ID</th>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Address 1</th>
            <th>Address 2</th>
            <th>City 1</th>
            <th>City 2</th>
            <th>Post Code 1</th>
            <th>Post Code 2</th>
            <th>Phone</th>
            <th>Phone Ext.</th>
            <th>Handphone No.</th>
            <th>Office Email</th>
            <th>Email</th>
            <th>Birth Place</th>
            <th>Birth Date</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Blood</th>
            <th>Height</th>
            <th>Weight</th>
            <th>Citizen</th>
            <th>Religion</th>
            <th>Marital Status</th>
            <th>Astek No.</th>
            <th>Astek Date</th>
            <th>Pension Code</th>
            <th>Pension No.</th>
            <th>Pension Date</th>
            <th>Join Date</th>
            <th>Quit Date</th>
            <th>Tahun Kerja</th>
            <th>Bulan Kerja</th>
            <th>Company Group</th>
            <th>Join Group</th>
            <th>Point of Hire</th>
            <th>NPWP</th>
            <th>Hubungan Ahli Waris</th>
            <th>Nama Ahli Waris</th>
            <th>Notes</th>
            <th>Level</th>
            <th>Asuransi Kematian</th>
            <th>Asuransi Kecelakaan</th>
            <th>JHT Karyawan</th>
            <th>JHT Company</th>
            <th>Insc Comp (1)</th>
            <th>Insc No (1)</th>
            <th>Insc Date (1)</th>
            <th>Remark (1)</th>
            <th>Insc Comp (2)</th>
            <th>Insc No (2)</th>
            <th>Insc Date (2)</th>
            <th>Remark (2)</th>
            <th>Hierarchy</th>
            <th>Hierarchy Description</th>
            <th>Hierarchy Mutation Date</th>
            @for($i = 1; $i <= 15; $i++)
                <th>Hierarchy{{ $i }}</th>
                <th>Hierarchy Description{{ $i }}</th>
            @endfor
            <th>Location</th>
            <th>Location Description</th>
            <th>Location Mutation Date</th>
            <th>Grade</th>
            <th>Grade Description</th>
            <th>Grade Mutation Date</th>
            <th>Employee Type</th>
            <th>Employee Type Description</th>
            <th>Employee Type Mutation Start</th>
            <th>Employee Type Mutation End</th>
            <th>Employee Class</th>
            <th>Employee Class Description</th>
            <th>Employee Class Mutation Date</th>
            <th>Title</th>
            <th>Title Description</th>
            <th>Title Mutation Date</th>
            <th>Education Level</th>
            <th>Education Description</th>
            <th>Status</th>
            <th>Cost Center</th>
            <th>Cost Center Desc</th>
            <th>Cost Center Date</th>
            <th>Job Type</th>
            <th>Job Type Description</th>
            <th>Title Memo</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                @foreach ($item as $val)
                    <td>{{ $val }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>