<?php

namespace App\Http\Controllers;

use App\Imports\CustomerImport;
use App\Imports\KategoriProdukImport;
use App\Imports\MerekImport;
use App\Imports\ProdukImport;
use App\Models\KategoriProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class ImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }

    public function importCustomer(Request $request)
    {
        try {
            if($request->file('file')){
                $data = Excel::import(new CustomerImport, request()->file('file'));
                
                return response()->json([
                    'msg' => 'Berhasil Import',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Import',
                'data' => $th->getMessage()
            ]);
        }
    }

    public function importKategoriProduk(Request $request)
    {
        try {
            if($request->file('file')){
                Excel::import(new KategoriProdukImport, request()->file('file'));
                
                return response()->json([
                    'msg' => 'Berhasil Import',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Import',
                'data' => $th->getMessage()
            ]);
        }
    }

    public function importMerek(Request $request)
    {
        try {
            if($request->file('file')){
                Excel::import(new MerekImport, request()->file('file'));
                
                return response()->json([
                    'msg' => 'Berhasil Import',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Import',
                'data' => $th->getMessage()
            ]);
        }
    }

    public function importProduk(Request $request)
    {
        try {
            if($request->file('file')){
                Excel::import(new ProdukImport, request()->file('file'));
                
                return response()->json([
                    'msg' => 'Berhasil Import',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Import',
                'data' => $th->getMessage()
            ]);
        }
    }
}
