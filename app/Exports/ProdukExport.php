<?php

namespace App\Exports;

use App\Models\Merek;
use App\Models\Pemesanan;
use App\Models\Produk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProdukExport implements FromView
{
    public function view(): View
    {
        $array['prod'] = [];
        foreach (Produk::all() as $prod) {
            $dprod = [];
            $checkStok = Pemesanan::where('id_produk',optional($prod)->id)->get()->sum('qty');
            $merek = Merek::where('id',optional($prod)->id_merek)->first();
            $dprod['nama_produk'] = optional($prod)->nama_produk;
            $dprod['kategori_produk'] = optional($prod)->kategori_produk;
            $dprod['harga'] = optional($prod)->harga ;
            $dprod['nama_merek'] = $merek->nama_merek;
            $dprod['stok'] = optional($prod)->stok - $checkStok;
            $dprod['img'] = optional($prod)->img;
            $dprod['nama_kategori'] = optional($prod)->nama_kategori;
            array_push($array['prod'], $dprod);
        }

        return view('export.produk-export', [
            'data' => $array['prod'],
        ]);
    }
}