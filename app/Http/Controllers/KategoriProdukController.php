<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Produk;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    
    public function index()
    {
        $data = KategoriProduk::get();
        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data
        ]);
    }


    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nama_kategori' => 'required',
            ]);
            
            $data = KategoriProduk::create([
                'nama_kategori'=>$request->nama_kategori,
            ]);
            $data = New KategoriProduk();
            if ($request->input('nama_kategori') != "") {
                $data->nama_kategori = $request->input('nama_kategori');
            }
            $data->save();


            return response()->json([
                'msg' => 'Berhasil Simpan Data Kategori Produk',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Simpan Data Kategori Produk',
                'eror' =>  $request->all(),
            ]);
        }
    }

    public function edit($id)
    {
        $data = KategoriProduk::find($id);

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama_kategori' => 'required',
        ]);
        $data =  KategoriProduk::find($id);
        if  ($data) {
            $data->nama_kategori = $request->input('nama_kategori');
            $data->update();

            return response()->json([
                'msg' => 'Berhasil Edit Data Kategori Produk',
                'data' => $data
            ]);
        }else {
            return response()->json([
                'msg' => 'Gagal Edit Data Kategori Produk',
            ]);
        }
    }
    
    function destroy($id)
    {
        $data = KategoriProduk::find($id);
        $checkProd = Produk::where('kategori_produk',$id)->first();
        try {
            // if ($checkProd == null) {
                $data->delete();
                
                return response()->json([
                    'msg' => 'Berhasil Hapus Kategori Produk',
                    'data' => $data
                ]);
            // }else {
            //     return response()->json([
            //         'msg' => 'Upss Data Sudah Digunakan',
            //     ]);
            // }
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Hapus Kategori Produk',
                'error' =>  $th->getMessage()
            ]);
        }
    }
}
