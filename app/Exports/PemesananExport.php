<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Pemesanan;
use App\Models\Produk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PemesananExport implements FromView
{
    public function view(): View
    {
        $array['quo'] = [];
        foreach (Pemesanan::all() as $quo) {
            $dquo = [];
            $customer = Customer::where('id',optional($quo)->id_customer)->first();
            $produk = Produk::where('id',optional($quo)->id_produk)->first();
            $dquo['id'] = optional($quo)->id;
            $dquo['no_qt'] = optional($quo)->no_qt;
            $dquo['id_customer'] = optional($quo)->id_customer;
            $dquo['nama_customer'] = $customer->nama_customer;
            $dquo['id_produk'] = optional($quo)->id_produk ;
            $dquo['nama_produk'] = $produk->nama_produk ;
            $dquo['qty'] = optional($quo)->qty;
            $dquo['total'] = optional($quo)->total;
            $dquo['pembuat'] = optional($quo)->pembuat;
            $dquo['status'] = optional($quo)->status;
            $dquo['created_at'] = optional($quo)->created_at;
            $dquo['updated_at'] = optional($quo)->updated_at;
            array_push($array['quo'], $dquo);
        }

        return view('export.quotation-export', [
            'data' => $array['quo'],
        ]);
    }
}