<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Pemesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
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
        $data = Invoice::get();

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data
        ]);
    }

    public function storeDraftInvoice(Request $request)
    {
        $update = Invoice::where('status','Draft')->where('pembuat',$this->guard()->user()->id)->first();
       
        if ($request->input('status') == 'Draft'){
            try {
                $update->update([
                    'status' => 'Draft',
                    'id_quo' => $request->input('id_quo'),
                    'keterangan' => $request->input('keterangan')
                ]);

                return response()->json([
                    'msg' => 'Berhasil Simpan Draft',
                    'data' =>  $update
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'msg' => 'Gagal Simpan Draft',
                    'error' =>  $th->getMessage()
                ]);
            }
        }else{
            try {
                $this->validate($request, [ 
                    'id_quo' => 'required||numeric',
                    'pembuat' => 'nullable|numeric',
                    'status' => 'nullable',
                    'keterangan' => 'required',
                ]);
        
                $data = [
                    'id_quo' => $request->input('id_quo'),
                    'pembuat' => $this->guard()->user()->id,
                    'status' => 'Draft',
                    'keterangan' => $request->input('keterangan'),
                ];
    
                Invoice::create($data);
    
                return response()->json([
                    'msg' => 'Berhasil Draft Invoice',
                    'data' => $data
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'msg' => 'Gagal Draft Invoice',
                    'error' =>  $th->getMessage(),
                ]);
            }
        }
    }

    public function storeSavedInvoice(Request $request)
    {
        $update = Invoice::where('status','Draft')->where('pembuat',$this->guard()->user()->id)->first();

        if ($request->input('status') == 'Draft'){
            try {
                $update->update([
                    'status' => 'Pending Invoice',
                    'id_quo' => $request->input('id_quo'),
                    'keterangan' => $request->input('keterangan')
                ]);

                return response()->json([
                    'msg' => 'Berhasil Simpan Draft',
                    'data' =>  $update
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'msg' => 'Gagal Simpan Draft',
                    'error' =>  $th->getMessage()
                ]);
            }
        }else {
            try {
                $this->validate($request, [ 
                    'id_quo' => 'required||numeric',
                    'pembuat' => 'nullable|numeric',
                    'status' => 'nullable',
                    'keterangan' => 'required',
                ]);
        
                $data = [
                    'id_quo' => $request->input('id_quo'),
                    'pembuat' => $this->guard()->user()->id,
                    'status' => 'Pending Invoice',
                    'keterangan' => $request->input('keterangan'),
                ];
    
                Invoice::create($data);
    
                return response()->json([
                    'msg' => 'Berhasil Draft Invoice',
                    'data' => $data
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'msg' => 'Gagal Draft Invoice',
                    'error' =>  $th->getMessage(),
                ]);
            }
        }

    }

    public function getDraft()
    {
        $data = Invoice::where('status','Draft')->where('pembuat',$this->guard()->user()->id)->first();

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data,
        ]);
    }


    public function sendInvoice($id)
    {
        $check = Invoice::find($id);

        if ($check->status == 'Pending Invoice') {
           $check->update(['status'=> 'Invoiced']);

            return response()->json([
                'msg' => 'Berhasil Kirim Invoice',
                'data' => $check
            ]);
        }elseif ($check->status == 'Invoiced'){
            return response()->json([
                'msg' => 'Maaf Status Quotation yang anda Pilih Sudah di Kirim',
            ]);
        }elseif ($check->status == 'Received Payment'){
            return response()->json([
                'msg' => 'Maaf Status Quotation yang anda Pilih Sudah Selesai',
            ]);
        }else {
            return response()->json([
                'msg' => 'Maaf Status Invoice yang anda Pilih Masih Draft',
            ]);
        }
    }

    public function edit($id)
    {
        $data = Invoice::find($id);

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data
        ]);
    }

    public function update(Request $request,$id)
    {
        try {

            $this->validate($request, [
                'id_quo' => 'required|numeric',
                'keterangan' => 'required',
            ]);
    
            $data = Invoice::find($id);

            $data->update([
                'id_quo' => $request->input('id_quo'),
                'keterangan' =>$request->input('keterangan')
            ]);
       
            return response()->json([
                'msg' => 'Berhasil Edit Invoice',
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Edit Invoice',
                'error' => $th->getMessage(),
            ]);
        }

    }

    public function destroy($id)
    {
        $data = Invoice::find($id);

        if ($data->delete()) {
            return response()->json([
                'msg' => 'Behasil Hapus Invoice',
            ]);
        }else {
            return response()->json([
                'msg' => 'Gagal Hapus Invoice',
            ]);
        }
    }

    public function payment($id)
    {
        $check = Invoice::find($id);
        
        if ($check->status == 'Invoiced') {
            $check->update(['status'=> 'Received Payment']);

            return response()->json([
                'msg' => 'Berhasil Payment',
                'data' => $check
            ]);
        }else {
            return response()->json([
                'msg' => 'Maaf Status Invoice yang anda Pilih Masih '.$check->status,
                'data' => $check
            ]);
        }
    }


    public function generateInvoice($id)
    {
        $data = Invoice::find($id);
        $Pemesanan = Pemesanan::find($data->id_quo);
        $Customer = Customer::where('id',$Pemesanan->id_customer)->first();
        $produk = Produk::where('id',$Pemesanan->id_produk)->first();
        $dataInvoice = [
            'no_qt' => $Pemesanan->no_qt,
            'nama_customer' => $Customer->nama_customer,
            'alamat_customer' => $Customer->alamat,
            'nama_produk' => $produk->nama_produk,
            'qty' => $Pemesanan->qty,
            'total_harga' => $Pemesanan->total,
            'status' => $data->status,
            'pembuat' => $data->pembuat,
            'keterangan' =>  $data->keterangan
        ];

        return response()->json([
            'msg' => 'Berhasil Show Detail',
            'data' => $dataInvoice
        ]);
    }
    
  


}