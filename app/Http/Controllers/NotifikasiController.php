<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
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
        $data = Notifikasi::where('pembuat',$this->guard()->user()->id)->get();
        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data
        ]);
    }

    public function detail($id)
    {
        $data = Notifikasi::where('pembuat',$this->guard()->user()->id)->where('id',$id)->first();

        return response()->json([
            'msg' => 'Berhasil Show Detail',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        try {
            $data = Notifikasi::where('pembuat',$this->guard()->user()->id)->where('id',$id)->first();
            return response()->json([
                'msg' => 'Berhasil Hapus Notifikasi',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Hapus Notifikasi',
                'data' => $th->getMessage()
            ]);
        }
    }

}
