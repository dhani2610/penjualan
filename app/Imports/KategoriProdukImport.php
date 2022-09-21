<?php

namespace App\Imports;

use App\Models\KategoriProduk;
use Maatwebsite\Excel\Concerns\ToModel;

class KategoriProdukImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $data = [
            'nama_kategori' => $row[0],
        ];
        KategoriProduk::create($data);
    }
}
