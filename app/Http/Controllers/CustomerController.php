<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Notifikasi;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
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
        $data = Customer::get();
        return response()->json([
            'msg' => 'berhasil',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'nama_customer' => 'required',
                'no_tlp' => 'required|numeric',
                'email' => 'required|string|email|max:100|unique:customers',
                'alamat' => 'required',
            ]);
    
            $data = New Customer();
            // $data = Customer::create($request->all());
            if ($request->input('nama_customer') != "") {
                $data->nama_customer = $request->input('nama_customer');
            }
            if ($request->input('no_tlp') != "") {
                $data->no_tlp = $request->input('no_tlp');
            }
            if ($request->input('email') != "") {
                $data->email = $request->input('email');
            }
            if ($request->input('alamat') != "") {
                $data->alamat = $request->input('alamat');
            }
            $data->save();


            $newNotifikasi = new Notifikasi();
            $newNotifikasi->judul = 'Berhasil Menambah Customer';
            $newNotifikasi->deskripsi = 'Anda Berhasil Menambahkan Customer '.$request->input('nama_customer');
            $newNotifikasi->datetime = date('Y-m-d H:i:s');
            $newNotifikasi->pembuat =  $this->guard()->user()->id;
            $newNotifikasi->from = 'Customer';
            $newNotifikasi->save();

            return response()->json([
                'msg' => 'Berhasil Simpan Data Customer',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Simpan Data Customer',
                'error' =>  $th->getMessage(),
            ]);
        }
    }

    public function edit($id)
    {
        $data = Customer::find($id);

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data
        ]);
    }

    public function updateCus(Request $request,$id)
    {
        $data =  Customer::find($id);
        if  ($data) {
            $data->nama_customer = $request->input('nama_customer');
            $data->no_tlp = $request->input('no_tlp');
            $data->email = $request->input('email');
            $data->alamat = $request->input('alamat');
            $data->update();

            $newNotifikasi = new Notifikasi();
            $newNotifikasi->judul = 'Berhasil Edit Customer';
            $newNotifikasi->deskripsi = 'Anda Berhasil Mengedit Customer '.$data->nama_customer;
            $newNotifikasi->datetime = date('Y-m-d H:i:s');
            $newNotifikasi->pembuat =  $this->guard()->user()->id;
            $newNotifikasi->from = 'Customer';
            $newNotifikasi->save();

            return response()->json([
                'msg' => 'Berhasil Edit Data Customer',
                'data' => $data
            ]);
        }else {
            return response()->json([
                'msg' => 'Gagal Edit Data Customer',
            ]);
        }
    }

    public function destroy($id)
    {
        $data = Customer::find($id);
        $checkquo = Pemesanan::where('id_customer',$id)->first();
        try {
            if ($checkquo == null) {
                $data->delete();
                
                $newNotifikasi = new Notifikasi();
                $newNotifikasi->judul = 'Berhasil Hapus Customer';
                $newNotifikasi->deskripsi = 'Anda Berhasil Hapus Customer '.$data->nama_customer;
                $newNotifikasi->datetime = date('Y-m-d H:i:s');
                $newNotifikasi->pembuat =  $this->guard()->user()->id;
                $newNotifikasi->from = 'Customer';
                $newNotifikasi->save();

                return response()->json([
                    'msg' => 'Berhasil Hapus Customer',
                    'data' => $data
                ]);
            }else {
                return response()->json([
                    'msg' => 'Upss Data Sudah Digunakan',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Hapus Customer',
                'error' =>  $th->getMessage()
            ]);
        }
    }
}
