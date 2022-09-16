<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use File;


class ProdukController extends Controller
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
        $data = Produk::get();
        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data,
            'url' => 'http://192.168.2.58/Api-Auth/public/img-produk/'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nama_produk' => 'required',
                'kategori_produk' => 'required',
                'harga' => 'required',
                'merek' => 'required',
                'stok' => 'required',
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
                'url' => 'http://192.168.2.58/Api-Auth/public/img-produk/'
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
            'url' => 'http://192.168.2.58/Api-Auth/public/img-produk/'
        ]);
    }
    
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'nama_produk' => 'required',
                'kategori_produk' => 'required',
                'harga' => 'required',
                'merek' => 'required',
                'stok' => 'required',
                'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            ]);
    
            $data =  Produk::find($id);
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
             
         if ($request->hasFile('img')) {
            // Delete Img
            if ($data->img) {
                $image_path = public_path('img/datas/'.$data->img); // Value is not URL but directory file path
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
            $data->save();

            return response()->json([
                'msg' => 'Berhasil Simpan Data Produk',
                'data' => $data,
                'url' => 'http://192.168.2.58/Api-Auth/public/img-produk/'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Simpan Data Produk',
                'eror' =>  $th->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    function destroy($id)
    {
        $data = Produk::find($id);
        try {
            $data->delete();
            
            return response()->json([
                'msg' => 'Berhasil Hapus Produk',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Hapus Produk',
                'error' =>  $th->getMessage()
            ]);
        }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('api');
    }
}
