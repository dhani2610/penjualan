<?php

namespace App\Imports;

use App\Models\Merek;
use Maatwebsite\Excel\Concerns\ToModel;

class MerekImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $data = [
            'nama_merek' => $row[0],
        ];
        Merek::create($data);
    }
}
