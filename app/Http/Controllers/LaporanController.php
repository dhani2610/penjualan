<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Produk;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
class LaporanController extends Controller
{
    public function getlaporan(Request $request)
    {
        // =============================================================== RANK PRODUK ===================================================================
        $date_from =$request->date_from; 
        $date_to =$request->date_to; 

        $array['prod'] = [];
        foreach (Produk::all() as $prod) {
            if ($date_from != null) {
                $checkStok = Pemesanan::where('id_produk',optional($prod)->id)->whereBetween('created_at', [$date_from,$date_to] )->get()->sum('qty');
            }else{
                $checkStok = Pemesanan::where('id_produk',optional($prod)->id)->get()->sum('qty');
            }
            if ($checkStok > 0) {
                $dprod = [];
                $dprod['id'] = optional($prod)->id;
                $dprod['nama_produk'] = optional($prod)->nama_produk;
                $dprod['total_qty'] = $checkStok;
                array_push($array['prod'], $dprod);
            }   
        }  

        $collectionProd = collect($array['prod']);
        $sortedProd = $collectionProd->sortByDesc('total_qty');
        $dataprod = $this->paginate($sortedProd);
        // =============================================================== END RANK PRODUK ===================================================================

         // =============================================================== RANK CUSTOMER  ===================================================================
        $array['cs'] = [];
        foreach (Customer::all() as $cus) {
            if ($date_from != null) {
                $checkCount = Pemesanan::where('id_customer',optional($cus)->id)->whereBetween('created_at', [$date_from,$date_to] )->get()->count();
            }else {
                $checkCount = Pemesanan::where('id_customer',optional($cus)->id)->get()->count();
            }
            if ($checkCount > 0) {
                $dcus = [];
                $dcus['id'] = optional($cus)->id;
                $dcus['nama_customer'] = optional($cus)->nama_customer;
                $dcus['jumlah_order'] = $checkCount;
                array_push($array['cs'], $dcus);
            }
        }
        
        $collectionCus = collect($array['cs']);
        $sortedCus = $collectionCus->sortByDesc('jumlah_order');
        $datacus = $this->paginate($sortedCus);
        // =============================================================== END RANK CUSTOMER ===================================================================      

         // =============================================================== TOTAL PEMESANAN ===================================================================      
        if ($date_from) {
            $countQuo = Pemesanan::whereBetween('created_at', [$date_from,$date_to])->get()->count();
        }else {
            $countQuo = Pemesanan::get()->count();
        }
        // =============================================================== END TOTAL PEMESANAN ===================================================================      

        return response()->json([
            'msg' => 'Berhasil',
            'total_quotation' => $countQuo,
            'data_rank_produk' => $dataprod,
            'data_rank_customer' => $datacus,
        ]);
    }

    public function paginate($items, $perPage = 10, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
