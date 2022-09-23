<?php

namespace App\Exports;

use App\Models\KategoriProduk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KategoriProdukExport implements FromView
{
    public function view(): View
    {
        return view('export.kategori-produk-export', [
            'data' => KategoriProduk::all()
        ]);
    }
}