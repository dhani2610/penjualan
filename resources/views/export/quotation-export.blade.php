<table border="1">
    <thead>
        <tr>
            <td width="50px">No</td>
            <td width="100px">No Quotaion</td>
            <td width="100px">Customer</td>
            <td width="100px">Produk</td>
            <td width="100px">Qty</td>
            <td width="100px">Status</td>
            <td width="100px">Total</td>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    @foreach ($data as $item)
    <tbody>
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item['no_qt'] }}</td>
            <td>{{ $item['nama_customer'] }}</td>
            <td>{{ $item['nama_produk'] }}</td>
            <td>{{ $item['qty'] }}</td>
            <td>{{ $item['status'] }}</td>
            <td>{{ $item['total'] }}</td>
        </tr>
    </tbody>
    @endforeach
</table>