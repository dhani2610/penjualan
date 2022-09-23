<table border="1">
    <thead>
        <tr>
            <td width="50px">No</td>
            <td width="100px">Nama Produk</td>
            <td width="100px">Merek</td>
            <td width="100px">Kategori Produk</td>
            <td width="100px">Stok</td>
            <td width="100px">Harga</td>
        </tr>
    </thead>
    @php
        $no = 1;
    @endphp
    @foreach ($data as $item)
    <tbody>
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $item['nama_produk'] }}</td>
            <td>{{ $item['nama_merek'] }}</td>
            <td>{{ $item['nama_kategori'] }}</td>
            <td>{{ $item['stok'] }}</td>
            <td>{{ $item['harga'] }}</td>
        </tr>
    </tbody>
    @endforeach
</table>