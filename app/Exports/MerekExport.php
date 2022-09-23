<?php

namespace App\Exports;

use App\Models\Merek;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MerekExport implements FromView
{
    public function view(): View
    {
        return view('export.merek-export', [
            'data' => Merek::all()
        ]);
    }
}