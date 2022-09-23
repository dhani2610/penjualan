<?php

namespace App\Http\Controllers;

use App\Models\Merek;
use App\Models\Notifikasi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerekController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }

    public function index()
    {
        $data = Merek::get();

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data 
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nama_merek' => 'required',
            ]);
    
            $data = New Merek();
            if ($request->input('nama_merek') != "") {
                $data->nama_merek = $request->input('nama_merek');
            }
            $data->save();

            $newNotifikasi = new Notifikasi();
            $newNotifikasi->judul = 'Berhasil Menambah Merek';
            $newNotifikasi->deskripsi = 'Anda Berhasil Menambahkan Merek '.$request->input('nama_merek');
            $newNotifikasi->datetime = date('Y-m-d H:i:s');
            $newNotifikasi->pembuat =  $this->guard()->user()->id;
            $newNotifikasi->from = 'Merek';
            $newNotifikasi->save();

            return response()->json([
                'msg' => 'Berhasil Simpan Data Merek',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal  Simpan Data Merek',
                'eror' =>  $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        $data = Merek::find($id);
        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data
        ]);
    }

    public function update(Request $request,$id)
    {
        try {
            $this->validate($request, [
                'nama_merek' => 'required',
            ]);
    
            $data =  Merek::find($id);
            if ($request->input('nama_merek') != "") {
                $data->nama_merek = $request->input('nama_merek');
            }
            $data->save();

            $newNotifikasi = new Notifikasi();
            $newNotifikasi->judul = 'Berhasil Edit Merek';
            $newNotifikasi->deskripsi = 'Anda Berhasil Mengedit Merek '.$request->input('nama_merek');
            $newNotifikasi->datetime = date('Y-m-d H:i:s');
            $newNotifikasi->pembuat =  $this->guard()->user()->id;
            $newNotifikasi->from = 'Merek';
            $newNotifikasi->save();

            return response()->json([
                'msg' => 'Berhasil Edit Data Merek',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Edit Data Merek',
                'eror' =>  $th->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        $data = Merek::find($id);
        $checkquo = Produk::where('id_merek',$id)->first();
        try {
            if ($checkquo == null) {
                $data->delete();
                
                $newNotifikasi = new Notifikasi();
                $newNotifikasi->judul = 'Berhasil Hapus Merek';
                $newNotifikasi->deskripsi = 'Anda Berhasil Hapus Merek '.$data->nama_merek;
                $newNotifikasi->datetime = date('Y-m-d H:i:s');
                $newNotifikasi->pembuat =  $this->guard()->user()->id;
                $newNotifikasi->from = 'Merek';
                $newNotifikasi->save();

                return response()->json([
                    'msg' => 'Berhasil Hapus Merek',
                    'data' => $data
                ]);
            }else {
                return response()->json([
                    'msg' => 'Upss Data Sudah Digunakan',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Hapus Merek',
                'error' =>  $th->getMessage()
            ]);
        }
    }
}
