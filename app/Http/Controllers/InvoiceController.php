<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Notifikasi;
use App\Models\Pemesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

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
        $array['inv'] = [];
        foreach (Invoice::all() as $inv) {
            $dinv = [];
            $quo = Pemesanan::where('id',optional($inv)->id_quo)->first();
            $dinv['id'] = optional($inv)->id;
            $dinv['no_inv'] = optional($inv)->no_inv;
            $dinv['id_quo'] = optional($inv)->id_quo;
            $dinv['no_qt'] = $quo->no_qt;
            $dinv['pembuat'] = optional($inv)->pembuat;
            $dinv['status'] = optional($inv)->status;
            $dinv['created_at'] = optional($inv)->created_at;
            $dinv['updated_at'] = optional($inv)->updated_at;
            array_push($array['inv'], $dinv);
        }

        return response()->json([
            'msg' => 'Berhasil',
            'data' => $array['inv']
        ]);
    }

    public function storeDraftInvoice(Request $request)
    {
        $update = Invoice::where('status','Draft')->where('pembuat',$this->guard()->user()->id)->first();
       
        $no = 'NO-INV-'.rand();
        if ($request->input('status') == 'Draft'){
            try {
                $update->update([
                    'status' => 'Draft',
                    'id_quo' => $request->input('id_quo'),
                    'keterangan' => $request->input('keterangan')
                ]);

                $newNotifikasi = new Notifikasi();
                $newNotifikasi->judul = 'Berhasil Draft Invoice';
                $newNotifikasi->deskripsi = 'Anda Berhasil Draft Invoice NO. '.$update->no_inv;
                $newNotifikasi->datetime = date('Y-m-d H:i:s');
                $newNotifikasi->pembuat =  $this->guard()->user()->id;
                $newNotifikasi->from =  'Invoice';
                $newNotifikasi->save();
                
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
            $data = Invoice::create([
                'id_quo' => $request->input('id_quo'),
                'pembuat' => $this->guard()->user()->id,
                'status' => 'Draft',
                'keterangan' => $request->input('keterangan'),
                'no_inv' => $no,
            ]);
            if ($data) {
                
                $newNotifikasi = new Notifikasi();
                $newNotifikasi->judul = 'Berhasil Simpan Invoice';
                $newNotifikasi->deskripsi = 'Anda Berhasil Simpan Invoice NO. '.$no;
                $newNotifikasi->datetime = date('Y-m-d H:i:s');
                $newNotifikasi->pembuat =  $this->guard()->user()->id;
                $newNotifikasi->from =  'Invoice';
                $newNotifikasi->save();

                return response()->json([
                    'msg' => 'Berhasil Draft Invoice',
                    'data' => $data
                ]);
            }else {
                return response()->json([
                    'msg' => 'Gagal Draft Invoice',
                    'error' =>  $data,
                ]);
            }
       
        }
    }

    public function storeSavedInvoice(Request $request)
    {
        $update = Invoice::where('status','Draft')->where('pembuat',$this->guard()->user()->id)->first();
        // dd( $request->input('status') == 'Draft');
        $no = 'NO-INV-'.rand();
        if ($request->input('status') == 'Draft'){
            try {
                $update->update([
                    'status' => 'Pending Invoice',
                    'id_quo' => $request->input('id_quo'),
                    'keterangan' => $request->input('keterangan')
                ]);

                $newNotifikasi = new Notifikasi();
                $newNotifikasi->judul = 'Berhasil Simpan Draft Invoice';
                $newNotifikasi->deskripsi = 'Anda Berhasil Simpan Draft Invoice NO. '.$update->no_inv;
                $newNotifikasi->datetime = date('Y-m-d H:i:s');
                $newNotifikasi->pembuat =  $this->guard()->user()->id;
                $newNotifikasi->from =  'Invoice';
                $newNotifikasi->save(); 

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
            $data = [
                'id_quo' => $request->input('id_quo'),
                'pembuat' => $this->guard()->user()->id,
                'status' => 'Pending Invoice',
                'keterangan' => $request->input('keterangan'),
                'no_inv' => $no,
            ];

            $check = Invoice::create($data);

            if ($check) {
                $newNotifikasi = new Notifikasi();
                $newNotifikasi->judul = 'Berhasil Simpan Invoice';
                $newNotifikasi->deskripsi = 'Anda Berhasil Simpan Invoice NO. '.$no;
                $newNotifikasi->datetime = date('Y-m-d H:i:s');
                $newNotifikasi->pembuat =  $this->guard()->user()->id;
                $newNotifikasi->from =  'Invoice';
                $newNotifikasi->save(); 

                return response()->json([
                    'msg' => 'Berhasil Saved Invoice',
                    'data' => $data
                ]);
            }else {
                return response()->json([
                    'msg' => 'Gagal Draft Invoice',
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

           $newNotifikasi = new Notifikasi();
           $newNotifikasi->judul = 'Berhasil Kirim Invoice';
           $newNotifikasi->deskripsi = 'Anda Berhasil Kirim Invoice NO. '.$check->no_inv;
           $newNotifikasi->datetime = date('Y-m-d H:i:s');
           $newNotifikasi->pembuat =  $this->guard()->user()->id;
           $newNotifikasi->from =  'Invoice';
           $newNotifikasi->save();

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

            $newNotifikasi = new Notifikasi();
            $newNotifikasi->judul = 'Berhasil Edit Invoice';
            $newNotifikasi->deskripsi = 'Anda Berhasil Mengedit Invoice NO. '.$data->no_inv;
            $newNotifikasi->datetime = date('Y-m-d H:i:s');
            $newNotifikasi->pembuat =  $this->guard()->user()->id;
            $newNotifikasi->from = 'Invoice';
            $newNotifikasi->save();
       
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

            $newNotifikasi = new Notifikasi();
            $newNotifikasi->judul = 'Berhasil Hapus Invoice';
            $newNotifikasi->deskripsi = 'Anda Berhasil Hapus Invoice '.$data->no_inv;
            $newNotifikasi->datetime = date('Y-m-d H:i:s');
            $newNotifikasi->pembuat =  $this->guard()->user()->id;
            $newNotifikasi->from = 'Invoice';
            $newNotifikasi->save();

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

            $newNotifikasi = new Notifikasi();
            $newNotifikasi->judul = 'Berhasil Received Payment Invoice';
            $newNotifikasi->deskripsi = 'Anda Berhasil Received Payment Invoice '.$check->no_inv;
            $newNotifikasi->datetime = date('Y-m-d H:i:s');
            $newNotifikasi->pembuat =  $this->guard()->user()->id;
            $newNotifikasi->from = 'Invoice';
            $newNotifikasi->save();

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
        if ( $data != null) {
            $Pemesanan = Pemesanan::find($data->id_quo);
            $Customer = Customer::where('id',$Pemesanan->id_customer)->first();
            $produk = Produk::where('id',$Pemesanan->id_produk)->first();
            $dataInvoice = [
                'no_inv' =>  $data->no_inv,
                'nama_perusahaan' =>  $this->guard()->user()->nama_perusahaan,
                'no_qt' => $Pemesanan->no_qt,
                'nama_customer' => $Customer->nama_customer,
                'alamat_customer' => $Customer->alamat,
                'nama_produk' => $produk->nama_produk,
                'harga' => $produk->harga,
                'qty' => $Pemesanan->qty,
                'total_harga' => $Pemesanan->total,
                'status' => $data->status,
                'pembuat' => $data->pembuat,
                'keterangan' =>  $data->keterangan,
                'tanggal_order' => $Pemesanan->created_at,
                'email_addres' => $Customer->email,
                'no_tlp' => $Customer->no_tlp,
            ];
    
            $pdf = PDF::loadView('invoice.pdf', $dataInvoice);
    
            return $pdf->download('Invoice.pdf');
        } else {
            return response()->json([
                'msg' => 'Data Not Found',
            ]);
        }
        
        
    }

    public function detail($id)
    {
        $data = Invoice::find($id);
        if ($data != null) {
            $Pemesanan = Pemesanan::find($data->id_quo);
            $Customer = Customer::where('id',$Pemesanan->id_customer)->first();
            $produk = Produk::where('id',$Pemesanan->id_produk)->first();
    
            $dataInvoice = [
                'no_inv' =>  $data->no_inv,
                'nama_perusahaan' =>  $this->guard()->user()->nama_perusahaan,
                'no_qt' => $Pemesanan->no_qt,
                'nama_customer' => $Customer->nama_customer,
                'alamat_customer' => $Customer->alamat,
                'nama_produk' => $produk->nama_produk,
                'harga' => $produk->harga,
                'qty' => $Pemesanan->qty,
                'total_harga' => $Pemesanan->total,
                'status' => $data->status,
                'pembuat' => $data->pembuat,
                'keterangan' =>  $data->keterangan,
                'tanggal_order' => $Pemesanan->created_at,
                'email_addres' => $Customer->email,
                'no_tlp' => $Customer->no_tlp,
            ];
    
            return response()->json([
                'msg' => 'Berhasil Show Detail',
                'data' => $dataInvoice
            ]);
        } else {
            return response()->json([
                'msg' => 'Data not found',
            ]);
        }
        
        
    }


}
