<table border="1">
    <thead>
        <tr>
            <td width="50px">No</td>
            <td width="100px">No Invoice</td>
            <td width="100px">No Quotation</td>
            <td width="100px">Status</td>
            <td width="100px">Keterangan</td>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    @foreach ($data as $item)
    <tbody>
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item['no_inv'] }}</td>
            <td>{{ $item['no_qt'] }}</td>
            <td>{{ $item['status'] }}</td>
            <td>{{ $item['keterangan'] }}</td>
        </tr>
    </tbody>
    @endforeach
</table>