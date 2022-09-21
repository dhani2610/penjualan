<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;

class CustomerImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $data = [
            'nama_customer' => $row[0],
            'no_tlp' => $row[1],
            'email' => $row[2],
            'alamat' => $row[3]
        ];
        Customer::create($data);
    }
}
