<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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

            return response()->json([
                'msg' => 'Berhasil Simpan Data Customer',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal  Simpan Data Customer',
                'eror' =>  $th->getMessage(),
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

    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'nama_customer' => 'required',
                'no_tlp' => 'required|numeric',
                'email' => 'required|string|email|max:100|unique:customers',
                'alamat' => 'required',
            ]);
    
            $data =  Customer::find($id);
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

            return response()->json([
                'msg' => 'Berhasil Edit Data Customer',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Edit Data Customer',
                'error' =>  $th->getMessage()
            ]);
        }
  
    }


    public function destroy($id)
    {
        $data = Customer::find($id);
        try {
            $data->delete();
            
            return response()->json([
                'msg' => 'Berhasil Hapus Customer',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Hapus Customer',
                'error' =>  $th->getMessage()
            ]);
        }
    }
}
