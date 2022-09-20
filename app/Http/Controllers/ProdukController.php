<?php

namespace App\Http\Controllers;

use App\Models\KategoriProduk;
use App\Models\Pemesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use File;


class ProdukController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    public function index()
    {
        $data = Produk::get();
       
        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data,
            'url' => 'http://192.168.1.239/Api-Auth/public/img-produk/'
        ]);
    }

    public function store(Request $request)
    {
        $kat = KategoriProduk::where('id',$request->input('kategori_produk'))->first();
        try {
            $this->validate($request, [
                'nama_produk' => 'required',
                'kategori_produk' => 'required',
                'harga' => 'required',
                'merek' => 'required',
                'stok' => 'required',
                'nama_kategori' => 'nullable',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ]);
    
            $data = New Produk();
            if ($request->input('nama_produk') != "") {
                $data->nama_produk = $request->input('nama_produk');
            }
            if ($request->input('kategori_produk') != "") {
                $data->kategori_produk = $request->input('kategori_produk');
            }
            if ($request->input('harga') != "") {
                $data->harga = $request->input('harga');
            }
            if ($request->input('merek') != "") {
                $data->merek = $request->input('merek');
            }
            if ($request->input('stok') != "") {
                $data->stok = $request->input('stok');
            }
            $data->nama_kategori = $kat->nama_kategori;

            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('img-produk/');
                $image->move($destinationPath, $name);
                $data->img = $name;
            }

            $data->save();

            return response()->json([
                'msg' => 'Berhasil Simpan Data Produk',
                'data' => $data,
                'url' => 'http://192.168.1.239/Api-Auth/public/img-produk/'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Simpan Data Produk',
                'eror' =>  $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        $data = Produk::find($id);

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data,
            'url' => 'http://192.168.1.239/Api-Auth/public/img-produk/'
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $kat = KategoriProduk::where('id',$request->input('kategori_produk'))->first();

        $this->validate($request, [
            'nama_produk' => 'required',
            'kategori_produk' => 'required',
            'harga' => 'required',
            'merek' => 'required',
            'stok' => 'required',
            'nama_kategori' => 'nullable',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        $data =  Produk::find($id);
        $produk = Produk::where('id',$request->input('id_produk'))->first();
        if  ($data) {
            $data->nama_produk = $request->input('nama_produk');
            $data->kategori_produk = $request->input('kategori_produk');
            $data->harga = $request->input('harga');
            $data->merek = $request->input('merek');
            $data->stok = $request->input('stok');
            $data->nama_kategori = $kat->nama_kategori;
                 
            if ($request->hasFile('img')) {
                // Delete Img
                if ($data->img) {
                    $image_path = public_path('img-produk/'.$data->img); // Value is not URL but directory file path
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                
                $image = $request->file('img');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('img-produk/');
                $image->move($destinationPath, $name);
                $data->img = $name;
            }
            $data->update();

            return response()->json([
                'msg' => 'Berhasil Edit Data Invoice',
                'data' => $data,
                'url' => 'http://192.168.1.239/Api-Auth/public/img-produk/'
            ]);
        }else {
            return response()->json([
                'msg' => 'Gagal Edit Data Invoice',
            ]);
        }

    }

    function destroy($id)
    {
        $data = Produk::find($id);
        $check = Pemesanan::where('id_produk',$id)->first();
        try {
            if ($check == null) {
                if ($data->img) {
                    $image_path = public_path('img-produk/'.$data->img); // Value is not URL but directory file path
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
                $data->delete();
                return response()->json([
                    'msg' => 'Berhasil Hapus Produk',
                ]);
            }else {
                return response()->json([
                    'msg' => 'Upss Data Ini Sudah Terpakai',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Hapus Produk',
                'error' =>  $th->getMessage()
            ]);
        }
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}
