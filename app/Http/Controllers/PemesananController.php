<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Pemesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananController extends Controller
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
        $data = Pemesanan::get();
        $array['quo'] = [];
        foreach (Pemesanan::all() as $quo) {
            $dquo = [];
            $customer = Customer::where('id',optional($quo)->id_customer)->first();
            $produk = Produk::where('id',optional($quo)->id_produk)->first();
            $dquo['id'] = optional($quo)->id;
            $dquo['no_qt'] = optional($quo)->no_qt;
            $dquo['id_customer'] = optional($quo)->id_customer;
            $dquo['nama_customer'] = $customer->nama_customer;
            $dquo['id_produk'] = optional($quo)->id_produk ;
            $dquo['nama_produk'] = $produk->nama_produk ;
            $dquo['qty'] = optional($quo)->qty;
            $dquo['total'] = optional($quo)->total;
            $dquo['pembuat'] = optional($quo)->pembuat;
            $dquo['status'] = optional($quo)->status;
            $dquo['created_at'] = optional($quo)->created_at;
            $dquo['updated_at'] = optional($quo)->update_at;
            array_push($array['quo'], $dquo);
        }
        return response()->json([
            'msg' => 'Berhasil',
            'data' => $array['quo']
        ]);
    }

    public function storeDraft(Request $request)
    {
        $update = Pemesanan::where('status','Draft')->where('pembuat',$this->guard()->user()->id)->first();
        $produk = Produk::where('id',$request->input('id_produk'))->first();
        // $checkStok = Pemesanan::where('id_produk',$request->input('id_produk'))->get()->sum('qty');
        
        if ($request->input('status') == 'Draft') {
            try {
                $update->update([
                    'status' => 'Draft',
                    'id_customer' =>$request->input('id_customer'),
                    'id_produk' =>$request->input('id_produk'),
                    'qty' => $request->input('qty'),
                    'total' => $request->input('qty') * $produk->harga,
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
                    'no_qt' => 'nullable',
                    'id_customer' => 'required|numeric',
                    'id_produk' => 'required|numeric',
                    'qty' => 'required|numeric',
                    'total' => 'nullable|numeric',
                    'status' => 'nullable',
                    'pembuat' => 'nullable',
                ]);
        
                $data = New Pemesanan();
    
                if ($request->input('id_customer') != "") {
                    $data->id_customer = $request->input('id_customer');
                }
                if ($request->input('id_produk') != "") {
                    $data->id_produk = $request->input('id_produk');
                }
                if ($request->input('qty') != "") {
                    $data->qty = $request->input('qty');
                }
                $data->total = $request->input('qty') * $produk->harga;
                $data->no_qt = 'QO-'.rand();
                $data->status = 'Draft';
                $data->pembuat = $this->guard()->user()->id;
               
                $data->save();
    
                return response()->json([
                    'msg' => 'Berhasil Draft Pemesanan',
                    'data' => $data,
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'msg' => 'Gagal Draft Pemesanan',
                    'error' =>  $th->getMessage(),
                ]);
            }
        }
    }

    public function getDraft()
    {
        $data = Pemesanan::where('status','Draft')->where('pembuat',$this->guard()->user()->id)->first();

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data
        ]);
    }

    public function storeSaved(Request $request)
    {
        $update = Pemesanan::where('status','Draft')->where('pembuat',$this->guard()->user()->id)->first();
        $produk = Produk::where('id',$request->input('id_produk'))->first();

        if ($request->input('status') == 'Draft') {
            try {
                $update->update([
                    'status' => 'Pending Quotation',
                    'id_customer' =>$request->input('id_customer'),
                    'id_produk' =>$request->input('id_produk'),
                    'qty' => $request->input('qty'),
                    'total' => $request->input('qty') * $produk->harga,
                ]);

                return response()->json([
                    'msg' => 'Berhasil Simpan Draft',
                    'data' =>  $update
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'msg' => 'Gagal Simpan Draft',
                    'error' => $th->getMessage()
                ]);
            }
        }else{
            try {
                $this->validate($request, [
                    'no_qt' => 'nullable',
                    'id_customer' => 'required|numeric',
                    'id_produk' => 'required|numeric',
                    'qty' => 'required',
                    'total' => 'nullable',
                    'status' => 'nullable',
                    'pembuat' => 'nullable',
                ]);
        
                $data = New Pemesanan();
    
                if ($request->input('id_customer') != "") {
                    $data->id_customer = $request->input('id_customer');
                }
                if ($request->input('id_produk') != "") {
                    $data->id_produk = $request->input('id_produk');
                }
                if ($request->input('qty') != "") {
                    $data->qty = $request->input('qty');
                }
                $data->total = $request->input('qty') * $produk->harga;
                $data->no_qt = 'QO-'.rand();
                $data->status = 'Pending Quotation';
                $data->pembuat = $this->guard()->user()->id;
                $data->save();
    
                return response()->json([
                    'msg' => 'Berhasil Simpan Pemesanan',
                    'data' => $data
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'msg' => 'Gagal Simpan Pemesanan',
                    'error' =>  $th->getMessage(),
                ]);
            }
        }
    }

    public function send($id)
    {
        $check = Pemesanan::find($id);

        if ($check->status == 'Pending Quotation') {
           $check->update(['status'=> 'Sent']);

            return response()->json([
                'msg' => 'Berhasil Kirim Pemesanan',
                'data' => $check
            ]);
        }else {
            return response()->json([
                'msg' => 'Maaf Status Quotation yang anda Pilih Masih Belum Bisa Dikirim',
            ]);
        }
    }

    public function edit($id)
    {
        $data = Pemesanan::find($id);

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $data
        ]);
    }
    

    public function update(Request $request,$id)
    {
        $this->validate($request, [
            'id_customer' => 'required|numeric',
            'id_produk' => 'required|numeric',
            'qty' => 'required|numeric',
            'total' => 'nullable|numeric',
        ]);

        $data =  Pemesanan::find($id);
        $produk = Produk::where('id',$request->input('id_produk'))->first();
        if  ($data) {
            $data->id_customer = $request->input('id_customer');
            $data->id_produk = $request->input('id_produk');
            $data->qty = $request->input('qty');
            $data->total = $request->input('qty') * $produk->harga;
            $data->update();

            return response()->json([
                'msg' => 'Berhasil Edit Data Pemesanan',
                'data' => $data
            ]);
        }else {
            return response()->json([
                'msg' => 'Gagal Edit Data Pemesanan',
            ]);
        }
    }

    public function detail($id)
    {
        try {
            $Pemesanan = Pemesanan::find($id);
            $Customer = Customer::where('id',$Pemesanan->id_customer)->first();
            $produk = Produk::where('id',$Pemesanan->id_produk)->first();
            $dataInvoice = [
                'no_qt' => $Pemesanan->no_qt,
                'id_customer' => $Customer->nama_customer,
                'alamat_customer' => $Customer->alamat,
                'nama_produk' => $produk->nama_produk,
                'qty' => $Pemesanan->qty,
                'total_harga' => $Pemesanan->total,
                'status' => $Pemesanan->status,
                'pembuat' => $Pemesanan->pembuat,
            ];
    
            return response()->json([
                'msg' => 'Berhasil Show Detail',
                'data' => $dataInvoice
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Show Detail',
                'error' => $th->getMessage()
            ]);
        }
    }
 
    public function destroy($id)
    {
        $data = Pemesanan::find($id);
        $check = Invoice::where('id_quo',$id)->first();
        try {
            if ( $check == null) {
                $data->delete();
    
                return response()->json([
                    'msg' => 'Behasil Hapus Pemesanan',
                    'data' => $data
                ]);
            }else {
                return response()->json([
                    'msg' => 'Upss Data Sudah Digunakan',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'msg' => 'Gagal Hapus Pemesanan',
                'error' => $th->getMessage()
            ]);
        }

    }

  
}
