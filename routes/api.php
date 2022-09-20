<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'middleware' => 'api'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController ::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);

    // Customer
    Route::get('get-customer', [CustomerController::class, 'index']);
    Route::post('tambah-customer', [CustomerController::class, 'store']);
    Route::post('get-edit-customer/{id}', [CustomerController::class, 'edit']);
    Route::post('hapus-customer/{id}', [CustomerController::class, 'destroy']);
    Route::post('update-customer/{id}',[CustomerController::class,'updateCus']);
    
    //Kategori Produk
    Route::get('get-kategori-produk', [KategoriProdukController::class, 'index']);
    Route::post('add-kategori', [KategoriProdukController::class, 'store']);
    Route::post('get-edit-kategori-produk/{id}', [KategoriProdukController::class, 'edit']);
    Route::post('edit-kategori-produk/{id}', [KategoriProdukController::class, 'update']);
    Route::post('hapus-kategori-produk/{id}', [KategoriProdukController::class, 'destroy']);

    //Produk
    Route::get('get-produk', [ProdukController::class, 'index']);
    Route::post('tambah-produk', [ProdukController::class, 'store']);
    Route::post('get-edit-produk/{id}', [ProdukController::class, 'edit']);
    Route::post('edit-produk/{id}', [ProdukController::class, 'update']);
    Route::post('hapus-produk/{id}', [ProdukController::class, 'destroy']);
    
    //Quotation
    Route::get('get-pemesanan', [PemesananController::class, 'index']);
    Route::post('tambah-pemesanan-draft', [PemesananController::class, 'storeDraft']);
    Route::get('get-pemesanan-draft', [PemesananController::class, 'getDraft']);
    Route::post('tambah-pemesanan-saved', [PemesananController::class, 'storeSaved']);
    Route::post('kirim-pemesanan/{id}', [PemesananController::class, 'send']);
    Route::post('detail-pemesanan/{id}', [PemesananController::class, 'detail']);
    Route::post('get-edit-pemesanan/{id}', [PemesananController::class, 'edit']);
    Route::post('edit-pemesanan/{id}', [PemesananController::class, 'update']);
    Route::post('hapus-pemesanan/{id}', [PemesananController::class, 'destroy']);

    // Invoice
    Route::get('get-invoice', [InvoiceController::class, 'index']);
    Route::post('tambah-invoice-draft',[InvoiceController::class, 'storeDraftInvoice']);
    Route::get('get-draft-invoice', [InvoiceController::class, 'getDraft']);
    Route::post('tambah-invoice-saved',[InvoiceController::class, 'storeSavedInvoice']); 
    Route::post('kirim-invoice/{id}',[InvoiceController::class, 'sendInvoice']); 
    Route::post('get-edit-invoice/{id}', [InvoiceController::class, 'edit']);
    Route::post('edit-invoice/{id}',[InvoiceController::class, 'update']); 
    Route::post('hapus-invoice/{id}',[InvoiceController::class, 'destroy']); 
    Route::post('generate-invoice/{id}',[InvoiceController::class, 'generateInvoice']); 

    // Invoice Payment
    Route::post('invoice-payment/{id}',[InvoiceController::class, 'payment']); 
});

