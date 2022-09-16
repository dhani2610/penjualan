<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use Illuminate\Http\Request;

class KategoriProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
                'eror' =>  $th->getMessage(),
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
        try {
            $this->validate($request, [
                'nama_kategori' => 'required',
            ]);
    
            $data =  KategoriProduk::find($id);
            if ($request->input('nama_kategori') != "") {
                $data->nama_kategori = $request->input('nama_kategori');
            }
            $data->save();

            return response()->json([
                'msg' => 'Berhasil Edit Data Kategori Produk',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Edit Data Kategori Produk',
                'eror' =>  $th->getMessage(),
            ]);
        }
    }
    
    function destroy($id)
    {
        $data = KategoriProduk::find($id);
        try {
            $data->delete();
            
            return response()->json([
                'msg' => 'Berhasil Hapus Kategori Produk',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Hapus Kategori Produk',
                'error' =>  $th->getMessage()
            ]);
        }
    }
}
