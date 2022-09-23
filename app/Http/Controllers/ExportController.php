<?php

namespace App\Http\Controllers;

use App\Exports\CustomerExport;
use App\Exports\InvoiceExport;
use App\Exports\KategoriProdukExport;
use App\Exports\MerekExport;
use App\Exports\PemesananExport;
use App\Exports\ProdukExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
 
    public function guard()
    {
        return Auth::guard('api');
    }
    
    public function exportCustomer()
    {
        return Excel::download(new CustomerExport, 'Customer.xlsx');
    }

    public function exportKategoriProduk()
    {
        return Excel::download(new KategoriProdukExport, 'Kategori Produk.xlsx');
    }

    public function exportMerek()
    {
        return Excel::download(new MerekExport, 'Merek.xlsx');
    }

    public function exportProduk()
    {
        return Excel::download(new ProdukExport, 'Produk.xlsx');
    }

    public function exportQuotation()
    {
        return Excel::download(new PemesananExport, 'Quotation.xlsx');
    }

    public function exportInvoice()
    {
        return Excel::download(new InvoiceExport, 'Invoice.xlsx');
    }
}
