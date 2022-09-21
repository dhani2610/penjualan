<?php

namespace App\Imports;

use App\Models\KategoriProduk;
use App\Models\Merek;
use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;

class ProdukImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $Kategori = KategoriProduk::where('nama_kategori', $row[1])->first();
        $idMerek = Merek::where('nama_merek', $row[4])->first()->id;
        
        $data = [
            'nama_produk' => $row[0] ?? null,
            'kategori_produk' => $Kategori->id,
            'harga' => $row[2] ?? null,
            'stok' => $row[3] ?? null,
            'id_merek' =>  $idMerek,
            'img' => null,
            'nama_kategori' => $Kategori->nama_kategori,
        ];
        Produk::create($data);
    }
}
