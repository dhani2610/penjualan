<table border="1">
    <thead>
        <tr>
            <td width="50px">No</td>
            <td width="100px">Nama Customer</td>
            <td width="100px">Nomor Telephone</td>
            <td width="100px">Email</td>
            <td width="100px">Alamat</td>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    @foreach ($data as $item)
    <tbody>
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item->nama_customer }}</td>
            <td>{{ $item->no_tlp }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->alamat }}</td>
        </tr>
    </tbody>
    @endforeach
</table>