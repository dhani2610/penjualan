<?php

namespace App\Exports;

use App\Models\Invoice;
use App\Models\Merek;
use App\Models\Pemesanan;
use App\Models\Produk;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoiceExport implements FromView
{
    public function view(): View
    {
        $array['inv'] = [];
        foreach (Invoice::all() as $inv) {
            $dinv = [];
            $quo = Pemesanan::where('id',optional($inv)->id_quo)->first();
            $dinv['id'] = optional($inv)->id;
            $dinv['no_inv'] = optional($inv)->no_inv;
            $dinv['id_quo'] = optional($inv)->id_quo;
            $dinv['no_qt'] = $quo->no_qt;
            $dinv['pembuat'] = optional($inv)->pembuat;
            $dinv['status'] = optional($inv)->status;
            $dinv['keterangan'] = optional($inv)->keterangan;
            $dinv['created_at'] = optional($inv)->created_at;
            $dinv['updated_at'] = optional($inv)->updated_at;
            array_push($array['inv'], $dinv);
        }

        return view('export.invoice-export', [
            'data' => $array['inv'],
        ]);
    }
}